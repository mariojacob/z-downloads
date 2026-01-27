# Performance-Bericht: Z-Downloads

Dieser Bericht enthält eine detaillierte Analyse der Performance-Aspekte des WordPress-Plugins "Z-Downloads" mit Fokus auf die Frontend-Geschwindigkeit und allgemeine Code-Effizienz.

---

## Zusammenfassung
Das Plugin ist solide strukturiert, weist jedoch in kritischen Bereichen (insbesondere bei Shortcodes im Frontend) Performance-Flaschenhälse auf. Die Hauptursachen für Verzögerungen sind synchrone Dateioperationen (ZIP-Erstellung), externe API-Aufrufe während des Seitenaufbaus und ineffiziente Datenbankabfragen.

---

## 1. Schnelle und einfache Umsetzung (Quick Wins)

Diese Änderungen können mit geringem Aufwand umgesetzt werden und bieten sofortige Verbesserungen.

### 1.1 Caching der Bot-Erkennung
**Datei:** `lib/ZDMCore.php`, Methode `check_for_bot()`
**Problem:** Die Methode wird bei jedem Download-Shortcode auf einer Seite erneut aufgerufen. Sie liest jedes Mal die Liste der User-Agents ein und durchläuft diese in einer Schleife.
**Lösung:** Speichern des Ergebnisses in einer statischen Variable innerhalb der Methode, damit die Prüfung pro Seitenaufruf nur einmal durchgeführt wird.

### 1.2 SQL-Optimierung: Verwendung von Aggregatfunktionen
**Datei:** `lib/ZDMStat.php`
**Problem:** Methoden wie `get_downloads_count()` laden alle Datensätze in den PHP-Speicher, um sie dort zu zählen oder zu summieren (z.B. mit `count($db_results)` oder einer `for`-Schleife).
**Lösung:** Nutzung von SQL-Aggregatfunktionen wie `COUNT(id)` oder `SUM(count)`. Dies reduziert die Speicherauslastung und beschleunigt die Abfrage erheblich, da die Berechnung direkt in der Datenbank erfolgt.

### 1.3 Fehlende Datenbank-Indizes
**Datei:** `lib/ZDMDatabase.php`
**Problem:** Die Tabellen `zdm_files_rel`, `zdm_log` und `zdm_files` besitzen keine Indizes auf Spalten, die häufig für Abfragen oder Verknüpfungen genutzt werden (`id_file`, `id_archive`, `time_create`, `hash_md5`).
**Lösung:** Hinzufügen von Indizes für diese Spalten in der `create_db()` Methode bzw. über ein Migrationsskript. Dies beschleunigt Such- und Verknüpfungsoperationen massiv.

### 1.4 Lokales Hosting von Google Fonts
**Datei:** `lib/ZDMCore.php`, Methoden `enqueue_admin_scripts()` und `enqueue_frontend_scripts()`
**Problem:** Material Icons werden von externen Google-Servern geladen. Dies verursacht zusätzliche DNS-Lookups und TCP-Verbindungsaufbauten, was das Rendering im Frontend verzögert. Zudem ist dies aus DSGVO-Sicht problematisch.
**Lösung:** Die Schriftarten lokal in das Plugin-Verzeichnis integrieren und von dort ausliefern.

### 1.5 Caching von Plugin-Optionen
**Problem:** Die Funktion `get_option('zdm_options')` wird an vielen Stellen im Code wiederholt aufgerufen.
**Lösung:** Obwohl WordPress Optionen intern im Speicher hält, ist es sauberer, die Optionen einmalig beim Initialisieren der Klasse in einer Property (z.B. `$this->options`) zu speichern und darauf zuzugreifen.

---

## 2. Komplexere Verbesserungen / Bugfixes

Diese Punkte erfordern eine tiefergehende Überarbeitung der Architektur, sind aber für eine optimale Skalierbarkeit notwendig.

### 2.1 Hintergrund-Verarbeitung für ZIP-Archive
**Problem:** Die Methode `check_files_from_archive()` wird in Shortcodes aufgerufen und kann die Neuerstellung eines ZIP-Archivs auslösen (`create_archive_cache()`). Wenn eine Datei in einem Archiv aktualisiert wurde, wird der nächste Besucher, der die Seite aufruft, mit einer langen Ladezeit bestraft, während der Server das ZIP-Archiv generiert.
**Lösung:**
- Archiv-Erstellung nur im Backend auslösen, wenn Dateien hochgeladen oder verknüpft werden.
- Alternativ: Nutzung von WP-Cron oder Action Scheduler, um die Archivierung asynchron im Hintergrund durchzuführen.

### 2.2 Asynchrone Lizenzprüfung
**Datei:** `lib/ZDMCore.php`, Methode `licence()`
**Problem:** Alle 30 Tage führt das Plugin während eines Seitenaufrufs (getriggert durch einen Shortcode) einen externen API-Request zu Gumroad aus (`wp_remote_post`). Dies führt zu einer Verzögerung von mehreren Sekunden für den betroffenen Besucher.
**Lösung:** Die Lizenzprüfung in einen wöchentlichen/monatlichen Cron-Job auslagern. Im Frontend sollte nur das bereits gespeicherte Ergebnis der letzten Prüfung abgefragt werden.

### 2.3 Vermeidung von N+1 Query-Problemen
**Datei:** `lib/ZDMCore.php`, Methode `create_archive_cache()` und `shortcode_list()`
**Problem:** Innerhalb von Schleifen werden einzelne Datenbankabfragen für jede Datei ausgeführt (z.B. `get_file_data()`). Bei 50 Dateien im Archiv sind das 50 zusätzliche Datenbankabfragen.
**Lösung:** Abfragen von Datenmengen in einem einzigen SQL-Statement unter Verwendung von `WHERE id IN (...)`.

### 2.4 Transient Caching für Shortcodes
**Problem:** Komplexere Shortcodes wie `[zdownload_list]` führen bei jedem Seitenaufruf mehrere Abfragen durch und generieren HTML.
**Lösung:** Nutzung der WordPress Transients API, um die generierten Shortcode-Outputs für eine gewisse Zeit (z.B. 12 Stunden) zwischenzuspeichern. Das Cache-Invalidierung sollte bei Aktualisierung von Dateien oder Archiven erfolgen.

### 2.5 Log-Pruning (Bereinigung)
**Problem:** Die Tabelle `zdm_log` wächst mit jedem Download unbegrenzt. Eine sehr große Log-Tabelle verlangsamt Statistikabfragen und Backups.
**Lösung:** Einführung einer automatischen Bereinigungsfunktion, die Einträge, die älter als ein bestimmter Zeitraum sind (z.B. 180 Tage), automatisch löscht.
