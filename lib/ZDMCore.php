<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

/**
 * ZDM-Core
 */
class ZDMCore
{

    protected $plugin_basename;

    function __construct()
    {
        $this->plugin_basename = plugin_basename(ZDM__PATH . ZDM__SLUG . '.php');
    }

    /**
     * Erzeugt im Backend einen Eintrag mit mehreren Seiten im Menü
     */
    public function admin_menu() {
        add_menu_page(
            ZDM__TITLE,                             // Seitentitel
            ZDM__TITLE,                             // Menuetext
            'manage_options',                       // Zugriffslevel
            ZDM__SLUG,                              // Pfad der Funktion,  __FILE__ bedeutet die Funktion ist in dieser Datei
            array($this, 'admin_menu_dashboard'),   // Name der Funktion die ausgeführt wird
            'dashicons-download');                  // Dashicon im Admin-Menü
        add_submenu_page(
            ZDM__SLUG,                              // top-level menu
            esc_html__('Dateien', 'zdm'),           // Seitentitel
            esc_html__('Dateien', 'zdm'),           // Menuetext
            'manage_options',                       // Zugriffslevel
            ZDM__SLUG . '-files',                   // URL des submenue
            array($this, 'admin_menu_files'));      // Name der Funktion die ausgeführt wird
        add_submenu_page(
            ZDM__SLUG,
            esc_html__('Datei hinzufügen', 'zdm'),
            esc_html__('Datei hinzufügen', 'zdm'),
            'manage_options',
            ZDM__SLUG . '-add-file',
            array($this, 'admin_menu_add_file'));
        add_submenu_page(
            ZDM__SLUG,
            esc_html__('Archive', 'zdm'),
            esc_html__('Archive', 'zdm'),
            'manage_options',
            ZDM__SLUG . '-ziparchive',
            array($this, 'admin_menu_ziparchive'));
        add_submenu_page(
            ZDM__SLUG,
            esc_html__('Archiv erstellen', 'zdm'),
            esc_html__('Archiv erstellen', 'zdm'),
            'manage_options',
            ZDM__SLUG . '-add-archive',
            array($this, 'admin_menu_add_archive'));
        add_submenu_page(
            ZDM__SLUG,
            esc_html__('Einstellungen', 'zdm'),
            esc_html__('Einstellungen', 'zdm'),
            'manage_options',
            ZDM__SLUG . '-settings',
            array($this, 'admin_menu_settings'));
        add_submenu_page(
            null,
            esc_html__('Hilfe', 'zdm'),
            esc_html__('Hilfe', 'zdm'),
            'manage_options',
            ZDM__SLUG . '-help',
            array($this, 'admin_hidden_help'));
        add_submenu_page(
            null,
            esc_html__('Hilfe', 'zdm'),
            esc_html__('Hilfe', 'zdm'),
            'manage_options',
            ZDM__SLUG . '-log',
            array($this, 'admin_hidden_log'));
    }

    /**
     * Admin Menü neues Archiv
     */
    public function admin_menu_add_archive()
    {
        require_once (plugin_dir_path(__FILE__) . '../templates/admin_menu_add_archive.php');
    }

    /**
     * Admin Menü Datei hinzufügen
     */
    public function admin_menu_add_file()
    {
        require_once (plugin_dir_path(__FILE__) . '../templates/admin_menu_add_file.php');
    }

    /**
     * Admin Menü Dashboard
     */
    public function admin_menu_dashboard()
    {
        require_once (plugin_dir_path(__FILE__) . '../templates/admin_menu_dashboard.php');
    }

    /**
     * Admin Menü Dateien
     */
    public function admin_menu_files()
    {
        require_once (plugin_dir_path(__FILE__) . '../templates/admin_menu_files.php');
    }

    /**
     * Admin Menü Einstellungen
     */
    public function admin_menu_settings()
    {
        require_once (plugin_dir_path(__FILE__) . '../templates/admin_menu_settings.php');
    }

    /**
     * Admin Menü Archive
     */
    public function admin_menu_ziparchive()
    {
        require_once (plugin_dir_path(__FILE__) . '../templates/admin_menu_ziparchive.php');
    }

    /**
     * Versteckt Admin Hilfe
     */
    public function admin_hidden_help()
    {
        require_once (plugin_dir_path(__FILE__) . '../templates/admin_hidden_help.php');
    }

    /**
     * Versteckt Admin Log
     */
    public function admin_hidden_log()
    {
        require_once (plugin_dir_path(__FILE__) . '../templates/admin_hidden_log.php');
    }

    /**
     * Prüft ob eine spezielle Datei zu einem Archiv verknüpft ist
     * @param int $file_id ID der Datei
     * @param int $archive_id ID des Archives
     * @return bool
     */
    public function check_file_rel_to_archive($files_id, $archive_id)
    {
        global $wpdb;

        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        // Daten aus DB files_rel holen
        $db_file_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE id_file = '$files_id' 
            AND id_archive = '$archive_id'
            "
            );

        if ($db_file_rel[0] === NULL) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Prüft Dateiverknüpfungen und aktualisiert den Cache
     * @param int $archive_id ID des Archives
     */
    public function check_files_from_archive($archive_id)
    {

        global $wpdb;
        
        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        // Daten aus DB files_rel holen
        $db_files_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE file_updated = '1' 
            AND id_archive = '$archive_id'
            "
            );

        if (count($db_files_rel) > 0) {
            $this->create_archive_cache($archive_id);
        }
    }

    /**
     * Prüft ob eine Datei zu einem Archiv verknüpft ist
     * @param int $archive_id ID des Archives
     * @return bool
     */
    public function check_if_any_file_rel_to_archive($archive_id)
    {
        global $wpdb;
        
        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        // Daten aus DB files_rel holen
        $db_files_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE id_archive = '$archive_id'
            "
            );

        if (count($db_files_rel) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Prüft ob die Datei in einem Archiv verknüpft ist
     *
     * @param int $files_id
     * @return mixed
     */
    public function check_if_file_is_in_archive($files_id)
    {
        global $wpdb;

        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        // Daten aus DB files_rel holen
        $db_file_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE id_file = '$files_id'
            "
            );

        $db_count = count($db_file_rel);

        if ($db_count > 0) {
            return $db_count;
        } else {
            return false;
        }
    }

    /**
     * Prüft ob alle Dateien zu einem Archiv aktuell sind
     *
     * @param int $archive_id
     * @return bool
     */
    public function check_if_archive_cache_ok($archive_id)
    {

        global $wpdb;
        
        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        // Daten aus DB files_rel holen
        $db_files_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE file_updated = '1' 
            AND id_archive = '$archive_id'
            "
            );

        // Daten aus DB files_rel holen
        $db_files_rel_check_archive = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE id_archive = '$archive_id'
            "
            );
        
        // Check ob das Archiv eine Datei enthält
        if (count($db_files_rel_check_archive) < 1) {
            return false;
        } else {

            // Check ob eine Datei verändert wurde
            if (count($db_files_rel) > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Prüft ob ein Archiv existiert
     *
     * @param [type] $archive_id
     * @return bool
     */
    public function check_if_archive_exists($archive_id)
    {
        global $wpdb;
        
        $tablename_archives = $wpdb->prefix . "zdm_archives";

        // Daten aus DB archives holen
        $db_archives = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_archives 
            WHERE id = '$archive_id'
            "
            );

        if (count($db_archives) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Prüft ob eine Datei existiert
     *
     * @param [type] $file_id
     * @return bool
     */
    public function check_if_file_exists($file_id)
    {
        global $wpdb;
        
        $tablename_files = $wpdb->prefix . "zdm_files";

        // Daten aus DB files holen
        $db_files = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files 
            WHERE id = '$file_id'
            "
            );

        if (count($db_files) >= 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Erzeugt Cache-Datei von Archiv
     * @param int $archive_id ID des Archives
     */
    public function create_archive_cache($archive_id)
    {

        $time = time();

        global $wpdb;

        $tablename_archives = $wpdb->prefix . "zdm_archives";
        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        // Daten aus DB archives holen
        $db_archive = $wpdb->get_results(
            "
            SELECT * 
            FROM $tablename_archives 
            WHERE id = '$archive_id'
            "
            );

        // Daten aus DB files_rel holen
        $db_files_rel = $wpdb->get_results(
            "
            SELECT * 
            FROM $tablename_files_rel 
            WHERE id_archive = '$archive_id' 
            AND file_deleted = '0'
            "
            );

        // Alte Datei und Ordner löschen
        $old_cache_folder = ZDM__DOWNLOADS_CACHE_PATH . '/' . $db_archive[0]->archive_cache_path;
        $old_cache_file = $old_cache_folder . '/' . $db_archive[0]->zip_name . '.zip';
        $old_cache_index = $old_cache_folder . '/' . 'index.php';
        if (file_exists($old_cache_file)) {
            unlink($old_cache_file);
        }
        if (file_exists($old_cache_index)) {
            unlink($old_cache_index);
        }
        if ($db_archive[0]->archive_cache_path != '') {
            if (is_dir($old_cache_folder)) {
                rmdir($old_cache_folder);
            }
        }
        
        // Archiv Cache Pfad
        // Ordnername erstellen
        $archive_cache_path = md5(time() . $archive_id);

        // Ordner erstellen
        if (!is_dir(ZDM__DOWNLOADS_CACHE_PATH . '/' . $archive_cache_path)) {
            wp_mkdir_p(ZDM__DOWNLOADS_CACHE_PATH . '/' . $archive_cache_path);
        }

        // Cache-Datei Pfad
        $file_path = ZDM__DOWNLOADS_CACHE_PATH . '/' . $archive_cache_path . '/' . $db_archive[0]->zip_name . '.zip';

        // index.php in Ordner kopieren
        $index_file = ZDM__DOWNLOADS_CACHE_PATH . '/' . $archive_cache_path . '/' . 'index.php';
        if (!file_exists($index_file)) {
            copy('index.php', $index_file);
        }

        // Dateien in Array speichern
        for ($i = 0; $i < count($db_files_rel); $i++) {
            $files[] = ZDM__DOWNLOADS_FILES_PATH . '/' . $this->get_file_data($db_files_rel[$i]->id_file)->folder_path . '/' . $this->get_file_data($db_files_rel[$i]->id_file)->file_name;
        }

        // Neue Instanz der ZipArchive Klasse
        $zip = new ZipArchive;

        // Zip-Archiv erstellen
        if ($zip->open($file_path, ZipArchive::CREATE) === TRUE) {

            // Dateien ins Zip-Archiv einfügen
            for ($i = 0; $i < count($db_files_rel); $i++) {
                $zip->addFile($files[$i], $this->get_file_data($db_files_rel[$i]->id_file)->file_name);
            }

            // Zip-Archiv schließen
            $zip->close();
        } else {
            // Log
            $this->log('error create zip', $file_path);
        }

        // Dateigröße bestimmen
        $file_size = $this->file_size_convert(filesize($file_path));

        // MD5 aus Datei
        $archive_hash_md5 = md5_file($file_path);

        // SHA1 aus Datei
        $archive_hash_sha1 = sha1_file($file_path);

        // Daten in archives aktualisieren
        $wpdb->update(
            $tablename_archives, 
            array(
                'archive_cache_path' => $archive_cache_path,
                'hash_md5'      => $archive_hash_md5,
                'hash_sha1'     => $archive_hash_sha1,
                'file_size'     => $file_size,
                'time_update'   => $time
            ), 
            array(
                'id' => $archive_id
            ));

        // file_updated in files_rel aktualisieren
        $wpdb->update(
            $tablename_files_rel, 
            array(
                'file_updated'  => 0,
                'time_update'   => $time
            ), 
            array(
                'id_archive' => $archive_id
            ));

        // Daten aus DB files_rel holen
        $db_files_rel_deleted = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE id_archive = '$archive_id' 
            AND file_deleted = '1'
            "
            );

        // Löscht alle Einträge aus files_rel mit file_deleted = '1' die zum Archiv zeigen
        for ($i = 0; $i < count($db_files_rel_deleted); $i++) { 
            $wpdb->delete(
                $tablename_files_rel, 
                array(
                    'id' => $db_files_rel_deleted[$i]->id
                ));
        }

        // Log
        $this->log('create archive cache' , 'ID: ' . $archive_id . ', path: ' . $file_path);
    }

    /**
     * Löscht alle Daten
     */
    public function delete_all_data()
    {

        global $wpdb;

        // DB Tabellenname
        $zdm_tablename_archives = $wpdb->prefix . "zdm_archives";
        $zdm_tablename_files = $wpdb->prefix . "zdm_files";
        $zdm_tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        ////////////////////
        // Archive löschen
        ////////////////////
        
        $zdm_db_archives = $wpdb->get_results( 
            "
            SELECT * 
            FROM $zdm_tablename_archives
            "
        );

        for ($i=0; $i < count($zdm_db_archives); $i++) {

            if ($zdm_db_archives[$i]->archive_cache_path != '') {
            
                // Datei und Ordner löschen
                $zdm_cache_folder = ZDM__DOWNLOADS_CACHE_PATH . '/' . $zdm_db_archives[$i]->archive_cache_path;
                $zdm_cache_file = $zdm_cache_folder . '/' . $zdm_db_archives[$i]->zip_name . '.zip';
                $zdm_cache_index = $zdm_cache_folder . '/' . 'index.php';

                if (file_exists($zdm_cache_file)) {
                    unlink($zdm_cache_file);
                }
                if (file_exists($zdm_cache_index)) {
                    unlink($zdm_cache_index);
                }
                if (is_dir($zdm_cache_folder)) {
                    rmdir($zdm_cache_folder);
                }
            }

            // DB Einträge löschen
            $wpdb->delete(
                $zdm_tablename_archives, 
                array(
                    'id' => $zdm_db_archives[$i]->id
                ));
        }

        ////////////////////
        // Dateien löschen
        ////////////////////
        
        $zdm_db_file = $wpdb->get_results( 
            "
            SELECT * 
            FROM $zdm_tablename_files
            "
        );

        for ($i=0; $i < count($zdm_db_file); $i++) {
            
            // Datei und Ordner löschen
            $zdm_folder_path = ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_db_file[$i]->folder_path;
            $zdm_file_path = $zdm_folder_path . '/' . $zdm_db_file[$i]->file_name;
            $zdm_file_index = $zdm_folder_path . '/' . 'index.php';

            if (file_exists($zdm_file_path)) {
                unlink($zdm_file_path);
            }
            if (file_exists($zdm_file_index)) {
                unlink($zdm_file_index);
            }
            if (is_dir($zdm_folder_path)) {
                rmdir($zdm_folder_path);
            }

            // DB Einträge löschen
            $wpdb->delete(
                $zdm_tablename_files, 
                array(
                    'id' => $zdm_db_file[$i]->id
                ));
        }

        ////////////////////
        // files_rel in DB löschen
        ////////////////////
        
        $zdm_db_file_rel = $wpdb->get_results( 
            "
            SELECT id 
            FROM $zdm_tablename_files_rel
            "
        );

        for ($i=0; $i < count($zdm_db_file_rel); $i++) {

            // DB Einträge löschen
            $wpdb->delete(
                $zdm_tablename_files_rel, 
                array(
                    'id' => $zdm_db_file_rel[$i]->id
                ));
        }

        ////////////////////
        // Hauptordner löschen
        ////////////////////

        if (is_dir(ZDM__DOWNLOADS_CACHE_PATH)) {
            @rmdir(ZDM__DOWNLOADS_CACHE_PATH);
        }

        if (is_dir(ZDM__DOWNLOADS_FILES_PATH)) {
            @rmdir(ZDM__DOWNLOADS_FILES_PATH);
        }

        if (is_dir(ZDM__DOWNLOADS_PATH)) {
            @rmdir(ZDM__DOWNLOADS_PATH);
        }

        // Log
        $this->log('delete all data');
    }

    /**
     * Download Steuerung
     */
    public function download()
    {

        // HTTP user agent
        $http_user_agent = @filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');

        require_once(ZDM__PATH . '/lib/bot_user_agents.php');

        if (in_array($http_user_agent, ZDM__BOT_USER_AGENTS)) {
            // Zugriff von Bot
        } else {
            // Zugriff von Benutzer

            ////////////////////
            // zip
            ////////////////////
            if (isset($_GET['zdownload'])) {

                $zdownload_url = base64_decode(filter_input(INPUT_GET, 'zdownload', FILTER_SANITIZE_URL));

                if ($zdownload_url != '' && is_numeric($zdownload_url)) {

                    if ($this->check_if_archive_exists($zdownload_url) === true) {

                        global $wpdb;
                        $tablename_archives = $wpdb->prefix . "zdm_archives";

                        // Daten aus DB archives holen
                        $db_archive = $wpdb->get_results(
                            "
                            SELECT zip_name, archive_cache_path 
                            FROM $tablename_archives 
                            WHERE id = '$zdownload_url'
                            "
                            );
                            
                        // Count aktualisieren
                        $count_new = $db_archive[0]->count + 1;
                        $wpdb->update(
                            $tablename_archives, 
                            array(
                                'count'         => $count_new,
                                'time_update'   => time()
                            ), 
                            array(
                                'id' => $zdownload_url
                            ));

                        // Statistik
                        $this->log('download archive', $zdownload_url);

                        // Pfad für ZIP-Datei
                        $zip_file = ZDM__DOWNLOADS_CACHE_PATH_URL . '/' . $db_archive[0]->archive_cache_path . '/' . $db_archive[0]->zip_name . '.zip';
                        header("Location: $zip_file");
                    } // end if ($this->check_if_archive_exists($zdownload_url) === true)
                } // end if ($zdownload_url != '' && is_numeric($zdownload_url))
            } // end if (isset($_GET['zdownload']))

            ////////////////////
            // file
            ////////////////////
            if (isset($_GET['zdownload_f'])) {

                $zdownload_url = base64_decode(filter_input(INPUT_GET, 'zdownload_f', FILTER_SANITIZE_URL));

                if ($zdownload_url != '' && is_numeric($zdownload_url)) {

                    if ($this->check_if_file_exists($zdownload_url) === true) {

                        global $wpdb;
                        $tablename_files = $wpdb->prefix . "zdm_files";

                        // Daten aus DB files holen
                        $db_files = $wpdb->get_results(
                            "
                            SELECT folder_path, file_name, file_type 
                            FROM $tablename_files 
                            WHERE id = '$zdownload_url'
                            "
                            );

                        // Count aktualisieren
                        $count_new = $db_files[0]->count + 1;
                        $wpdb->update(
                            $tablename_files, 
                            array(
                                'count'         => $count_new,
                                'time_update'   => time()
                            ), 
                            array(
                                'id' => $zdownload_url
                            ));

                        // Statistik
                        $this->log('download file', $zdownload_url);

                        // Interner Pfad für Datei
                        $file_path = ZDM__DOWNLOADS_FILES_PATH . '/' . $db_files[0]->folder_path . '/' . $db_files[0]->file_name;

                        // Dateigröße
                        $file_size = filesize($file_path);

                        // Datei bereitstellen
                        header('Content-Disposition: attachment; filename=' . $db_files[0]->file_name);
                        header('Content-type: application/force-download');
                        header('Content-Length: ' . $file_size);
                        header('Content-type: ' . $db_files[0]->file_type . '; charset=utf-8');
                        readfile($file_path);
                    } // end if ($this->check_if_file_exists($zdownload_url) === true)
                } // end if ($zdownload_url != '' && is_numeric($zdownload_url))
            } // end if (isset($_GET['zdownload_f']))
        } // end else (in_array($http_user_agent, ZDM__BOT_USER_AGENTS))
    }

    /**
     * Gibt die jeweiligen CSS Klassen zurück für Download Button
     * @return string CSS class
     */
    public function download_button_class()
    {

        $options = get_option('zdm_options');

        // Standard CSS Klassen
        $class = 'button zdm-btn';

        if ($options['download-btn-outline'] == 'on') {
            $outline = '-outline';
        } else {
            $outline = '';
        }

        $class .= ' zdm-btn-style-' . $options['download-btn-style'] . $outline;

        if ($options['download-btn-border-radius'] != 'none') {
            $class .= ' zdm-btn-radius' . $options['download-btn-border-radius'];
        }

        return $class;
    }

    /**
     * Bindet Admin Skripte ein
     */
    public function enqueue_admin_scripts()
    {
        // Admin CSS
        wp_register_style('zdm_admin_styles', plugins_url('../admin/css/zdm_admin_style.css?v=' . ZDM__VERSION . time(), __FILE__));
        wp_enqueue_style('zdm_admin_styles');

        // Ionicons CSS
        wp_register_style('zdm_admin_styles_ionic', 'https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css');
        wp_enqueue_style('zdm_admin_styles_ionic');

        // Ionicons JS
        wp_register_script('zdm_admin_ionic_js', 'https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js');
        wp_enqueue_script('zdm_admin_ionic_js');
    }

    /**
     * Bindet Frontend Skripte ein
     */
    public function enqueue_frontend_scripts()
    {
        // Frontend CSS
        wp_register_style('zdm_styles', plugins_url('../public/css/zdm_style.css?v=' . ZDM__VERSION, __FILE__));
        wp_enqueue_style('zdm_styles');

        // Ionicons CSS
        wp_register_style('zdm_styles_ionic', 'https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css');
        wp_enqueue_style('zdm_styles_ionic');

        // Ionicons JS
        wp_register_script('zdm_ionic_js', 'https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js');
        wp_enqueue_script('zdm_ionic_js');
    }

    /**
     * Konvertiert Bytes in B, KB, MB, GB, TB
     * @param int $bytes Bytes als Input
     * @return string
     */
    public function file_size_convert($bytes)
    {
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    /**
     * Konvertiert Strings zu Bytes
     * @param string $str String wie von ini_get('upload_max_filesize')
     * @return mixed
     */
    public function file_size_convert_str2bytes($str) {
        // Nur Strings
        $unit_byte = preg_replace('/[^a-zA-Z]/', '', $str);
        $unit_byte = strtolower($unit_byte);
        // Nur Zahlen (Dezimalpunkt zulassen)
        $num_val = preg_replace('/\D\.\D/', '', $str);
        switch ($unit_byte) {
            case 'p':	// petabyte
            case 'pb':
                $num_val *= 1024;
            case 't':	// terabyte
            case 'tb':
                $num_val *= 1024;
            case 'g':	// gigabyte
            case 'gb':
                $num_val *= 1024;
            case 'm':	// megabyte
            case 'mb':
                $num_val *= 1024;
            case 'k':	// kilobyte
            case 'kb':
                $num_val *= 1024;
            case 'b':	// byte
                return $num_val *= 1;
                break; // Stelle sichen
            default:
                return FALSE;
        }
        return FALSE;
    }

    /**
     * Gibt Archivname zurück
     *
     * @param [type] $archive_id
     * @return string
     */
    public function get_archive_name($archive_id)
    {
        global $wpdb;

        $tablename_archives = $wpdb->prefix . "zdm_archives";

        // Daten aus DB archives holen
        $db_archive = $wpdb->get_results(
            "
            SELECT name 
            FROM $tablename_archives 
            WHERE id = '$archive_id'
            "
            );

        return $db_archive[0]->name;
    }

    /**
     * Prüft Hashwert einer Datei
     *
     * @param string $type
     * @param string $hash
     * @return int Anzahl an gefundenen Hashes
     */
    public function get_count_of_files_by_hash($type, $hash)
    {
        global $wpdb;

        $tablename_files = $wpdb->prefix . "zdm_files";

        if ($type == 'md5') {

            // Daten aus DB files holen
            $db_file = $wpdb->get_results(
                "
                SELECT id 
                FROM $tablename_files 
                WHERE hash_md5 = '$hash'
                "
                );
        }

        if ($type == 'sha1') {

            // Daten aus DB files holen
            $db_file = $wpdb->get_results(
                "
                SELECT id 
                FROM $tablename_files 
                WHERE hash_sha1 = '$hash'
                "
                );
        }

        if ($db_file[0] === NULL) {
            return count($db_file);
        } else {
            return count($db_file);
        }
    }

    /**
     * Gibt die ID des aktuell eingeloggten Benutzers zurück
     * @return int WordPress Benutzer ID
     */
    public function get_current_user_id()
    {
        if (!function_exists('wp_get_current_user'))
            return 0;
        $user = wp_get_current_user();
        return (isset($user->ID) ? (int) $user->ID : 0);
    }

    /**
     * Gibt Objekt mit allen Daten von files aus DB zurück
     * @param int $file_id ID der Datei
     * @return object
     */
    public function get_file_data($file_id)
    {
        global $wpdb;

        $tablename_files = $wpdb->prefix . "zdm_files";

        // Daten aus DB files holen
        $db_file = $wpdb->get_results(
            "
            SELECT * 
            FROM $tablename_files 
            WHERE id = '$file_id'
            "
            );

        return $db_file[0];
    }

    /**
     * Gibt Name von Datei zurück
     * @param int $file_id ID der Datei
     * @return object
     */
    public function get_file_name($file_id)
    {
        global $wpdb;

        $tablename_files = $wpdb->prefix . "zdm_files";

        // Daten aus DB files holen
        $db_file = $wpdb->get_results(
            "
            SELECT name 
            FROM $tablename_files 
            WHERE id = '$file_id'
            "
            );

        return $db_file[0]->name;
    }

    /**
     * Prüft den Lizenzschlüssel
     * @return bool Gibt true zurück wenn gültig und false wenn nicht
     */
    public function licence()
    {
        $options = get_option('zdm_options');

        if ($options['licence-key'] != '') {

            $timeDiff = time() - $options['licence-time'];

            if ($timeDiff > 2592000) { // 2592000 = 30 Tage
                
                $licence_array = $this->licence_array();

                if ($licence_array['success'] === true) {

                    $options['licence-email'] = $licence_array['purchase']['email'];
                    $options['licence-purchase'] = $licence_array['purchase']['created_at'];
                    $options['licence-product-name'] = $licence_array['purchase']['product_name'];
                    $options['licence-time'] = time();

                    if (add_option('zdm_options', $options) === FALSE) {
                        update_option('zdm_options', $options);
                    }

                    $options = get_option('zdm_options');

                    return true;
                } else {

                    $options['licence-key'] = '';
                    $options['licence-email'] = '';
                    $options['licence-purchase'] = '';
                    $options['licence-product-name'] = '';
                    $options['licence-time'] = 0;

                    if (add_option('zdm_options', $options) === FALSE) {
                        update_option('zdm_options', $options);
                    }

                    $options = get_option('zdm_options');

                    return false;
                }
            } else {
                return true;
            }
        } else {

            $options['licence-email'] = '';
            $options['licence-purchase'] = '';
            $options['licence-product-name'] = '';
            $options['licence-time'] = 0;

            if (add_option('zdm_options', $options) === FALSE) {
                update_option('zdm_options', $options);
            }

            $options = get_option('zdm_options');

            return false;
        }
    }

    /**
     * Gibt Daten von Gumroad in Array zurück
     * @param string $licence_key Lizenzschlüssel
     * @return mixed Array wenn gültig, false wenn nicht
     */
    public static function licence_array($licence_key = '')
    {
        if ($licence_key == '') {
            $options = get_option('zdm_options');
            $licence_key = $options['licence-key'];
        }

        $post_data = array(
            'product_permalink' => 'zdPRE',
            'license_key' => $licence_key,
            'increment_uses_count' => 'false'
        );

        $args = array(
            'body' => $post_data,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array()
        );

        $url = wp_remote_post('https://api.gumroad.com/v2/licenses/verify', $args);

        $array = json_decode($url['body'], true);

        if ($array['success'] === true) {
            return $array;
        } else {
            return false;
        }
    }

    /**
     * Log
     * @param string $type Typ des Logs
     * @param string $message Sonstige Informationen
     */
    public function log($type, $message = '---')
    {
        // user IP-Adresse
        if (filter_input(INPUT_SERVER, 'REMOTE_ADDR')) {

            $options = get_option('zdm_options');

            $user_ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR');

            // IP-Adresse anonymisieren
            if ($options['secure-ip'] === 'on') {
                require_once(ZDM__PATH . '/lib/ZDMIPAnonymizer.php');
                $ip_anonymizer = new ZDMIPAnonymizer();
                $user_ip = $ip_anonymizer->anonymize($user_ip);
            }
        } else {
            $user_ip = "---";
        }

        // HTTP user agent
        if (filter_input(INPUT_SERVER, 'HTTP_USER_AGENT')) {
            $http_user_agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
        } else {
            $http_user_agent = "---";
        }

        global $wpdb;
        
        $tablename_log = $wpdb->prefix . "zdm_log";

        // Log in DB speichern
        $wpdb->insert(
            $tablename_log, 
            array(
                'type'          => htmlspecialchars($type),
                'message'       => htmlspecialchars($message),
                'user_agent'    => $http_user_agent,
                'user_ip'       => $user_ip,
                'user_id'       => ZDMCore::get_current_user_id(),
                'time_create'   => time()
            )
        );
    }

    /**
     * Formatiert Zahlen und macht einen Punkt bei jedem tausender Schritt
     * @param int $number Zahl Eingabe
     * @return string Formatierte Zahl
     */
    public function number_format($number)
    {
        return number_format($number, 0, ',', '.');
    }

    /**
     * Registriert grundlegende Komponenten in WP
     */
    public function register()
    {

        // Admin Scripte einbinden
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Frontend Scripte einbinden
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));

        // Admin Menu
        add_action('admin_menu', array($this, 'admin_menu'));

        // Fügt Links in der Plugin-Übersicht ein
        add_filter('plugin_action_links_' . $this->plugin_basename, array($this, 'settings_link'));

        // Shortcode Download Archiv und Datei
        add_shortcode('zdownload', array($this, 'shortcode_download'));

        // Shortcode Audio Ausgabe
        add_shortcode('zdownload_audio', array($this, 'shortcode_audio'));

        // Shortcode Metadaten Ausgabe
        add_shortcode('zdownload_meta', array($this, 'shortcode_meta'));

        // Shortcode Video Ausgabe
        add_shortcode('zdownload_video', array($this, 'shortcode_video'));
    }

    /**
     * Fügt Links in der Plugin-Übersicht ein
     *
     * @param [type] $links
     * @return string
     */
    public function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=z-downloads-settings">Einstellungen</a>';
        array_push($links, $settings_link);
        return $links;
    }

    /**
     * Shortcode für HTML5 Audioplayer: [zdownload_audio file="123"]
     *
     * @param [type] $atts
     * @param [type] $content
     * @return void HTML5 Audioplayer
     */
    public function shortcode_audio($atts, $content = null)
    {

        $atts = shortcode_atts(
            array(
                'file'      => '',
                'autoplay'  => '',
                'loop'      => '',
                'controls'  => ''
                ), $atts
        );
        
        $file = htmlspecialchars($atts['file']);
        if (htmlspecialchars($atts['autoplay']) == 'on') {
            $autoplay = ' autoplay';
        } else {
            $autoplay = '';
        }
        if (htmlspecialchars($atts['loop']) == 'on') {
            $loop = ' loop';
        } else {
            $loop = '';
        }
        if (htmlspecialchars($atts['controls']) == 'on') {
            $controls = '';
        } else {
            $controls = ' controls';
        }

        if ($file != '') {

            global $wpdb;
            $tablename_files = $wpdb->prefix . "zdm_files";

            // Daten aus DB files holen
            $db_file = $wpdb->get_results(
                "
                SELECT id, folder_path, file_name, file_type 
                FROM $tablename_files 
                WHERE id = '$file'
                "
                );

            // Ausgabe
            $audio = '<audio preload="none" id="zdmAudio' . $db_file[0]->id . '"' . $autoplay . $loop . $controls . '>';
            $audio .= esc_html__('Dein Browser unterstützt keine HTML Audio-Elemente.', 'zdm');
            $audio .= '<source src="' . ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $db_file[0]->folder_path . '/' . $db_file[0]->file_name . '" type="' . $db_file[0]->file_type . '">';
            $audio .= '</audio>';

            if (in_array($db_file[0]->file_type, ZDM__MIME_TYPES_AUDIO)) {
                return $audio;
            }
        }
    }

    /**
     * Shortcode für Downloads: [zdownload zip="123"] oder [zdownload file="123"]
     * @return string
     */
    public function shortcode_download($atts, $content = null)
    {

        $atts = shortcode_atts(
            array(
                'zip'   => '',
                'file'  => ''
                ), $atts
        );
        
        $zip = htmlspecialchars($atts['zip']);
        $file = htmlspecialchars($atts['file']);

        ////////////////////
        // zip
        ////////////////////
        if ($zip != '') {
            // Check ob überhaupt eine Datei zugewiesen ist
            if ($this->check_if_any_file_rel_to_archive($zip)) {

                $options = get_option('zdm_options');

                // Dateien auf Aktualität prüfen
                $this->check_files_from_archive($zip);

                global $wpdb;
                $tablename_archives = $wpdb->prefix . "zdm_archives";

                // Daten aus DB archives holen
                $db_archive = $wpdb->get_results(
                    "
                    SELECT * 
                    FROM $tablename_archives 
                    WHERE id = '$zip'
                    "
                    );

                // Button Text bestimmen
                if ($options['download-btn-icon-only'] != '') {
                    $download_text = '';
                    $icon_class = '  zdm-btn-icon-only';
                } else {

                    if ($db_archive[0]->button_text != '') {
                        $download_text = $db_archive[0]->button_text;
                    } else {
                        $download_text = $options['download-btn-text'];
                    }

                    $icon_class = ' zdm-btn-icon';
                }

                $type = 'zdownload';
                $id = base64_encode($db_archive[0]->id);

                // Ausgabe
                if ($options['download-btn-icon'] != 'none') {
                    $icon = '<ion-icon name="' . $options['download-btn-icon'] . '" class="' . $icon_class . '"></ion-icon>';
                } else {
                    $icon = '';
                }

                return '<a href="?' . $type . '=' . $id . '" class="' . $this->download_button_class() . '" target="_blank" rel="nofollow">' . $icon . $download_text . '</a>';
            } else {
                // Leerer Rückgabewert wenn keine Datei verknüpft ist
                return '';
            }
        } // end if ($zip != '')

        ////////////////////
        // file
        ////////////////////
        if ($file != '') {

            if ($this->check_if_file_exists($file) === true) {

                $options = get_option('zdm_options');

                global $wpdb;
                $tablename_files = $wpdb->prefix . "zdm_files";

                // Daten aus DB files holen
                $db_files = $wpdb->get_results(
                    "
                    SELECT id, button_text, folder_path, file_name, file_type 
                    FROM $tablename_files 
                    WHERE id = '$file'
                    "
                    );

                // Button Text bestimmen
                if ($options['download-btn-icon-only'] != '') {
                    $download_text = '';
                    $icon_class = '  zdm-btn-icon-only';
                } else {

                    if ($db_files[0]->button_text != '') {
                        $download_text = $db_files[0]->button_text;
                    } else {
                        $download_text = $options['download-btn-text'];
                    }

                    $icon_class = ' zdm-btn-icon';
                }

                $type = 'zdownload_f';
                $id = base64_encode($db_files[0]->id);

                // Ausgabe
                if ($options['download-btn-icon'] != 'none') {
                    $icon = '<ion-icon name="' . $options['download-btn-icon'] . '" class="' . $icon_class . '"></ion-icon>';
                } else {
                    $icon = '';
                }

                // HTTP user agent
                $http_user_agent = @filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');

                require_once(ZDM__PATH . '/lib/bot_user_agents.php');

                if (in_array($http_user_agent, ZDM__BOT_USER_AGENTS)) {
                    // Zugriff von Bot
                } else {
                    // Zugriff von Benutzer

                    if ($options['file-open-in-browser-pdf'] == 'on' && $db_files[0]->file_type == 'application/pdf') {

                        // Externer Pfad für Datei
                        $file_path_url = ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $db_files[0]->folder_path . '/' . $db_files[0]->file_name;

                        return '<a href="' . $file_path_url . '" class="' . $this->download_button_class() . '" target="_blank" rel="nofollow">' . $icon . $download_text . '</a>';
                    } else {
                        return '<a href="?' . $type . '=' . $id . '" class="' . $this->download_button_class() . '" target="_blank" rel="nofollow">' . $icon . $download_text . '</a>';
                    }
                }
            }
        } // end if ($file != '')
    }

    /**
     * Shortcode für Metadaten Ausgabe: [zdownload_meta zip="123" type="count"]
     * @return string Metadaten Ausgabe
     */
    public function shortcode_meta($atts, $content = null)
    {

        $atts = shortcode_atts(
            array(
                'zip'   => '',
                'file'  => '',
                'type'  => ''
                ), $atts
        );
        
        $zip = htmlspecialchars($atts['zip']);
        $file = htmlspecialchars($atts['file']);
        $type = htmlspecialchars($atts['type']);

        if ($type != '') {

            ////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////
            // zip
            ////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////
            if ($zip != '') {

                global $wpdb;
                $tablename_archives = $wpdb->prefix . "zdm_archives";

                ////////////////////
                // Count: Download-Anzahl
                ////////////////////
                if ($type === 'count') {

                    // Daten aus DB archives holen
                    $db_archive = $wpdb->get_results(
                        "
                        SELECT count 
                        FROM $tablename_archives 
                        WHERE id = '$zip'
                        "
                        );

                    // Anzahl Ausgabe
                    return $this->number_format($db_archive[0]->count);
                }

                ////////////////////
                // Dateigröße
                ////////////////////
                if ($type === 'size') {
                    
                    // Check ob überhaupt eine Datei zugewiesen ist
                    if ($this->check_if_any_file_rel_to_archive($zip)) {

                        // Dateien auf Aktualität prüfen
                        $this->check_files_from_archive($zip);

                        global $wpdb;
                        $tablename_archives = $wpdb->prefix . "zdm_archives";

                        // Daten aus DB archives holen
                        $db_archive = $wpdb->get_results(
                            "
                            SELECT file_size 
                            FROM $tablename_archives 
                            WHERE id = '$zip'
                            "
                            );

                        // Dateigröße Ausgabe
                        return $db_archive[0]->file_size;
                    }
                }

                ////////////////////
                // Name
                ////////////////////
                if ($type === 'name') {

                    // Daten aus DB archives holen
                    $db_archive = $wpdb->get_results(
                        "
                        SELECT name 
                        FROM $tablename_archives 
                        WHERE id = '$zip'
                        "
                        );

                    // Name Ausgabe
                    return $db_archive[0]->name;
                }

                ////////////////////
                // Dateiname
                ////////////////////
                if ($type === 'filename') {

                    // Daten aus DB archives holen
                    $db_archive = $wpdb->get_results(
                        "
                        SELECT zip_name 
                        FROM $tablename_archives 
                        WHERE id = '$zip'
                        "
                        );

                    // Dateiname Ausgabe
                    return $db_archive[0]->zip_name . '.zip';
                }

                ////////////////////
                // Hash MD5, Sha1
                ////////////////////
                if ($type === 'hash-md5' OR $type === 'hash-sha1') {
                    
                    // Check ob überhaupt eine Datei zugewiesen ist
                    if ($this->check_if_any_file_rel_to_archive($zip)) {

                        // Dateien auf Aktualität prüfen
                        $this->check_files_from_archive($zip);

                        global $wpdb;
                        $tablename_archives = $wpdb->prefix . "zdm_archives";

                        // Daten aus DB archives holen
                        $db_archive = $wpdb->get_results(
                            "
                            SELECT hash_md5, hash_sha1 
                            FROM $tablename_archives 
                            WHERE id = '$zip'
                            "
                            );

                        // Dateigröße Ausgabe
                        if ($this->licence() != true) {
                            
                        } else {
                            if ($type === 'hash-md5') {
                                return $db_archive[0]->hash_md5;
                            } elseif ($type === 'hash-sha1') {
                                return $db_archive[0]->hash_sha1;
                            }
                        }
                    }
                }
            } // end $zip != ''

            ////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////
            // file
            ////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////
            if ($file != '') {
                    
                global $wpdb;
                $tablename_files = $wpdb->prefix . "zdm_files";

                ////////////////////
                // Count: Download-Anzahl
                ////////////////////
                if ($type === 'count') {
        
                    // Daten aus DB files holen
                    $db_files = $wpdb->get_results(
                        "
                        SELECT count 
                        FROM $tablename_files 
                        WHERE id = '$file'
                        "
                        );
        
                    // Anzahl Ausgabe
                    return $this->number_format($db_files[0]->count);
                }

                ////////////////////
                // Dateigröße
                ////////////////////
                if ($type === 'size') {
                    
                    // Daten aus DB files holen
                    $db_files = $wpdb->get_results(
                        "
                        SELECT file_size 
                        FROM $tablename_files 
                        WHERE id = '$file'
                        "
                        );

                    // Dateigröße Ausgabe
                    return $db_files[0]->file_size;
                }

                ////////////////////
                // Name
                ////////////////////
                if ($type === 'name') {

                    // Daten aus DB files holen
                    $db_files = $wpdb->get_results(
                        "
                        SELECT name 
                        FROM $tablename_files 
                        WHERE id = '$file'
                        "
                        );

                    // Name Ausgabe
                    return $db_files[0]->name;
                }

                ////////////////////
                // Dateiname
                ////////////////////
                if ($type === 'filename') {

                    // Daten aus DB files holen
                    $db_files = $wpdb->get_results(
                        "
                        SELECT file_name 
                        FROM $tablename_files 
                        WHERE id = '$file'
                        "
                        );

                    // Dateiname Ausgabe
                    return $db_files[0]->file_name;
                }

                ////////////////////
                // Hash MD5, Sha1
                ////////////////////
                if ($type === 'hash-md5' OR $type === 'hash-sha1') {

                    // Daten aus DB files holen
                    $db_files = $wpdb->get_results(
                        "
                        SELECT hash_md5, hash_sha1 
                        FROM $tablename_files 
                        WHERE id = '$file'
                        "
                        );

                    // Dateigröße Ausgabe
                    if ($this->licence() != true) {
                        
                    } else {
                        if ($type === 'hash-md5') {
                            return $db_files[0]->hash_md5;
                        } elseif ($type === 'hash-sha1') {
                            return $db_files[0]->hash_sha1;
                        }
                    }
                }
            } // end if ($file != '')
            
        } // end if ($type != '')
    }

    /**
     * Shortcode für HTML5 Videooplayer: [zdownload_video file="123"]
     * @return string HTML5 Videooplayer
     */
    public function shortcode_video($atts, $content = null)
    {

        $atts = shortcode_atts(
            array(
                'file'      => '',
                'w'         => '100%',
                'autoplay'  => '',
                'loop'      => '',
                'controls'  => ''
                ), $atts
        );
        
        $file = htmlspecialchars($atts['file']);
        $width = htmlspecialchars($atts['w']);
        if (htmlspecialchars($atts['autoplay']) == 'on') {
            $autoplay = ' autoplay';
        } else {
            $autoplay = '';
        }
        if (htmlspecialchars($atts['loop']) == 'on') {
            $loop = ' loop';
        } else {
            $loop = '';
        }
        if (htmlspecialchars($atts['controls']) == 'on') {
            $controls = '';
        } else {
            $controls = ' controls';
        }

        if ($file != '') {

            global $wpdb;
            $tablename_files = $wpdb->prefix . "zdm_files";

            // Daten aus DB files holen
            $db_file = $wpdb->get_results(
                "
                SELECT id, folder_path, file_name, file_type 
                FROM $tablename_files 
                WHERE id = '$file'
                "
                );

            // Ausgabe
            $video = '<video id="zdmVideo' . $db_file[0]->id . '" width="' . $width . '"' . $autoplay . $loop . $controls . '>';
            $audio .= esc_html__('Dein Browser unterstützt keine HTML Video-Elemente.', 'zdm');
            $video .= '<source src="' . ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $db_file[0]->folder_path . '/' . $db_file[0]->file_name . '" type="' . $db_file[0]->file_type . '">';
            $video .= '</video>';

            if (in_array($db_file[0]->file_type, ZDM__MIME_TYPES_VIDEO)) {
                return $video;
            }
        }
    }
}
