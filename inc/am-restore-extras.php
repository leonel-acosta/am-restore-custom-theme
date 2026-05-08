<?php
/**
 * Child-theme overrides for functions in the parent's extras.php.
 *
 * The parent theme always loads its own extras.php, which declares functions
 * without if(!function_exists()) guards.  To avoid fatal redeclaration errors
 * we:
 *  1. Do NOT require the parent's (or child's) extras.php from functions.php.
 *  2. Hook into after_setup_theme at priority 20 (runs after both parent and
 *     child functions.php have executed) to swap the parent's registered hooks
 *     for our overridden versions.
 */

add_action( 'after_setup_theme', 'am_restore_swap_extras_hooks', 20 );
function am_restore_swap_extras_hooks() {
	remove_filter( 'body_class', 'screenr_body_classes' );
	add_filter( 'body_class', 'am_restore_body_classes' );

	remove_action( 'wp_enqueue_scripts', 'screenr_custom_style', 55 );
	add_action( 'wp_enqueue_scripts', 'am_restore_custom_style', 55 );

	remove_action( 'screenr_after_site_header', 'screenr_page_header_cover' );
	add_action( 'screenr_after_site_header', 'am_restore_page_header_cover' );
}

/**
 * Child override: body classes.
 * Change: uses 'templates/template-full-width.php' instead of parent's
 * 'templates/full-width-page.php'.
 */
function am_restore_body_classes( $classes ) {
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( get_theme_mod( 'screenr_hide_sitetitle' ) ) {
		$classes[] = 'no-site-title';
	} else {
		$classes[] = 'has-site-title';
	}

	if ( get_theme_mod( 'screenr_hide_tagline' ) ) {
		$classes[] = 'no-site-tagline';
	} else {
		$classes[] = 'has-site-tagline';
	}

	if ( get_option( 'header_layout' ) != 'default' ) {
		$classes[] = 'header-layout-fixed';
	}

	if ( is_page() ) {
		if ( is_page_template( 'templates/template-full-width.php' ) ) {
			$classes[] = 'full-width-page';
		}
	}

	return $classes;
}

/**
 * Child override: inline styles.
 * Changes: uses CSS custom properties for header colours, adds typography /
 * header-height / logo-height / menu-font-size settings, attaches to the
 * child's enqueued stylesheet handle ('screenr-parent-style').
 */
function am_restore_custom_style() {
	$layout = get_theme_mod( 'header_layout' );
	ob_start();

	$header_vars = array();

	if ( $layout != 'transparent' ) {
		$v = get_theme_mod( 'header_bg_color' );
		if ( $v ) $header_vars[] = '--am-header-bg: #' . esc_attr( $v );

		$v = get_theme_mod( 'menu_color' );
		if ( $v ) $header_vars[] = '--am-header-link: #' . esc_attr( $v );

		$v = get_theme_mod( 'menu_hover_color' );
		if ( $v ) $header_vars[] = '--am-header-link-hover: #' . esc_attr( $v );
	} else {
		$v = get_theme_mod( 'header_t_bg_color' );
		if ( $v ) $header_vars[] = '--am-header-bg: ' . esc_attr( $v );

		$v = get_theme_mod( 'menu_t_color' );
		if ( $v ) $header_vars[] = '--am-header-link: #' . esc_attr( $v );

		$v = get_theme_mod( 'menu_t_hover_color' );
		if ( $v ) $header_vars[] = '--am-header-link-hover: #' . esc_attr( $v );
	}

	$v = get_theme_mod( 'menu_toggle_button_color' );
	if ( $v ) $header_vars[] = '--am-header-bar: #' . esc_attr( $v );

	if ( $header_vars ) {
		echo '.am-header {' . "\n\t" . implode( ";\n\t", $header_vars ) . ";\n}\n";
	}

	$logo_text_color = get_theme_mod( 'logo_text_color' );
	if ( $logo_text_color ) {
		?>
	.am-header__logo-name {
		color: #<?php echo esc_attr( $logo_text_color ); ?>;
	}
		<?php
	}

	$header_h = absint( get_theme_mod( 'am_header_height', 85 ) );
	if ( $header_h && $header_h !== 85 ) {
		?>
	:root { --am-header-h: <?php echo $header_h; ?>px; }
		<?php
	}

	$logo_h = absint( get_theme_mod( 'am_logo_height', 40 ) );
	if ( $logo_h && $logo_h !== 40 ) {
		?>
	.am-header__logo-img { height: <?php echo $logo_h; ?>px; }
		<?php
	}

	$font_size = absint( get_theme_mod( 'am_menu_font_size', 12 ) );
	if ( $font_size && $font_size !== 12 ) {
		?>
	.am-header__menu > li > a { font-size: <?php echo $font_size; ?>px; }
		<?php
	}

	$body_font = get_theme_mod( 'am_body_font_family', '' );
	if ( $body_font ) {
		?>
	body, p, li, td, th, input, textarea, select, button {
		font-family: '<?php echo esc_attr( $body_font ); ?>', sans-serif;
	}
		<?php
	}

	$body_font_size = absint( get_theme_mod( 'am_body_font_size', 16 ) );
	if ( $body_font_size && $body_font_size !== 16 ) {
		?>
	body { font-size: <?php echo $body_font_size; ?>px; }
		<?php
	}

	$heading_font = get_theme_mod( 'am_heading_font_family', '' );
	if ( $heading_font ) {
		?>
	h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
		font-family: '<?php echo esc_attr( $heading_font ); ?>', sans-serif;
	}
		<?php
	}

	$slider_overlay_color = get_theme_mod( 'slider_overlay_color' );
	$c                    = screenr_color_alpha_parse( $slider_overlay_color );
	if ( $slider_overlay_color && $c ) {
		?>
	.swiper-slider .swiper-slide .overlay {
		background-color: <?php echo screenr_rgb2hex( $c['color'] ); ?>;
		opacity: <?php echo esc_attr( $c['opacity'] ); ?>;
	}
		<?php
	}

	$v_overlay = get_theme_mod( 'videolightbox_overlay' );
	if ( $v_overlay ) {
		?>
	.parallax-window.parallax-videolightbox .parallax-mirror::before{
		background-color: <?php echo esc_attr( $v_overlay ); ?>;
	}
		<?php
	}

	$page_header_bg_overlay = get_theme_mod( 'page_header_bg_overlay' );
	$bg_cover               = get_theme_mod( 'page_header_bg_color', '000000' );
	$c                      = screenr_color_alpha_parse( $page_header_bg_overlay );
	if ( $c ) {
		?>
	#page-header-cover.swiper-slider .swiper-slide .overlay {
		background-color: <?php echo screenr_rgb2hex( $c['color'] ); ?>;
		opacity: <?php echo $c['opacity']; ?>;
	}
		<?php
	}
	?>
	#page-header-cover.swiper-slider.no-image .swiper-slide .overlay {
		background-color: #<?php echo esc_attr( $bg_cover ); ?>;
		opacity: 1;
	}
	<?php
	$footer_w_bg = get_theme_mod( 'footer_widgets_bg' );
	if ( $footer_w_bg ) {
		?>
	.footer-widgets {
		background-color: #<?php echo esc_attr( $footer_w_bg ); ?>;
	}
	<?php } ?>

	<?php
	$footer_w_color = get_theme_mod( 'footer_widgets_color' );
	if ( $footer_w_color ) {
		?>
	.footer-widgets, .footer-widgets caption {
		color: #<?php echo esc_attr( $footer_w_color ); ?>;
	}
	<?php } ?>

	<?php
	$footer_widgets_heading = get_theme_mod( 'footer_widgets_heading' );
	if ( $footer_widgets_heading ) {
		?>
	.footer-widgets .widget-title, .site-footer .sidebar .widget .widget-title {
		color: #<?php echo esc_attr( $footer_widgets_heading ); ?>;
	}
	<?php } ?>

	<?php
	$footer_w_link_color = get_theme_mod( 'footer_widgets_link_color' );
	if ( $footer_w_link_color ) {
		?>
	.footer-widgets a, .footer-widgets .sidebar .widget a{
		color: #<?php echo esc_attr( $footer_w_link_color ); ?>;
	}
	<?php } ?>

	<?php
	$footer_w_link_hover_color = get_theme_mod( 'footer_widgets_link_hover_color' );
	if ( $footer_w_link_hover_color ) {
		?>
	.footer-widgets a:hover, .footer-widgets .sidebar .widget a:hover{
	color: #<?php echo esc_attr( $footer_w_link_hover_color ); ?>;
	}
	<?php } ?>

	<?php
	$footer_copyright_border_top = get_theme_mod( 'footer_copyright_border_top' );
	if ( $footer_copyright_border_top ) {
		?>
	.site-footer .site-info{
		border-top-color: #<?php echo esc_attr( $footer_copyright_border_top ); ?>;
	}
	<?php } ?>

	<?php
	$footer_c_bg = get_theme_mod( 'footer_copyright_bg' );
	if ( $footer_c_bg ) {
		?>
	.site-footer .site-info {
		background-color: #<?php echo esc_attr( $footer_c_bg ); ?>;
	}
	<?php } ?>

	<?php
	$footer_c_color = get_theme_mod( 'footer_copyright_color' );
	if ( $footer_c_color ) {
		?>
	.site-footer .site-info, .site-footer .site-info a {
		color: #<?php echo esc_attr( $footer_c_color ); ?>;
	}
		<?php
	}

	$primary = get_theme_mod( 'primary_color' );
	if ( $primary ) {
		?>
		input[type="reset"], input[type="submit"], input[type="submit"],
		.btn-theme-primary,
		.btn-theme-primary-outline:hover,
		.features-content .features__item,
		.nav-links a:hover,
		.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce button.button.alt
		{
			background-color: #<?php echo esc_attr( $primary ); ?>;
		}
		textarea:focus,
		input[type="date"]:focus,
		input[type="datetime"]:focus,
		input[type="datetime-local"]:focus,
		input[type="email"]:focus,
		input[type="month"]:focus,
		input[type="number"]:focus,
		input[type="password"]:focus,
		input[type="search"]:focus,
		input[type="tel"]:focus,
		input[type="text"]:focus,
		input[type="time"]:focus,
		input[type="url"]:focus,
		input[type="week"]:focus {
			border-color: #<?php echo esc_attr( $primary ); ?>;
		}

		a,
		.screen-reader-text:hover,
		.screen-reader-text:active,
		.screen-reader-text:focus,
		.header-social a,
		.nav-menu li.current-menu-item > a,
		.nav-menu a:hover,
		.nav-menu ul li a:hover,
		.nav-menu li.onepress-current-item > a,
		.nav-menu ul li.current-menu-item > a,
		.nav-menu > li a.menu-actived,
		.nav-menu.nav-menu-mobile li.nav-current-item > a,
		.site-footer a,
		.site-footer .btt a:hover,
		.highlight,
		.entry-meta a:hover,
		.entry-meta i,
		.sticky .entry-title:after,
		#comments .comment .comment-wrapper .comment-meta .comment-time:hover, #comments .comment .comment-wrapper .comment-meta .comment-reply-link:hover, #comments .comment .comment-wrapper .comment-meta .comment-edit-link:hover,
		.sidebar .widget a:hover,
		.services-content .service-card-icon i,
		.contact-details i,
		.contact-details a .contact-detail-value:hover, .contact-details .contact-detail-value:hover,
		.btn-theme-primary-outline
		{
			color: #<?php echo esc_attr( $primary ); ?>;
		}

		.entry-content blockquote {
			border-left: 3px solid #<?php echo esc_attr( $primary ); ?>;
		}

		.btn-theme-primary-outline, .btn-theme-primary-outline:hover {
			border-color: #<?php echo esc_attr( $primary ); ?>;
		}
		.section-news .entry-grid-elements {
			border-top-color: #<?php echo esc_attr( $primary ); ?>;
		}
		<?php
	}

	$gallery_spacing = absint( get_theme_mod( 'gallery_spacing', 20 ) );
	?>
	.gallery-carousel .g-item{
		padding: 0px <?php echo intval( $gallery_spacing / 2 ); ?>px;
	}
	.gallery-carousel {
		margin-left: -<?php echo intval( $gallery_spacing / 2 ); ?>px;
		margin-right: -<?php echo intval( $gallery_spacing / 2 ); ?>px;
	}
	.gallery-grid .g-item, .gallery-masonry .g-item .inner {
		padding: <?php echo intval( $gallery_spacing / 2 ); ?>px;
	}
	.gallery-grid, .gallery-masonry {
		margin: -<?php echo intval( $gallery_spacing / 2 ); ?>px;
	}
	<?php

	$css    = ob_get_clean();
	$custom = get_option( 'screenr_custom_css' );
	if ( $custom ) {
		$css .= "\n/* --- Begin custom CSS --- */\n" . $custom . "\n/* --- End custom CSS --- */\n";
	}
	$css = apply_filters( 'screenr_custom_style', $css );

	if ( screenr_is_selective_refresh() ) {
		return $css;
	} else {
		wp_add_inline_style( 'screenr-parent-style', $css );
	}
}

/**
 * Child override: page header cover.
 * Change: also suppresses the banner on is_front_page() and the
 * 'templates/template-homepage.php' template (in addition to the parent's
 * existing 'template-frontpage.php' check).
 */
function am_restore_page_header_cover() {
	if ( is_front_page() || is_page_template( 'templates/template-homepage.php' ) ) {
		return;
	}
	screenr_page_header_cover();
}

/**
 * Footer social media icons bar.
 */
function am_restore_footer_social() {
	$label     = get_theme_mod( 'footer_social_label', 'Folge Sie uns' );
	$instagram = get_theme_mod( 'footer_social_instagram', 'https://www.instagram.com/am.restore' );
	$linkedin  = get_theme_mod( 'footer_social_linkedin', '' );

	if ( ! $instagram && ! $linkedin ) {
		return;
	}
	?>
	<div class="footer-social-bar">
		<div class="container">
			<div class="footer-social-inner">
				<?php if ( $label ) : ?>
					<span class="footer-social-label"><?php echo esc_html( $label ); ?></span>
				<?php endif; ?>
				<div class="footer-social-icons">
					<?php if ( $instagram ) : ?>
						<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="Instagram">
							<i class="fa fa-instagram"></i>
						</a>
					<?php endif; ?>
					<?php if ( $linkedin ) : ?>
						<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="LinkedIn">
							<i class="fa fa-linkedin"></i>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'screenr_footer', 'am_restore_footer_social', 5 );
