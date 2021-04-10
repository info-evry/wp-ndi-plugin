<?php

/**
 * Plugin Name: Nuit de l'Info
 * Description: Gestion des Inscriptions
 * Author: Guillaume Coquard
 * Author URI: https://github.com/aemi-dev
 * Version: 1.0.0
 * Licence: (c) All rights reserved.
 */

define('ndi_dir', plugin_dir_path(__FILE__));
define('ndi_url', plugin_dir_url(__FILE__));

require_once ndi_dir . 'assets/php/admin/ndi-database.php';
require_once ndi_dir . 'assets/php/ndi-scripts.php';
require_once ndi_dir . 'assets/php/ndi-utils.php';
require_once ndi_dir . 'assets/php/admin/ndi-members.php';
require_once ndi_dir . 'assets/php/admin/ndi-shortcodes.php';
require_once ndi_dir . 'assets/php/public/ndi-handlers.php';