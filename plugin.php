<?php
/*
    Plugin Name: Shay Easy Timeline
    Description: Save me from figuring this out manually time and again
    Version: 1.0.19
    Author: Shay Pottle
    Author URI: https://shaypottle.com
    License: MIT
*/


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

session_start();


class ShayEasyTimeline {
    
    public function __construct() {
        
          add_action('init', array($this, 'init')); // Hook into WordPress to run your initialization function.          
          add_action('admin_menu', array($this, 'add_plugin_menu')); // Hook to add menu item.          
          add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets')); // Hook to enqueue custom styles and scripts for the admin page.
          add_action('admin_post_handle_timeline_form_hook', array($this, 'handle_timeline_form'));

    }

    

    public function init() {
        // Add your initialization code here.
        // Example: add_shortcode('your_shortcode', array($this, 'your_shortcode_function'));
    }

    // Example shortcode function.
    public function your_shortcode_function($atts) {
        // Your shortcode logic goes here.
    }


    public function add_plugin_menu() {
        add_menu_page(
            'Shay Easy Timeline', // Page title
            'Shay Easy Timeline', // Menu title
            'manage_options', // Capability
            'shay-easy-timeline', // Menu slug
            array($this, 'display_admin_page'), // Callback function to display page content
            'dashicons-clock',
            99 // Menu position
        );
    }
    
    public function display_admin_page() {        
        require_once 'admin-page.php';
    }

    
    public function enqueue_admin_assets() {

        global $pagenow;

        if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'shay-easy-timeline') {            
            wp_enqueue_style('shay-easy-timeline-admin-styles', plugin_dir_url(__FILE__) . 'admin.css');
            wp_enqueue_script('shay-easy-timeline-admin-script', plugin_dir_url(__FILE__) . 'admin.js', array('jquery'), '1.0', true);
        }

    }

    public function handle_timeline_form() {
        global $wpdb;

        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'handle_timeline_form_nonce')) {
            echo 'Form failed!<br>';
            wp_die('Security check failed');
        }

        if (isset($_POST['timeline_title'])) {
            $table_name = $wpdb->prefix . 'pws_timelines';
    
            // Sanitize and save form data to the custom table
            $timeline_title = sanitize_text_field($_POST['timeline_title']);
            $wpdb->insert($table_name, array('timeline_title' => $timeline_title), array('%s'));
        }
        

        $_SESSION['success'] = 'Form submitted successfully';
        
        wp_safe_redirect(wp_get_referer());
        exit;
    
    }

    public function create_timeline_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'pws_timelines';
    
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            timeline_title varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public function create_timeline_single_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'pws_timelines_single';    
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            timeline_id int NOT NULL,
            display_date varchar(255),
            safe_date varchar(255),
            description varchar(255),
            order int NOT NULL DEFAULT 0,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
    
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public function drop_tables() {
        global $wpdb;

        $pws_timelines = $wpdb->prefix . 'pws_timelines';
        $wpdb->query("DROP TABLE IF EXISTS $pws_timelines");

        $pws_timelines_single = $wpdb->prefix . 'pws_timelines_single';        
        $wpdb->query("DROP TABLE IF EXISTS $pws_timelines_single");

    }
    
}

$your_plugin_instance = new ShayEasyTimeline();




register_activation_hook(__FILE__, array('ShayEasyTimeline', 'activate'));
register_activation_hook(__FILE__, array('ShayEasyTimeline', 'create_timeline_table'));
register_activation_hook(__FILE__, array('ShayEasyTimeline', 'create_timeline_single_table'));



register_deactivation_hook(__FILE__, array('ShayEasyTimeline', 'deactivate'));
register_uninstall_hook(__FILE__, array('ShayEasyTimeline', 'uninstall'));
register_uninstall_hook(__FILE__, array('ShayEasyTimeline', 'drop_tables'));

