<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    // Aktiven Tab bestimmen
    if( isset($_GET['tab'])) {
        $active_tab = htmlspecialchars($_GET['tab']);
    } else {
        $active_tab = 'beginner';
    }

    // Text für Premiumfunktionen
    if (!ZDMCore::licence()) {
        $zdm_premium_text = '(<a href="' . ZDM__PRO_URL . '" target="_blank" title="code.urban-base.net">' . ZDM__PRO . ' ' . esc_html__('feature', 'zdm') . ' </a>)';
    } else {
        $zdm_premium_text = '';
    }

    ?>

    <div class="wrap">
        <h1 class="wp-heading-inline"><span class="material-icons-round zdm-md-1">help_outline</span> <?=ZDM__TITLE?> <?=esc_html__('Help', 'zdm')?></h1>

        <hr class="wp-header-end">

        <nav class="nav-tab-wrapper wp-clearfix zdm-nav-tabs">
		    <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=beginner" class="nav-tab <?php echo $active_tab == 'beginner' ? 'nav-tab-active' : ''; ?>" aria-current="page"><?=esc_html__('First steps', 'zdm')?></a>
		    <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=advanced" class="nav-tab <?php echo $active_tab == 'advanced' ? 'nav-tab-active' : ''; ?>"><?=esc_html__('Advanced', 'zdm')?></a>
		    <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert" class="nav-tab <?php echo $active_tab == 'expert' ? 'nav-tab-active' : ''; ?>"><?=esc_html__('Expert', 'zdm')?></a>
		</nav>

    <?php

    //////////////////////////////////////////////////
    // Tabs
    //////////////////////////////////////////////////
    if ($active_tab == 'beginner') { // Tab: Erste Schritte
        ?>
        <br>
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Add files', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('To upload a file click on', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('menu on', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-add-file"><?=esc_html__('Add file', 'zdm')?></a>".</p>
                <p><?=esc_html__('Select a file and click "Upload".', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Replace files', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('If you replace the file, only the file will be replaced, the ID for the shortcodes is retained.', 'zdm')?></p>
                <p><?=esc_html__('The cache of all archives with which this file is linked is updated automatically.', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Download button for file with shortcode', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('To show a file as a button on a page or post click on', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('menu on', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-files"><?=esc_html__('Files', 'zdm')?></a>".</p>
                <p><?=esc_html__('Here you can see an overview of all files you have already uploaded.', 'zdm')?></p>
                <p><?=esc_html__('The shortcode appears in the list and looks like this', 'zdm')?>: <code>[zdownload file="123"]</code></p>
                <p><?=esc_html__('"123" is the unique ID of each file.', 'zdm')?></p>
                <p><?=esc_html__('You can also click on the name to get more details about this file, on the detail page you can see more shortcodes that you can use.', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Create ZIP archive', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('To create a ZIP archive click on', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('menu on', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-add-archive"><?=esc_html__('Create archive', 'zdm')?></a>".</p>
                <p><?=esc_html__('Here you can enter a name, a ZIP name and other information about the archive.', 'zdm')?></p>
                <p><?=esc_html__('In order to add files to the ZIP archive you select in the lower area under "Link files" from your already uploaded files and click on "Save".', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Print download button for archive with shortcode', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('To output an archive as a button on a page or post click on', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('menu on', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-ziparchive"><?=esc_html__('Archives', 'zdm')?></a>".</p>
                <p><?=esc_html__('Here you can see an overview of all archives you have created.', 'zdm')?></p>
                <p><?=esc_html__('The shortcode appears in the list and looks like this', 'zdm')?>: <code>[zdownload zip="123"]</code></p>
                <p><?=esc_html__('"123" is the unique ID of the respective archive.', 'zdm')?></p>
                <p><?=esc_html__('You can also click on the name to get more details about this archive, on the detail page you can see more shortcodes that you can use.', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Button color and styles', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('To make the color or other button settings click on', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('menu on', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-settings"><?=esc_html__('Settings', 'zdm')?></a>".</p>
                <p><?=esc_html__('Here you can change the following in the area "Download-Button"', 'zdm')?>:</p>
                <p><?=esc_html__('The standard text, the style (color of the button), outline, round corners or an icon.', 'zdm')?></p>
                <p><?=esc_html__('All available colors can be found on the', 'zdm')?> <?=ZDM__TITLE?> <a href="https://code.urban-base.net/z-downloads/farben/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Colors', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('website', 'zdm')?></a></p>
            </div>
        </div>
        
        <?php
    //////////////////////////////////////////////////
    // Ende Tab: Erste Schritte
    //////////////////////////////////////////////////
    } elseif ($active_tab == 'advanced') { // Tab: Erweitert
        ?>
        <br>
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Visability', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('With this option you can set a file or archive on the detail page.', 'zdm')?></p>
                <p><?=esc_html__('By default, every added file or archive is set to "Public".', 'zdm')?></p>
                <h4><?=esc_html__('Visibility of files', 'zdm')?></h4>
                <p><?=esc_html__('The visibility settings of a file only affect the output of this file, if this file is linked in an archive and you set the visibility of the file to "Private", then the file remains in the archive.', 'zdm')?></p>
                <p><?=esc_html__('If the file is set to "Private", the file can no longer be downloaded, even if someone calls the URL of the download button directly.', 'zdm')?></p>
                <h4><?=esc_html__('Visibility of archives', 'zdm')?></h4>
                <p><?=esc_html__('The visibility setting of an archive determines whether a button or other information is displayed in the front end.', 'zdm')?></p>
                <p><?=esc_html__('If the archive is set to "Private", the archive can no longer be downloaded, even if someone calls the URL of the download button directly.', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Dashboard', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('The dashboard shows you all the important information about your downloads.', 'zdm')?></p>
                <h4><?=esc_html__('Download statistics', 'zdm')?></h4>
                <p><?=esc_html__('Here you can see the total number of file and archive downloads and the latest downloads 30 days, 7 days and 24 hours.', 'zdm')?></p>
                <h4><?=esc_html__('Last downloads', 'zdm')?></h4>
                <p><?=esc_html__('This area is divided into archives and files and shows you the last downloads with name, time and date.', 'zdm')?></p>
                <h4><?=esc_html__('Popular Downloads', 'zdm')?></h4>
                <p><?=esc_html__('This section is also divided into archives and files and shows you the five most popular downloads.', 'zdm')?></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Lists', 'zdm')?> <span class="zdm-color-primary" style="float: right"><?=esc_html__('NEW', 'zdm')?></span></h3>
                <hr>
                <p><?=esc_html__('You can output the files from an archive as a list.', 'zdm')?></p>
                <h4><?=esc_html__('Quick output', 'zdm')?></h4>
                <p><?=esc_html__('Use this shortcode to display a list of the files in an archive. You define the standard styles in the', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-settings"><?=esc_html__('settings', 'zdm')?></a>.</p>
                <p><code>[zdownload_list zip="123"]</code></p>
                <h3><?=esc_html__('Specific styles', 'zdm')?></h3>
                <p><?=esc_html__('These special details overwrite the default values. All styles listed below can also be combined with one another.', 'zdm')?></p>
                <h4><?=esc_html__('Style', 'zdm')?></h4>
                <p><?=esc_html__('Use the keyword "style" and "rows" for rows, "ul" for an unordered list or "ol" for an ordered list.', 'zdm')?></p>
                <p><code>[zdownload_list zip="123" style="ul"]</code></p>
                <h4><?=esc_html__('Bold', 'zdm')?></h4>
                <p><?=esc_html__('Use the keyword', 'zdm')?> <code>bold="on"</code> <?=esc_html__('to make the text of the list bold.', 'zdm')?></p>
                <p><code>[zdownload_list zip="123" bold="on"]</code></p>
                <p><?=esc_html__('Use the keyword', 'zdm')?> <code>bold="off"</code> <?=esc_html__('to make the text of the list normal.', 'zdm')?></p>
                <p><code>[zdownload_list zip="123" bold="off"]</code></p>
                <h4><?=esc_html__('Links', 'zdm')?></h4>
                <p><?=esc_html__('Use the keyword', 'zdm')?> <code>links="on"</code> <?=esc_html__('to output the text of the list elements as a link.', 'zdm')?></p>
                <p><code>[zdownload_list zip="123" links="on"]</code></p>
                <p><?=esc_html__('Use the keyword', 'zdm')?> <code>links="off"</code> <?=esc_html__('to output the text of the list elements as a normal text.', 'zdm')?></p>
                <p><code>[zdownload_list zip="123" links="off"]</code></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Output metadata', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('You can not just print a file or archive as a button but also more information about this download.', 'zdm')?></p>
                <h4><?=esc_html__('Download count', 'zdm')?></h4>
                <p><code>[zdownload_meta file="123" type="count"]</code> <?=esc_html__('or', 'zdm')?> <code>[zdownload_meta zip="123" type="count"]</code></p>
                <h4><?=esc_html__('File size', 'zdm')?></h4>
                <p><code>[zdownload_meta file="123" type="size"]</code> <?=esc_html__('or', 'zdm')?> <code>[zdownload_meta zip="123" type="size"]</code></p>
                <h4><?=esc_html__('More shortcodes', 'zdm')?></h4>
                <p><?=esc_html__('More shortcode options for outputting advanced metadata can be found in the tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Expert', 'zdm')?></a> 
                <?=esc_html__('or on the', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('website', 'zdm')?></a></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Audio player', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('If you upload an audio file, such as an MP3 file, you can not only show it as a download button, but also as an audio player.', 'zdm')?></p>
                <p><?=esc_html__('For this you use this shortcode', 'zdm')?>: <code>[zdownload_audio file="123"]</code></p>
                <p><?=esc_html__('The audio player shortcode is automatically displayed on the file details page if it is an audio file.', 'zdm')?></p>
                <p><?=esc_html__('Other output options for the audio player can be found in the tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Expert', 'zdm')?></a> 
                <?=esc_html__('or on the', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('website', 'zdm')?></a></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Video player', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('If you upload a video file, such as an MP4 file, you can not only display it as a download button but also as a video player.', 'zdm')?></p>
                <p><?=esc_html__('For this you use this shortcode', 'zdm')?>: <code>[zdownload_video file="123"]</code></p>
                <p><?=esc_html__('The video player shortcode is automatically displayed on the file details page if it is a video file.', 'zdm')?></p>
                <p><?=esc_html__('Other output options for the video player can be found in the tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Expert', 'zdm')?></a> 
                <?=esc_html__('or on the', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('website', 'zdm')?></a></p>
            </div>
        </div>
        <?php
    //////////////////////////////////////////////////
    // Ende Tab: Erweitert
    //////////////////////////////////////////////////
    } elseif ($active_tab == 'expert') { // Tab: Experte
        ?>
        <br>
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Output hash value of file with shortcode', 'zdm')?></h3>
                <hr>
                <h4><?=esc_html__('MD5', 'zdm')?> <?=$zdm_premium_text?></h4>
                <p><?=esc_html__('You can output the MD5 hash value of a file or ZIP archive.', 'zdm')?></p>
                <p><code>[zdownload_meta file="123" type="hash-md5"]</code> <?=esc_html__('or', 'zdm')?> <code>[zdownload_meta zip="123" type="hash-md5"]</code></p>
                <h4><?=esc_html__('SHA1', 'zdm')?> <?=$zdm_premium_text?></h4>
                <p><?=esc_html__('You can output the SHA1 hash value of a file or a ZIP archive.', 'zdm')?></p>
                <p><code>[zdownload_meta file="123" type="hash-sha1"]</code> <?=esc_html__('or', 'zdm')?> <code>[zdownload_meta zip="123" type="hash-sha1"]</code></p>
            </div>
        </div>

        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('All options of the audio player', 'zdm')?></h3>
                <hr>
                <h4><?=esc_html__('Autoplay', 'zdm')?></h4>
                <p><code>[zdownload_audio file="123" autoplay="on"]</code></p>
                <p><?=esc_html__('The option "autoplay" indicates whether the audio player should start automatically when the page is called or not. By default, this feature is disabled if the autoplay option is not specified.', 'zdm')?></p>
                <h4><?=esc_html__('Continuous loop', 'zdm')?></h4>
                <p><code>[zdownload_audio file="123" loop="on"]</code></p>
                <p><?=esc_html__('If the option "loop" is set to "on", then the audio file runs in a continuous loop. By default, this option is disabled.', 'zdm')?></p>
                <h4><?=esc_html__('Disable controls', 'zdm')?></h4>
                <p><code>[zdownload_audio file="123" controls="off"]</code></p>
                <p><?=esc_html__('The "controls" option is enabled by default, if this option is set to "off" then no player will be displayed. In conjunction with the "autoplay" option, an audio file can be played automatically without a visible player, but this is not recommended for user-friendliness.', 'zdm')?></p>
                <h4><?=esc_html__('Disable download', 'zdm')?></h4>
                <p><code>[zdownload_audio file="123" nodownload="on"]</code></p>
                <p><?=esc_html__('If the "nodownload" option is set to "on", the download button on the audio player is hidden.', 'zdm')?></p>
                <h4><?=esc_html__('Info', 'zdm')?></h4>
                <p><?=esc_html__('Of course, all options can also be combined, which looks like this', 'zdm')?>:</p>
                <p><code>[zdownload_audio file="123" autoplay="on" loop="on" controls="off"]</code></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('All options of the video player', 'zdm')?></h3>
                <hr>
                <h4><?=esc_html__('Specify width', 'zdm')?></h4>
                <p><code>[zdownload_video file="123" w="50%"]</code> <?=esc_html__('or', 'zdm')?> <code>[zdownload_video file="123" w="720px"]</code></p>
                <p><?=esc_html__('The option "w" is optional with which you can specify the width of the video in the form of "%" or "px". By default, a width of "100%" is set if the option "w" is not specified.', 'zdm')?></p>
                <h4><?=esc_html__('Autoplay', 'zdm')?></h4>
                <p><code>[zdownload_video file="123" autoplay="on"]</code></p>
                <p><?=esc_html__('The option "autoplay" indicates whether the video should start automatically when the page is called or not. By default, this feature is disabled if the autoplay option is not specified.', 'zdm')?></p>
                <h4><?=esc_html__('Continuous loop', 'zdm')?></h4>
                <p><code>[zdownload_video file="123" loop="on"]</code></p>
                <p><?=esc_html__('If the option "loop" is set to "on", then the video runs in a continuous loop. By default, this option is disabled.', 'zdm')?></p>
                <h4><?=esc_html__('Disable controls', 'zdm')?></h4>
                <p><code>[zdownload_video file="123" controls="off"]</code></p>
                <p><?=esc_html__('The "controls" option is enabled by default, if this option is set to "off", then no controls such as play, pause, volume, full screen, timeline, or other video options are displayed. In conjunction with the autoplay option, such a video file can be played automatically without any possibility to stop the video, but this is not recommended for usability.', 'zdm')?></p>
                <h4><?=esc_html__('Info', 'zdm')?></h4>
                <p><?=esc_html__('Of course, all options can also be combined, which looks like this', 'zdm')?>:</p>
                <p><code>[zdownload_video file="123" w="720px" autoplay="on" loop="on" controls="off"]</code></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Control individual audio and video players with CSS and JavaScript', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('In order to control an audio player using CSS and JavaScript, each HTML audio element has an individual ID, which is made up of zdmAudio and the ID of the file.', 'zdm')?></p>
                <p><code>id="zdmAudio123"</code></p>
                <p><?=esc_html__('To control a video player using CSS and JavaScript, each HTML video element has an individual ID, which is made up of zdmVideo and the ID of the file.', 'zdm')?></p>
                <p><code>id="zdmVideo123"</code></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('Control all audio players, video players and buttons with CSS and JavaScript', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('To control all audio players using CSS and JavaScript, each HTML audio element has a class name, which looks like this', 'zdm')?>:</p>
                <p><code>class="zdm-audio"</code></p>
                <p><?=esc_html__('In order to control all video players using CSS and JavaScript, each HTML video element has a class name, which looks like this', 'zdm')?>:</p>
                <p><code>class="zdm-video"</code></p>
                <p><?=esc_html__('In order to control all buttons with the help of CSS and JavaScript, each HTML video element has a class name, which looks like this', 'zdm')?>:</p>
                <p><code>class="zdm-btn"</code></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="inside">
                <h3><?=esc_html__('IP address anonymity', 'zdm')?></h3>
                <hr>
                <p><?=esc_html__('The IP address of the website visitors who download something via this plugin is anonymized by default.', 'zdm')?></p>
                <p><?=esc_html__('To change this setting and to completely track the IP address, click on', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('menu on', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-settings"><?=esc_html__('Settings', 'zdm')?></a>".</p>
            </div>
        </div>
        <?php
    }
    //////////////////////////////////////////////////
    // Ende Tab: Experte
    //////////////////////////////////////////////////
    ?>

    </div><!-- end class="wrap" -->

<?php
} // end if (current_user_can(ZDM__STANDARD_USER_ROLE))