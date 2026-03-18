<?php

/**
 * Template Part: Text + Image Section
 *
 * Reusable across pages/templates. Pass ACF fields via $args.
 *
 * Args:
 *   heading      string   Section heading
 *   text         string   Body text / HTML
 *   image        array    ACF image array { url, alt }
 *   button_text  string   Optional button label
 *   button_url   string   Optional button URL
 *   reversed     bool     Image left, text right (default: false = text left, image right)
 *   theme        string   Color scheme: 'dark' (default) | 'light' | 'white'
 *   border       bool     Add 1px top + bottom border (default: false)
 *   class        string   Extra classes on the <section> element
 */

$heading     = $args['heading']     ?? '';
$text        = $args['text']        ?? '';
$image       = $args['image']       ?? null;
$button_text = $args['button_text'] ?? '';
$button_url  = $args['button_url']  ?? '';
$reversed    = ! empty($args['reversed']);
$theme       = $args['theme']       ?? 'dark';
$border      = ! empty($args['border']);
$extra_class = $args['class']       ?? '';

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

// Row direction: reversed = image left (row-reverse), default = image right (row)
$row_dir = $reversed ? 'md:flex-row-reverse' : 'md:flex-row';

$section_class = 'text-image-section';
if ($reversed) {
    $section_class .= ' text-image-section--reversed';
}
if ($theme === 'light') {
    $section_class .= ' text-image-section--light';
} elseif ($theme === 'white') {
    $section_class .= ' text-image-section--white';
}
if ($border) {
    $section_class .= ' text-image-section--bordered';
}
if ($extra_class) {
    $section_class .= ' ' . $extra_class;
}
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

            <?php if ($image_url) : ?>
                <div class="text-image-section__image-wrap w-full md:w-1/2 overflow-hidden center">
                    <img
                        src="<?php echo esc_url($image_url); ?>"
                        alt="<?php echo esc_attr($image_alt); ?>"
                        class="text-image-section__image w-full h-full object-cover block">
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>