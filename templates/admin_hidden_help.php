<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

if( isset($_GET['tab'])) {
    $active_tab = htmlspecialchars($_GET['tab']);
} else {
    $active_tab = 'beginner';
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

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
                <p><?=esc_html__('Hier kannst du einen Namen, einen Namen und sonstige Infos zum Archiv eintragen.', 'zdm')?></p>
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
                <p><?=esc_html__('Hier skannst du im Bereich "Download-Button" folgendes ändern:', 'zdm')?></p>
                <p><?=esc_html__('Den Standardtext, den Style (Farbe des Buttons), Outline, Runde Ecken oder ein Icon.', 'zdm')?></p>
                <p><?=esc_html__('Alle verfügbaren Farben findest du auf der', 'zdm')?> <?=ZDM__TITLE?> <a href="https://code.urban-base.net/z-downloads/farben/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Farben', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?></a></p>
            </div>
        </div>
        <?php
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
                <h4><?=esc_html__('Die Download-Anzahl', 'zdm')?></h4>
                <p><code>[zdownload_meta file="123" type="count"]</code> <?=esc_html__('oder', 'zdm')?> <code>[zdownload_meta zip="123" type="count"]</code></p>
                <h4><?=esc_html__('Die Dateigröße', 'zdm')?></h4>
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
    } elseif ($active_tab == 'expert') { // Tab: Experte
        ?>
        <br>
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Alle Optionen des Audioplayer', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Text...', 'zdm')?></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Alle Optionen des Videoplayer', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Text...', 'zdm')?></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Audioplayer und Videoplayer mit JavaScript steuern', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Text...', 'zdm')?></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('IP Adresse Anonymität', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('Die IP Adresse der Webseitenbesucher die über dieses Plugin etwas herunterladen ist standardmäßig anonymisiert.', 'zdm')?></p>
                <p><?=esc_html__('Um diese Einstellung zu ändern und die IP Adresse vollständig zu tracken klicke im', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('Menü auf "Einstellungen".', 'zdm')?></p>
            </div>
        </div>
        <?php
    }

    ?>

    

    </div>

<?php }