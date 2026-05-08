<?php
/**
 * Child-theme customizer additions.
 *
 * Hooked at priority 20 so the parent's screenr_customize_register() has
 * already run.  We remove the parent's upsell "Go Plus" placeholder for
 * Typography and replace it with a real, working section, then append the
 * extra header / logo / menu-font-size controls to the existing Header section.
 */

add_action( 'customize_register', 'am_restore_customize_register', 20 );
function am_restore_customize_register( $wp_customize ) {

	/* Typography
	   ---------------------------------------------------------------------- */
	$wp_customize->remove_section( 'screenr_typography_plus' );

	$wp_customize->add_section( 'am_typography',
		array(
			'priority'    => 4,
			'title'       => esc_html__( 'Typography', 'screenr' ),
			'description' => '',
			'panel'       => 'screenr_options',
		)
	);

	$am_font_choices = array(
		''                 => esc_html__( 'Barlow (default)', 'screenr' ),
		'Inter'            => 'Inter',
		'Open Sans'        => 'Open Sans',
		'Roboto'           => 'Roboto',
		'Lato'             => 'Lato',
		'Montserrat'       => 'Montserrat',
		'Raleway'          => 'Raleway',
		'Oswald'           => 'Oswald',
		'Playfair Display' => 'Playfair Display',
		'Merriweather'     => 'Merriweather',
	);

	$wp_customize->add_setting( 'am_body_font_family',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		)
	);
	$wp_customize->add_control( 'am_body_font_family',
		array(
			'type'    => 'select',
			'label'   => esc_html__( 'Body Font', 'screenr' ),
			'section' => 'am_typography',
			'choices' => $am_font_choices,
		)
	);

	$wp_customize->add_setting( 'am_body_font_size',
		array(
			'sanitize_callback' => 'absint',
			'default'           => 16,
		)
	);
	$wp_customize->add_control( 'am_body_font_size',
		array(
			'type'        => 'number',
			'label'       => esc_html__( 'Body Font Size (px)', 'screenr' ),
			'section'     => 'am_typography',
			'input_attrs' => array( 'min' => 12, 'max' => 24, 'step' => 1 ),
		)
	);

	$wp_customize->add_setting( 'am_heading_font_family',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		)
	);
	$wp_customize->add_control( 'am_heading_font_family',
		array(
			'type'    => 'select',
			'label'   => esc_html__( 'Headings Font', 'screenr' ),
			'section' => 'am_typography',
			'choices' => $am_font_choices,
		)
	);

	/* Extra header controls
	   ---------------------------------------------------------------------- */

	// Header Height
	$wp_customize->add_setting( 'am_header_height',
		array(
			'sanitize_callback' => 'absint',
			'default'           => 85,
		)
	);
	$wp_customize->add_control( 'am_header_height',
		array(
			'type'        => 'number',
			'label'       => esc_html__( 'Header Height (px)', 'screenr' ),
			'section'     => 'header_settings',
			'input_attrs' => array( 'min' => 40, 'max' => 200, 'step' => 1 ),
		)
	);

	// Logo Max Height
	$wp_customize->add_setting( 'am_logo_height',
		array(
			'sanitize_callback' => 'absint',
			'default'           => 40,
		)
	);
	$wp_customize->add_control( 'am_logo_height',
		array(
			'type'        => 'number',
			'label'       => esc_html__( 'Logo Max Height (px)', 'screenr' ),
			'section'     => 'header_settings',
			'input_attrs' => array( 'min' => 20, 'max' => 150, 'step' => 1 ),
		)
	);

	// Menu Font Size
	$wp_customize->add_setting( 'am_menu_font_size',
		array(
			'sanitize_callback' => 'absint',
			'default'           => 12,
		)
	);
	$wp_customize->add_control( 'am_menu_font_size',
		array(
			'type'        => 'number',
			'label'       => esc_html__( 'Menu Font Size (px)', 'screenr' ),
			'section'     => 'header_settings',
			'input_attrs' => array( 'min' => 10, 'max' => 24, 'step' => 1 ),
		)
	);

	/* Footer Social Media
	   ---------------------------------------------------------------------- */
	$wp_customize->add_section( 'footer_social_settings',
		array(
			'priority'    => 21,
			'title'       => esc_html__( 'Footer Social Media', 'screenr' ),
			'panel'       => 'screenr_options',
		)
	);

	$wp_customize->add_setting( 'footer_social_label',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 'Folge Sie uns',
		)
	);
	$wp_customize->add_control( 'footer_social_label',
		array(
			'label'       => esc_html__( 'Label text', 'screenr' ),
			'description' => esc_html__( 'Text shown above the icons. Leave blank to hide.', 'screenr' ),
			'section'     => 'footer_social_settings',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting( 'footer_social_instagram',
		array(
			'sanitize_callback' => 'esc_url_raw',
			'default'           => 'https://www.instagram.com/am.restore',
		)
	);
	$wp_customize->add_control( 'footer_social_instagram',
		array(
			'label'       => esc_html__( 'Instagram URL', 'screenr' ),
			'description' => esc_html__( 'Leave blank to hide the Instagram icon.', 'screenr' ),
			'section'     => 'footer_social_settings',
			'type'        => 'url',
		)
	);

	$wp_customize->add_setting( 'footer_social_linkedin',
		array(
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		)
	);
	$wp_customize->add_control( 'footer_social_linkedin',
		array(
			'label'       => esc_html__( 'LinkedIn URL', 'screenr' ),
			'description' => esc_html__( 'Leave blank to hide the LinkedIn icon.', 'screenr' ),
			'section'     => 'footer_social_settings',
			'type'        => 'url',
		)
	);
}
