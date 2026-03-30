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

$valid_themes = ['dark', 'medium', 'light', 'white', 'primary', 'accent'];
if (! in_array($theme, $valid_themes, true)) {
    $theme = 'light';
}

$image_url = '';
$image_alt = '';
if ($image) {
    $image_url = $image['sizes']['large'] ?? $image['url'] ?? '';
    $image_alt = $image['alt'] ?? '';
}

if (! $heading && ! $text && ! $image_url) {
    return;
}

$card_class = 'service-card service-card--' . $theme;
if ($reversed) {
    $card_class .= ' service-card--reversed';
}
?>

<section class="service-card-section">
    <div class="container">
        <div class="<?php echo esc_attr($card_class); ?>">

            <div class="service-card__text">
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
                <div class="service-card__image" aria-hidden="true">
                    <img
                        src="<?php echo esc_url($image_url); ?>"
                        alt="<?php echo esc_attr($image_alt); ?>"
                        loading="lazy">
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
