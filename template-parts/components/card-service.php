<?php

/**
 * Component: Service Card
 *
 * A two-column card (text + image) where the theme background is applied
 * to the card itself, not the outer section. The image is flush — no padding
 * or margin — and always fills its column via object-fit: cover, adapting to
 * taller cards when the text is long.
 *
 * Args:
 *   heading       string   Section heading
 *   text          string   Body copy / HTML (wysiwyg output)
 *   image         array    ACF image array { url, alt, sizes }
 *   button_text   string   Optional CTA label
 *   button_url    string   Optional CTA URL
 *   reversed      bool     Image on the left (default: false = image right)
 *   theme         string   'dark' | 'medium' | 'light' | 'white' | 'primary' | 'accent'
 */

$heading     = $args['heading']     ?? '';
$text        = $args['text']        ?? '';
$image       = $args['image']       ?? null;
$button_text = $args['button_text'] ?? '';
$button_url  = $args['button_url']  ?? '';
$reversed    = ! empty($args['reversed']);
$theme       = $args['theme']       ?? 'light';
$padding     = $args['padding']     ?? 'sm';

$valid_themes  = ['dark', 'medium', 'light', 'white', 'primary', 'accent'];
$valid_padding = ['none', 'sm', 'md', 'lg'];
if (! in_array($theme, $valid_themes, true)) {
    $theme = 'light';
}
if (! in_array($padding, $valid_padding, true)) {
    $padding = 'sm';
}

$image_url = '';
$image_alt = '';
$image_w   = 0;
$image_h   = 0;
if ($image) {
    $image_url = $image['sizes']['large']        ?? $image['url']    ?? '';
    $image_alt = $image['alt']                   ?? '';
    $image_w   = $image['sizes']['large-width']  ?? $image['width']  ?? 0;
    $image_h   = $image['sizes']['large-height'] ?? $image['height'] ?? 0;
}

if (! $heading && ! $text && ! $image_url) {
    return;
}

$section_class = 'service-card-section service-card-section--padding-' . $padding;

$card_class = 'service-card service-card--' . $theme;
if ($reversed) {
    $card_class .= ' service-card--reversed';
}

$text_aos  = $reversed ? 'fade-left'  : 'fade-right';
$image_aos = $reversed ? 'fade-right' : 'fade-left';
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="<?php echo esc_attr($card_class); ?>">

            <div class="service-card__text" data-aos="<?php echo esc_attr($text_aos); ?>">
                <?php if ($heading) : ?>
                    <h2 class="service-card__heading"><?php echo esc_html($heading); ?></h2>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <div class="service-card__body"><?php echo wp_kses_post($text); ?></div>
                <?php endif; ?>

                <?php if ($button_text && $button_url) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="service-card__btn">
                        <?php echo esc_html($button_text); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($image_url) : ?>
                <div class="service-card__image" aria-hidden="true" data-aos="<?php echo esc_attr($image_aos); ?>" data-aos-delay="100">
                    <img
                        src="<?php echo esc_url($image_url); ?>"
                        alt="<?php echo esc_attr($image_alt); ?>"
                        loading="lazy"
                        decoding="async"
                        <?php if ($image_w && $image_h) : ?>
                        width="<?php echo (int) $image_w; ?>"
                        height="<?php echo (int) $image_h; ?>"
                        <?php endif; ?>>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
