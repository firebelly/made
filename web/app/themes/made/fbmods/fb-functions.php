<?php

// Enqueue styles.
add_action( 'wp_enqueue_scripts', 'fb_enqueue_styles', 1 );
function fb_enqueue_styles() {
  wp_enqueue_style( 'main-css', get_stylesheet_directory_uri() . '/fbmods/css/main.min-1564521315.css', array(), CHILD_THEME_VERSION );
}

// Enqueue scripts.
add_action( 'wp_enqueue_scripts', 'fb_enqueue_scripts', 11);
function fb_enqueue_scripts() {
  wp_enqueue_script( 'site-js', get_stylesheet_directory_uri() . '/fbmods/js/build/site.min-1564521315.js',  array(), CHILD_THEME_VERSION  );
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
  the_post_thumbnail('featured_image');
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
      return ' <div class="thumbnail-wrap"><div class="thumbnail" style="background-image: url('.wp_get_attachment_image_url(get_post_thumbnail_id($post_id),'featured_image').');"></div></div>';
    }

    return $html;
};
add_filter( 'post_thumbnail_html', 'fb_post_thumbnail_html', 10, 5 );


// Set the favicon
function fb_favicon_url($favicon) {
  return get_stylesheet_directory_uri() . '/fbmods/images/favicon.png';
}
add_filter('genesis_favicon_url', 'fb_favicon_url');

// Image settings
add_image_size( 'featured_image', 1200, 0, false );
add_filter('jpeg_quality', function($arg){ return 70; } );


// Change Title -- be rid of dash on home page
add_filter('wp_title', 'fb_custom_title');
function fb_custom_title($title) {

    if(is_front_page()){
      return 'MADE Collaborative';
    }

    return $title;
}

// A shorcode to generate the hover cards for participating artists
function fb_participating_artists_shortcode() {

  $output = '';

  $output .= '<div class="widget-area flexible-widgets fadeup-effect widget-thirds fadeInUp hover-card-area participating-artists">';

  $output .= '<section class="widget widget_text"><div class="widget-wrap">      <div class="textwidget"></div>
    </div></section>'; //sigh... this shouldnt be here, but its a remnant from when there was one and is necessary for styling because of deadline.

  $args = array(
    'post_parent' => get_page_by_path( 'about' )->ID,
    'numberposts' => -1,
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
    'post_status' => 'publish',
  );
  $artist_pages = get_children( $args );

  if (!empty($artist_pages)) {
    foreach ($artist_pages as $artist_page) {
      $title = get_the_title($artist_page->ID) ? get_the_title($artist_page->ID) : __( '(no title)', 'genesis' );
      ob_start();
        ?>
        <section class="widget featured-content featuredpage">
          <div class="widget-wrap">
            <article class="page type-page status-publish entry hover-card">
              <div class="thumbnail-wrap">
                <div class="thumbnail" style="background-image: url(<?= wp_get_attachment_image_url(get_post_thumbnail_id($artist_page->ID),'featured_image') ?>);"></div>
              </div>
              <header class="entry-header">
                <h4 class="entry-title" itemprop="headline"><a href="<?= get_permalink($artist_page->ID) ?>"><?= $title ?></a></h4>
              </header>
            </article>
          </div>
        </section>
        <?php
      $output .= ob_get_contents();
      ob_end_clean();
    }
  }

  $output .= '</div>';


  return $output;
}
add_shortcode('participating-artist-cards', 'fb_participating_artists_shortcode');


// Add style file
add_editor_style( get_stylesheet_directory_uri() . '/fbmods/css/main.min.css' );