<?php

// Abbruch bei direktem Zugriff
if (!defined('ABSPATH')) {
    die;
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    // Aktiven Tab bestimmen
    if( isset($_GET['tab'])) {
        $active_tab = htmlspecialchars($_GET['tab']);
    } else {
        $active_tab = 'beginner';
    }

    // Text für Premium Funktionen
    if (!ZDMCore::licence()) {
        $zdm_premium_text = '(<a href="' . ZDM__PRO_URL . '" target="_blank" title="code.urban-base.net">' . ZDM__PRO . ' ' . esc_html__('Funktion', 'zdm') . ' </a>)';
    } else {
        $zdm_premium_text = '';
    }

    ?>

    <div class="wrap">
        <h1 class="wp-heading-inline"><ion-icon name="help-circle-outline"></ion-icon> <?=ZDM__TITLE?> <?=esc_html__('Hilfe', 'zdm')?></h1>

        <hr class="wp-header-end">

        <nav class="nav-tab-wrapper wp-clearfix">
		    <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=beginner" class="nav-tab <?php echo $active_tab == 'beginner' ? 'nav-tab-active' : ''; ?>" aria-current="page"><?=esc_html__('Erste Schritte', 'zdm')?></a>
		    <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=advanced" class="nav-tab <?php echo $active_tab == 'advanced' ? 'nav-tab-active' : ''; ?>"><?=esc_html__('Fortgeschritten', 'zdm')?></a>
		    <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert" class="nav-tab <?php echo $active_tab == 'expert' ? 'nav-tab-active' : ''; ?>"><?=esc_html__('Experte', 'zdm')?></a>
		</nav>

    <?php

    // Tabs
    if ($active_tab == 'beginner') { // Tab: Erste Schritte
        ?>
        <br>
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Dateien hinzufügen', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Um eine Datei hochzuladen klicke im', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('Menü auf', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-add-file"><?=esc_html__('Datei hinzufügen', 'zdm')?></a>".</p>
                <p><?=esc_html__('Wähle eine Datei aus und klicke auf "Hochladen".', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Download-Button für Datei mit Shortcode ausgeben', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Um eine Datei als Button auf einer Seite oder Beitrag auszugeben klicke im', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('Menü auf', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-files"><?=esc_html__('Dateien', 'zdm')?></a>".</p>
                <p><?=esc_html__('Hier siehst du eine Übersicht aller Dateien die du schon hochgeladen hast.', 'zdm')?></p>
                <p><?=esc_html__('Der Shortcode wird in der Liste angezeigt und sieht so aus:', 'zdm')?> <code>[zdownload file="123"]</code></p>
                <p><?=esc_html__('"123" ist die einzigartige ID der jeweiligen Datei.', 'zdm')?></p>
                <p><?=esc_html__('Du kannst auch auf den Namen klicken um mehr Details zu dieser Datei zu bekommen, auf der Detailseite siehst du auch weitere Shortcodes die du verwendenden kannst.', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('ZIP-Archiv anlegen', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Um ein ZIP-Archiv zu erstellen klicke im', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('Menü auf', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-add-archive"><?=esc_html__('Archiv erstellen', 'zdm')?></a>".</p>
                <p><?=esc_html__('Hier kannst du einen Namen, einen ZIP-Namen und sonstige Infos zum Archiv eintragen.', 'zdm')?></p>
                <p><?=esc_html__('Um Dateien zum ZIP-Archiv hinzuzufügen wählst du im unteren Bereich bei "Dateien verknüpfen" aus deinen bereits hochgeladenen Dateien aus und klickst auf "Speichern".', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Download-Button für Archiv mit Shortcode ausgeben', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Um ein Archiv als Button auf einer Seite oder Beitrag auszugeben klicke im', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('Menü auf', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-ziparchive"><?=esc_html__('Archive', 'zdm')?></a>".</p>
                <p><?=esc_html__('Hier siehst du eine Übersicht aller Archive die du erstellt hast.', 'zdm')?></p>
                <p><?=esc_html__('Der Shortcode wird in der Liste angezeigt und sieht so aus:', 'zdm')?> <code>[zdownload zip="123"]</code></p>
                <p><?=esc_html__('"123" ist die einzigartige ID des jeweiligen Archives.', 'zdm')?></p>
                <p><?=esc_html__('Du kannst auch auf den Namen klicken um mehr Details zu diesem Archiv zu bekommen, auf der Detailseite siehst du auch weitere Shortcodes die du verwendenden kannst.', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Button Farbe und Styles', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Um die Farbe oder sonstige Button-Einstellungen vorzunehmen klicke im', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('Menü auf', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-settings"><?=esc_html__('Einstellungen', 'zdm')?></a>".</p>
                <p><?=esc_html__('Hier kannst du im Bereich "Download-Button" folgendes ändern:', 'zdm')?></p>
                <p><?=esc_html__('Den Standardtext, den Style (Farbe des Buttons), Outline, Runde Ecken oder ein Icon.', 'zdm')?></p>
                <p><?=esc_html__('Alle verfügbaren Farben findest du auf der', 'zdm')?> <?=ZDM__TITLE?> <a href="https://code.urban-base.net/z-downloads/farben/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Farben', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?></a></p>
            </div>
        </div>
        <?php
    // end if ($active_tab == 'beginner')
    } elseif ($active_tab == 'advanced') { // Tab: Fortgeschritten
        ?>
        <br>
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Dashboard', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Das Dashboard zeigt dir alle wichtigen Informationen über deine Downloads.', 'zdm')?></p>
                <h4><?=esc_html__('Download Statistik', 'zdm')?></h4>
                <p><?=esc_html__('Hier siehst du die Gesamte Anzahl an Datei und Archiv Downloads und die Downloads der letzten Zeit 30 Tage, 7 Tage und 24 Stunden.', 'zdm')?></p>
                <h4><?=esc_html__('Letzte Downloads', 'zdm')?></h4>
                <p><?=esc_html__('Dieser Bereich ist in Archive und Dateien aufgeteilt und zeigt dir die letzten Downloads mit Namen, Zeit und Datum.', 'zdm')?></p>
                <h4><?=esc_html__('Beliebte Downloads', 'zdm')?></h4>
                <p><?=esc_html__('Dieser Bereich ist auch in Archive und Dateien aufgeteilt und zeigt dir die fünf beliebtesten Downloads.', 'zdm')?></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Metadaten ausgeben', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Du kannst eine Datei oder Archiv nicht nur als Button ausgeben sonder auch weitere Informationen zu diesem Download.', 'zdm')?></p>
                <h4><?=esc_html__('Download-Anzahl', 'zdm')?></h4>
                <p><code>[zdownload_meta file="123" type="count"]</code> <?=esc_html__('oder', 'zdm')?> <code>[zdownload_meta zip="123" type="count"]</code></p>
                <h4><?=esc_html__('Dateigröße', 'zdm')?></h4>
                <p><code>[zdownload_meta file="123" type="size"]</code> <?=esc_html__('oder', 'zdm')?> <code>[zdownload_meta zip="123" type="size"]</code></p>
                <h4><?=esc_html__('Weitere Shortcodes', 'zdm')?></h4>
                <p><?=esc_html__('Weitere Shortcode Optionen für die ausgabe erweiterter Metadaten findest du im Tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Experte', 'zdm')?></a> 
                <?=esc_html__('oder auf der', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?></a></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Audioplayer', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Wenn du eine Audiodatei wie zum Beispiel eine MP3-Datei hochlädst kannst du diese nicht nur als Download-Button anzeigen lassen sondern auch als Audioplayer.', 'zdm')?></p>
                <p><?=esc_html__('Dazu verwendest du diesen Shortcode:', 'zdm')?> <code>[zdownload_audio file="123"]</code></p>
                <p><?=esc_html__('Der Shortcode für den Audioplayer wird auf der Datei-Detailseite automatisch angezeigt wenn es sich um eine Audiodatei handelt.', 'zdm')?></p>
                <p><?=esc_html__('Weitere Ausgabeoptionen für den Audioplayer findest du im Tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Experte', 'zdm')?></a> 
                <?=esc_html__('oder auf der', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?></a></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Videoplayer', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Wenn du eine Videodatei wie zum Beispiel eine MP4-Datei hochlädst kannst du diese nicht nur als Download-Button anzeigen lassen sondern auch als Videoplayer.', 'zdm')?></p>
                <p><?=esc_html__('Dazu verwendest du diesen Shortcode:', 'zdm')?> <code>[zdownload_video file="123"]</code></p>
                <p><?=esc_html__('Der Shortcode für den Videoplayer wird auf der Datei-Detailseite automatisch angezeigt wenn es sich um eine Videodatei handelt.', 'zdm')?></p>
                <p><?=esc_html__('Weitere Ausgabeoptionen für den Videoplayer findest du im Tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Experte', 'zdm')?></a> 
                <?=esc_html__('oder auf der', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?></a></p>
            </div>
        </div>
        <?php
    // end elseif ($active_tab == 'advanced')
    } elseif ($active_tab == 'expert') { // Tab: Experte
        ?>
        <br>
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Hashwert von Datei mit Shortcode ausgeben', 'zdm')?></h3>
                <hr>
                <h4><?=esc_html__('MD5', 'zdm')?> <?=$zdm_premium_text?></h4>
                <p><?=esc_html__('Du kannst den MD5 Hashwert einer Datei oder eines ZIP-Archives ausgeben.', 'zdm')?></p>
                <p><code>[zdownload_meta file="123" type="hash-md5"]</code> <?=esc_html__('oder', 'zdm')?> <code>[zdownload_meta zip="123" type="hash-md5"]</code></p>
                <h4><?=esc_html__('SHA1', 'zdm')?> <?=$zdm_premium_text?></h4>
                <p><?=esc_html__('Du kannst den SHA1 Hashwert einer Datei oder eines ZIP-Archives ausgeben.', 'zdm')?></p>
                <p><code>[zdownload_meta file="123" type="hash-sha1"]</code> <?=esc_html__('oder', 'zdm')?> <code>[zdownload_meta zip="123" type="hash-sha1"]</code></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Alle Optionen des Audioplayer', 'zdm')?></h3>
                <hr>
                <h4><?=esc_html__('Autoplay', 'zdm')?></h4>
                <p><code>[zdownload_audio file="123" autoplay="on"]</code></p>
                <p><?=esc_html__('Die Option "autoplay" gibt an ob der Audioplayer beim Aufruf der Seite automatisch starten soll oder nicht. Standardmäßig ist diese Funktion deaktiviert wenn die Option "autoplay" nicht angegeben wird.', 'zdm')?></p>
                <h4><?=esc_html__('Dauerschleife', 'zdm')?></h4>
                <p><code>[zdownload_audio file="123" loop="on"]</code></p>
                <p><?=esc_html__('Wenn die Option "loop" auf "on" gestellt wird, dann läuft die Audiodatei in einer Dauerschleife ab. Standardmäßig ist diese Option deaktiviert.', 'zdm')?></p>
                <h4><?=esc_html__('Kontrollen deaktivieren', 'zdm')?></h4>
                <p><code>[zdownload_audio file="123" controls="off"]</code></p>
                <p><?=esc_html__('Die Option "controls" ist standardmäßig aktiviert, wenn diese Option auf "off" gestellt wird, dann wird kein Player angezeigt. In Verbindung mit der Option "autoplay" kann so eine Audiodatei automatisch abgespielt werden ohne einen sichtbaren Player, das ist aber für die Benutzerfreundlichkeit nicht zu empfehlen.', 'zdm')?></p>
                <h4><?=esc_html__('Info', 'zdm')?></h4>
                <p><?=esc_html__('Natürlich können alle Optionen auch kombiniert werden, das sieht dann so aus:', 'zdm')?></p>
                <p><code>[zdownload_audio file="123" autoplay="on" loop="on" controls="off"]</code></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Alle Optionen des Videoplayer', 'zdm')?></h3>
                <hr>
                <h4><?=esc_html__('Breite angeben', 'zdm')?></h4>
                <p><code>[zdownload_video file="123" w="50%"]</code> <?=esc_html__('oder', 'zdm')?> <code>[zdownload_video file="123" w="720px"]</code></p>
                <p><?=esc_html__('Die Option "w" ist optional mit der du die Breite des Videos in Form von "%" oder "px" angeben kannst. Standardmäßig ist eine Breite von "100%" eingestellt wenn die Option "w" nicht angegeben wird.', 'zdm')?></p>
                <h4><?=esc_html__('Autoplay', 'zdm')?></h4>
                <p><code>[zdownload_video file="123" autoplay="on"]</code></p>
                <p><?=esc_html__('Die Option "autoplay" gibt an ob das Video beim Aufruf der Seite automatisch starten soll oder nicht. Standardmäßig ist diese Funktion deaktiviert wenn die Option "autoplay" nicht angegeben wird.', 'zdm')?></p>
                <h4><?=esc_html__('Dauerschleife', 'zdm')?></h4>
                <p><code>[zdownload_video file="123" loop="on"]</code></p>
                <p><?=esc_html__('Wenn die Option "loop" auf "on" gestellt wird, dann läuft das Video in einer Dauerschleife ab. Standardmäßig ist diese Option deaktiviert.', 'zdm')?></p>
                <h4><?=esc_html__('Kontrollen deaktivieren', 'zdm')?></h4>
                <p><code>[zdownload_video file="123" controls="off"]</code></p>
                <p><?=esc_html__('Die Option "controls" ist standardmäßig aktiviert, wenn diese Option auf "off" gestellt wird, dann werden keine Kontrollelemente wie Play, Pause, Lautstärke, Vollbild, Zeitleiste oder weitere Optionen beim Video angezeigt. In Verbindung mit der Option autoplay kann so eine Videodatei automatisch abgespielt werden ohne eine Möglichkeit das Video zu stoppen, das ist aber für die Benutzerfreundlichkeit nicht zu empfehlen.', 'zdm')?></p>
                <h4><?=esc_html__('Info', 'zdm')?></h4>
                <p><?=esc_html__('Natürlich können alle Optionen auch kombiniert werden, das sieht dann so aus:', 'zdm')?></p>
                <p><code>[zdownload_video file="123" w="720px" autoplay="on" loop="on" controls="off"]</code></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Audioplayer und Videoplayer mit JavaScript steuern', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Um den Audioplayer mit Hilfe von JavaScript zu steuern besitzt jedes HTML Audio-Element eine individuelle id, diese setzt sich aus zdmAudio und der ID der Datei zusammen.', 'zdm')?></p>
                <p><code>id="zdmAudio123"</code></p>
                <p><?=esc_html__('Um den Videoplayer mit Hilfe von JavaScript zu steuern besitzt jedes HTML Video-Element eine individuelle id, diese setzt sich aus zdmVideo und der ID der Datei zusammen.', 'zdm')?></p>
                <p><code>id="zdmVideo123"</code></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('IP Adresse Anonymität', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Die IP Adresse der Webseitenbesucher die über dieses Plugin etwas herunterladen ist standardmäßig anonymisiert.', 'zdm')?></p>
                <p><?=esc_html__('Um diese Einstellung zu ändern und die IP Adresse vollständig zu tracken klicke im', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('Menü auf', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-settings"><?=esc_html__('Einstellungen', 'zdm')?></a>".</p>
            </div>
        </div>
        <?php
    } // end elseif ($active_tab == 'expert')
    ?>

    </div><!-- end class="wrap" -->

<?php
} // end if (current_user_can(ZDM__STANDARD_USER_ROLE))