<?php

/**
 * Template Part: Logo Slider
 *
 * Auto-advances one item at a time.
 *
 * Optional args:
 *   post_id   int    post to read partners_logos from (default: current page)
 *   interval  int    ms between slides (default: 2000)
 */

$post_id   = $args['post_id']  ?? get_the_ID();
$interval  = intval($args['interval'] ?? 2000);
$slider_id = 'logo-slider-' . uniqid();

$logos = get_field('partners_logos', $post_id);

if (empty($logos)) {
    $logos = get_field('partners_logos', 2101);
}

$section_source  = (!empty(get_field('section_title', $post_id)) || !empty(get_field('section_heading', $post_id))) ? $post_id : 2101;
$section_title   = get_field('section_title', $section_source);
$section_heading = get_field('section_heading', $section_source);
$section_content = get_field('section_content', $section_source);

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
?>
<?php if ($section_title || $section_heading || $section_content) : ?>
    <div class="logo-slider-header">
        <?php if ($section_title) : ?>
            <p class="section-title"><?php echo esc_html($section_title); ?></p>
        <?php endif; ?>
        <?php if ($section_heading) : ?>
            <h2 class="section-heading"><?php echo esc_html($section_heading); ?></h2>
        <?php endif; ?>
        <?php if ($section_content) : ?>
            <div class="section-content"><?php echo wp_kses_post($section_content); ?></div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div id="<?php echo esc_attr($slider_id); ?>" class="logo-slider">
    <div class="logo-track">
        <?php foreach ($logos as $item) :
            // Support both gallery field (image direct) and repeater field (logo + link sub-fields)
            if (isset($item['url'])) {
                // Gallery field: item is the image array directly
                $src  = $item['sizes']['medium'] ?? $item['url'] ?? '';
                $alt  = $item['alt'] ?? $item['title'] ?? '';
                $link = '';
            } else {
                // Repeater field: item has logo + link sub-fields
                $logo = $item['logo'] ?? [];
                $link = $item['link'] ?? '';
                $src  = $logo['sizes']['medium'] ?? $logo['url'] ?? '';
                $alt  = $logo['alt'] ?? $logo['title'] ?? '';
            }
        ?>
            <div class="logo-item">
                <?php if ($link) : ?>
                    <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">
                        <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($alt); ?>">
                    </a>
                <?php else : ?>
                    <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($alt); ?>">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    (function() {
        var slider = document.getElementById('<?php echo esc_js($slider_id); ?>');
        var track = slider.querySelector('.logo-track');
        var items = track.querySelectorAll('.logo-item');
        var total = items.length;
        var current = 0;
        var paused = false;
        var interval = <?php echo intval($interval); ?>;

        track.style.transition = 'transform 0.5s ease';

        function getVisible() {
            var w = window.innerWidth;
            if (w >= 1024) return 6;
            if (w >= 640) return 3;
            return 2;
        }

        function slide() {
            if (paused) return;

            var visible = getVisible();
            var maxIndex = total - visible;

            current++;

            if (current > maxIndex) {
                // Snap back without transition, then re-enable
                track.style.transition = 'none';
                current = 0;
                track.style.transform = 'translateX(0)';
                // Force reflow before re-enabling transition
                track.offsetHeight;
                track.style.transition = 'transform 0.5s ease';
                return;
            }

            var itemWidth = items[0].offsetWidth;
            track.style.transform = 'translateX(-' + (current * itemWidth) + 'px)';
        }

        slider.addEventListener('mouseenter', function() {
            paused = true;
        });
        slider.addEventListener('mouseleave', function() {
            paused = false;
        });

        setInterval(slide, interval);
    })();
</script>