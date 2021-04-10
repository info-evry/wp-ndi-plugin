<?php
if (!function_exists('ndi_setup_database')) {
    function ndi_setup_database()
    {
        global $wpdb, $teamsDB, $membersDB;
        $teamsDB = "{$wpdb->prefix}ndi_teams";
        $membersDB = "{$wpdb->prefix}ndi_members";
        $version = 3;
        $charset_collate = $wpdb->get_charset_collate();

        $ndi_teams_db_version = (int) get_site_option('ndi_teams_db_version');
        $ndi_members_db_version = (int) get_site_option('ndi_members_db_version');

        if ($ndi_teams_db_version !== $version) {
            $sql = "CREATE TABLE {$teamsDB} (
            id int(8) UNSIGNED NOT NULL AUTO_INCREMENT,
            teamName varchar(128) NOT NULL,
            teamDesc varchar(256) NOT NULL,
            date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id)
            ) $charset_collate;";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
            update_site_option('ndi_teams_db_version', $version);
        }
        
        if ($ndi_members_db_version !== $version) {
            $sql = "CREATE TABLE {$membersDB} (
            id int(8) UNSIGNED NOT NULL AUTO_INCREMENT,
            firstname varchar(128) NOT NULL,
            lastname varchar(256) NOT NULL,
            email varchar(256) NOT NULL,
            fooddiet varchar(256) DEFAULT NULL,
            baclevel int(8) NOT NULL DEFAULT 0,
            ismanager int(8) NOT NULL DEFAULT 0,
            teamid int(8) UNSIGNED NOT NULL,
            date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id)
            ) $charset_collate;";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
            update_site_option('ndi_members_db_version', $version);
        }
    }
}
add_action('init', 'ndi_setup_database');
