<?php

/**
 * Template Part: Hero Slider
 *
 * Crossfade slider. Each slide has a full-screen background (image or video),
 * overlay, heading, subheading and two independently styled buttons.
 *
 * Args:
 *   slides  array  ACF repeater rows, each containing:
 *     background_type    string  'image' | 'video'
 *     background_image   array   ACF image array
 *     background_video   string  .mp4 URL
 *     overlay_color      string  hex color
 *     overlay_opacity    int     0–100
 *     heading            string
 *     heading_size       string  sm | md | lg | xl | xxl
 *     heading_color      string  hex color
 *     subheading         string
 *     subheading_color   string  hex color
 *     button_1_text      string
 *     button_1_url       string
 *     button_1_style     string  filled | outline
 *     button_1_color     string  hex color
 *     button_2_text      string
 *     button_2_url       string
 *     button_2_style     string  filled | outline
 *     button_2_color     string  hex color
 */

$slides = $args['slides'] ?? [];

if (empty($slides)) {
    return;
}

$slider_id     = 'hp-hero-' . uniqid();
$slide_count   = count($slides);
$valid_sizes   = ['sm', 'md', 'lg', 'xl', 'xxl'];
$valid_aligns  = ['middle', 'bottom'];

/**
 * Build inline style string for a button.
 */
function hp_hero_btn_style( string $color, string $style ): string {
    if ($style === 'filled') {
        return "background-color:{$color};border-color:{$color};color:#fff;";
    }
    return "background-color:transparent;border-color:{$color};color:{$color};";
}

/**
 * Convert #rrggbb + int opacity to rgba().
 */
function hp_hero_overlay_rgba( string $hex, int $opacity ): string {
    if (preg_match('/^#([a-f0-9]{6})$/i', $hex, $m)) {
        $r = hexdec(substr($m[1], 0, 2));
        $g = hexdec(substr($m[1], 2, 2));
        $b = hexdec(substr($m[1], 4, 2));
        $a = round($opacity / 100, 2);
        return "rgba({$r},{$g},{$b},{$a})";
    }
    return 'rgba(0,0,0,0.4)';
}
?>

<section class="hp-hero" id="<?php echo esc_attr($slider_id); ?>" aria-label="Hero Slider">

    <?php foreach ($slides as $i => $slide) :
        $bg_type    = $slide['background_type']  ?? 'image';
        $bg_image   = $slide['background_image'] ?? null;
        $bg_video   = $slide['background_video'] ?? '';
        $ov_color   = $slide['overlay_color']    ?? '#000000';
        $ov_opacity = intval($slide['overlay_opacity'] ?? 40);
        $heading    = $slide['heading']           ?? '';
        $hd_size    = in_array($slide['heading_size'] ?? '', $valid_sizes, true) ? $slide['heading_size'] : 'xl';
        $hd_color   = $slide['heading_color']     ?? '#ffffff';
        $subheading = $slide['subheading']        ?? '';
        $sub_color  = $slide['subheading_color']  ?? '#ffffff';
        $btn1_text  = $slide['button_1_text']     ?? '';
        $btn1_url   = $slide['button_1_url']      ?? '';
        $btn1_style = $slide['button_1_style']    ?? 'filled';
        $btn1_color = $slide['button_1_color']    ?? '#8b1a2f';
        $btn2_text  = $slide['button_2_text']     ?? '';
        $btn2_url   = $slide['button_2_url']      ?? '';
        $btn2_style = $slide['button_2_style']    ?? 'outline';
        $btn2_color = $slide['button_2_color']    ?? '#ffffff';

        $bg_img_url    = '';
        $bg_img_w      = 0;
        $bg_img_h      = 0;
        if ($bg_type === 'image' && $bg_image) {
            $bg_img_url = $bg_image['sizes']['large'] ?? $bg_image['url'] ?? '';
            $bg_img_w   = $bg_image['sizes']['large-width']  ?? $bg_image['width']  ?? 0;
            $bg_img_h   = $bg_image['sizes']['large-height'] ?? $bg_image['height'] ?? 0;
        }

        $parallax      = !empty($slide['parallax']) && $bg_type === 'image';
        $align         = in_array($slide['content_align'] ?? '', $valid_aligns, true) ? $slide['content_align'] : 'middle';
        $ov_rgba       = hp_hero_overlay_rgba($ov_color, $ov_opacity);
        $is_active     = $i === 0 ? ' hp-hero__slide--active' : '';
        $slide_classes = 'hp-hero__slide' . $is_active . ' hp-hero__slide--align-' . $align;
    ?>
        <div
            class="<?php echo esc_attr($slide_classes); ?>"
            aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>"
            data-slide="<?php echo $i; ?>"
            <?php if ($parallax) : ?>data-parallax="1"<?php endif; ?>>

            <?php if ($bg_type === 'video' && $bg_video) : ?>
                <video
                    class="hp-hero__bg hp-hero__bg--video"
                    src="<?php echo esc_url($bg_video); ?>"
                    autoplay muted loop playsinline
                    aria-hidden="true">
                </video>
            <?php elseif ($bg_img_url) : ?>
                <?php if ($i === 0) : ?>
                    <?php /* First slide: <img> with fetchpriority=high for best LCP */ ?>
                    <img
                        class="hp-hero__bg hp-hero__bg--image"
                        src="<?php echo esc_url($bg_img_url); ?>"
                        alt=""
                        aria-hidden="true"
                        fetchpriority="high"
                        decoding="async"
                        <?php if ($bg_img_w && $bg_img_h) : ?>
                        width="<?php echo (int) $bg_img_w; ?>"
                        height="<?php echo (int) $bg_img_h; ?>"
                        <?php endif; ?>>
                <?php else : ?>
                    <?php /* Subsequent slides: background-image, lazy via CSS (hidden until active) */ ?>
                    <div
                        class="hp-hero__bg hp-hero__bg--image"
                        style="background-image:url('<?php echo esc_url($bg_img_url); ?>');"
                        aria-hidden="true">
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="hp-hero__overlay" style="background-color:<?php echo esc_attr($ov_rgba); ?>;"></div>

            <div class="container">
                <div class="hp-hero__content">

                    <?php if ($heading) : ?>
                        <h1
                            class="hp-hero__heading hp-hero__heading--<?php echo esc_attr($hd_size); ?>"
                            style="color:<?php echo esc_attr($hd_color); ?>;">
                            <?php echo esc_html($heading); ?>
                        </h1>
                    <?php endif; ?>

                    <?php if ($subheading) : ?>
                        <p
                            class="hp-hero__subheading"
                            style="color:<?php echo esc_attr($sub_color); ?>;">
                            <?php echo wp_kses($subheading, ['br' => []]); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($btn1_text || $btn2_text) : ?>
                        <div class="hp-hero__buttons">
                            <?php if ($btn1_text && $btn1_url) : ?>
                                <a
                                    href="<?php echo esc_url($btn1_url); ?>"
                                    class="hp-hero__btn hp-hero__btn--<?php echo esc_attr($btn1_style); ?>"
                                    style="<?php echo esc_attr(hp_hero_btn_style($btn1_color, $btn1_style)); ?>">
                                    <?php echo esc_html($btn1_text); ?>
                                </a>
                            <?php endif; ?>
                            <?php if ($btn2_text && $btn2_url) : ?>
                                <a
                                    href="<?php echo esc_url($btn2_url); ?>"
                                    class="hp-hero__btn hp-hero__btn--<?php echo esc_attr($btn2_style); ?>"
                                    style="<?php echo esc_attr(hp_hero_btn_style($btn2_color, $btn2_style)); ?>">
                                    <?php echo esc_html($btn2_text); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div><!-- .hp-hero__slide -->

    <?php endforeach; ?>

    <?php if ($slide_count > 1) : ?>

        <div class="hp-hero__controls">

            <button class="hp-hero__arrow hp-hero__arrow--prev" aria-label="Previous slide">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <span class="hp-hero__counter" aria-live="polite" aria-atomic="true">
                <span class="hp-hero__counter-current">1</span>
                <span class="hp-hero__counter-sep">/</span>
                <span class="hp-hero__counter-total"><?php echo $slide_count; ?></span>
            </span>

            <button class="hp-hero__arrow hp-hero__arrow--next" aria-label="Next slide">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

        </div>

        <div class="hp-hero__dots" role="tablist" aria-label="Slide indicators">
            <?php for ($d = 0; $d < $slide_count; $d++) : ?>
                <button
                    class="hp-hero__dot<?php echo $d === 0 ? ' hp-hero__dot--active' : ''; ?>"
                    role="tab"
                    aria-selected="<?php echo $d === 0 ? 'true' : 'false'; ?>"
                    aria-label="Slide <?php echo $d + 1; ?>"
                    data-dot="<?php echo $d; ?>">
                </button>
            <?php endfor; ?>
        </div>

    <?php endif; ?>

</section>

<?php if ($slide_count > 1) : ?>
<script>
(function () {
    var hero        = document.getElementById('<?php echo esc_js($slider_id); ?>');
    var slides      = hero.querySelectorAll('.hp-hero__slide');
    var dots        = hero.querySelectorAll('.hp-hero__dot');
    var prevBtn     = hero.querySelector('.hp-hero__arrow--prev');
    var nextBtn     = hero.querySelector('.hp-hero__arrow--next');
    var counter     = hero.querySelector('.hp-hero__counter-current');
    var total       = slides.length;
    var current     = 0;
    var paused      = false;
    var interval    = 5000;
    var timer;

    function goTo(index) {
        slides[current].classList.remove('hp-hero__slide--active');
        slides[current].setAttribute('aria-hidden', 'true');
        dots[current].classList.remove('hp-hero__dot--active');
        dots[current].setAttribute('aria-selected', 'false');

        current = (index + total) % total;

        slides[current].classList.add('hp-hero__slide--active');
        slides[current].setAttribute('aria-hidden', 'false');
        dots[current].classList.add('hp-hero__dot--active');
        dots[current].setAttribute('aria-selected', 'true');

        if (counter) counter.textContent = current + 1;
    }

    function startTimer() {
        timer = setInterval(function () {
            if (!paused) goTo(current + 1);
        }, interval);
    }

    nextBtn.addEventListener('click', function () { goTo(current + 1); resetTimer(); });
    prevBtn.addEventListener('click', function () { goTo(current - 1); resetTimer(); });

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            goTo(parseInt(this.dataset.dot, 10));
            resetTimer();
        });
    });

    function resetTimer() {
        clearInterval(timer);
        startTimer();
    }

    hero.addEventListener('mouseenter', function () { paused = true; });
    hero.addEventListener('mouseleave', function () { paused = false; });

    // Parallax (rAF-throttled)
    var parallaxSlides = hero.querySelectorAll('[data-parallax="1"]');
    if (parallaxSlides.length > 0) {
        var rafPending = false;
        function updateParallax() {
            var rect = hero.getBoundingClientRect();
            if (rect.bottom < 0 || rect.top > window.innerHeight) { rafPending = false; return; }
            var offset = -rect.top * 0.25;
            parallaxSlides.forEach(function (slide) {
                var bg = slide.querySelector('.hp-hero__bg--image');
                if (bg) bg.style.transform = 'translateY(' + offset + 'px)';
            });
            rafPending = false;
        }
        window.addEventListener('scroll', function () {
            if (!rafPending) { rafPending = true; requestAnimationFrame(updateParallax); }
        }, { passive: true });
        updateParallax();
    }

    startTimer();
}());
</script>
<?php endif; ?>
