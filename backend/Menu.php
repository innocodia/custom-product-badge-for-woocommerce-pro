<?php

namespace CPBW_PRO\Backend;

class Menu {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu'], 11);
    }

    public function add_menu() {
        add_submenu_page(
            'cpbw',
            'License',
            'License',
            'manage_options',
            'cpbw-manage-license',
            [$this, 'cpbw_manage_license']
        );
    }

    // Add this method to handle the license page rendering
    public function cpbw_manage_license() {
        // Check if the current user has the necessary permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
    
        // Redirect to the menu page with slug 'cpbw-manage-license-account'
        $redirect_url = admin_url('admin.php?page=cpbw-manage-license-account');
        wp_redirect($redirect_url);
        exit; // Always call exit() after wp_redirect to prevent further execution
    }
    
}