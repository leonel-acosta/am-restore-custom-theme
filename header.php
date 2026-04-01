<?php
/**
 * The header for our theme.
 *
 * @package Screenr
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php
// Preconnect to Google Fonts only if a custom font is selected in the customizer.
if ( get_theme_mod( 'am_body_font_family', '' ) || get_theme_mod( 'am_heading_font_family', '' ) ) : ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php endif; ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'screenr' ); ?></a>

    <?php $header_layout = get_theme_mod( 'header_layout', 'default' ); ?>
    <header id="masthead" class="am-header<?php echo $header_layout === 'transparent' ? ' am-header--transparent' : ''; ?>" role="banner">
        <div class="container">
            <div class="am-header__inner">

                <a class="am-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <?php
                    $logo_id  = get_theme_mod( 'custom_logo' );
                    $logo_img = $logo_id ? wp_get_attachment_image_src( $logo_id, 'full' ) : false;
                    if ( $logo_img ) : ?>
                        <img class="am-header__logo-img" src="<?php echo esc_url( $logo_img[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                    <?php else : ?>
                        <span class="am-header__logo-name"><?php bloginfo( 'name' ); ?></span>
                    <?php endif; ?>
                </a>

                <nav id="site-navigation" class="am-header__nav" role="navigation" aria-label="<?php esc_attr_e( 'Main navigation', 'screenr' ); ?>">
                    <?php wp_nav_menu( [
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'am-header__menu',
                    ] ); ?>
                </nav>

                <button
                    class="am-header__burger"
                    id="am-nav-toggle"
                    aria-label="<?php esc_attr_e( 'Toggle menu', 'screenr' ); ?>"
                    aria-expanded="false"
                    aria-controls="site-navigation"
                >
                    <span class="am-header__bars">
                        <span class="am-header__bar"></span>
                        <span class="am-header__bar"></span>
                        <span class="am-header__bar"></span>
                    </span>
                </button>

            </div>
        </div>
    </header>

<?php
do_action( 'screenr_after_site_header' );

// Burger toggle JS — inline, no dependencies
?>
<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var burger = document.getElementById('am-nav-toggle');
        var nav    = document.getElementById('site-navigation');
        if (!burger || !nav) return;

        burger.addEventListener('click', function () {
            var open = nav.classList.toggle('is-open');
            burger.classList.toggle('is-open', open);
            burger.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        // Close on outside click
        document.addEventListener('click', function (e) {
            if (!burger.contains(e.target) && !nav.contains(e.target)) {
                nav.classList.remove('is-open');
                burger.classList.remove('is-open');
                burger.setAttribute('aria-expanded', 'false');
            }
        });
    });
})();
</script>
