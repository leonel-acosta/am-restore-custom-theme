<?php

/**
 * Template Part: Logo Slider Section
 *
 * Self-contained section with optional heading, body text and an
 * auto-advancing logo strip. Shares the same theme / padding / align
 * system as text-section, text-image-section and text-media-section.
 *
 * Args:
 *   logos            array    Repeater rows: [ ['logo' => ACF image array, 'link' => url], … ]
 *   section_title    string   Small label above heading
 *   section_heading  string   Main heading
 *   section_content  string   Body text / HTML
 *   theme            string   'dark' (default) | 'light' | 'white'
 *   align            string   'center' (default) | 'left'
 *   padding          string   'sm' | 'md' (default) | 'lg'
 *   color_mode       string   'bw' (default, grayscale + dim) | 'color'
 *   interval         int      ms between slides (default: 2000)
 */

$logos           = $args['logos']           ?? null;
$section_title   = $args['section_title']   ?? null;
$section_heading = $args['section_heading'] ?? null;
$section_content = $args['section_content'] ?? null;
$theme           = $args['theme']           ?? 'dark';
$align           = $args['align']           ?? 'center';
$padding         = $args['padding']         ?? 'md';
$color_mode      = $args['color_mode']      ?? 'bw';
$interval        = intval($args['interval'] ?? 2000);

// Fallback: read from ACF page fields if not passed directly
if ($logos === null) {
    $post_id = $args['post_id'] ?? get_the_ID();
    $logos   = get_field('partners_logos', $post_id);
    if (empty($logos)) {
        $logos = get_field('partners_logos', 2101);
    }
}

if ($section_title === null && $section_heading === null) {
    $post_id        = $args['post_id'] ?? get_the_ID();
    $section_source = (!empty(get_field('section_title', $post_id)) || !empty(get_field('section_heading', $post_id))) ? $post_id : 2101;
    $section_title   = get_field('section_title',   $section_source);
    $section_heading = get_field('section_heading', $section_source);
    $section_content = get_field('section_content', $section_source);
}

// Fallback: repeat site logo for layout testing
if (empty($logos)) {
    $logo_id  = get_theme_mod('custom_logo');
    $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : '';
    if ($logo_url) {
        $logos = array_fill(0, 8, [
            'logo' => [
                'url'   => $logo_url,
                'alt'   => get_bloginfo('name'),
                'sizes' => ['medium' => $logo_url],
            ],
            'link' => '',
        ]);
    } else {
        return;
    }
}

$valid_themes     = ['dark', 'light', 'white'];
$valid_align      = ['center', 'left'];
$valid_padding    = ['sm', 'md', 'lg'];
$valid_color_mode = ['bw', 'color'];

$theme      = in_array($theme,      $valid_themes,     true) ? $theme      : 'dark';
$align      = in_array($align,      $valid_align,      true) ? $align      : 'center';
$padding    = in_array($padding,    $valid_padding,    true) ? $padding    : 'md';
$color_mode = in_array($color_mode, $valid_color_mode, true) ? $color_mode : 'bw';

$slider_id = 'logo-slider-' . uniqid();

$section_class = implode(' ', array_filter([
    'logo-slider-section',
    'logo-slider-section--' . $theme,
    'logo-slider-section--padding-' . $padding,
    'logo-slider-section--align-' . $align,
    'logo-slider-section--color-' . $color_mode,
]));
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">

        <?php if ($section_title || $section_heading || $section_content) : ?>
            <div class="logo-slider-section__header" data-aos="fade-up">
                <?php if ($section_title) : ?>
                    <p class="logo-slider-section__label"><?php echo esc_html($section_title); ?></p>
                <?php endif; ?>
                <?php if ($section_heading) : ?>
                    <h2 class="logo-slider-section__heading"><?php echo esc_html($section_heading); ?></h2>
                <?php endif; ?>
                <?php if ($section_content) : ?>
                    <div class="logo-slider-section__body"><?php echo wp_kses_post($section_content); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div><!-- .container -->

    <div id="<?php echo esc_attr($slider_id); ?>" class="logo-slider">
        <div class="logo-track">
            <?php foreach ($logos as $item) :
                // Support both gallery field (image direct) and repeater field (logo + link)
                if (isset($item['url'])) {
                    $src  = $item['sizes']['medium'] ?? $item['url'] ?? '';
                    $alt  = $item['alt'] ?? $item['title'] ?? '';
                    $link = '';
                } else {
                    $logo = $item['logo'] ?? [];
                    $link = $item['link'] ?? '';
                    $src  = $logo['sizes']['medium'] ?? $logo['url'] ?? '';
                    $alt  = $logo['alt'] ?? $logo['title'] ?? '';
                }
            ?>
                <div class="logo-item">
                    <?php if ($link) : ?>
                        <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer"
                           aria-label="<?php echo esc_attr($alt); ?> (<?php esc_attr_e('opens in new tab', 'am-restore'); ?>)">
                            <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($alt); ?>">
                        </a>
                    <?php else : ?>
                        <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($alt); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div><!-- .logo-slider -->

</section>

<script>
(function() {
    var slider = document.getElementById('<?php echo esc_js($slider_id); ?>');
    var track  = slider.querySelector('.logo-track');
    var items  = track.querySelectorAll('.logo-item');
    var total  = items.length;
    var current = 0;
    var paused  = false;
    var interval = <?php echo intval($interval); ?>;

    track.style.transition = 'transform 0.5s ease';

    function getVisible() {
        var w = window.innerWidth;
        if (w >= 1024) return 6;
        if (w >= 640)  return 3;
        return 2;
    }

    function slide() {
        if (paused) return;
        var visible  = getVisible();
        var maxIndex = total - visible;
        current++;
        if (current > maxIndex) {
            track.style.transition = 'none';
            current = 0;
            track.style.transform = 'translateX(0)';
            track.offsetHeight; // force reflow
            track.style.transition = 'transform 0.5s ease';
            return;
        }
        var itemWidth = items[0].offsetWidth;
        track.style.transform = 'translateX(-' + (current * itemWidth) + 'px)';
    }

    slider.addEventListener('mouseenter', function() { paused = true; });
    slider.addEventListener('mouseleave', function() { paused = false; });

    setInterval(slide, interval);
}());
</script>
