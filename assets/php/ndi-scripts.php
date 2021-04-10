<?php

/**
 * Register & Enqueue Admin JavaScripts Scripts
 *
 * @return void
 */
if (!function_exists('ndi_admin_script_enqueue')) {
    function ndi_admin_script_enqueue()
    {
        $current_screen = get_current_screen();
        if (strpos($current_screen->base, 'ndi') !== false) {
            wp_register_script(
                'ndi-admin-js',
                ndi_url . 'assets/js/ndi-admin.js',
                array('wp-api'),
                false,
                true
            );
            wp_register_script(
                'ndi-ecs-js',
                ndi_url . 'assets/js/ecs.js',
                false,
                false,
                false
            );
            wp_register_script(
                'ndi-utils-js',
                ndi_url . 'assets/js/utils.js',
                false,
                false,
                false
            );

            wp_enqueue_script('ndi-admin-js');
            wp_enqueue_script('ndi-ecs-js');
            wp_enqueue_script('ndi-utils-js');

            wp_localize_script(
                'ndi-admin-js',
                'ndi_globals',
                array(
                    'ndi_teams'            => get_ndi_teams(),
                    'ndi_members'          => get_ndi_members()
                )
            );
        }
    }
}
/**
 * Register & Enqueue Public JavaScripts Scripts
 *
 * @return void
 */
if (!function_exists('ndi_public_script_enqueue')) {
    function ndi_public_script_enqueue()
    {
        wp_register_script(
            'ndi-js',
            ndi_url . 'assets/js/ndi.js',
            false,
            false,
            true
        );
        wp_register_script(
            'ndi-ecs-js',
            ndi_url . 'assets/js/ecs.js',
            false,
            false,
            true
        );
        wp_register_style(
            'ndi-css',
            ndi_url . 'assets/css/style.css',
            false,
            false,
            false
        );

        wp_enqueue_script('ndi-js');
		wp_enqueue_script('ndi-ecs-js');
        wp_enqueue_style('ndi-css');
		

        wp_localize_script(
            'ndi-js',
            'ndi_globals',
            array(
                'ndi_ajax'             => admin_url('admin-ajax.php'),
                'ndi_action'           => 'ndi_handler',
                'ndi_teams'            => get_not_completed_teams(),
                'ndi_members'          => get_strict_members(),
                'ndi_nonce'            => wp_create_nonce('ndi_nonce'),
                'ndi_capacity'         => 300
            )
        );

		aemi_defer_scripts( "ndi-js" );
		
    }
}
add_action( 'admin_enqueue_scripts', 'ndi_admin_script_enqueue' );
add_action( 'wp_enqueue_scripts', 'ndi_public_script_enqueue' );
