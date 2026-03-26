/**
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	wp.customize( 'screenr_hide_sitetitle', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( 'body' ).addClass( 'no-site-title' ).removeClass('has-site-title');
			} else {
				$( 'body' ).removeClass( 'no-site-title' ).addClass('has-site-title');
			}
		} );
	} );

	wp.customize( 'screenr_hide_tagline', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( 'body' ).addClass( 'no-site-tagline' ).removeClass('has-site-tagline');
			} else {
				$( 'body' ).removeClass( 'no-site-tagline' ).addClass('has-site-tagline');
			}
		} );
	} );


	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	// ── AM Header live preview ────────────────────────────────────────────────

	function amHeaderVar( prop, val ) {
		document.querySelector( '.am-header' ).style.setProperty( prop, val );
	}

	// Header background color
	wp.customize( 'header_bg_color', function( value ) {
		value.bind( function( to ) {
			amHeaderVar( '--am-header-bg', to ? '#' + to : '' );
		} );
	} );

	// Menu link color
	wp.customize( 'menu_color', function( value ) {
		value.bind( function( to ) {
			amHeaderVar( '--am-header-link', to ? '#' + to : '' );
		} );
	} );

	// Menu link hover / active color
	wp.customize( 'menu_hover_color', function( value ) {
		value.bind( function( to ) {
			amHeaderVar( '--am-header-link-hover', to ? '#' + to : '' );
		} );
	} );

	// Transparent — background color
	wp.customize( 'header_t_bg_color', function( value ) {
		value.bind( function( to ) {
			amHeaderVar( '--am-header-bg', to || '' );
		} );
	} );

	// Transparent — menu link color
	wp.customize( 'menu_t_color', function( value ) {
		value.bind( function( to ) {
			amHeaderVar( '--am-header-link', to ? '#' + to : '' );
		} );
	} );

	// Transparent — menu link hover color
	wp.customize( 'menu_t_hover_color', function( value ) {
		value.bind( function( to ) {
			amHeaderVar( '--am-header-link-hover', to ? '#' + to : '' );
		} );
	} );

	// Burger bar color
	wp.customize( 'menu_toggle_button_color', function( value ) {
		value.bind( function( to ) {
			amHeaderVar( '--am-header-bar', to ? '#' + to : '' );
		} );
	} );

	// Logo text color (text-only fallback)
	wp.customize( 'logo_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.am-header__logo-name' ).css( 'color', to ? '#' + to : '' );
		} );
	} );

	// Header height
	wp.customize( 'am_header_height', function( value ) {
		value.bind( function( to ) {
			var px = parseInt( to, 10 );
			if ( px ) {
				document.documentElement.style.setProperty( '--am-header-h', px + 'px' );
				$( '#page.site' ).css( 'padding-top', px + 'px' );
			}
		} );
	} );

	// Logo image max-height
	wp.customize( 'am_logo_height', function( value ) {
		value.bind( function( to ) {
			var px = parseInt( to, 10 );
			if ( px ) {
				$( '.am-header__logo-img' ).css( 'height', px + 'px' );
			}
		} );
	} );

	// Menu font size
	wp.customize( 'am_menu_font_size', function( value ) {
		value.bind( function( to ) {
			var px = parseInt( to, 10 );
			if ( px ) {
				$( '.am-header__menu > li > a' ).css( 'font-size', px + 'px' );
			}
		} );
	} );

} )( jQuery );





