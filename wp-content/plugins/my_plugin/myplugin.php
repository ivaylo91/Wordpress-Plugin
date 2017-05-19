<?php

/**
 * Plugin name: My Plugin
 * Description: My First WordPress Plugin
 * Author name: Ivaylo Penev
 */

if (!class_exists('My_Plugin')) {

    class My_Plugin
    {
        public function __construct()
        {
            add_action('init', array($this, 'register_episode'));

            add_action('add_meta_boxes', array($this, 'meta_box'));

            add_action('init', array($this, 'custom_taxonomies'));

            add_action('save_post', array($this, 'save'));

            add_action('save_audio', array($this, 'save_custom_file'));

            add_action('init', array($this, 'shortcode'));


        }

        public function shortcode()
        {
            add_shortcode('episode', array($this, 'shortcode_cb'));

        }

        public function shortcode_cb($args)
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

        public function save_custom_file($post_id)
        {
            $upload_file = "";

            if (!empty($_FILES['add_custom_file'])) {

                $upload_file = wp_upload_bits($_FILES['add_custom_file']['name'], null, wp_remote_get($_FILES['add_custom_file']['tmp_name']));
            }

                update_post_meta($post_id, 'add_custom-file', $upload_file);

        }


        public function attach_custom_file_cb($post)
        {
            $custom_file = "";

            if (!empty($_FILES['add_custom_file']['name'])) {

                $custom_file = get_post_meta($post->ID, 'add_custom_file', true);
            }
            ?>
            <form enctype="multipart/form-data" method="post">

                <input type="file" name="add_custom_file" id="add_custom_file" value="<?php echo $custom_file; ?>"/>

            </form>
            <?php
        }

        public function save($post_id)
        {
            if (defined('DOING_AJAX') && DOING_AJAX) {

                return $post_id;
            }
            if (isset($_POST['add_info'])) {

                update_post_meta($post_id, 'add_info', esc_html($_POST['add_info']));
            }

            return $post_id;
        }

        public
        function custom_taxonomies()
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

        public function meta_box()
        {

            add_meta_box('meta_box', 'Additional info', array($this, 'meta_box_cb'), 'episode');

            add_meta_box('add_custom_file', 'Upload File', array($this, 'attach_custom_file_cb'), 'episode');
        }

        public
        function meta_box_cb($post)

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

        public
        function register_episode()
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