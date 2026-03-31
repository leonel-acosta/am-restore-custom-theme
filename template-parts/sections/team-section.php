<?php
/**
 * Template Part: Team Section
 *
 * Two blocks sharing the same background:
 *   1. text-section component (heading, content, optional button)
 *   2. team-grid container
 *
 * Args match template-parts/sections/text.php exactly:
 *   heading        string
 *   text           string  (HTML / wysiwyg)
 *   button_text    string
 *   button_url     string
 *   theme          string  'dark' | 'light' | 'white'  (default: 'light')
 *   direction      string  'row' | 'column'            (default: 'row')
 *   align          string  'left' | 'center'           (default: 'left')
 *   padding        string  'sm' | 'md' | 'lg'          (default: 'md')
 *   border_content bool
 */

$theme = $args['theme'] ?? 'light';
if ( ! in_array( $theme, [ 'dark', 'light', 'white' ], true ) ) {
    $theme = 'light';
}
?>

<?php get_template_part( 'template-parts/sections/text', null, $args ); ?>

<div class="team-section__grid text-section--<?php echo esc_attr( $theme ); ?>" id="team">
    <div class="container" data-aos="fade-up">
        <?php get_template_part( 'template-parts/sections/team-grid' ); ?>
    </div>
</div>
