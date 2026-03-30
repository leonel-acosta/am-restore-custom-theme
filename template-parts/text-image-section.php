<?php

/**
 * Template Part: Text + Image Section
 *
 * Reusable across pages/templates. Pass ACF fields via $args.
 *
 * Args:
 *   heading        string   Section heading
 *   text           string   Body text / HTML
 *   image          array    ACF image array { url, alt }
 *   button_text    string   Optional button label
 *   button_url     string   Optional button URL
 *   reversed       bool     Image left, text right (default: false = text left, image right)
 *   theme          string   Color scheme: 'dark' (default) | 'light' | 'white'
 *   column_layout  string   'equal' (default) | 'text-wide' | 'image-wide' | 'one-column'
 *   align          string   'left' (default) | 'center'
 *   padding        string   'sm' | 'md' (default) | 'lg'
 *   border         bool     Add 1px top + bottom border on the section (default: false)
 *   border_content bool     Add 1px top border above the text column (default: false)
 *   class          string   Extra classes on the <section> element
 */

$heading       = $args['heading']       ?? '';
$text          = $args['text']          ?? '';
$image         = $args['image']         ?? null;
$button_text   = $args['button_text']   ?? '';
$button_url    = $args['button_url']    ?? '';
$reversed      = ! empty($args['reversed']);
$theme         = $args['theme']         ?? 'dark';
$column_layout = $args['column_layout'] ?? 'equal';
$align         = $args['align']         ?? 'left';
$padding       = $args['padding']       ?? 'md';
$border         = ! empty($args['border']);
$border_content = ! empty($args['border_content']);
$extra_class    = $args['class']         ?? '';

$valid_col_layouts = ['equal', 'text-wide', 'image-wide', 'one-column'];
$column_layout = in_array($column_layout, $valid_col_layouts, true) ? $column_layout : 'equal';
$align         = in_array($align, ['left', 'center'], true) ? $align : 'left';
$padding       = in_array($padding, ['sm', 'md', 'lg'], true) ? $padding : 'md';

// Resolve image URL: prefer large size, fall back to full URL
$image_url = '';
$image_alt = '';
if ($image) {
    $image_url = $image['sizes']['large'] ?? $image['url'] ?? '';
    $image_alt = $image['alt'] ?? '';
}

if (! $heading && ! $text && ! $image_url) {
    return;
}

// One-column: always stacked, no side-by-side layout
$one_column = $column_layout === 'one-column';

// Row direction: reversed = image left (row-reverse), default = image right (row)
$row_dir = $one_column ? '' : ($reversed ? 'md:flex-row-reverse' : 'md:flex-row');

$section_class = implode(' ', array_filter([
    'text-image-section',
    'text-image-section--' . $theme,
    'text-image-section--padding-' . $padding,
    $reversed && ! $one_column ? 'text-image-section--reversed' : '',
    $column_layout !== 'equal' ? 'text-image-section--layout-' . $column_layout : '',
    $align === 'center' ? 'text-image-section--align-center' : '',
    $border ? 'text-image-section--bordered' : '',
    $extra_class,
]));
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="text-image-section__inner flex flex-col <?php echo esc_attr($row_dir); ?> items-center gap-10">

            <div class="text-image-section__text<?php echo $border_content ? ' text-image-section__text--bordered' : ''; ?> w-full <?php echo $one_column ? '' : 'md:w-1/2'; ?> py-5">
                <?php if ($heading) : ?>
                    <h2 class="text-image-section__heading"><?php echo esc_html($heading); ?></h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="text-image-section__body"><?php echo wp_kses_post($text); ?></div>
                <?php endif; ?>

                <?php if ($button_text && $button_url) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="text-image-section__btn">
                        <?php echo esc_html($button_text); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($image_url) : ?>
                <div class="text-image-section__image-wrap w-full <?php echo $one_column ? '' : 'md:w-1/2'; ?> overflow-hidden center">
                    <img
                        src="<?php echo esc_url($image_url); ?>"
                        alt="<?php echo esc_attr($image_alt); ?>"
                        class="text-image-section__image w-full h-full object-cover block">
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>