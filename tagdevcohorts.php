<?php
/**
 * Plugin Name: TagDevCohorts by Kasingye Viva
 * Description: Custom post type for managing cohort members with course titles and categories.
 * Version: 1.2
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

// Save Meta Box Data
function tagdevcohorts_save_meta($post_id) {
    if (isset($_POST['cohort_name'])) {
        update_post_meta($post_id, 'cohort_name', sanitize_text_field($_POST['cohort_name']));
    }
    if (isset($_POST['cohort_course'])) {
        update_post_meta($post_id, 'cohort_course', sanitize_text_field($_POST['cohort_course']));
    }
}
add_action('save_post', 'tagdevcohorts_save_meta');

// Shortcode to Display Cohorts with Tabs
function tagdevcohorts_display_shortcode($atts) {
    // Check for a custom shortcode parameter set by admin
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
        $category_names = $categories ? implode(', ', wp_list_pluck($categories, 'name')) : 'All';
        $thumbnail = get_the_post_thumbnail(get_the_ID(), 'ful', ['class' => 'cohort-thumbnail']);

        $output .= '<div class="cohort-item parent-cohort-item ' . esc_attr($category_classes) . '">';
        $output .= $thumbnail ? $thumbnail : '<img src="https://via.placeholder.com/150" class="cohort-thumbnail">';
   $output .= '<h3 class="cohort-name">' . esc_html(get_the_title()) . '</h3>';
        $output .= '<p><strong></strong> ' . esc_html($course) . '</p>';
        $output .= '</div>';
    }
    wp_reset_postdata();
    $output .= '</div>';

    $output .= '<style>
    . .tagdevcohorts-tabs-container {
        margin-bottom: 20px;
    }
    .tagdevcohorts-tabs {
        margin-bottom: 15px;
        text-align: center;
    }
    .cohort-tab {
        margin: 0 10px;
        padding: 6px 26px;
        cursor: pointer;
        border: 1px solid #15598F;
        border-radius: 5px;
        background-color: #f1f1f1;
        color: #333;
        font-weight: 500 !important;
    }
    .cohort-tab.active {
        background-color: #165A90;
        color: #fff;
    }
    .tagdevcohorts-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }
    .cohort-item {
        text-align: center;
       
        border: 1px solid #ddd;
        border-radius: 5px;
        transition: transform 0.3s ease-in-out;
    }
    .cohort-item:hover {
        transform: scale(1.05);
    }
.cohort-item img {
	width: 100% !important;
	height: 215px;
	object-fit: cover;
	border-bottom: 5px solid #135283;
}
.cohort-item h3 {
	font-size: 16px;
	margin: 0;
	padding-top: 4px;
}

 .cohort-item p{
	font-size: 12px;
	color: #14588f;
}
    .parent-cohort-item {
        background-color: #f9f9f9;
    }
    @media (min-width: 768px) {
        .tagdevcohorts-list {
            grid-template-columns: repeat(5, 1fr);
        }
    }
    
    </style>';

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

?>
