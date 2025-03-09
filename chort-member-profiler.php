<?php
/**
 * Plugin Name: TagDevCohorts by Kasingye Viva
 * Description: Custom post type for managing cohort members with course titles and categories.
 * Version: 1.4
 * Author: Kasingye Viva
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Register Custom Post Type
function tagdevcohorts_register_post_type() {
    $args = array(
        'label' => __('TagDev Cohorts', 'textdomain'),
        'public' => true,
        'show_ui' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-groups',
        'has_archive' => true,
        'rewrite' => array('slug' => 'tagdevcohorts'),
    );
    register_post_type('tagdevcohorts', $args);
}
add_action('init', 'tagdevcohorts_register_post_type');

// Register Custom Taxonomy for Cohort Categories
function tagdevcohorts_register_taxonomy() {
    $args = array(
        'label' => __('Cohort Categories', 'textdomain'),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'rewrite' => array('slug' => 'cohort-category'),
    );
    register_taxonomy('cohort_category', 'tagdevcohorts', $args);
}
add_action('init', 'tagdevcohorts_register_taxonomy');

// Add Meta Box for Name and Course Title
function tagdevcohorts_add_meta_box() {
    add_meta_box('tagdevcohorts_meta', 'Cohort Details', 'tagdevcohorts_meta_callback', 'tagdevcohorts', 'normal', 'high');
    add_meta_box('tagdevcohorts_additional_meta', 'Additional Details', 'tagdevcohorts_additional_meta_callback', 'tagdevcohorts', 'normal', 'high');
}
add_action('add_meta_boxes', 'tagdevcohorts_add_meta_box');

function tagdevcohorts_meta_callback($post) {
    $name = get_post_meta($post->ID, 'cohort_name', true);
    $course = get_post_meta($post->ID, 'cohort_course', true);
    ?>
    <div class="tagdevcohorts-meta-container">
        <label for="cohort_name">Name:</label>
        <input type="text" id="cohort_name" name="cohort_name" value="<?php echo esc_attr($name); ?>" style="width:100%;"><br><br>
        
        <label for="cohort_course">Course Title:</label>
        <input type="text" id="cohort_course" name="cohort_course" value="<?php echo esc_attr($course); ?>" style="width:100%;"><br><br>
        
        <p class="description">Use the post title to auto-fill the name field.</p>
    </div>
    
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            const postTitle = document.getElementById('title'); // Default post title input
            const cohortNameInput = document.getElementById('cohort_name');
            
            postTitle.addEventListener('input', function() {
                cohortNameInput.value = postTitle.value;
            });
        });
    </script>
    <?php
}

function tagdevcohorts_additional_meta_callback($post) {
    $full_name = get_post_meta($post->ID, 'full_name', true);
    $gender = get_post_meta($post->ID, 'gender', true);
    $country = get_post_meta($post->ID, 'country', true);
    $academic_program = get_post_meta($post->ID, 'academic_program', true);
    $year_of_admission = get_post_meta($post->ID, 'year_of_admission', true);
    $goals_vision = get_post_meta($post->ID, 'goals_vision', true);
    ?>
    <div class="tagdevcohorts-meta-container">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo esc_attr($full_name); ?>" style="width:100%;"><br><br>
        
        <label for="gender">Gender:</label>
        <select id="gender" name="gender" style="width:100%;">
            <option value="Male" <?php selected($gender, 'Male'); ?>>Male</option>
            <option value="Female" <?php selected($gender, 'Female'); ?>>Female</option>
            <option value="Other" <?php selected($gender, 'Other'); ?>>Other</option>
        </select><br><br>
        
        <label for="country">Country of Origin:</label>
        <input type="text" id="country" name="country" value="<?php echo esc_attr($country); ?>" style="width:100%;"><br><br>
        
        <label for="academic_program">Academic Program:</label>
        <input type="text" id="academic_program" name="academic_program" value="<?php echo esc_attr($academic_program); ?>" style="width:100%;"><br><br>
        
        <label for="year_of_admission">Year of Admission:</label>
        <input type="number" id="year_of_admission" name="year_of_admission" value="<?php echo esc_attr($year_of_admission); ?>" style="width:100%;"><br><br>
        
        <label for="goals_vision">Goals/Vision/Career Aspiration:</label>
        <textarea id="goals_vision" name="goals_vision" style="width:100%; height:100px;"><?php echo esc_textarea($goals_vision); ?></textarea><br><br>
    </div>
    <?php
}

// Save Meta Box Data
function tagdevcohorts_save_meta($post_id) {
    if (isset($_POST['cohort_name'])) {
        update_post_meta($post_id, 'cohort_name', sanitize_text_field($_POST['cohort_name']));
    }
    if (isset($_POST['cohort_course'])) {
        update_post_meta($post_id, 'cohort_course', sanitize_text_field($_POST['cohort_course']));
    }
    if (isset($_POST['full_name'])) {
        update_post_meta($post_id, 'full_name', sanitize_text_field($_POST['full_name']));
    }
    if (isset($_POST['gender'])) {
        update_post_meta($post_id, 'gender', sanitize_text_field($_POST['gender']));
    }
    if (isset($_POST['country'])) {
        update_post_meta($post_id, 'country', sanitize_text_field($_POST['country']));
    }
    if (isset($_POST['academic_program'])) {
        update_post_meta($post_id, 'academic_program', sanitize_text_field($_POST['academic_program']));
    }
    if (isset($_POST['year_of_admission'])) {
        update_post_meta($post_id, 'year_of_admission', sanitize_text_field($_POST['year_of_admission']));
    }
    if (isset($_POST['goals_vision'])) {
        update_post_meta($post_id, 'goals_vision', sanitize_textarea_field($_POST['goals_vision']));
    }
}


add_action('save_post', 'tagdevcohorts_save_meta');

// Force Archive Template for TagDev Cohorts
function tagdevcohorts_force_archive_template($template) {
    if (is_post_type_archive('tagdevcohorts')) {
        // Path to the archive template file in the plugin
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/archive-tagdevcohorts.php';

        // Check if the template file exists in the plugin
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('template_include', 'tagdevcohorts_force_archive_template', 99);




// Force Taxonomy Template for Cohort Categories
function tagdevcohorts_force_taxonomy_template($template) {
    if (is_tax('cohort_category')) {
        // Path to the taxonomy template file in the plugin
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/taxonomy-cohort_category.php';

        // Check if the template file exists in the plugin
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('template_include', 'tagdevcohorts_force_taxonomy_template', 99);
// Modify the Main Query for Cohort Category Taxonomy Archive
function tagdevcohorts_modify_taxonomy_query($query) {
    // Check if it's the main query and the taxonomy archive for 'cohort_category'
    if (!is_admin() && $query->is_main_query() && is_tax('cohort_category')) {
        // Set the number of posts per page
        $query->set('posts_per_page', 12);
    }
}
add_action('pre_get_posts', 'tagdevcohorts_modify_taxonomy_query');









// Enqueue CSS File
function tagdevcohorts_enqueue_styles() {
    // Enqueue the main CSS file
    wp_enqueue_style(
        'tagdevcohorts-style', // Handle
        plugin_dir_url(__FILE__) . 'css/tagdevcohorts.css', // CSS file URL
        array(), // Dependencies
        filemtime(plugin_dir_path(__FILE__) . 'css/tagdevcohorts.css') // Version (file modification time)
    );
}
add_action('wp_enqueue_scripts', 'tagdevcohorts_enqueue_styles');


// Shortcode to Display Cohorts with Tabs
function tagdevcohorts_display_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'show_tabs' => 'true', // Default value
        ),
        $atts,
        'tagdevcohorts'
    );

    $categories = get_terms(array('taxonomy' => 'cohort_category', 'hide_empty' => false));
    $output = '<div class="tagdevcohorts-tabs-container">';
    
    if ('true' === $atts['show_tabs']) {
        $output .= '<div class="tagdevcohorts-tabs">';
        $output .= '<button class="cohort-tab active" data-filter="all">All</button>';
        foreach ($categories as $category) {
            $output .= '<button class="cohort-tab" data-filter="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</button>';
        }
        $output .= '</div>';
    }
    
    $output .= '<div class="tagdevcohorts-list">';
    $query = new WP_Query(array('post_type' => 'tagdevcohorts', 'posts_per_page' => -1));
    while ($query->have_posts()) {
        $query->the_post();
        $name = get_post_meta(get_the_ID(), 'cohort_name', true);
        $course = get_post_meta(get_the_ID(), 'cohort_course', true);
        $categories = get_the_terms(get_the_ID(), 'cohort_category');
        $category_classes = $categories ? implode(' ', wp_list_pluck($categories, 'slug')) : 'all';
        $thumbnail = get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'cohort-thumbnail']);

        $output .= '<div class="cohort-item parent-cohort-item ' . esc_attr($category_classes) . '">';
        $output .= $thumbnail ? $thumbnail : '<img src="https://via.placeholder.com/150" class="cohort-thumbnail">';
        $output .= '<h3 class="cohort-name">' . esc_html(get_the_title()) . '</h3>';
        $output .= '<p><strong>Course:</strong> ' . esc_html($course) . '</p>';
        $output .= '<a href="' . esc_url(get_permalink()) . '" class="cohort-detail-button">Read More</a>';
        $output .= '</div>';
    }
    wp_reset_postdata();
    $output .= '</div>';

   

    $output .= '<script>
    document.addEventListener("DOMContentLoaded", function() {
        const buttons = document.querySelectorAll(".cohort-tab");
        const items = document.querySelectorAll(".cohort-item");
        buttons.forEach(button => {
            button.addEventListener("click", function() {
                buttons.forEach(btn => btn.classList.remove("active"));
                this.classList.add("active");
                let filter = this.getAttribute("data-filter");
                items.forEach(item => {
                    item.style.display = (filter === "all" || item.classList.contains(filter)) ? "block" : "none";
                });
            });
        });
    });
    </script>';
    
    return $output;
}
add_shortcode('tagdevcohorts', 'tagdevcohorts_display_shortcode');





// Shortcode to Display Cohorts in a Slider
function tagdevcohorts_slider_shortcode() {
    ob_start();
    
    $query = new WP_Query(array('post_type' => 'tagdevcohorts', 'posts_per_page' => -1));
    
    if ($query->have_posts()) {
        ?>
        <div class="tagdevcohorts-slider">
            <?php while ($query->have_posts()) {
                $query->the_post();
                $course = get_post_meta(get_the_ID(), 'cohort_course', true);
                $thumbnail = get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'cohort-thumbnail']);
                $categories = get_the_terms(get_the_ID(), 'cohort_category');
                $first_category = ($categories && !is_wp_error($categories) && isset($categories[0])) ? $categories[0]->name : 'Uncategorized';
                ?>
                <!-- Make the entire slide clickable -->
                <div class="cohort-slide" onclick="window.location.href='<?php the_permalink(); ?>'">
                    <?php echo $thumbnail ? $thumbnail : '<img src="https://via.placeholder.com/150" class="cohort-thumbnail">'; ?>
                    <h3 class="cohort-name"><?php the_title(); ?></h3>
                    <p class="cohort-cattwe"><strong></strong> <?php echo esc_html($course); ?></p>
                    <p class="cohort-catt"><strong>Read More</strong></p>
                </div>
            <?php } ?>
        </div>

        <script type="text/javascript">
        jQuery(document).ready(function($){
            $('.tagdevcohorts-slider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                arrows: true,
                dots: false,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        });
        </script>

        <?php
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('tagdevcohorts_slider', 'tagdevcohorts_slider_shortcode');

// Enqueue Slick Slider
function enqueue_slick_slider() {
    wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), null, true);
    wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
    wp_enqueue_style('slick-theme-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');
}
add_action('wp_enqueue_scripts', 'enqueue_slick_slider');

// Force Custom Template for Single Posts
function tagdevcohorts_force_template($template) {
    global $post;

    // Check if this is a single post of the 'tagdevcohorts' post type
    if (is_singular('tagdevcohorts')) {
        // Path to the template file in the plugin
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/single-tagdevcohorts.php';

        // Check if the template file exists in the plugin
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }

    return $template;
}
add_filter('template_include', 'tagdevcohorts_force_template');








