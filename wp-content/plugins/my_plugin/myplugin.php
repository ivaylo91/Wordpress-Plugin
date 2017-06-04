<?php

/**
 * Plugin name: My Plugin
 * Description: My First WordPress Plugin
 * Author name: Ivaylo Penev
 */

define( 'DXP_VERSION', '1.6' );
define( 'DXP_PATH', dirname( __FILE__ ) );
define( 'DXP_PATH_INCLUDES', dirname( __FILE__ ) . '/inc' );
define( 'DXP_FOLDER', basename( DXP_PATH ) );
define( 'DXP_URL', plugins_url() . '/' . DXP_FOLDER );
define( 'DXP_URL_INCLUDES', DXP_URL . '/inc' );

if (!class_exists('My_Plugin')) {

    class My_Plugin
    {
        public function __construct()
        {
            add_action('init', array($this, 'register_episode'));

            add_action('add_meta_boxes', array($this, 'meta_box'));

            add_action('init', array($this, 'custom_taxonomies'));

            add_action('save_post', array($this, 'save'));

            add_action('post_edit_form_tag', array($this, 'save_custom_file'));

            add_action('init', array($this, 'shortcode'));

            add_action('widgets_init', array($this, 'simple_event_widget'));

        }

        public function simple_event_widget()
        {
            include_once DXP_PATH_INCLUDES . '/event-widget.class.php';
        }

        public function save_custom_file($post_id)
        {
            global $post;
            if (!empty($_FILES['add_custom_file'])) {

                $file = $_FILES['add_custom_file'];

                $upload = wp_handle_upload($file, array('test_form' => false));

                if (!isset($upload['error']) && isset($upload['error']) != 0) {
                    $filetype = wp_check_filetype(basename($upload['file']), null);
                    $title = $file['name'];
                    $attachment = array(
                        'post_mime_type' => $filetype,
                        'post_title' => addslashes_gpc($title),
                        'post_content' => '',
                        'post_status' => 'inherit',
                        'post_parent' => $post->ID
                    );
                    $attach_id = wp_insert_attachment($attachment, $upload['file']);

                    update_post_meta($post->ID, 'add_custom_file', $attach_id);
                }
            }
        }

        public function attach_custom_file($post)
        {
            $file = '';
            if (!empty($post)) {

                $file = get_post_meta($post->id, 'add_custom_file', true);
            }
            ?>
            <form enctype="multipart/form-data" method="post">
                <input type="file" name="add_custom_file" id="add_custom_file" value="<?php echo $file; ?>"/>
            </form>
            <?php
        }

        public
        function shortcode()
        {
            add_shortcode('episode', array($this, 'shortcode_cb'));

        }

        public
        function shortcode_cb($args)
        {
            $out = "";

            $query = new WP_Query(array(

                'post_type' => 'episode',
                'post_per_page' => -1
            ));

            if ($query->have_posts()) {

                while ($query->have_posts()) {

                    $query->the_post();

                    $out .= '<p><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></p>';
                }
            }

            return $out;
        }


        public function save($post_id)
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {

                return $post_id;
            }
            if (isset($_POST['add_info'])) {

                update_post_meta($post_id, 'add_info', $_POST['add_info']);
            }
            return $post_id;
        }

        public function custom_taxonomies()
        {

            register_taxonomy('custom_taxonomies', 'episode', array(
                'hierarchical' => true,
                'labels' => array(
                    'name' => 'Categories',
                    'singular_name' => 'Category',
                    'search_items' => 'Search',
                    'popular_item' => 'Popular',
                    'all_items' => 'All',
                    'parent_item' => null,
                    'parent_item_column' => null,
                    'edit_item' => 'Edit',
                    'add_new_item' => 'Add New',
                    'new_item_name' => 'New',
                    'separate_items_with_commas' => 'Separate',
                    'add_remove_items' => 'Add or remove',
                    'not_found' => 'No found',
                    'menu_name' => 'Categories'
                ),
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array('slug' => 'category')
            ));


            register_taxonomy_for_object_type('custom_taxonomies', 'episode');

        }

        public
        function meta_box()
        {

            add_meta_box('meta_box', 'Additional info', array($this, 'meta_box_cb'), 'episode');

            add_meta_box('add_custom_file', 'Upload File', array($this, 'attach_custom_file'), 'episode');
        }

        public function meta_box_cb($post)

        {
            $add_info = " ";

            if (!empty($post)) {

                $add_info = get_post_meta($post->ID, 'add_info', true);

            }

            ?>
            <textarea name="add_info" cols="50" rows="10">
                  <?php echo $add_info; ?>
            </textarea>
            <?php
        }

        public function register_episode()
        {
            register_post_type('Episode', array(
                'labels' => array(
                    'name' => 'Episodes',
                    'singular_name' => 'Episode'
                ),
                'public' => true,
                'has archive' => true,
                'taxonomies' => array('categories', 'post_tag')

            ));
        }
    }

    new My_Plugin();
}
