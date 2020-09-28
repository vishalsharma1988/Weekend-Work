<?php
/**
 * Plugin Name: export items
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       This plugin will export all the posts entry to csv file.
 * Version:           1.0.0
 * Requires PHP:      7.2
 * Author:            Vishal Sharma
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */
// Fires after WordPress has finished loading, but before any headers are sent.
add_action( 'init', 'script_call' );

function script_call() {
   
   wp_enqueue_script( "export_js", plugin_dir_url(__FILE__).'export.js', array('jquery') );  
   wp_localize_script( 'export_js', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
   
}

add_action("wp_ajax_export_all_posts", "export_all_posts_callback");
//add_action("wp_ajax_nopriv_export_all_posts", "export_all_posts");

function export_all_posts_callback() {

// echo $_POST['my_val'];
// die();
    if(isset($_POST['export_items'])) {
        $arg = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );
 
        global $post;
        $all_post = get_posts($arg);
        if ($all_post) {
 
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="export-items.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
 
            $file = fopen('php://output', 'w');
 
            fputcsv($file, array('Post Title', 'URL'));
 
            foreach ($all_post as $post) {
                setup_postdata($post);
                fputcsv($file, array(get_the_title(), get_the_permalink()));
            }
 
            exit();
        }
    }
}


function add_export_button( $export_button ) {
    global $post_type;
  
    if ( 'post' === $post_type && 'top' === $export_button ) {
        ?>
        <input id="export-button" type="submit" name="export_items" class="button button-primary" value="<?php _e('Export Items'); ?>" />
        <?php
    }
}
 
add_action( 'manage_posts_extra_tablenav', 'add_export_button', 20, 1 );

