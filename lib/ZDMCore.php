<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

/**
 * ZDM core main class
 */
class ZDMCore
{

    protected $plugin_basename;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->plugin_basename = plugin_basename(ZDM__PATH . ZDM__SLUG . '.php');
    }

    /**
     * Erstellt einen Eintrag im Backend mit mehreren Seiten im Menü
     *
     * @return void
     */
    public function admin_menu()
    {
        add_menu_page(
            ZDM__TITLE,                             // Seitentitel
            ZDM__TITLE,                             // Menütext
            ZDM__STANDARD_USER_ROLE,                // Zugriffslevel
            ZDM__SLUG,                              // Pfad der Funktion, (__FILE__ bedeutet die Funktion ist in dieser Datei)
            array($this, 'admin_menu_dashboard'),   // Name der Datei die ausgeführt wird
            'dashicons-download'
        );                  // Dashicon im Adminmenü
        add_submenu_page(
            ZDM__SLUG,                              // top-level menu
            esc_html__('Files', 'zdm'),             // Seitentitel
            esc_html__('Files', 'zdm'),             // Menütext
            ZDM__STANDARD_USER_ROLE,                // Zugriffslevel
            ZDM__SLUG . '-files',                   // URL von Submenü
            array($this, 'admin_menu_files')
        );      // Name der Datei die ausgeführt wird
        add_submenu_page(
            ZDM__SLUG,
            esc_html__('Add file', 'zdm'),
            esc_html__('Add file', 'zdm'),
            ZDM__STANDARD_USER_ROLE,
            ZDM__SLUG . '-add-file',
            array($this, 'admin_menu_add_file')
        );
        add_submenu_page(
            ZDM__SLUG,
            esc_html__('Archives', 'zdm'),
            esc_html__('Archives', 'zdm'),
            ZDM__STANDARD_USER_ROLE,
            ZDM__SLUG . '-ziparchive',
            array($this, 'admin_menu_ziparchive')
        );
        add_submenu_page(
            ZDM__SLUG,
            esc_html__('Create archive', 'zdm'),
            esc_html__('Create archive', 'zdm'),
            ZDM__STANDARD_USER_ROLE,
            ZDM__SLUG . '-add-archive',
            array($this, 'admin_menu_add_archive')
        );
        add_submenu_page(
            ZDM__SLUG,
            esc_html__('Settings', 'zdm'),
            esc_html__('Settings', 'zdm'),
            ZDM__STANDARD_USER_ROLE,
            ZDM__SLUG . '-settings',
            array($this, 'admin_menu_settings')
        );
        add_submenu_page(
            ZDM__SLUG,
            esc_html__('Logs', 'zdm'),
            esc_html__('Logs', 'zdm'),
            ZDM__STANDARD_USER_ROLE,
            ZDM__SLUG . '-log',
            array($this, 'admin_hidden_log')
        );
        add_submenu_page(
            ZDM__SLUG,
            esc_html__('Help', 'zdm'),
            esc_html__('Help', 'zdm'),
            ZDM__STANDARD_USER_ROLE,
            ZDM__SLUG . '-help',
            array($this, 'admin_menu_help')
        );
        if (self::licence() != true) {
            add_submenu_page(
                ZDM__SLUG,
                esc_html__('Premium', 'zdm'),
                esc_html__('Premium', 'zdm'),
                ZDM__STANDARD_USER_ROLE,
                ZDM__SLUG . '-premium',
                array($this, 'admin_premium')
            );
            add_submenu_page(
                ZDM__SLUG,
                esc_html__('Upgrade', 'zdm'),
                esc_html__('Upgrade', 'zdm'),
                ZDM__STANDARD_USER_ROLE,
                ZDM__PRO_URL
            );
        }
    }

    /**
     * Adminmenü: Dashboard
     *
     * @return void
     */
    public function admin_menu_dashboard()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin_menu_dashboard.php');
    }

    /**
     * Adminmenü: Dateien
     *
     * @return void
     */
    public function admin_menu_files()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin_menu_files.php');
    }

    /**
     * Adminmenü: Datei hinzufügen
     *
     * @return void
     */
    public function admin_menu_add_file()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin_menu_add_file.php');
    }

    /**
     * Adminmenü: Archive
     *
     * @return void
     */
    public function admin_menu_ziparchive()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin_menu_ziparchive.php');
    }

    /**
     * Adminmenü: Neues Archiv
     *
     * @return void
     */
    public function admin_menu_add_archive()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin_menu_add_archive.php');
    }

    /**
     * Adminmenü: Einstellungen
     *
     * @return void
     */
    public function admin_menu_settings()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin_menu_settings.php');
    }

    /**
     * Adminmenü: Hilfe
     *
     * @return void
     */
    public function admin_menu_help()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin_menu_help.php');
    }

    /**
     * Adminmenü: Log (versteckt)
     *
     * @return void
     */
    public function admin_hidden_log()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin_hidden_log.php');
    }

    /**
     * Adminmenü: Premium (versteckt)
     *
     * @return void
     */
    public function admin_premium()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin_premium.php');
    }

    /**
     * Überprüft, ob eine spezielle Datei mit einem Archiv verknüpft ist
     * 
     * @param int $file_id
     * @param int $archive_id
     * @return bool
     */
    public static function check_file_rel_to_archive($files_id, $archive_id)
    {
        global $wpdb;

        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        $db_file_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE id_file = '$files_id' 
            AND id_archive = '$archive_id'
            "
        );

        if (@$db_file_rel[0] === NULL)
            return false;

        return true;
    }

    /**
     * Überprüft Dateizuordnungen und aktualisiert den Cache
     *
     * @param int $archive_id
     * @return void
     */
    public static function check_files_from_archive($archive_id)
    {
        global $wpdb;

        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        $db_files_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE file_updated = '1' 
            AND id_archive = '$archive_id'
            "
        );

        if (count($db_files_rel) > 0)
            self::create_archive_cache($archive_id);
    }

    /**
     * Prüft ob der übergebene User Agent zu einem Bot gehört
     *
     * @return bool true wenn es ein Bot ist, false wenn nicht
     */
    public static function check_for_bot()
    {
        require_once(ZDM__PATH . '/lib/bot_user_agents.php');
        $http_user_agent = @filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
        $bot_list = ZDM__BOT_USER_AGENTS;
        $values = '';
        foreach ($bot_list as $value) {
            $values .= $value . '|';
        }
        $values = rtrim($values, '|');
        if (preg_match("/$values/i", $http_user_agent, $matches))
            return true;
        return false;
    }

    /**
     * Überprüft, ob eine Datei mit einem Archiv verknüpft ist
     * 
     * @param int $archive_id
     * @return bool
     */
    public static function check_if_any_file_rel_to_archive($archive_id)
    {
        global $wpdb;

        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        $db_files_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE id_archive = '$archive_id'
            "
        );

        if (count($db_files_rel) > 0)
            return true;

        return false;
    }

    /**
     * Prüft ob die Datei in einem Archiv verknüpft ist
     *
     * @param int $files_id
     * @return mixed
     */
    public static function check_if_file_is_in_archive($files_id)
    {
        global $wpdb;

        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        $db_file_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE id_file = '$files_id'
            "
        );

        $db_count = count($db_file_rel);

        if ($db_count > 0)
            return $db_count;

        return false;
    }

    /**
     * Überprüft, ob alle Dateien in einem Archiv aktuell sind
     *
     * @param int $archive_id
     * @return bool
     */
    public static function check_if_archive_cache_ok($archive_id)
    {
        global $wpdb;

        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        $db_files_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE file_updated = '1' 
            AND id_archive = '$archive_id'
            "
        );

        $db_files_rel_check_archive = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE id_archive = '$archive_id'
            "
        );

        // Überprüfen Sie, ob das Archiv eine Datei enthält
        if (count($db_files_rel_check_archive) < 1) {
            return false;
        } else {

            // Überprüfen Sie, ob eine Datei geändert wurde
            if (count($db_files_rel) > 0)
                return false;

            return true;
        }
    }

    /**
     * Überprüft, ob das Archiv vorhanden ist
     *
     * @param int $archive_id
     * @return bool
     */
    public static function check_if_archive_exists($archive_id)
    {
        if (is_numeric($archive_id)) {
            global $wpdb;

            $tablename_archives = $wpdb->prefix . "zdm_archives";

            $db_archives = $wpdb->get_results(
                "
                SELECT id 
                FROM $tablename_archives 
                WHERE id = '$archive_id'
                "
            );

            if (count($db_archives) > 0)
                return true;

            return false;
        } else {
            return false;
        }
    }

    /**
     * Überprüft, ob die Datei vorhanden ist
     *
     * @param int $file_id
     * @return bool
     */
    public static function check_if_file_exists($file_id)
    {
        if (is_numeric($file_id)) {
            global $wpdb;

            $tablename_files = $wpdb->prefix . "zdm_files";

            $db_files = $wpdb->get_results(
                "
                SELECT id 
                FROM $tablename_files 
                WHERE id = '$file_id'
                "
            );

            if (count($db_files) >= 1)
                return true;

            return false;
        } else {
            return false;
        }
    }

    /**
     * Erstellt eine Cache-Datei aus dem Archiv
     *
     * @param int $archive_id
     * @return void
     */
    public static function create_archive_cache($archive_id)
    {
        $time = time();

        global $wpdb;

        $tablename_archives = $wpdb->prefix . "zdm_archives";
        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        $db_archive = $wpdb->get_results(
            "
            SELECT * 
            FROM $tablename_archives 
            WHERE id = '$archive_id'
            "
        );

        $db_files_rel = $wpdb->get_results(
            "
            SELECT * 
            FROM $tablename_files_rel 
            WHERE id_archive = '$archive_id' 
            AND file_deleted = '0'
            "
        );

        $db_files_rel_count = count($db_files_rel);

        // Alte Datei und Ordner löschen
        $old_cache_folder = ZDM__DOWNLOADS_CACHE_PATH . '/' . $db_archive[0]->archive_cache_path;
        $old_cache_file = $old_cache_folder . '/' . $db_archive[0]->zip_name . '.zip';
        $old_cache_index = $old_cache_folder . '/' . 'index.php';
        if (file_exists($old_cache_file))
            unlink($old_cache_file);
        if (file_exists($old_cache_index))
            unlink($old_cache_index);
        if ($db_archive[0]->archive_cache_path != '') {
            if (is_dir($old_cache_folder))
                rmdir($old_cache_folder);
        }

        // Archiv-Cache Pfad
        // Ordnernamen erstellen
        $archive_cache_path = md5(time() . $archive_id);

        // Ordner erstellen
        if (!is_dir(ZDM__DOWNLOADS_CACHE_PATH . '/' . $archive_cache_path))
            wp_mkdir_p(ZDM__DOWNLOADS_CACHE_PATH . '/' . $archive_cache_path);

        // Cachedatei Pfad
        $file_path = ZDM__DOWNLOADS_CACHE_PATH . '/' . $archive_cache_path . '/' . $db_archive[0]->zip_name . '.zip';

        // Erstelle index.php
        $index_file_handle = fopen(ZDM__DOWNLOADS_CACHE_PATH . '/' . $archive_cache_path . '/' . 'index.php', 'w');
        fclose($index_file_handle);

        // Speichere Dateien in Array
        for ($i = 0; $i < $db_files_rel_count; $i++) {
            $file_data = self::get_file_data($db_files_rel[$i]->id_file);
            $files[] = ZDM__DOWNLOADS_FILES_PATH . '/' . $file_data->folder_path . '/' . $file_data->file_name;
        }

        $zip = new ZipArchive;

        // Erstelle ein Zip-Archiv
        if ($zip->open($file_path, ZipArchive::CREATE) === TRUE) {

            // Dateien ins Zip-Archiv einfügen
            for ($i = 0; $i < $db_files_rel_count; $i++) {
                $zip->addFile($files[$i], self::get_file_data($db_files_rel[$i]->id_file)->file_name);
            }

            $zip->close();
        } else {
            self::log('error archive cache created', 'path: ' . $file_path);
        }

        // Bestimmen Sie die Dateigröße
        $file_size = self::file_size_convert(filesize($file_path));

        // MD5 von Datei
        $archive_hash_md5 = md5_file($file_path);

        // SHA1 von Datei
        $archive_hash_sha1 = sha1_file($file_path);

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
            )
        );

        $wpdb->update(
            $tablename_files_rel,
            array(
                'file_updated'  => 0,
                'time_update'   => $time
            ),
            array(
                'id_archive' => $archive_id
            )
        );

        $db_files_rel_deleted = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename_files_rel 
            WHERE id_archive = '$archive_id' 
            AND file_deleted = '1'
            "
        );

        $db_files_rel_deleted_count = count($db_files_rel_deleted);

        // Lösche alle Einträge aus files_rel mit file_deleted = '1', die auf das Archiv verweisen
        for ($i = 0; $i < $db_files_rel_deleted_count; $i++) {
            $wpdb->delete(
                $tablename_files_rel,
                array(
                    'id' => $db_files_rel_deleted[$i]->id
                )
            );
        }

        self::log('create archive cache', 'ID: ' . $archive_id . ', path: ' . $file_path);
    }

    /**
     * Fügt ein Dashboard-Widget hinzu
     *
     * @return void
     */
    public function dashboard_widget()
    {
        wp_add_dashboard_widget('zdm_dashboard_widget', __('Download statistics', 'zdm'), array($this, 'dashboard_widget_handler'));
    }

    /**
     * Dashboard-Widget Inhalt
     *
     * @return void
     */
    public function dashboard_widget_handler()
    {
        require_once(plugin_dir_path(__FILE__) . '../templates/admin_dashboard_widget.php');
    }

    /**
     * Lösche alle Daten
     *
     * @return void
     */
    public static function delete_all_data()
    {
        global $wpdb;

        $zdm_tablename_archives = $wpdb->prefix . "zdm_archives";
        $zdm_tablename_files = $wpdb->prefix . "zdm_files";
        $zdm_tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        ////////////////////
        // Lösche Archive
        ////////////////////

        $zdm_db_archives = $wpdb->get_results(
            "
            SELECT * 
            FROM $zdm_tablename_archives
            "
        );

        $zdm_db_archives_count = count($zdm_db_archives);

        for ($i = 0; $i < $zdm_db_archives_count; $i++) {

            if ($zdm_db_archives[$i]->archive_cache_path != '') {

                // Dateien und Ordner löschen
                $zdm_cache_folder = ZDM__DOWNLOADS_CACHE_PATH . '/' . $zdm_db_archives[$i]->archive_cache_path;
                $zdm_cache_file = $zdm_cache_folder . '/' . $zdm_db_archives[$i]->zip_name . '.zip';
                $zdm_cache_index = $zdm_cache_folder . '/' . 'index.php';

                if (file_exists($zdm_cache_file))
                    unlink($zdm_cache_file);
                if (file_exists($zdm_cache_index))
                    unlink($zdm_cache_index);
                if (is_dir($zdm_cache_folder))
                    rmdir($zdm_cache_folder);
            }

            // Datenbankeinträge löschen
            $wpdb->delete(
                $zdm_tablename_archives,
                array(
                    'id' => $zdm_db_archives[$i]->id
                )
            );
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

        $zdm_db_file_count = count($zdm_db_file);

        for ($i = 0; $i < $zdm_db_file_count; $i++) {

            // Dateien und Ordner löschen
            $zdm_folder_path = ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_db_file[$i]->folder_path;
            $zdm_file_path = $zdm_folder_path . '/' . $zdm_db_file[$i]->file_name;
            $zdm_file_index = $zdm_folder_path . '/' . 'index.php';

            if (file_exists($zdm_file_path))
                unlink($zdm_file_path);
            if (file_exists($zdm_file_index))
                unlink($zdm_file_index);
            if (is_dir($zdm_folder_path))
                rmdir($zdm_folder_path);

            // Datenbankeinträge löschen
            $wpdb->delete(
                $zdm_tablename_files,
                array(
                    'id' => $zdm_db_file[$i]->id
                )
            );
        }

        ////////////////////
        // Lösche files_rel in der Datenbank
        ////////////////////

        $zdm_db_file_rel = $wpdb->get_results(
            "
            SELECT id 
            FROM $zdm_tablename_files_rel
            "
        );

        $zdm_db_file_rel_count = count($zdm_db_file_rel);

        for ($i = 0; $i < $zdm_db_file_rel_count; $i++) {

            // Datenbankeinträge löschen
            $wpdb->delete(
                $zdm_tablename_files_rel,
                array(
                    'id' => $zdm_db_file_rel[$i]->id
                )
            );
        }

        ////////////////////
        // Hauptordner löschen
        ////////////////////

        if (is_dir(ZDM__DOWNLOADS_CACHE_PATH))
            @rmdir(ZDM__DOWNLOADS_CACHE_PATH);

        if (is_dir(ZDM__DOWNLOADS_FILES_PATH))
            @rmdir(ZDM__DOWNLOADS_FILES_PATH);

        if (is_dir(ZDM__DOWNLOADS_PATH))
            @rmdir(ZDM__DOWNLOADS_PATH);

        self::log('delete all data');
    }

    /**
     * Download controller
     *
     * @return void
     */
    public function download()
    {
        if (!self::check_for_bot()) {

            global $wpdb;

            $options = get_option('zdm_options');

            ////////////////////
            // ZIP
            ////////////////////
            if (isset($_GET['zdownload'])) {

                $zdownload_url = base64_decode(filter_input(INPUT_GET, 'zdownload', FILTER_SANITIZE_URL));

                if ($zdownload_url != '' && is_numeric($zdownload_url)) {

                    if (self::check_if_archive_exists($zdownload_url) === true) {

                        $tablename_archives = $wpdb->prefix . "zdm_archives";

                        $db_archive_query = $wpdb->prepare(
                            "
                            SELECT zip_name, count, archive_cache_path, status 
                            FROM $tablename_archives 
                            WHERE id = %d
                            ",
                            $zdownload_url
                        );
                        $db_archive = $wpdb->get_results($db_archive_query);

                        if ($db_archive[0]->status != 'private') {

                            self::log('download archive', $zdownload_url);

                            $db_archive[0]->count++;
                            $wpdb->update(
                                $tablename_archives,
                                array(
                                    'count' => $db_archive[0]->count
                                ),
                                array(
                                    'id' => $zdownload_url
                                )
                            );

                            // Pfad für Datei
                            $zip_file = ZDM__DOWNLOADS_CACHE_PATH_URL . '/' . $db_archive[0]->archive_cache_path . '/' . $db_archive[0]->zip_name . '.zip';
                            $zip_file_root = ZDM__DOWNLOADS_CACHE_PATH . '/' . $db_archive[0]->archive_cache_path . '/' . $db_archive[0]->zip_name . '.zip';

                            // Provide file
                            header('Content-Description: File Transfer');
                            header('Pragma: public');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate');
                            header("Content-Type: application/zip");
                            header('Content-Disposition: attachment; filename=' . $db_archive[0]->zip_name . '.zip');
                            header('Content-Length: ' . filesize($zip_file_root));
                            readfile($zip_file);
                            exit;
                        }
                    } // end if (self::check_if_archive_exists($zdownload_url) === true)
                    else {
                        self::repair_folder_token_name();
                    }
                } // end if ($zdownload_url != '' && is_numeric($zdownload_url))
            } // end if (isset($_GET['zdownload']))

            ////////////////////
            // Datei
            ////////////////////
            if (isset($_GET['zdownload_f'])) {

                $zdownload_url = base64_decode(filter_input(INPUT_GET, 'zdownload_f', FILTER_SANITIZE_URL));

                if ($zdownload_url != '' && is_numeric($zdownload_url)) {

                    if (self::check_if_file_exists($zdownload_url) === true) {

                        $tablename_files = $wpdb->prefix . "zdm_files";

                        $db_files_query = $wpdb->prepare(
                            "
                            SELECT count, folder_path, file_name, file_type, status 
                            FROM $tablename_files 
                            WHERE id = %d
                            ",
                            $zdownload_url
                        );
                        $db_files = $wpdb->get_results($db_files_query);

                        if ($db_files[0]->status != 'private') {

                            self::log('download file', $zdownload_url);

                            $db_files[0]->count++;
                            $wpdb->update(
                                $tablename_files,
                                array(
                                    'count' => $db_files[0]->count
                                ),
                                array(
                                    'id' => $zdownload_url
                                )
                            );

                            if ($options['file-open-in-browser-pdf'] == 'on' && $db_files[0]->file_type == 'application/pdf') {

                                // Externer Pfad für Datei
                                $file_path_url = ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $db_files[0]->folder_path . '/' . $db_files[0]->file_name;
                                wp_redirect($file_path_url);
                                exit;
                            } else {

                                // Interner Pfad für Datei
                                $file_path = ZDM__DOWNLOADS_FILES_PATH . '/' . $db_files[0]->folder_path . '/' . $db_files[0]->file_name;

                                // Download
                                header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename="' . $db_files[0]->file_name . '"');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($file_path));
                                readfile($file_path);
                                exit;
                            }
                        }
                    } // end if (self::check_if_file_exists($zdownload_url) === true)
                    else {
                        self::repair_folder_token_name();
                    }
                } // end if ($zdownload_url != '' && is_numeric($zdownload_url))
            } // end if (isset($_GET['zdownload_f']))
        } // end else (in_array($http_user_agent, ZDM__BOT_USER_AGENTS))
    }

    /**
     * Gibt die entsprechenden CSS-Klassen für den Download-Button zurück
     *
     * @return string class
     */
    public static function download_button_class()
    {
        $options = get_option('zdm_options');

        // Standard CSS-Klasse
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
     * Integriert Administrationsskripte
     *
     * @return void
     */
    public function enqueue_admin_scripts()
    {
        // Admin CSS
        wp_register_style('zdm_admin_styles', plugins_url('../admin/css/zdm_admin_styles_v1.11.0.min.css', __FILE__));
        wp_enqueue_style('zdm_admin_styles');

        // Material Icons
        wp_register_style('zdm_admin_material_icons', 'https://fonts.googleapis.com/icon?family=Material+Icons+Outlined|Material+Icons+Round');
        wp_enqueue_style('zdm_admin_material_icons');
    }

    /**
     * Integriert Frontend-Skripte
     *
     * @return void
     */
    public function enqueue_frontend_scripts()
    {
        // Frontend CSS
        wp_register_style('zdm_styles', plugins_url('../public/css/zdm_styles_v1.10.0.min.css', __FILE__));
        wp_enqueue_style('zdm_styles');

        // Material Icons
        wp_register_style('zdm_material_icons', 'https://fonts.googleapis.com/icon?family=Material+Icons+Outlined|Material+Icons+Round');
        wp_enqueue_style('zdm_material_icons');
    }

    /**
     * Konvertiert Bytes to B, KB, MB, GB, TB
     * 
     * @param int $bytes Bytes as input
     * @return string
     */
    public static function file_size_convert($bytes)
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

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    /**
     * Konvertiert strings to bytes
     * 
     * @param string $str String as from ini_get ('upload_max_filesize')
     * @return mixed
     */
    public static function file_size_convert_str2bytes($str)
    {
        // Strings only
        $unit_byte = preg_replace('/[^a-zA-Z]/', '', $str);
        $unit_byte = strtolower($unit_byte);
        // Numbers only (allow decimal point)
        $num_val = preg_replace('/\D\.\D/', '', $str);
        $num_val = intval($num_val);
        switch ($unit_byte) {
            case 'p':    // petabyte
            case 'pb':
                $num_val *= 1024;
            case 't':    // terabyte
            case 'tb':
                $num_val *= 1024;
            case 'g':    // gigabyte
            case 'gb':
                $num_val *= 1024;
            case 'm':    // megabyte
            case 'mb':
                $num_val *= 1024;
            case 'k':    // kilobyte
            case 'kb':
                $num_val *= 1024;
            case 'b':    // byte
                return $num_val *= 1;
                break;
            default:
                return FALSE;
        }
        return FALSE;
    }

    /**
     * Ruft alle Daten aus der Datenbank eines Archivs ab
     *
     * @param int $archive_id
     * @return void
     */
    public static function get_archive_data($archive_id)
    {
        global $wpdb;

        $tablename_archives = $wpdb->prefix . "zdm_archives";

        $db_archive_query = $wpdb->prepare(
            "
            SELECT * 
            FROM $tablename_archives 
            WHERE id = %d
            ",
            $archive_id
        );
        $db_archive = $wpdb->get_results($db_archive_query);

        return $db_archive[0];
    }

    /**
     * Ruft alle Archiv-IDs ab, in denen die Datei verknüpft ist
     *
     * @param int $file_id
     * @return array
     */
    public static function get_linked_archives($file_id)
    {
        global $wpdb;

        $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

        $db_archive_query = $wpdb->prepare(
            "
            SELECT id_archive 
            FROM $tablename_files_rel 
            WHERE id_file = '$file_id'
            ",
            $file_id
        );
        $db_archive = $wpdb->get_results($db_archive_query);

        return $db_archive;
    }

    /**
     * Gibt den Archivnamen zurück
     *
     * @param int $archive_id
     * @return string
     */
    public static function get_archive_name($archive_id)
    {
        global $wpdb;

        $tablename_archives = $wpdb->prefix . "zdm_archives";

        $db_archive_query = $wpdb->prepare(
            "
            SELECT name 
            FROM $tablename_archives 
            WHERE id = %d
            ",
            $archive_id
        );
        $db_archive = $wpdb->get_results($db_archive_query);

        if (array_key_exists(0, $db_archive))
            return $db_archive[0]->name;
        else
            return '';
    }

    /**
     * Gibt die Anzahl an Dateien zurück mit dem selben Hash
     *
     * @param string $type
     * @param string $hash
     * @return int Number of hashes found
     */
    public static function get_count_of_files_by_hash($type, $hash)
    {
        global $wpdb;

        $tablename_files = $wpdb->prefix . "zdm_files";

        if ($type == 'md5') {

            $db_file_query = $wpdb->prepare(
                "
                SELECT id 
                FROM $tablename_files 
                WHERE hash_md5 = %s
                ",
                $hash
            );
            $db_file = $wpdb->get_results($db_file_query);
        }

        if ($type == 'sha1') {

            $db_file_query = $wpdb->prepare(
                "
                SELECT id 
                FROM $tablename_files 
                WHERE hash_sha1 = %s
                ",
                $hash
            );
            $db_file = $wpdb->get_results($db_file_query);
        }

        return count($db_file);
    }

    /**
     * Gibt ein Objekt mit allen Daten aus der Datenbank (files) zurück
     * 
     * @param int $file_id
     * @return object
     */
    public static function get_file_data($file_id)
    {
        global $wpdb;

        $tablename_files = $wpdb->prefix . "zdm_files";

        $db_file_query = $wpdb->prepare(
            "
            SELECT * 
            FROM $tablename_files 
            WHERE id = %d
            ",
            $file_id
        );
        $db_file = $wpdb->get_results($db_file_query);

        return $db_file[0];
    }

    /**
     * Gibt ein Array mit MD5-Hashes aller Dateien zurück
     *
     * @return array
     */
    public static function get_files_md5()
    {
        global $wpdb;

        $tablename_files = $wpdb->prefix . "zdm_files";

        $db_files_md5 = $wpdb->get_results(
            "
            SELECT hash_md5 
            FROM $tablename_files
            "
        );

        $db_files_md5_count = count($db_files_md5);

        $files_md5 = [];
        for ($i = 0; $i < $db_files_md5_count; $i++) {
            $files_md5[] = $db_files_md5[$i]->hash_md5;
        }

        return $files_md5;
    }

    /**
     * Gibt den Namen der Datei zurück
     * 
     * @param int $file_id
     * @return object
     */
    public static function get_file_name($file_id)
    {
        global $wpdb;

        $tablename_files = $wpdb->prefix . "zdm_files";

        $db_file_query = $wpdb->prepare(
            "
            SELECT name 
            FROM $tablename_files 
            WHERE id = %d
            ",
            $file_id
        );
        $db_file = $wpdb->get_results($db_file_query);

        if (array_key_exists(0, $db_file))
            return $db_file[0]->name;
        else
            return '';
    }

    /**
     * Überprüft den Lizenzschlüssel
     * 
     * @return bool Gibt true zurück, wenn gültig, und false, wenn nicht
     */
    public static function licence()
    {
        $options = get_option('zdm_options');

        if ($options['licence-key'] != '') {

            $timeDiff = time() - $options['licence-time'];

            if ($timeDiff > 2592000) { // 2592000 = 30 Tage

                $licence_array = self::licence_array();

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

                    if (add_option('zdm_options', $options) === FALSE)
                        update_option('zdm_options', $options);

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

            if (add_option('zdm_options', $options) === FALSE)
                update_option('zdm_options', $options);

            $options = get_option('zdm_options');

            return false;
        }
    }

    /**
     * Gibt Daten von Gumroad im Array zurück
     * 
     * @param string $licence_key
     * @return mixed Gibt Array mir Daten zurück falls gültig, ansonsten bool false
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

        if ($array['success'] === true)
            return $array;

        return false;
    }

    /**
     * Log
     * 
     * @param string $type Art des Protokolls
     * @param string $message Sonstige Information
     * @return void
     */
    public static function log($type, $message = '---')
    {
        // Benutzer IP Adresse
        if (filter_input(INPUT_SERVER, 'REMOTE_ADDR')) {

            $options = get_option('zdm_options');

            $user_ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR');

            // Anonymize the IP address
            if ($options['secure-ip'] === 'on') {
                require_once(ZDM__PATH . '/lib/ZDMIPAnonymizer.php');
                $ip_anonymizer = new ZDMIPAnonymizer();
                $user_ip = $ip_anonymizer->anonymize($user_ip);
            }
        } else {
            $user_ip = "---";
        }

        // HTTP user agent
        if (filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'))
            $http_user_agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
        else
            $http_user_agent = "---";

        global $wpdb;

        $tablename_log = $wpdb->prefix . "zdm_log";

        // Neuer Log Datenbankeintrag
        $wpdb->insert(
            $tablename_log,
            array(
                'type'          => htmlspecialchars($type),
                'message'       => htmlspecialchars($message),
                'user_agent'    => $http_user_agent,
                'user_ip'       => $user_ip,
                'time_create'   => time()
            )
        );
    }

    /**
     * Formatiert Zahlen und Punkte für tausender Schritte
     * 
     * @param int $number
     * @return string Formatierte Nummer
     */
    public static function number_format($number, $decimals = 0)
    {
        if (in_array(get_locale(), ZDM__COUNTRIES_USING_DECIMAL_POINT))
            return number_format($number, $decimals, '.', ',');

        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Überprüft die PHP Version und ein paar Module
     *
     * @return void
     */
    public function php_modules_check_and_notice()
    {
        $php_modules_text = '';
        if (phpversion() >= 7.4) {

            if (!extension_loaded('mbstring'))
                $php_modules_text .= '<code>mbstring</code><br>';
            if (!extension_loaded('gd'))
                $php_modules_text .= '<code>GD</code><br>';
            if (!extension_loaded('zip'))
                $php_modules_text .= '<code>zip</code><br>';

            if ($php_modules_text != '') {
                echo '<div class="notice notice-warning">';
                echo '<p><h3>' . ZDM__TITLE . ' Plugin</h3></p>';
                echo '<p><b>' . esc_html__('The following PHP modules are missing', 'zdm') . ':</b></p>';
                echo '<p>' . $php_modules_text . '</p>';
                echo '<p>' . esc_html__('Please contact the administrator or web hosting provider of this website to install/activate the missing PHP modules.', 'zdm') . '</p>';
                echo '</div>';
            }
        }
    }

    /**
     * Registriert grundlegende Komponenten in WP
     *
     * @return void
     */
    public function register()
    {
        // Fügt Administrationsskripte hinzu
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Fügt Frontend-Skripte hinzu
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));

        // Adminmenü
        add_action('admin_menu', array($this, 'admin_menu'));

        // Dashboard-Widget
        add_action('wp_dashboard_setup', array($this, 'dashboard_widget'));

        // Fügt Links in die Plugin-Übersicht ein
        add_filter('plugin_action_links_' . $this->plugin_basename, array($this, 'settings_link'));

        // Shortcode für Download-Archiv und -Datei
        add_shortcode('zdownload', array($this, 'shortcode_download'));

        // Shortcode für Audio Player Ausgabe
        add_shortcode('zdownload_audio', array($this, 'shortcode_audio'));

        // Shortcode für Dateilisten
        add_shortcode('zdownload_list', array($this, 'shortcode_list'));

        // Shortcode Metadaten Ausgabe
        add_shortcode('zdownload_meta', array($this, 'shortcode_meta'));

        // Shortcode für Video Player Ausgabe
        add_shortcode('zdownload_video', array($this, 'shortcode_video'));
    }

    /**
     * Überprüft, ob der Name des Token-Ordner gleich ist, andernfalls wird er repariert
     *
     * @return bool true: if repair was successful, otherwise: false
     */
    public static function repair_folder_token_name()
    {
        $options = get_option('zdm_options');

        $dir =  wp_upload_dir()['basedir'];
        $dir_array = scandir($dir);
        $regex_search = '/z-downloads-.{32}/';
        $dir_count = count($dir_array);
        for ($i = 0; $i < $dir_count; $i++) {

            if (preg_match($regex_search, $dir_array[$i])) {

                if ($dir_array[$i] != 'z-downloads-' . $options['download-folder-token']) {
                    rename(wp_upload_dir()['basedir'] . '/' . $dir_array[$i], wp_upload_dir()['basedir'] . '/z-downloads-' . $options['download-folder-token']);
                    self::log('folder token repaired');
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Fügt Links in die Plugin-Übersicht ein
     *
     * @param string $links
     * @return string
     */
    public function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=z-downloads-settings">Einstellungen</a>';
        array_push($links, $settings_link);
        return $links;
    }

    /**
     * Shortcode für HTML5-Audio-Player: [zdownload_audio file="123"]
     *
     * @param string $atts
     * @param string $content
     * @return void HTML5 Audioplayer
     */
    public function shortcode_audio($atts, $content = null)
    {
        $options = get_option('zdm_options');

        $atts = shortcode_atts(
            array(
                'file'        => '',
                'autoplay'    => '',
                'loop'        => '',
                'controls'    => '',
                'nodownload'  => '',
                'align'       => ''
            ),
            $atts
        );

        $file = htmlspecialchars($atts['file']);
        if (htmlspecialchars($atts['autoplay']) == 'on')
            $autoplay = ' autoplay';
        else
            $autoplay = '';
        if (htmlspecialchars($atts['loop']) == 'on')
            $loop = ' loop';
        else
            $loop = '';
        if (htmlspecialchars($atts['controls']) == 'off')
            $controls = '';
        else
            $controls = ' controls';
        if (htmlspecialchars($atts['nodownload']) == 'on')
            $nodownload = ' controlslist="nodownload"';
        else
            $nodownload = '';
        if (htmlspecialchars($atts['align']) == 'center')
            $align = ' zdm-center';
        else
            $align = '';

        if ($file != '') {

            global $wpdb;
            $tablename_files = $wpdb->prefix . "zdm_files";

            $db_file_query = $wpdb->prepare(
                "
                SELECT id, folder_path, file_name, file_type, status 
                FROM $tablename_files 
                WHERE id = %d
                ",
                $file
            );
            $db_file = $wpdb->get_results($db_file_query);

            if ($db_file[0]->status != 'private') {

                $html_id = '';
                if ($options['hide-html-id'] != 'on')
                    $html_id = ' id="zdmAudio' . htmlspecialchars($db_file[0]->id) . '"';

                // Ausgabe
                $audio = '<audio preload="none"' . $html_id . ' class="zdm-audio' . $align . '"' . $autoplay . $loop . $controls . $nodownload . '>';
                $audio .= esc_html__('Your browser does not support HTML audio elements.', 'zdm');
                $audio .= '<source src="' . ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $db_file[0]->folder_path . '/' . $db_file[0]->file_name . '" type="' . $db_file[0]->file_type . '">';
                $audio .= '</audio>';

                if (in_array($db_file[0]->file_type, ZDM__MIME_TYPES_AUDIO))
                    return $audio;
            }
        }
    }

    /**
     * Shortcode für Downloads: [zdownload zip="123"] oder [zdownload file="123"]
     * 
     * @return string
     */
    public function shortcode_download($atts, $content = null)
    {
        if (!self::check_for_bot()) {

            $atts = shortcode_atts(
                array(
                    'zip'   => '',
                    'file'  => '',
                    'align' => ''
                ),
                $atts
            );

            $zip = htmlspecialchars($atts['zip']);
            $file = htmlspecialchars($atts['file']);

            if (htmlspecialchars($atts['align']) == 'center')
                $align = ' zdm-center';
            else
                $align = '';

            ////////////////////
            // ZIP
            ////////////////////
            if ($zip != '') {
                // Überprüft, ob überhaupt eine Datei zugewiesen ist
                if (self::check_if_any_file_rel_to_archive($zip)) {

                    $options = get_option('zdm_options');

                    // Überprüft, ob die Dateien aktuell sind
                    self::check_files_from_archive($zip);

                    global $wpdb;
                    $tablename_archives = $wpdb->prefix . "zdm_archives";

                    $db_archive_query = $wpdb->prepare(
                        "
                        SELECT id, button_text, status 
                        FROM $tablename_archives 
                        WHERE id = %d
                        ",
                        $zip
                    );
                    $db_archive = $wpdb->get_results($db_archive_query);

                    if ($db_archive[0]->status != 'private') {

                        // Text-Button bestimmen
                        if ($options['download-btn-icon-only'] != '') {
                            $download_text = '';
                            $icon_class = ' zdm-btn-icon-only';
                        } else {

                            if ($db_archive[0]->button_text != '')
                                $download_text = $db_archive[0]->button_text;
                            else
                                $download_text = $options['download-btn-text'];

                            if ($options['download-btn-icon-position'] == 'left')
                                $icon_class = 'zdm-btn-icon-frontend zdm-mr-2';
                            else
                                $icon_class = 'zdm-btn-icon-frontend zdm-ml-2';
                        }

                        $type = 'zdownload';
                        $id = base64_encode($db_archive[0]->id);

                        // Ausgabe
                        $icon = '';
                        if ($options['download-btn-icon'] != 'none')
                            $icon = '<span class="material-icons-round ' . $icon_class . '">' . $options['download-btn-icon'] . '</span>';

                        $html_id = '';
                        if ($options['hide-html-id'] != 'on')
                            $html_id = ' id="zdmBtn' . htmlspecialchars($db_archive[0]->id) . '"';

                        if ($options['download-btn-icon-position'] == 'left')
                            $icon_and_text = $icon . $download_text;
                        else
                            $icon_and_text = $download_text . $icon;

                        return '<a href="?' . $type . '=' . $id . '"' . $html_id . ' class="' . self::download_button_class() . $align . '" target="_blank" rel="nofollow noopener noreferrer">' . $icon_and_text . '</a>';
                    }
                } else {
                    // Leerer Rückgabewert, wenn keine Datei verknüpft ist
                    return '';
                }
            } // end if ($zip != '')

            ////////////////////
            // Datei
            ////////////////////
            if ($file != '') {

                if (self::check_if_file_exists($file) === true) {

                    $options = get_option('zdm_options');

                    global $wpdb;
                    $tablename_files = $wpdb->prefix . "zdm_files";

                    $db_files_query = $wpdb->prepare(
                        "
                        SELECT id, button_text, folder_path, file_name, file_type, status 
                        FROM $tablename_files 
                        WHERE id = %d
                        ",
                        $file
                    );
                    $db_files = $wpdb->get_results($db_files_query);

                    if ($db_files[0]->status != 'private') {

                        // Text-Button bestimmen
                        if ($options['download-btn-icon-only'] != '') {
                            $download_text = '';
                            $icon_class = '  zdm-btn-icon-only';
                        } else {

                            if ($db_files[0]->button_text != '')
                                $download_text = $db_files[0]->button_text;
                            else
                                $download_text = $options['download-btn-text'];

                            if ($options['download-btn-icon-position'] == 'left')
                                $icon_class = 'zdm-btn-icon zdm-mr-2';
                            else
                                $icon_class = 'zdm-btn-icon zdm-ml-2';
                        }

                        $type = 'zdownload_f';
                        $id = base64_encode($db_files[0]->id);

                        // Ausgabe
                        if ($options['download-btn-icon'] != 'none') {
                            $icon = '<span class="material-icons-round ' . $icon_class . '">' . $options['download-btn-icon'] . '</span>';
                        } else {
                            $icon = '';
                        }

                        if ($options['download-btn-icon-position'] == 'left')
                            $icon_and_text = $icon . $download_text;
                        else
                            $icon_and_text = $download_text . $icon;

                        return '<a href="?' . $type . '=' . $id . '" id="zdmBtn' . htmlspecialchars($db_files[0]->id) . '" class="' . self::download_button_class() . $align . '" target="_blank" rel="nofollow noopener noreferrer">' . $icon_and_text . '</a>';
                    }
                } else {
                    // Leerer Rückgabewert, wenn Datei nicht vorhanden ist
                    return '';
                }
            } // end if ($file != '')
        }
    }

    /**
     * Shortcode für HTML-Liste: [zdownload_list zip="123" style="ul" links="on" bold="on"]
     *
     * @param string $atts
     * @param string $content
     * @return string
     */
    public function shortcode_list($atts, $content = null)
    {
        $options = get_option('zdm_options');

        $atts = shortcode_atts(
            array(
                'zip'   => '',
                'links'  => '',
                'style' => '',
                'bold'  => ''
            ),
            $atts
        );

        $zip = htmlspecialchars($atts['zip']);
        $link_1 = '';
        $link_1_1 = '';
        $link_2 = '';
        if ($options['list-links'] == 'on') {
            $link_1 = '<a href="' . get_site_url() . '?zdownload_f=';
            $link_1_1 = '" target="_blank" rel="nofollow noopener noreferrer">';
            $link_2 = '</a>';
        } elseif ($options['list-links'] == 'off') {
            $link_1 = '';
            $link_1_1 = '';
            $link_2 = '';
        }
        if ($atts['links'] == 'on') {
            $link_1 = '<a href="' . get_site_url() . '?zdownload_f=';
            $link_1_1 = '" target="_blank" rel="nofollow noopener noreferrer">';
            $link_2 = '</a>';
        } elseif ($atts['links'] == 'off') {
            $link_1 = '';
            $link_1_1 = '';
            $link_2 = '';
        }
        $style = $options['list-style'];
        if ($atts['style'] != '') {
            $style = htmlspecialchars($atts['style']);
        }
        $bold_1 = '';
        $bold_2 = '';
        if ($options['list-bold'] == 'on') {
            $bold_1 = '<b>';
            $bold_2 = '</b>';
        } elseif ($options['list-bold'] == 'off') {
            $bold_1 = '';
            $bold_2 = '';
        }
        if ($atts['bold'] == 'on') {
            $bold_1 = '<b>';
            $bold_2 = '</b>';
        } elseif ($atts['bold'] == 'off') {
            $bold_1 = '';
            $bold_2 = '';
        }

        if ($zip != '') {

            $archive_data = self::get_archive_data($zip);

            if ($archive_data->status != 'private' && self::check_if_any_file_rel_to_archive($zip) == true) {

                global $wpdb;

                $tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

                $db_linked_files_query = $wpdb->prepare(
                    "
                    SELECT id_file
                    FROM $tablename_files_rel 
                    WHERE id_archive = %d 
                    AND file_deleted = 0
                    ",
                    $zip
                );
                $linked_files = $wpdb->get_results($db_linked_files_query);

                $linked_files_count = count($linked_files);

                $list = '';

                if ($style == 'rows') {

                    for ($i = 0; $i < $linked_files_count; $i++) {

                        $file_data = self::get_file_data($linked_files[$i]->id_file);
                        $link_id = '';
                        if ($link_1 != '') {
                            $link_id = base64_encode($file_data->id);
                        }
                        $list .= $link_1 . $link_id . $link_1_1 . $bold_1 . htmlspecialchars($file_data->name) . $bold_2 . $link_2;
                        $list .= '<br>';
                    }
                } elseif ($style == 'ul') {

                    $list .= '<ul>';
                    for ($i = 0; $i < $linked_files_count; $i++) {

                        $file_data = self::get_file_data($linked_files[$i]->id_file);
                        $link_id = '';
                        if ($link_1 != '') {
                            $link_id = base64_encode($file_data->id);
                        }
                        $list .= '<li>' . $link_1 . $link_id . $link_1_1 . $bold_1 . htmlspecialchars($file_data->name) . $bold_2 . $link_2 . '</li>';
                    }
                    $list .= '</ul>';
                } elseif ($style == 'ol') {

                    $list .= '<ol>';
                    for ($i = 0; $i < $linked_files_count; $i++) {

                        $file_data = self::get_file_data($linked_files[$i]->id_file);
                        $link_id = '';
                        if ($link_1 != '') {
                            $link_id = base64_encode($file_data->id);
                        }
                        $list .= '<li>' . $link_1 . $link_id . $link_1_1 . $bold_1 . htmlspecialchars($file_data->name) . $bold_2 . $link_2 . '</li>';
                    }
                    $list .= '</ol>';
                }

                return $list;
            }
        }
    }

    /**
     * Shortcode für Metadatenausgabe: [zdownload_meta zip="123" type="count"]
     * 
     * @return string Metadaten Ausgabe
     */
    public function shortcode_meta($atts, $content = null)
    {
        $atts = shortcode_atts(
            array(
                'zip'   => '',
                'file'  => '',
                'type'  => ''
            ),
            $atts
        );

        $zip = htmlspecialchars($atts['zip']);
        $file = htmlspecialchars($atts['file']);
        $type = htmlspecialchars($atts['type']);

        if ($type != '') {

            ////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////
            // ZIP
            ////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////
            if ($zip != '') {

                global $wpdb;
                $tablename_archives = $wpdb->prefix . "zdm_archives";

                ////////////////////
                // Count: Anzahl der Downloads
                ////////////////////
                if ($type === 'count') {

                    $db_archive_query = $wpdb->prepare(
                        "
                        SELECT count, status 
                        FROM $tablename_archives 
                        WHERE id = %d
                        ",
                        $zip
                    );
                    $db_archive = $wpdb->get_results($db_archive_query);

                    if (array_key_exists(0, $db_archive)) {
                        if ($db_archive[0]->status != 'private')
                            return htmlspecialchars(self::number_format($db_archive[0]->count));
                    }
                }

                ////////////////////
                // Dateigröße
                ////////////////////
                if ($type === 'size') {

                    // Check ob überhaupt eine Datei zugewiesen ist
                    if (self::check_if_any_file_rel_to_archive($zip)) {

                        // Dateien auf Aktualität prüfen
                        self::check_files_from_archive($zip);

                        global $wpdb;
                        $tablename_archives = $wpdb->prefix . "zdm_archives";

                        $db_archive_query = $wpdb->prepare(
                            "
                            SELECT file_size, status 
                            FROM $tablename_archives 
                            WHERE id = %d
                            ",
                            $zip
                        );
                        $db_archive = $wpdb->get_results($db_archive_query);

                        if (array_key_exists(0, $db_archive)) {
                            if ($db_archive[0]->status != 'private')
                                return htmlspecialchars($db_archive[0]->file_size);
                        }
                    }
                }

                ////////////////////
                // Name
                ////////////////////
                if ($type === 'name') {

                    $db_archive_query = $wpdb->prepare(
                        "
                        SELECT name, status 
                        FROM $tablename_archives 
                        WHERE id = %d
                        ",
                        $zip
                    );
                    $db_archive = $wpdb->get_results($db_archive_query);

                    if (array_key_exists(0, $db_archive)) {
                        if ($db_archive[0]->status != 'private')
                            return htmlspecialchars($db_archive[0]->name);
                    }
                }

                ////////////////////
                // Dateiname
                ////////////////////
                if ($type === 'filename') {

                    $db_archive_query = $wpdb->prepare(
                        "
                        SELECT zip_name, status 
                        FROM $tablename_archives 
                        WHERE id = %d
                        ",
                        $zip
                    );
                    $db_archive = $wpdb->get_results($db_archive_query);

                    if (array_key_exists(0, $db_archive)) {
                        if ($db_archive[0]->status != 'private')
                            return htmlspecialchars($db_archive[0]->zip_name . '.zip');
                    }
                }

                ////////////////////
                // Hash: MD5, SHA1
                ////////////////////
                if ($type === 'hash-md5' or $type === 'hash-sha1') {

                    // Überprüft, ob überhaupt eine Datei zugewiesen ist
                    if (self::check_if_any_file_rel_to_archive($zip)) {

                        // Überprüf, ob die Dateien aktuell sind
                        self::check_files_from_archive($zip);

                        global $wpdb;
                        $tablename_archives = $wpdb->prefix . "zdm_archives";

                        $db_archive_query = $wpdb->prepare(
                            "
                            SELECT hash_md5, hash_sha1, status 
                            FROM $tablename_archives 
                            WHERE id = %d
                            ",
                            $zip
                        );
                        $db_archive = $wpdb->get_results($db_archive_query);

                        if (array_key_exists(0, $db_archive)) {

                            if ($db_archive[0]->status != 'private') {

                                // Ausgabe der Dateigröße
                                if (self::licence() != true) {
                                } else {
                                    if ($type === 'hash-md5') {
                                        return htmlspecialchars($db_archive[0]->hash_md5);
                                    } elseif ($type === 'hash-sha1') {
                                        return htmlspecialchars($db_archive[0]->hash_sha1);
                                    }
                                }
                            }
                        }
                    }
                }
            } // end $zip != ''

            ////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////
            // Datei
            ////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////
            if ($file != '') {

                global $wpdb;
                $tablename_files = $wpdb->prefix . "zdm_files";

                ////////////////////
                // Count: Anzahl der Downloads
                ////////////////////
                if ($type === 'count') {

                    $db_files_query = $wpdb->prepare(
                        "
                        SELECT count, status 
                        FROM $tablename_files 
                        WHERE id = %d
                        ",
                        $file
                    );
                    $db_files = $wpdb->get_results($db_files_query);

                    if (array_key_exists(0, $db_files)) {
                        if ($db_files[0]->status != 'private')
                            return htmlspecialchars(self::number_format($db_files[0]->count));
                    }
                }

                ////////////////////
                // Dateigröße
                ////////////////////
                if ($type === 'size') {

                    $db_files_query = $wpdb->prepare(
                        "
                        SELECT file_size, status 
                        FROM $tablename_files 
                        WHERE id = $d
                        ",
                        $file
                    );
                    $db_files = $wpdb->get_results($db_files_query);

                    if (array_key_exists(0, $db_files)) {
                        if ($db_files[0]->status != 'private')
                            return htmlspecialchars($db_files[0]->file_size);
                    }
                }

                ////////////////////
                // Name
                ////////////////////
                if ($type === 'name') {

                    $db_files_query = $wpdb->prepare(
                        "
                        SELECT name, status 
                        FROM $tablename_files 
                        WHERE id = %d
                        ",
                        $file
                    );
                    $db_files = $wpdb->get_results($db_files_query);

                    if (array_key_exists(0, $db_files)) {
                        if ($db_files[0]->status != 'private')
                            return htmlspecialchars($db_files[0]->name);
                    }
                }

                ////////////////////
                // Dateiname
                ////////////////////
                if ($type === 'filename') {

                    $db_files_query = $wpdb->prepare(
                        "
                        SELECT file_name, status 
                        FROM $tablename_files 
                        WHERE id = %d
                        ",
                        $file
                    );
                    $db_files = $wpdb->get_results($db_files_query);

                    if (array_key_exists(0, $db_files)) {
                        if ($db_files[0]->status != 'private')
                            return htmlspecialchars($db_files[0]->file_name);
                    }
                }

                ////////////////////
                // Hash MD5, SHA1
                ////////////////////
                if ($type === 'hash-md5' or $type === 'hash-sha1') {

                    $db_files_query = $wpdb->prepare(
                        "
                        SELECT hash_md5, hash_sha1, status 
                        FROM $tablename_files 
                        WHERE id = %d
                        ",
                        $file
                    );
                    $db_files = $wpdb->get_results($db_files_query);

                    if (array_key_exists(0, $db_files)) {

                        if ($db_files[0]->status != 'private') {

                            // Ausgabe der Dateigröße
                            if (self::licence() != true) {
                            } else {
                                if ($type === 'hash-md5') {
                                    return htmlspecialchars($db_files[0]->hash_md5);
                                } elseif ($type === 'hash-sha1') {
                                    return htmlspecialchars($db_files[0]->hash_sha1);
                                }
                            }
                        }
                    }
                }
            } // end if ($file != '')

        } // end if ($type != '')
    }

    /**
     * Shortcode für HTML5-Video-Player: [zdownload_video file="123"]
     * 
     * @return string HTML5-Video-Player
     */
    public function shortcode_video($atts, $content = null)
    {
        $options = get_option('zdm_options');

        $atts = shortcode_atts(
            array(
                'file'        => '',
                'w'           => '100%',
                'autoplay'    => '',
                'loop'        => '',
                'controls'    => '',
                'nodownload'  => '',
                'align'       => ''
            ),
            $atts
        );

        $file = htmlspecialchars($atts['file']);
        $width = htmlspecialchars($atts['w']);
        if (htmlspecialchars($atts['autoplay']) == 'on')
            $autoplay = ' autoplay';
        else
            $autoplay = '';
        if (htmlspecialchars($atts['loop']) == 'on')
            $loop = ' loop';
        else
            $loop = '';
        if (htmlspecialchars($atts['controls']) == 'off')
            $controls = '';
        else
            $controls = ' controls';
        if (htmlspecialchars($atts['nodownload']) == 'on')
            $nodownload = ' controlslist="nodownload"';
        else
            $nodownload = '';
        if (htmlspecialchars($atts['align']) == 'center')
            $align = ' zdm-center';
        else
            $align = '';

        if ($file != '') {

            global $wpdb;
            $tablename_files = $wpdb->prefix . "zdm_files";

            $db_file_query = $wpdb->prepare(
                "
                SELECT id, folder_path, file_name, file_type, status 
                FROM $tablename_files 
                WHERE id = %d
                ",
                $file
            );
            $db_file = $wpdb->get_results($db_file_query);

            if ($db_file[0]->status != 'private') {

                $html_id = '';
                if ($options['hide-html-id'] != 'on')
                    $html_id = ' id="zdmVideo' . htmlspecialchars($db_file[0]->id) . '"';

                // Ausgabe
                $video = '<video' . $html_id . ' width="' . $width . '" class="zdm-video' . $align . '"' . $autoplay . $loop . $controls . $nodownload . '>';
                $video .= esc_html__('Your browser does not support HTML video elements.', 'zdm');
                $video .= '<source src="' . ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $db_file[0]->folder_path . '/' . $db_file[0]->file_name . '" type="' . $db_file[0]->file_type . '">';
                $video .= '</video>';

                if (in_array($db_file[0]->file_type, ZDM__MIME_TYPES_VIDEO))
                    return $video;
            }
        }
    }
}
