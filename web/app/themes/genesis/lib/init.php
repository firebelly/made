<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Framework
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

/**
 * Check system requirements before loading the full framework.
 */
require_once dirname( __FILE__ ) . '/functions/requirements.php';
require_once dirname( __FILE__ ) . '/classes/class-genesis-requirements-views.php';

$genesis_requirements_messages = genesis_check_requirements();

if ( true !== $genesis_requirements_messages ) {
	$genesis_requirements_views = new Genesis_Requirements_Views( $genesis_requirements_messages );
	$genesis_requirements_views->add_hooks();
}

spl_autoload_register( 'genesis_autoload_register' );
/**
 * Allow Genesis_* class and StudioPress\Genesis namespaced files to be loaded automatically.
 *
 * @since 2.7.0 Allowed autoloading of namespaced classes.
 *
 * @param string $class_name Class name.
 * @return mixed|null|string Null if the classname format is not recognized otherwise the file path.
 */
function genesis_autoload_register( $class_name ) {

	// If the class being requested does not start with our prefix, we know it's not one in our project.
	if ( 0 !== strpos( $class_name, 'Genesis_' ) && 0 !== strpos( $class_name, 'StudioPress\Genesis' ) ) {
		return null;
	}

	$file_name = strtolower( str_replace( array( 'StudioPress\Genesis\\', '\\', '_' ), array( '', '-', '-' ), $class_name ) );
	$file      = get_template_directory() . '/lib/classes/class-' . $file_name . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}

	return $file;
}

/**
 * Fires before init functions are defined, and hooked in.
 *
 * @since 1.2.0
 */
do_action( 'genesis_pre' );

add_action( 'genesis_init', 'genesis_i18n' );
/**
 * Load the Genesis textdomain for internationalization.
 *
 * @since 1.9.0
 */
function genesis_i18n() {

	if ( ! defined( 'GENESIS_LANGUAGES_DIR' ) ) {
		define( 'GENESIS_LANGUAGES_DIR', get_template_directory() . '/lib/languages' );
	}

	load_theme_textdomain( 'genesis', GENESIS_LANGUAGES_DIR );

}

add_action( 'genesis_init', 'genesis_theme_support' );
/**
 * Activates default theme features.
 *
 * @since 1.6.0
 */
function genesis_theme_support() {

	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'body-open' );
	add_theme_support( 'genesis-inpost-layouts' );
	add_theme_support( 'genesis-archive-layouts' );
	add_theme_support( 'genesis-admin-menu' );
	add_theme_support( 'genesis-seo-settings-menu' );
	add_theme_support( 'genesis-import-export-menu' );
	add_theme_support( 'genesis-readme-menu' );
	add_theme_support( 'genesis-customizer-theme-settings' );
	add_theme_support( 'genesis-customizer-seo-settings' );
	add_theme_support( 'genesis-auto-updates' );
	add_theme_support( 'genesis-breadcrumbs' );

	// Maybe add support for Genesis menus.
	if ( ! current_theme_supports( 'genesis-menus' ) ) {

		$menus = array(
			'primary'   => __( 'Primary Navigation Menu', 'genesis' ),
			'secondary' => __( 'Secondary Navigation Menu', 'genesis' ),
		);

		/**
		 * Filter for the menus that Genesis supports by default.
		 *
		 * @since 2.3.0
		 *
		 * @param array $menus The array of supported menus.
		 */
		$menus = apply_filters( 'genesis_theme_support_menus', $menus );

		add_theme_support( 'genesis-menus', $menus );

	}

	// Maybe add support for structural wraps.
	if ( ! current_theme_supports( 'genesis-structural-wraps' ) ) {

		$structural_wraps = array( 'header', 'menu-primary', 'menu-secondary', 'footer-widgets', 'footer' );

		/**
		 * Filter for the structural wraps that Genesis supports by default.
		 *
		 * @since 2.3.0
		 *
		 * @param array $structural_wraps The array of supported structural wraps.
		 */
		$structural_wraps = apply_filters( 'genesis_theme_support_structural_wraps', $structural_wraps );

		add_theme_support( 'genesis-structural-wraps', $structural_wraps );

	}

	// Turn on HTML5 and responsive viewport if Genesis is active.
	if ( ! is_child_theme() ) {
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_theme_support( 'genesis-responsive-viewport' );
		add_theme_support(
			'genesis-accessibility',
			array(
				'drop-down-menu',
				'headings',
				'rems',
				'search-form',
				'skip-links',
			)
		);
	}

}

add_action( 'genesis_init', 'genesis_post_type_support' );
/**
 * Initialize post type support for Genesis features (Layout selector, SEO).
 *
 * @since 1.8.0
 */
function genesis_post_type_support() {

	add_post_type_support( 'post', array( 'genesis-seo', 'genesis-scripts', 'genesis-layouts', 'genesis-rel-author' ) );
	add_post_type_support( 'page', array( 'genesis-seo', 'genesis-scripts', 'genesis-layouts' ) );

}

add_action( 'init', 'genesis_post_type_support_post_meta', 11 );
/**
 * Add post type support for post meta to all post types except page.
 *
 * @since 2.2.0
 */
function genesis_post_type_support_post_meta() {

	$public_post_types = get_post_types( array( 'public' => true ) );

	foreach ( $public_post_types as $post_type ) {
		if ( 'page' !== $post_type ) {
			add_post_type_support( $post_type, 'genesis-entry-meta-before-content' );
			add_post_type_support( $post_type, 'genesis-entry-meta-after-content' );
		}
	}

	// For backward compatibility.
	if ( current_theme_supports( 'genesis-after-entry-widget-area' ) ) {
		add_post_type_support( 'post', 'genesis-after-entry-widget-area' );
	}

}

add_action( 'genesis_init', 'genesis_constants' );
/**
 * This function defines the Genesis theme constants
 *
 * @since 1.6.0
 */
function genesis_constants() {

	// Define Theme Info Constants.
	// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
	define( 'PARENT_THEME_NAME', 'Genesis' );
	define( 'PARENT_THEME_VERSION', '3.0.2' );
	define( 'PARENT_THEME_BRANCH', '3.0' );
	define( 'PARENT_DB_VERSION', '3001' );
	define( 'PARENT_THEME_RELEASE_DATE', date_i18n( 'F j, Y', strtotime( '03 July 2019' ) ) );

	// Define Parent and Child Directory Location and URL Constants.
	define( 'PARENT_DIR', get_template_directory() );
	define( 'CHILD_DIR', get_stylesheet_directory() );
	define( 'PARENT_URL', get_template_directory_uri() );
	define( 'CHILD_URL', get_stylesheet_directory_uri() );
	// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound

	// Define URL Location Constants.
	$lib_url = PARENT_URL . '/lib';
	if ( ! defined( 'GENESIS_IMAGES_URL' ) ) {
		define( 'GENESIS_IMAGES_URL', PARENT_URL . '/images' );
	}
	if ( ! defined( 'GENESIS_ADMIN_IMAGES_URL' ) ) {
		define( 'GENESIS_ADMIN_IMAGES_URL', $lib_url . '/admin/images' );
	}
	if ( ! defined( 'GENESIS_JS_URL' ) ) {
		define( 'GENESIS_JS_URL', $lib_url . '/js' );
	}
	if ( ! defined( 'GENESIS_CSS_URL' ) ) {
		define( 'GENESIS_CSS_URL', $lib_url . '/css' );
	}

	// Define directory locations constants.
	define( 'GENESIS_VIEWS_DIR', PARENT_DIR . '/lib/views' );
	define( 'GENESIS_CONFIG_DIR', PARENT_DIR . '/config' );

	// Define Settings Field Constants (for DB storage).
	define( 'GENESIS_SETTINGS_FIELD', (string) apply_filters( 'genesis_settings_field', 'genesis-settings' ) );
	define( 'GENESIS_SEO_SETTINGS_FIELD', (string) apply_filters( 'genesis_seo_settings_field', 'genesis-seo-settings' ) );
	define( 'GENESIS_CPT_ARCHIVE_SETTINGS_FIELD_PREFIX', (string) apply_filters( 'genesis_cpt_archive_settings_field_prefix', 'genesis-cpt-archive-settings-' ) );

	// Unused in Genesis, considered deprecated.
	if ( apply_filters( 'genesis_load_deprecated', true ) ) {
		// Directory Constants.
		$lib_dir = PARENT_DIR . '/lib';
		define( 'GENESIS_IMAGES_DIR', PARENT_DIR . '/images' );
		define( 'GENESIS_ADMIN_IMAGES_DIR', $lib_dir . '/admin/images' );
		define( 'GENESIS_TOOLS_DIR', $lib_dir . '/tools' );
		define( 'GENESIS_LIB_DIR', $lib_dir );
		define( 'GENESIS_ADMIN_DIR', $lib_dir . '/admin' );
		define( 'GENESIS_JS_DIR', $lib_dir . '/js' );
		define( 'GENESIS_CSS_DIR', $lib_dir . '/css' );
		define( 'GENESIS_FUNCTIONS_DIR', $lib_dir . '/functions' );
		define( 'GENESIS_SHORTCODES_DIR', $lib_dir . '/shortcodes' );
		define( 'GENESIS_STRUCTURE_DIR', $lib_dir . '/structure' );
		define( 'GENESIS_WIDGETS_DIR', $lib_dir . '/widgets' );

		// URL Constants.
		define( 'GENESIS_ADMIN_URL', $lib_url . '/admin' );
		define( 'GENESIS_LIB_URL', $lib_url );
		define( 'GENESIS_FUNCTIONS_URL', $lib_url . '/functions' );
		define( 'GENESIS_SHORTCODES_URL', $lib_url . '/shortcodes' );
		define( 'GENESIS_STRUCTURE_URL', $lib_url . '/structure' );
		define( 'GENESIS_WIDGETS_URL', $lib_url . '/widgets' );
	}

}

add_action( 'genesis_init', 'genesis_load_framework' );
/**
 * Loads all the framework files and features.
 *
 * The function can only be effective once, due to the use of the GENESIS_LOADED FRAMEWORK constant.
 *
 * The genesis_pre_framework action hook is called before any of the files are
 * required().
 *
 * If a child theme defines GENESIS_LOAD_FRAMEWORK as false before requiring
 * this init.php file, then this function will abort before any other framework
 * files are loaded.
 *
 * @since 1.6.0
 *
 * @global array $_genesis_formatting_allowed_tags Array of allowed tags for output formatting.
 */
function genesis_load_framework() {

	/**
	 * Fires before the framework files are loaded.
	 *
	 * @since 1.2.0
	 */
	do_action( 'genesis_pre_framework' );

	// Short circuit, if necessary.
	if ( defined( 'GENESIS_LOAD_FRAMEWORK' ) && false === GENESIS_LOAD_FRAMEWORK ) {
		return;
	}

	$lib_dir = trailingslashit( PARENT_DIR ) . 'lib/';

	// Load Framework.
	require_once $lib_dir . 'framework.php';

	// Load Functions.
	$functions_dir = $lib_dir . 'functions/';
	require_once $functions_dir . 'upgrade.php';
	require_once $functions_dir . 'compat.php';
	require_once $functions_dir . 'general.php';
	require_once $functions_dir . 'options.php';
	require_once $functions_dir . 'image.php';
	require_once $functions_dir . 'markup.php';
	require_if_theme_supports( 'genesis-breadcrumbs', $functions_dir . 'breadcrumb.php' );
	require_once $functions_dir . 'menu.php';
	require_once $functions_dir . 'layout.php';
	require_once $functions_dir . 'formatting.php';
	require_once $functions_dir . 'seo.php';
	require_once $functions_dir . 'widgetize.php';
	require_once $functions_dir . 'feed.php';
	require_once $functions_dir . 'toolbar.php';
	require_once $functions_dir . 'head.php';

	if ( apply_filters( 'genesis_load_deprecated', true ) ) {
		require_once $functions_dir . 'deprecated.php';
	}

	// Load Shortcodes.
	$shortcodes_dir = $lib_dir . 'shortcodes/';
	require_once $shortcodes_dir . 'post.php';
	require_once $shortcodes_dir . 'footer.php';

	// Load Structure.
	$structure_dir = $lib_dir . 'structure/';
	require_once $structure_dir . 'header.php';
	require_once $structure_dir . 'footer.php';
	require_once $structure_dir . 'menu.php';
	require_once $structure_dir . 'layout.php';
	require_once $structure_dir . 'post.php';
	require_once $structure_dir . 'loops.php';
	require_once $structure_dir . 'comments.php';
	require_once $structure_dir . 'sidebar.php';
	require_once $structure_dir . 'archive.php';

	// Load Admin.
	$admin_dir = $lib_dir . 'admin/';
	if ( is_admin() ) {
		require_once $admin_dir . 'menu.php';
		require_once $admin_dir . 'dashboard.php';
		require_once $admin_dir . 'admin-functions.php';
		require_once $admin_dir . 'inpost-metaboxes.php';
		require_once $admin_dir . 'use-child-theme.php';
		require_once $admin_dir . 'sanitization.php';
		require_once $admin_dir . 'privacy-requests.php';
		require_once $admin_dir . 'plugin-install.php';
		require_once $admin_dir . 'site-health.php';
		require_once $admin_dir . 'onboarding/theme-activation.php';
		require_once $admin_dir . 'onboarding/ajax-functions.php';
		require_once $functions_dir . 'onboarding.php';
	}
	if ( is_customize_preview() ) {
		require_once $admin_dir . 'customizer.php';
	}
	require_once $admin_dir . 'term-meta.php';
	require_once $admin_dir . 'user-meta.php';

	// Load JavaScript.
	require_once $lib_dir . '/js/load-scripts.php';

	// Load CSS.
	require_once $lib_dir . '/css/load-styles.php';

	// Load Widgets.
	$widgets_dir = $lib_dir . 'widgets/';
	require_once $widgets_dir . 'widgets.php';
	require_once $widgets_dir . 'user-profile-widget.php';
	require_once $widgets_dir . 'featured-post-widget.php';
	require_once $widgets_dir . 'featured-page-widget.php';

	// Load CLI command.
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::add_command( 'genesis db', 'Genesis_Cli_Db_Command' );
		WP_CLI::add_command( 'genesis core', 'Genesis_Cli_Core_Command' );
		WP_CLI::add_command( 'genesis setting', 'Genesis_Cli_Settings_Command' );
		WP_CLI::add_command( 'genesis option', 'Genesis_Cli_Settings_Command' );
		WP_CLI::add_command( 'genesis', 'Genesis_Cli_Command' );
	}

	global $_genesis_formatting_allowedtags;
	$_genesis_formatting_allowedtags = genesis_formatting_allowedtags();

	define( 'GENESIS_LOADED_FRAMEWORK', true );
}

/**
 * Fires during Genesis intialization.
 *
 * @since 1.0.0
 */
do_action( 'genesis_init' );

/**
 * Fires after Genesis initialization.
 *
 * @since 1.6.0
 */
do_action( 'genesis_setup' );
