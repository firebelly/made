<?php 

// Enqueue styles.
add_action( 'wp_enqueue_scripts', 'fb_enqueue_styles', 1 );
function fb_enqueue_styles() {
  wp_enqueue_style( 'main-css', get_stylesheet_directory_uri() . '/fbmods/css/main.min.css?'.time(), array(), CHILD_THEME_VERSION );
}

// Enqueue scripts.
add_action( 'wp_enqueue_scripts', 'fb_enqueue_scripts', 11);
function fb_enqueue_scripts() {
  wp_enqueue_script( 'site-js', get_stylesheet_directory_uri() . '/fbmods/js/build/site.min.js?'.time(),  array(), CHILD_THEME_VERSION  );
}

// Remove footer widgets, we're gonna stick em somewhere else
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

// Remove footer text, we're gonna use our own
remove_action( 'genesis_footer', 'genesis_do_footer' );

// Do the footer OUR way!
add_action( 'genesis_footer', 'genesis_footer_widget_areas');
add_action( 'genesis_footer', 'fb_custom_footer' );
function fb_custom_footer() {
  ?>
  <div class="copyright">
  <span class="line">Copyright © <?= date('Y'); ?></span>
  <span class="line">Made Collaborative, LLC  |  All rights reserved</span>
  </div>
  <?php
}

// Add widget for Contributing Artists
genesis_register_sidebar( array(
  'id'    => 'about-artists',
  'name'    => __( 'Participating Artists', 'made' ),
  'description' => __( 'A listing of all artists to appear on about page.', 'made' ),
) );

// Add page and parent slug body class

function fb_body_class_for_pages( $classes ) {

  if ( is_singular( 'page' ) ) {
    global $post;

    // Add class for this post's name
    $classes[] = 'page-' . $post->post_name;   

    //Add class for this parent's post name
    $parents = get_post_ancestors( $post->ID );
    $id = ($parents) ? $parents[count($parents)-1]: $post->ID;
    $id = ($parents) ? $parents[count($parents)-1] : false;
    if($id) {
      $parent = get_post( $id );
      $classes[] = 'parent-' . $parent->post_name;
    }
  }

  return $classes;
}
add_filter( 'body_class', 'fb_body_class_for_pages' );

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

// Add Logo to header/nav in various places
function fb_add_logo_to_nav($menu, $args) {
  return '<li class="menu-item menu-item-home"><a href="'.get_home_url().'" itemprop="url"><h1 class="sr-only">Made Collaborative</h1><img alt="Made Logo" class="made-logo" src="'.get_stylesheet_directory_uri() . '/fbmods/images/made-logo-header.png"></a></li>'.$menu;
}
add_filter( 'genesis_nav_items', 'fb_add_logo_to_nav', 10, 2 );
add_filter( 'wp_nav_menu_items', 'fb_add_logo_to_nav', 10, 2 );

function fb_add_logo_to_header() {
 echo '<a href="'.get_home_url().'" itemprop="url" class="menu-item-home-mobile"><h1 class="sr-only">Made Collaborative</h1><img alt="Made Logo" class="made-logo" src="'.get_stylesheet_directory_uri() . '/fbmods/images/made-logo-header.png"></a>';
}
add_action( 'genesis_header', 'fb_add_logo_to_header', 5 );


// Replace post thumbnail with background-image divs
function fb_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) { 

    // See if we are a child of about
    $parents = get_post_ancestors( $post_id );
    $id = ($parents) ? $parents[count($parents)-1] : false;
    $parent_slug = $id ? get_post( $id )->post_name : '';

    if($parent_slug === 'about' || is_archive()) { 
      return ' <div class="thumbnail-wrap"><div class="thumbnail" style="background-image: url('.get_the_post_thumbnail_url($post_id).');"></div></div>'; 
    }

    return $html;
}; 
add_filter( 'post_thumbnail_html', 'fb_post_thumbnail_html', 10, 5 ); 


// Set the favicon
function fb_favicon_url($favicon) {
  return get_stylesheet_directory_uri() . '/fbmods/images/favicon.png';
}
add_filter('genesis_favicon_url', 'fb_favicon_url');

// Add image size

add_image_size( 'featured_image', 1200, 0, false );
// add_image_size( 'artist', 600, 600, true );
add_filter('jpeg_quality', function($arg){ return 70; } );