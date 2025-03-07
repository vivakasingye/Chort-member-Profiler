<?php
/**
 * Plugin Name: Chort Member Profiler
 * Description: A custom post type for managing cohort members, their details, and course profiles.
 * Version: 1.0
 * Author: Kasingye Viva
 * Text Domain: chort-member-profiler
 */

// Hook to register custom post type and taxonomy
function cmp_register_cohort_member_post_type() {
    $args = array(
        'public' => true,
        'label'  => 'Cohort Members',
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        'menu_icon' => 'dashicons-groups',
    );
    register_post_type( 'chort_member_profiler', $args );
}
add_action( 'init', 'cmp_register_cohort_member_post_type' );

// Register taxonomy for cohort categories
function cmp_register_cohort_category_taxonomy() {
    $args = array(
        'hierarchical' => true,
        'label' => 'Cohort Categories',
        'show_ui' => true,
    );
    register_taxonomy( 'cohort_category', 'chort_member_profiler', $args );
}
add_action( 'init', 'cmp_register_cohort_category_taxonomy' );

// Add meta boxes for cohort member details
function cmp_add_cohort_member_details_meta_box() {
    add_meta_box(
        'cohort_member_details',
        'Cohort Member Details',
        'cmp_display_cohort_member_details_meta_box',
        'chort_member_profiler',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cmp_add_cohort_member_details_meta_box' );

// Display the meta box fields
function cmp_display_cohort_member_details_meta_box( $post ) {
    $name = get_post_meta( $post->ID, '_cohort_member_name', true );
    $course_title = get_post_meta( $post->ID, '_cohort_member_course_title', true );

    echo '<label for="cohort_member_name">Cohort Member Name:</label>';
    echo '<input type="text" name="cohort_member_name" value="' . esc_attr( $name ) . '" class="widefat"/>';
    
    echo '<label for="cohort_member_course_title">Course Title:</label>';
    echo '<input type="text" name="cohort_member_course_title" value="' . esc_attr( $course_title ) . '" class="widefat"/>';
}

// Save the cohort member details
function cmp_save_cohort_member_details( $post_id ) {
    if ( !isset( $_POST['cohort_member_name'] ) || !isset( $_POST['cohort_member_course_title'] ) ) {
        return;
    }

    $name = sanitize_text_field( $_POST['cohort_member_name'] );
    $course_title = sanitize_text_field( $_POST['cohort_member_course_title'] );

    update_post_meta( $post_id, '_cohort_member_name', $name );
    update_post_meta( $post_id, '_cohort_member_course_title', $course_title );
}
add_action( 'save_post', 'cmp_save_cohort_member_details' );

// Shortcode to display cohort members
function cmp_cohort_member_profiler_shortcode() {
    $args = array(
        'post_type' => 'chort_member_profiler',
        'posts_per_page' => -1
    );

    $query = new WP_Query( $args );
    $output = '<div class="cohort-member-profiler">';

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $name = get_post_meta( get_the_ID(), '_cohort_member_name', true );
            $course_title = get_post_meta( get_the_ID(), '_cohort_member_course_title', true );

            $output .= '<div class="cohort-member">';
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '<p><strong>Name:</strong> ' . esc_html( $name ) . '</p>';
            $output .= '<p><strong>Course Title:</strong> ' . esc_html( $course_title ) . '</p>';
            $output .= '</div>';
        }
    } else {
        $output .= '<p>No cohort members found.</p>';
    }

    $output .= '</div>';
    wp_reset_postdata();

    return $output;
}
add_shortcode( 'chort_member_profiler', 'cmp_cohort_member_profiler_shortcode' );

// Enqueue styles for the frontend
function cmp_enqueue_styles() {
    wp_enqueue_style( 'chort-member-profiler-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'cmp_enqueue_styles' );

// Enqueue admin styles
function cmp_admin_enqueue_styles() {
    wp_enqueue_style( 'chort-member-profiler-admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css' );
}
add_action( 'admin_enqueue_scripts', 'cmp_admin_enqueue_styles' );

// Custom CSS for frontend styling
function cmp_add_custom_css() {
    echo "
    <style>
        .cohort-member-profiler {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .cohort-member {
            width: calc(33.33% - 20px);
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cohort-member h3 {
            font-size: 1.5em;
        }

        .cohort-member p {
            margin: 10px 0;
        }
    </style>
    ";
}
add_action( 'wp_head', 'cmp_add_custom_css' );

// Admin panel custom CSS
function cmp_admin_custom_css() {
    echo "
    <style>
        #cohort_member_details label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }

        #cohort_member_details input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
    </style>
    ";
}
add_action( 'admin_head', 'cmp_admin_custom_css' );
