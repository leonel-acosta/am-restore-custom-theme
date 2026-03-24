<?php

/**
 * Template Part: Text + Media Section
 *
 * Like text-image-section, but the right column accepts either
 * an image OR an embed / shortcode string.
 *
 * Args:
 *   heading      string   Section heading
 *   text         string   Body text / HTML
 *   button_text  string   Optional button label
 *   button_url   string   Optional button URL
 *   media_type   string   'image' | 'embed'  (default: 'image')
 *   image        array    ACF image array { url, alt }  — used when media_type = 'image'
 *   embed        string   Shortcode or raw embed HTML  — used when media_type = 'embed'
 *   reversed     bool     Media left, text right (default: false = text left, media right)
 *   theme        string   'dark' (default) | 'light' | 'white'
 *   border       string   'top' | 'bottom' | 'both' | ''  (default: '')
 *   class        string   Extra classes on the <section> element
 */

$heading     = $args['heading']     ?? '';
$text        = $args['text']        ?? '';
$button_text = $args['button_text'] ?? '';
$button_url  = $args['button_url']  ?? '';
$media_type  = $args['media_type']  ?? 'image';
$image       = $args['image']       ?? null;
$embed       = $args['embed']       ?? '';
$reversed    = ! empty($args['reversed']);
$theme       = $args['theme']       ?? 'dark';
$border      = $args['border']      ?? '';
$extra_class = $args['class']       ?? '';

// Resolve image
$image_url = '';
$image_alt = '';
if ($image) {
    $image_url = $image['sizes']['large'] ?? $image['url'] ?? '';
    $image_alt = $image['alt'] ?? '';
}

// Determine what to show in the media column
$has_media = ($media_type === 'embed' && $embed) || ($media_type === 'image' && $image_url);

if (! $heading && ! $text && ! $has_media) {
    return;
}

$valid_themes  = ['dark', 'light', 'white'];
$valid_borders = ['top', 'bottom', 'both'];
$theme  = in_array($theme,  $valid_themes,  true) ? $theme  : 'dark';
$border = in_array($border, $valid_borders, true) ? $border : '';

$row_dir = $reversed ? 'md:flex-row-reverse' : 'md:flex-row';

$section_class = implode(' ', array_filter([
    'text-image-section',
    'text-image-section--' . $theme,
    $reversed ? 'text-image-section--reversed' : '',
    $border   ? 'text-image-section--border-' . $border : '',
    $extra_class,
]));
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="text-image-section__inner flex flex-col <?php echo esc_attr($row_dir); ?> items-center gap-10 py-10">

            <div class="text-image-section__text w-full md:w-1/2 py-5">
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

            <?php if ($has_media) : ?>
                <div class="text-image-section__image-wrap w-full md:w-1/2">
                    <?php if ($media_type === 'embed' && $embed) : ?>
                        <div class="text-image-section__embed">
                            <?php echo do_shortcode($embed); ?>
                        </div>
                    <?php elseif ($image_url) : ?>
                        <img
                            src="<?php echo esc_url($image_url); ?>"
                            alt="<?php echo esc_attr($image_alt); ?>"
                            class="text-image-section__image w-full h-full object-cover block">
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
