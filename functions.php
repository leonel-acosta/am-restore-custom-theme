<?php

/**
 * am-restore functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package am-restore
 */

/**
 * is Elementor editor?
 *
 * @return bool
 */
if (!function_exists('am_restore_elementor_is_editor')) :
	function am_restore_elementor_is_editor()
	{
		if (class_exists('Elementor\Plugin')) {
			if (Elementor\Plugin::$instance->preview->is_preview_mode() || Elementor\Plugin::$instance->editor->is_edit_mode()) {
				return true;
			}
		}
		return false;
	}
endif;

if (!function_exists('am_restore_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function am_restore_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on am-restore, use a find and replace
		 * to change 'am-restore' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('am-restore', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');
		add_post_type_support('page', 'excerpt');
		add_image_size('am-restore-blog-grid-small', 350, 200, true);
		add_image_size('am-restore-blog-grid', 540, 300, true);
		add_image_size('am-restore-blog-list', 790, 400, true);
		add_image_size('am-restore-service-small', 538, 280, true);

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 60,
				'width'       => 240,
				'flex-height' => true,
				'flex-width'  => true,
				// 'header-text' => array( 'site-title', 'site-description' ),
			)
		);

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__('Primary', 'am-restore'),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'am_restore_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		add_theme_support(
			'custom-header',
			array(
				'default-image'          => get_template_directory_uri() . '/assets/images/header-default.jpg',
				'width'                  => 1600,
				'height'                 => 800,
				'flex-height'            => false,
				'flex-width'             => false,
				'uploads'                => true,
				'random-default'         => false,
				'header-text'            => false,
				'default-text-color'     => '',
				'wp-head-callback'       => '',
				'admin-head-callback'    => '',
				'admin-preview-callback' => '',
			)
		);

		// Recommend plugins.
		add_theme_support(
			'recommend-plugins',
			array(
				'contact-form-7' => array(
					'name'            => esc_html__('Contact Form 7', 'am-restore'),
					'active_filename' => 'contact-form-7/wp-contact-form-7.php',
				),
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/*
		 * WooCommerce support.
		 */
		add_theme_support('woocommerce');
		// Add support for WooCommerce.
		add_theme_support('wc-product-gallery-zoom');
		add_theme_support('wc-product-gallery-lightbox');
		add_theme_support('wc-product-gallery-slider');
		/**
		 * Add support for Gutenberg.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/reference/theme-support/
		 */
		add_theme_support('editor-styles');
		add_theme_support('align-wide');

		// Disables the block editor from managing widgets in the Gutenberg plugin.
		add_filter('gutenberg_use_widgets_block_editor', '__return_false');
		// Disables the block editor from managing widgets.
		add_filter('use_widgets_block_editor', '__return_false');
	}
endif;
add_action('after_setup_theme', 'am_restore_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function am_restore_content_width()
{
	$GLOBALS['content_width'] = apply_filters('am_restore_content_width', 790);
}
add_action('after_setup_theme', 'am_restore_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function am_restore_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'am-restore'),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	if (class_exists('WooCommerce')) {
		register_sidebar(
			array(
				'name'          => esc_html__('Shop', 'am-restore'),
				'id'            => 'sidebar-shop',
				'description'   => '',
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}

	register_sidebar(
		array(
			'name'          => esc_html__('Footer 1', 'am-restore'),
			'id'            => 'footer-1',
			'description'   => screenr_sidebar_desc('footer-1'),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__('Footer 2', 'am-restore'),
			'id'            => 'footer-2',
			'description'   => screenr_sidebar_desc('footer-2'),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__('Footer 3', 'am-restore'),
			'id'            => 'footer-3',
			'description'   => screenr_sidebar_desc('footer-3'),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__('Footer 4', 'am-restore'),
			'id'            => 'footer-4',
			'description'   => screenr_sidebar_desc('footer-4'),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action('widgets_init', 'am_restore_widgets_init');

/**
 * Add Google Fonts, editor styles to WYSIWYG editor
 */
function am_restore_editor_styles()
{
	add_editor_style(array('assets/css/barlow.css', 'assets/css/editor-style.css'));
}
add_action('after_setup_theme', 'am_restore_editor_styles');

/**
 * Enqueue scripts and styles.
 */

function am_restore_enqueue_styles()
{
	wp_enqueue_style(
		'screenr-parent-style',
		get_template_directory_uri() . '/style.css'
	);
	wp_enqueue_style(
		'am-restore-style',
		get_stylesheet_directory_uri() . '/style.css',
		['screenr-parent-style', 'am-restore-tailwind'],
		wp_get_theme()->get('Version')
	);
}
add_action('wp_enqueue_scripts', 'am_restore_enqueue_styles');

add_filter('acf/settings/save_json', function ($path) {
	return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
	$paths[] = get_stylesheet_directory() . '/acf-json';
	return $paths;
});

function am_restore_scripts()
{
	$theme   = wp_get_theme();
	$version = $theme->get('Version');

	wp_enqueue_style('am-restore-fonts', get_stylesheet_directory_uri() . '/assets/css/barlow.css', array(), '1.0.0');

	wp_enqueue_style('am-restore-fa', get_template_directory_uri() . '/assets/fontawesome-v6/css/all.min.css', array(), '6.5.1');
	wp_enqueue_style('am-restore-fa-shims', get_template_directory_uri() . '/assets/fontawesome-v6/css/v4-shims.min.css', array(), '6.5.1');
	wp_enqueue_style('am-restore-tailwind', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), filemtime(get_stylesheet_directory() . '/assets/css/main.css'));

	wp_enqueue_script('am-restore-plugin', get_template_directory_uri() . '/assets/js/plugins.js', array('jquery'), '4.0.0', true);

	$am_restore_js = array(
		'ajax_url'           => admin_url('admin-ajax.php'),
		'full_screen_slider' => (get_theme_mod('slider_fullscreen')) ? true : false,
		'header_layout'      => get_theme_mod('header_layout'),
		'slider_parallax'    => (get_theme_mod('slider_parallax', 1) == 1) ? 1 : 0,
		'is_home_front_page' => (is_page_template('template-frontpage.php') && is_front_page()) ? 1 : 0,
		'autoplay'           => 7000,
		'speed'              => 700,
		'effect'             => 'slide',
		'gallery_enable'     => '',
	);

	// Load gallery scripts
	$galley_disable = get_theme_mod('gallery_disable') == 1 ? true : false;
	if (!$galley_disable || is_customize_preview()) {
		$am_restore_js['gallery_enable'] = 1;
		$display                         = get_theme_mod('gallery_display', 'grid');
		if (!is_customize_preview()) {
			switch ($display) {
				case 'masonry':
					wp_enqueue_script('am-restore-gallery-masonry', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js', array(), $version, true);
					break;
				case 'justified':
					wp_enqueue_script('am-restore-gallery-justified', get_template_directory_uri() . '/assets/js/jquery.justifiedGallery.min.js', array(), $version, true);
					break;
				case 'slider':
				case 'carousel':
					wp_enqueue_script('am-restore-gallery-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), $version, true);
					break;
				default:
					break;
			}
		} else {
			wp_enqueue_script('am-restore-gallery-masonry', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js', array(), $version, true);
			wp_enqueue_script('am-restore-gallery-justified', get_template_directory_uri() . '/assets/js/jquery.justifiedGallery.min.js', array(), $version, true);
			wp_enqueue_script('am-restore-gallery-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), $version, true);
		}
	}

	wp_enqueue_style('am-restore-gallery-lightgallery', get_template_directory_uri() . '/assets/css/lightgallery.css');

	wp_enqueue_script('am-restore-theme', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), '20120206', true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	wp_localize_script('am-restore-theme', 'am_restore', apply_filters('am_restore_localize_script', $am_restore_js));

	if (class_exists('WooCommerce')) {
		wp_enqueue_style('am-restore-woocommerce', get_template_directory_uri() . '/woocommerce.css');
	}
}
add_action('wp_enqueue_scripts', 'am_restore_scripts');

if (!function_exists('am_restore_fonts_url')) :
	/**
	 * Returns false — fonts are served locally via barlow.css.
	 */
	function am_restore_fonts_url()
	{
		return false;
	}
endif;

// Parent theme handles all inc/ file requires.

add_filter('single_template', function ($template) {
	if (!is_singular('post')) return $template;
	$post_categories = ['betonrestaurierung', 'restaurierung', 'sichtbetonretusche', 'untersuchungen'];
	if (in_category($post_categories)) {
		$custom = get_stylesheet_directory() . '/templates/project-page.php';
		if (file_exists($custom)) return $custom;
	}
	return $template;
});

add_filter('register_post_type_args', function ($args, $post_type) {
	if ($post_type === 'team') {
		$args['capability_type'] = 'post';
		$args['map_meta_cap'] = true;
		$args['capabilities'] = array(
			'edit_post'          => 'edit_post',
			'read_post'          => 'read_post',
			'delete_post'        => 'delete_post',
			'edit_posts'         => 'edit_posts',
			'edit_others_posts'  => 'edit_others_posts',
			'publish_posts'      => 'publish_posts',
			'read_private_posts' => 'read_private_posts',
		);
	}
	return $args;
}, 10, 2);
