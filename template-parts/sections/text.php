<?php

/**
 * Template Part: Text Section (heading + content, no image)
 *
 * Row layout on desktop: heading left, content + button right.
 * Always column on mobile.
 *
 * Args:
 *   heading         string   Section heading
 *   text            string   Body text / HTML
 *   button_text     string   Optional button label
 *   button_url      string   Optional button URL
 *   heading_size    string   'sm' | 'md' | 'lg' | 'xl'  (default: 'md')
 *   direction       string   'row' (default) | 'column'
 *   layout          string   'equal' | 'heading-wide' | 'content-wide'  (default: 'equal') — only applies to 'row'
 *   align           string   'left' (default) | 'center'
 *   padding         string   'sm' | 'md' | 'lg'  (default: 'md')
 *   theme           string   'dark' | 'light' | 'white'  (default: 'dark')
 *   border          bool     1px top + bottom border on section  (default: false)
 *   border_content  bool     1px top border on content column, hidden on mobile  (default: false)
 *   class           string   Extra classes on the <section> element
 */

$heading        = $args['heading']        ?? '';
$text           = $args['text']           ?? '';
$button_text    = $args['button_text']    ?? '';
$button_url     = $args['button_url']     ?? '';
$heading_size   = $args['heading_size']   ?? 'md';
$direction      = $args['direction']      ?? 'row';
$layout         = $args['layout']         ?? 'equal';
$align          = $args['align']          ?? 'left';
$padding        = $args['padding']        ?? 'md';
$theme          = $args['theme']          ?? 'dark';
$border         = ! empty($args['border']);
$border_content = ! empty($args['border_content']);
$extra_class    = $args['class']          ?? '';

if (! $heading && ! $text) {
    return;
}

$valid_sizes      = ['sm', 'md', 'lg', 'xl'];
$valid_directions = ['row', 'column'];
$valid_layouts    = ['equal', 'heading-wide', 'content-wide'];
$valid_padding    = ['sm', 'md', 'lg'];
$valid_themes     = ['dark', 'light', 'white'];

$heading_size = in_array($heading_size, $valid_sizes,      true) ? $heading_size : 'md';
$direction    = in_array($direction,    $valid_directions, true) ? $direction    : 'row';
$layout       = in_array($layout,       $valid_layouts,    true) ? $layout       : 'equal';
$align        = in_array($align,        ['left', 'center'], true) ? $align       : 'left';
$padding      = in_array($padding,      $valid_padding,    true) ? $padding      : 'md';
$theme        = in_array($theme,        $valid_themes,     true) ? $theme        : 'dark';

// layout modifier only relevant in row direction
$layout_class = $direction === 'row' ? 'text-section--' . $layout : '';

$section_class = implode(' ', array_filter([
    'text-section',
    'text-section--' . $theme,
    'text-section--' . $direction,
    $layout_class,
    'text-section--padding-' . $padding,
    $align === 'center' ? 'text-section--align-center' : '',
    $border ? 'text-section--bordered' : '',
    $extra_class,
]));

$content_col_class = implode(' ', array_filter([
    'text-section__content',
    $border_content ? 'text-section__content--bordered' : '',
]));
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="text-section__inner">

            <div class="text-section__heading-col" data-aos="fade-right">
                <?php if ($heading) : ?>
                    <h2 class="text-section__heading text-section__heading--<?php echo esc_attr($heading_size); ?>">
                        <?php echo esc_html($heading); ?>
                    </h2>
                <?php endif; ?>
            </div>

            <div class="<?php echo esc_attr($content_col_class); ?>" data-aos="fade-up" data-aos-delay="80">
                <?php if ($text) : ?>
                    <div class="text-section__body"><?php echo wp_kses_post(wpautop($text)); ?></div>
                <?php endif; ?>

                <?php if ($button_text && $button_url) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="text-section__btn">
                        <?php echo esc_html($button_text); ?>
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>