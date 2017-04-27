<?php 

// Enqueue styles.
add_action( 'wp_enqueue_scripts', 'fb_enqueue_styles', 1 );
function fb_enqueue_styles() {
  wp_enqueue_style( 'main-css', get_stylesheet_directory_uri() . '/fbmods/css/main.min.css?Papr-26-17', array(), CHILD_THEME_VERSION );
}

// Enqueue scripts.
add_action( 'wp_enqueue_scripts', 'fb_enqueue_scripts', 11);
function fb_enqueue_scripts() {
  wp_enqueue_script( 'main-js', get_stylesheet_directory_uri() . '/fbmods/js/main.js?Papr-26-17',  array(), CHILD_THEME_VERSION  );
}

// Override site footer text
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'fb_custom_footer' );
function fb_custom_footer() {
  ?>
  <p>&copy; Copyright <?= date('Y'); ?>  &middot; <a href="<?= get_bloginfo('url'); ?>">Made Collaborative</a></p>
  <?php
}

// Add widget for Contributing Artists
genesis_register_sidebar( array(
  'id'    => 'contributing-artists',
  'name'    => __( 'Contributing Artists', 'made' ),
  'description' => __( 'A listing of all artists to appear on about page.', 'made' ),
) );


// Add page slug body class
add_filter( 'body_class', 'fb_body_class_for_pages' );
/**
 * Adds a css class to the body element
 *
 * @param  array $classes the current body classes
 * @return array $classes modified classes
 */
function fb_body_class_for_pages( $classes ) {

  if ( is_singular( 'page' ) ) {
    global $post;
    $classes[] = 'page-' . $post->post_name;
  }

  return $classes;

}

// Display Featured Image on top of the post 
add_action( 'genesis_before_entry', 'fb_featured_post_image', 8 );
function fb_featured_post_image() {
  the_post_thumbnail('post-image');
}

// Customize entry meta header
add_filter( 'genesis_post_info', 'fb_post_info_filter' );
function fb_post_info_filter( $post_info ) {
 $post_info = '[post_date] [post_edit]';
 return $post_info;
}


// Remove entry-footer with "Filed Under" category on posts
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );