<?php 

// Enqueue styles.
add_action( 'wp_enqueue_scripts', 'fb_enqueue_styles', 1 );
function fb_enqueue_styles() {
  wp_enqueue_style( 'main-css', get_stylesheet_directory_uri() . '/fbmods/css/main.min.css?Papr-27-17d', array(), CHILD_THEME_VERSION );
}

// Enqueue scripts.
add_action( 'wp_enqueue_scripts', 'fb_enqueue_scripts', 11);
function fb_enqueue_scripts() {
  wp_enqueue_script( 'site-js', get_stylesheet_directory_uri() . '/fbmods/js/build/site.min.js?Papr-27-17d',  array(), CHILD_THEME_VERSION  );
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
  'id'    => 'about-artists',
  'name'    => __( 'Participating Artists', 'made' ),
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


// Shortcodes in Widgets --source: https://www.billerickson.net/wordpress-shortcode-sidebar-widget/
add_filter('widget_text', 'do_shortcode');
/**
 * URL Shortcode
 * @author Bill Erickson
 * @link http://www.billerickson.net/wordpress-shortcode-sidebar-widget/
 * @return Site URL.
 */
function fb_url_shortcode() {
  return get_bloginfo('url');
}
add_shortcode('url','fb_url_shortcode');
/**
 * WordPress URL
 * WordPress isn't always installed in same directory as Site URL.
 * @author Bill Erickson
 * @link http://www.billerickson.net/wordpress-shortcode-sidebar-widget/
 * @return WordPress URL
 */
function fb_wpurl_shortcode() {
  return get_bloginfo('wpurl');
}
add_shortcode('wpurl', 'fb_wpurl_shortcode');
/**
 * Child Theme URL
 * @author Bill Erickson
 * @link http://www.billerickson.net/wordpress-shortcode-sidebar-widget/
 * @return child theme directory URL
 */
function fb_child_shortcode() {
  return get_bloginfo('stylesheet_directory');
}
add_shortcode('child', 'fb_child_shortcode');