<?php
/**
 * Plugin name: My Plugin
 * Description: My First WordPress Plugin
 * Author name: Ivaylo Penev
 */
define('DXP_VERSION', '1.6');
define('DXP_PATH', dirname(__FILE__));
define('DXP_PATH_INCLUDES', dirname(__FILE__) . '/inc');
define('DXP_FOLDER', basename(DXP_PATH));
define('DXP_URL', plugins_url() . '/' . DXP_FOLDER);
define('DXP_URL_INCLUDES', DXP_URL . '/inc');


if (!class_exists('My_Plugin')) {
    class My_Plugin
    {
        public function __construct()
        {
            add_action('init', array($this, 'register_episode'));
            
            add_action('add_meta_boxes', array($this, 'meta_box'));
            
            add_action('init', array($this, 'custom_taxonomies'));
            
            add_action('save_post', array($this, 'save'));
            
            add_action('init', array($this, 'shortcode'));

            add_action('save_post', array($this, 'save_custom_file'));
        }
        public function custom_file_cb($post)
        {
            $get = '';

            if (!empty($post)) {

                $get = get_post_meta($post->ID, 'add_file', true);
            }
            ?>
            <input type="file" name="add_file" id="add_file" value="<?php echo $get; ?>">
            <?php
        }

        public function save_custom_file($post_id)
        {
            if (isset($_POST['add_file'])) {

                update_post_meta($post_id, 'add_file', $_POST['add_file']);
            }

            return $post_id;
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
            register_taxonomy('custom_taxonomies', 'episodes', array(
                'hierarchical' => true,
                'labels' => array(
                    'name' => 'Podcast Types',
                    'singular_name' => 'Podcast',
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
                'rewrite' => array('slug' => 'podcast type')
            ));
            register_taxonomy_for_object_type('custom_taxonomies', 'episodes');
        }

        public
        function meta_box()
        {
            add_meta_box('meta_box', 'Additional info', array($this, 'meta_box_cb'), 'episodes');

           add_meta_box('custom_file', 'Custom File', array($this, 'custom_file_cb'), 'episodes');
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
            register_post_type('Episodes', array(
                'labels' => array(
                    'name' => 'Episodes',
                    'singular_name' => 'Episode'
                ),
                'public' => true,
                'has archive' => true,
                'taxonomies' => array('podcast type', 'post_tag')
            ));
        }
    }

    new My_Plugin();
}
