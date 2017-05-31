<?php
/**
 * Infinity Pro.
 *
 * This file adds the front page to the Infinity Pro Theme.
 *
 * @package Infinity
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/infinity/
 */

add_action( 'genesis_meta', 'infinity_front_page_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 * @since 1.0.0
 */
function infinity_front_page_genesis_meta() {

  if ( is_active_sidebar( 'front-page-1' ) || is_active_sidebar( 'front-page-2' ) || is_active_sidebar( 'front-page-3' ) || is_active_sidebar( 'front-page-4' ) || is_active_sidebar( 'front-page-5' ) || is_active_sidebar( 'front-page-6' ) || is_active_sidebar( 'front-page-7' ) ) {

    // Enqueue scripts.
    add_action( 'wp_enqueue_scripts', 'infinity_enqueue_front_script_styles', 1 );
    function infinity_enqueue_front_script_styles() {

      wp_enqueue_script( 'infinity-front-scripts', get_stylesheet_directory_uri() . '/js/front-page.js', array( 'jquery' ), CHILD_THEME_VERSION, true );

    }

    // Add front-page body class.
    add_filter( 'body_class', 'infinity_body_class' );
    function infinity_body_class( $classes ) {

      $classes[] = 'front-page';

      return $classes;

    }

    // Force full width content layout.
    add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

    // Remove breadcrumbs.
    remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

    // Remove the default Genesis loop.
    remove_action( 'genesis_loop', 'genesis_do_loop' );

    // Remove site footer widgets.
    // remove_theme_support( 'genesis-footer-widgets' ); -- REMOVED BY FB -- We want the footer on all pages

    // Add front page widgets.
    add_action( 'genesis_loop', 'infinity_front_page_widgets' );

  }

}

// Add markup for front page widgets.
function infinity_front_page_widgets() {

  echo '<h2 class="screen-reader-text">' . __( 'Main Content', 'infinity-pro' ) . '</h2>';
  echo '<h1>404</h1>';

  // Banner

}

// Run the Genesis loop.
genesis();
