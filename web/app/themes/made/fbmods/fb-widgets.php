<?php

// Register and load all widgets
function fb_load_widgets() {
  register_widget( 'fb_banner_made_logo_widget' );
  register_widget( 'fb_footer_made_logo_widget' );
  register_widget( 'fb_footer_connect_widget' );
  register_widget( 'fb_project_news_slider_widget' );
  register_widget( 'fb_comic_updates_widget' );
  register_widget( 'fb_artists_widget' );
}
add_action( 'widgets_init', 'fb_load_widgets' );




// Banner MADE logo
class fb_banner_made_logo_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
      // Base ID of your widget
      'fb_banner_made_logo_widget', 

      // Widget name will appear in UI
      __('Banner MADE Logo', 'fb_widgets'), 

      // Widget description
      array( 'description' => __( 'Displays the MADE logo for homepage banner', 'fb_widgets' ), ) 
    );
  }

  // Creating widget front-end
  public function widget( $args, $instance ) {
    echo '<img class="made-logo" src="'.get_stylesheet_directory_uri().'/fbmods/images/made-logo.png">';
  }
}

// Footer MADE logo
class fb_footer_made_logo_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
      // Base ID of your widget
      'fb_footer_made_logo_widget', 

      // Widget name will appear in UI
      __('Footer MADE Logo', 'fb_widgets'), 

      // Widget description
      array( 'description' => __( 'Displays the MADE logo for footer', 'fb_widgets' ), ) 
    );
  }

  // Creating widget front-end
  public function widget( $args, $instance ) {
    echo '<section class="widget widget_text widget_made_logo_footer"><div class="widget-wrap"><div class="textwidget">';
    
    echo '<a href="'.get_bloginfo('url').'" itemprop="url"><h1 class="sr-only">Made Collaborative</h1><img alt="Made Logo" class="made-logo" src="'.get_stylesheet_directory_uri().'/fbmods/images/made-logo-footer.png"></a>';

    echo '</div></div></section>';
  }
}


// Footer Connect Section
class fb_footer_connect_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
      // Base ID of your widget
      'fb_footer_connect_widget', 

      // Widget name will appear in UI
      __('Footer Connect', 'fb_widgets'), 

      // Widget description
      array( 'description' => __( 'Displays Social Media Icons/Links for footer', 'fb_widgets' ), ) 
    );
  }

  // Creating widget front-end
  public function widget( $args, $instance ) {
    ?>
      <section class="widget widget_text">
        <div class="widget-wrap"><h3 class="widgettitle widget-title">Connect</h3>
          <div class="textwidget">
            <ul class="social-links">
              <li class="social-link"><a target="_blank" href="https://www.facebook.com/madecollaborative"><img src="<?= get_stylesheet_directory_uri() ?>/fbmods/images/facebook.png" class="social-icon no-wireframify"></a></li>
              <li class="social-link"><a target="_blank" href="https://www.instagram.com/madecollaborative/"><img src="<?= get_stylesheet_directory_uri() ?>/fbmods/images/insta.png" class="social-icon no-wireframify"></a></li>
              <li class="social-link"><a target="_blank" href="https://twitter.com/MadeCollab"><img src="<?= get_stylesheet_directory_uri() ?>/fbmods/images/twitter.png" class="social-icon no-wireframify"></a></li>
            </ul> 
          </div>
        </div>
      </section>
    <?php
  }
}


// Project Mews Slider
class fb_project_news_slider_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
      // Base ID of your widget
      'fb_project_news_slider_widget', 

      // Widget name will appear in UI
      __('Project News Slider', 'fb_widgets'), 

      // Widget description
      array( 'description' => __( 'Project News slider for Front Page', 'fb_widgets' ), ) 
    );
  }

  // Creating widget front-end
  public function widget( $args, $instance ) {

    $query_args = [
      'post_type'   => 'post',
      'category_name'   => 'project-news',
      'orderby'     => 'date',
      'numberposts' => 5,
    ];
    $recent_posts = get_posts($query_args);

    if (!empty($recent_posts)) { 
      foreach ($recent_posts as $recent_post) {
        $title = get_the_title($recent_post->ID) ? get_the_title($recent_post->ID) : __( '(no title)', 'genesis' );
        $excerpt = empty( $recent_post->post_excerpt ) ? 
          wp_kses_post( wp_trim_words( $recent_post->post_content, 30 ) ) : 
          wp_kses_post( $recent_post->post_excerpt ); 

        ?>
        <section class="widget featured-content featuredpost">
          <div class="widget-wrap">
            <article class="post type-post status-publish format-standard category-project-news entry bigclicky">
              <a href="<?= get_permalink($recent_post->ID) ?>" aria-hidden="true">
                <div class="thumbnail-wrap">
                  <div class="thumbnail" style="background-image: url(<?= wp_get_attachment_image_url(get_post_thumbnail_id($recent_post->ID),'featured_image') ?>);"></div>
                </div>
              </a>
              <header class="entry-header">
                <h4 class="entry-title" itemprop="headline"><a href="<?= get_permalink($recent_post->ID) ?>" tabindex="-1"><?= $title ?></a></h4>
                <p class="entry-meta">
                  <time class="entry-time" itemprop="datePublished" datetime="<?= get_the_time('U',$recent_post->ID) ?>"><?= get_the_time("F j, Y",$recent_post->ID) ?></time>
                </p>
              </header>
              <div class="entry-content">
                <?= $excerpt ?>
              </div>
            </article>
          </div>
        </section>
        <?php
      }
    }
  }
}

// Comic Updates
class fb_comic_updates_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
      // Base ID of your widget
      'fb_comic_updates_widget', 

      // Widget name will appear in UI
      __('Recent Comic Updates', 'fb_widgets'), 

      // Widget description
      array( 'description' => __( 'Comic Updates Recent Posts for Front Page', 'fb_widgets' ), ) 
    );
  }

  // Creating widget front-end
  public function widget( $args, $instance ) {

    $query_args = [
      'post_type'   => 'post',
      'category_name'   => 'comic-updates',
      'orderby'     => 'date',
      'numberposts' => 6,
    ];
    $recent_posts = get_posts($query_args);

    if (!empty($recent_posts)) { 
      foreach ($recent_posts as $recent_post) {
        $title = get_the_title($recent_post->ID) ? get_the_title($recent_post->ID) : __( '(no title)', 'genesis' );
        ?>
        <section class="widget featured-content featuredpost">
          <div class="widget-wrap">
            <article class="post type-post status-publish category-comic-updates entry hover-card bigclicky">
              <div class="thumbnail-wrap">
                <div class="thumbnail" style="background-image: url(<?= wp_get_attachment_image_url(get_post_thumbnail_id($recent_post->ID),'featured_image') ?>);"></div>
              </div>
              <header class="entry-header">
                <h4 class="entry-title" itemprop="headline"><a href="<?= get_permalink($recent_post->ID) ?>"><?= $title ?></a></h4>
              </header>
            </article>
          </div>
        </section>
        <?php
      }
    }
  }
}

// Comic Updates
class fb_artists_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
      // Base ID of your widget
      'fb_artists_widget', 

      // Widget name will appear in UI
      __('Participating Artists List', 'fb_widgets'), 

      // Widget description
      array( 'description' => __( 'Artist List for About Page', 'fb_widgets' ), ) 
    );
  }

  // Creating widget front-end
  public function widget( $args, $instance ) {

    $args = array(
      'post_parent' => get_page_by_path( 'about' )->ID,
      'numberposts' => -1,
    );
    $artist_pages = get_children( $args );

    if (!empty($artist_pages)) { 
      foreach ($artist_pages as $artist_page) {
        $title = get_the_title($artist_page->ID) ? get_the_title($artist_page->ID) : __( '(no title)', 'genesis' );
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
      }
    }
  }
}