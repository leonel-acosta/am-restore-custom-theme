<?php

/**
 * Template Part: Featured Projects Section
 *
 * Args:
 *   post_id           int     Post ID to read ACF fields from (default: current post)
 *   theme_override    string  'dark' | 'light' | 'white' — overrides ACF theme field
 *   autoplay          bool    Auto-advance carousel slides (default: false)
 *
 * ACF fields (flat, on $post_id):
 *   section_title       text
 *   section_content     wysiwyg
 *   categories          checkbox  (All, Recent, Untersuchungen, Restaurierung, …)
 *   all_projects_link   url
 *   theme               select    dark | light | white
 *
 * Query logic:
 *   - Empty or ["All"] or ["Recent"] → latest posts (any category, max 12)
 *   - Specific category names         → posts matching those categories (max 12)
 *
 * Carousel: shown automatically when there are more than 3 posts.
 * Groups posts into pages of 3; prev/next arrows + dot indicators.
 */

$post_id  = $args['post_id']  ?? get_the_ID();
$title    = $args['title']    ?? get_field('section_title',     $post_id) ?: '';
$content  = $args['content']  ?? get_field('section_content',   $post_id) ?: '';
$cat      = $args['cat']      ?? get_field('categories',        $post_id) ?: '';
$link     = $args['link']     ?? get_field('all_projects_link', $post_id) ?: '';
$theme    = $args['theme']    ?? $args['theme_override'] ?? (get_field('theme', $post_id) ?: 'dark');
$acf_enable_carousel = get_field('enable_carousel', $post_id);
$autoplay         = isset($args['autoplay'])         ? (bool) $args['autoplay']         : (bool) get_field('autoplay', $post_id);
$enable_carousel  = isset($args['enable_carousel'])  ? (bool) $args['enable_carousel']  : ( $acf_enable_carousel !== false ? (bool) $acf_enable_carousel : true );
$columns          = max(1, min(3, intval($args['columns'] ?? ( get_field('columns', $post_id) ?: 3 ))));

if (! in_array($theme, ['dark', 'light', 'white'], true)) {
    $theme = 'dark';
}

// ── Build WP_Query args ──────────────────────────────────────────────────────
$query_args = [
    'post_type'           => 'post',
    'posts_per_page'      => $enable_carousel ? 12 : 3,
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
];

// Always exclude Uncategorized
$uncategorized = get_category_by_slug('uncategorized');
if ($uncategorized) {
    $query_args['category__not_in'] = [ $uncategorized->term_id ];
}

// If a specific category is selected (not All / Recent / empty), filter by it
if ($cat && ! in_array($cat, ['All', 'Recent'], true)) {
    $query_args['tax_query'] = [[
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => sanitize_title($cat),
        'operator' => 'IN',
    ]];
    unset($query_args['category__not_in']);
}

$fp_query = new WP_Query($query_args);

if (! $fp_query->have_posts()) return;

// ── Collect rendered cards ───────────────────────────────────────────────────
$cards = [];
while ($fp_query->have_posts()) :
    $fp_query->the_post();
    $pid   = get_the_ID();
    $pdata = get_field('project_page', $pid) ?: [];

    $img_src = get_the_post_thumbnail_url($pid, 'large');
    if (! $img_src && ! empty($pdata['gallery'][0])) {
        $img_src = $pdata['gallery'][0]['sizes']['large'] ?? $pdata['gallery'][0]['url'] ?? '';
    }

    $type     = ! empty($pdata['type']) ? implode(', ', (array) $pdata['type']) : '';
    $client   = $pdata['client']   ?? '';
    $location = $pdata['location'] ?? '';
    $year     = $pdata['year']     ?? '';

    ob_start();
    get_template_part('template-parts/components/card-project', null, [
        'img_src'  => $img_src,
        'img_alt'  => get_the_title(),
        'type'     => $type,
        'client'   => $client,
        'location' => $location,
        'year'     => $year,
    ]);
    $cards[] = ob_get_clean();
endwhile;
wp_reset_postdata();

$total        = count($cards);
$use_carousel = $enable_carousel && $total > $columns;
$pages        = $use_carousel ? array_chunk($cards, $columns) : [ $cards ];
$grid_class   = 'featured-projects__grid' . ( $columns < 3 ? ' featured-projects__grid--cols-' . $columns : '' );
$page_count   = count($pages);
$carousel_id  = 'fp-carousel-' . uniqid();
?>

<section class="featured-projects featured-projects--<?php echo esc_attr($theme); ?>">
    <div class="container">

        <?php if ($title || $content || $link) : ?>
        <div class="featured-projects__header" data-aos="fade-up">
            <div class="featured-projects__header-text">
                <?php if ($title) : ?>
                    <h2 class="featured-projects__title text-section__heading text-section__heading--md">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>
                <?php if ($content) : ?>
                    <div class="featured-projects__subtitle">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($link) : ?>
                <a href="<?php echo esc_url($link); ?>" class="featured-projects__all-link">
                    <?php esc_html_e('More Projects', 'am-restore'); ?>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($use_carousel) : ?>

            <div class="featured-projects__carousel" id="<?php echo esc_attr($carousel_id); ?>" data-autoplay="<?php echo $autoplay ? '1' : '0'; ?>">

                <div class="featured-projects__track-wrap">
                    <div class="featured-projects__track">
                        <?php foreach ($pages as $page_cards) : ?>
                            <div class="featured-projects__slide">
                                <div class="<?php echo esc_attr($grid_class); ?>">
                                    <?php foreach ($page_cards as $card_html) : ?>
                                        <div class="featured-projects__card">
                                            <?php echo $card_html; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($page_count > 1) : ?>
                    <div class="featured-projects__carousel-footer">
                        <button class="featured-projects__carousel-arrow featured-projects__carousel-arrow--prev" aria-label="<?php esc_attr_e('Previous', 'am-restore'); ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <div class="featured-projects__carousel-dots" role="tablist">
                            <?php for ($d = 0; $d < $page_count; $d++) : ?>
                                <button
                                    class="featured-projects__carousel-dot<?php echo $d === 0 ? ' featured-projects__carousel-dot--active' : ''; ?>"
                                    role="tab"
                                    aria-selected="<?php echo $d === 0 ? 'true' : 'false'; ?>"
                                    aria-label="<?php echo esc_attr(sprintf(__('Page %d', 'am-restore'), $d + 1)); ?>"
                                    data-page="<?php echo $d; ?>">
                                </button>
                            <?php endfor; ?>
                        </div>

                        <button class="featured-projects__carousel-arrow featured-projects__carousel-arrow--next" aria-label="<?php esc_attr_e('Next', 'am-restore'); ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>

            </div>

        <?php else : ?>

            <div class="<?php echo esc_attr($grid_class); ?>">
                <?php foreach ($cards as $idx => $card_html) : ?>
                    <div class="featured-projects__card" data-aos="fade-up" data-aos-delay="<?php echo ( $idx % $columns ) * 80; ?>">
                        <?php echo $card_html; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php if ($use_carousel && $page_count > 1) : ?>
<script>
(function () {
    var car      = document.getElementById('<?php echo esc_js($carousel_id); ?>');
    var track    = car.querySelector('.featured-projects__track');
    var dots     = car.querySelectorAll('.featured-projects__carousel-dot');
    var prevBtn  = car.querySelector('.featured-projects__carousel-arrow--prev');
    var nextBtn  = car.querySelector('.featured-projects__carousel-arrow--next');
    var total    = <?php echo $page_count; ?>;
    var doAuto   = car.dataset.autoplay === '1';
    var current  = 0;
    var paused   = false;
    var timer;

    function goTo(index) {
        dots[current].classList.remove('featured-projects__carousel-dot--active');
        dots[current].setAttribute('aria-selected', 'false');

        current = (index + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';

        dots[current].classList.add('featured-projects__carousel-dot--active');
        dots[current].setAttribute('aria-selected', 'true');

    }

    if (prevBtn) prevBtn.addEventListener('click', function () { goTo(current - 1); resetTimer(); });
    if (nextBtn) nextBtn.addEventListener('click', function () { goTo(current + 1); resetTimer(); });

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            goTo(parseInt(this.dataset.page, 10));
            resetTimer();
        });
    });

    function startTimer() {
        if (! doAuto) return;
        timer = setInterval(function () {
            if (! paused) goTo(current + 1);
        }, 5000);
    }

    function resetTimer() {
        clearInterval(timer);
        startTimer();
    }

    car.addEventListener('mouseenter', function () { paused = true; });
    car.addEventListener('mouseleave', function () { paused = false; });

    startTimer();
}());
</script>
<?php endif; ?>
