<?php
/**
 * Template Part: Projects Gallery Carousel
 *
 * Full-width step-based carousel of project posts.
 * Steps 2 items at a time. Auto-advances every 5s. Pauses on hover.
 * Prev/Next controls. Drag (mouse + touch) support.
 *
 * Args:
 *   posts  array  WP_Post objects or IDs
 */

if (! defined('ABSPATH')) exit;

$posts = $args['posts'] ?? [];
$id    = 'pg-' . uniqid();

if (empty($posts)) return;
?>

<div class="projects-gallery-section">
    <div id="<?php echo esc_attr($id); ?>" class="projects-gallery" role="region" aria-label="<?php esc_attr_e('Projects Gallery', 'am-restore'); ?>">
        <div class="pg-track">
            <?php foreach ($posts as $post_obj) :
                $post_id = is_object($post_obj) ? $post_obj->ID : intval($post_obj);
                if (! $post_id) continue;

                $title   = get_the_title($post_id);
                $link    = get_permalink($post_id);
                $img_src = get_the_post_thumbnail_url($post_id, 'large');

                // Fallback: first image from project_gallery ACF layout
                $sections = get_field('sections', $post_id);
                if (! $img_src && $sections) {
                    foreach ($sections as $sec) {
                        if ($sec['acf_fc_layout'] === 'project_gallery' && ! empty($sec['gallery'])) {
                            $img_src = $sec['gallery'][0]['sizes']['large'] ?? $sec['gallery'][0]['url'] ?? '';
                            break;
                        }
                    }
                }

                if (! $img_src) continue;

                // Project meta from project_details ACF layout
                $client = $year = '';
                if ($sections) {
                    foreach ($sections as $sec) {
                        if ($sec['acf_fc_layout'] === 'project_details') {
                            $client = $sec['client'] ?? '';
                            $year   = $sec['year']   ?? '';
                            break;
                        }
                    }
                }
            ?>
                <a href="<?php echo esc_url($link); ?>"
                   class="pg-item"
                   draggable="false">
                    <img src="<?php echo esc_url($img_src); ?>"
                         alt="<?php echo esc_attr($title); ?>"
                         draggable="false">
                    <div class="pg-overlay">
                        <div class="pg-meta">
                            <h3 class="pg-title"><?php echo esc_html($title); ?></h3>
                            <?php if ($client) : ?>
                                <p class="pg-client"><?php echo esc_html($client); ?></p>
                            <?php endif; ?>
                            <?php if ($year) : ?>
                                <p class="pg-year"><?php echo esc_html($year); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <button class="pg-btn pg-prev" aria-label="<?php esc_attr_e('Previous', 'am-restore'); ?>">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M13 4L7 10L13 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
    <button class="pg-btn pg-next" aria-label="<?php esc_attr_e('Next', 'am-restore'); ?>">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
</div>

<script>
(function () {
    var section  = document.querySelector('.projects-gallery-section:has(#<?php echo esc_js($id); ?>)') ||
                   document.getElementById('<?php echo esc_js($id); ?>').parentElement;
    var wrap     = document.getElementById('<?php echo esc_js($id); ?>');
    var track    = wrap.querySelector('.pg-track');
    var items    = Array.from(track.querySelectorAll('.pg-item'));
    var prevBtn  = section.querySelector('.pg-prev');
    var nextBtn  = section.querySelector('.pg-next');
    if (!items.length) return;

    var STEP     = 2;
    var INTERVAL = 5000;
    var current  = 0;
    var paused   = false;
    var timer;

    function visibleCount() {
        var w = window.innerWidth;
        if (w >= 1024) return 4;
        if (w >= 640)  return 2;
        return 1;
    }
    function getGap() {
        return parseFloat(getComputedStyle(track).gap) || 16;
    }
    function getItemWidth() {
        return items[0].offsetWidth + getGap();
    }
    function maxIndex() {
        return Math.max(0, items.length - visibleCount());
    }
    function goTo(index) {
        current = Math.max(0, Math.min(index, maxIndex()));
        track.style.transition = 'transform 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        track.style.transform  = 'translateX(-' + (current * getItemWidth()) + 'px)';
    }
    function next() {
        var n = current + STEP;
        goTo(n > maxIndex() ? 0 : n);
    }
    function prev() {
        var p = current - STEP;
        goTo(p < 0 ? Math.floor(maxIndex() / STEP) * STEP : p);
    }
    function resetTimer() {
        clearInterval(timer);
        timer = setInterval(function () { if (!paused) next(); }, INTERVAL);
    }

    resetTimer();

    // Hover pause
    section.addEventListener('mouseenter', function () { paused = true; });
    section.addEventListener('mouseleave', function () { paused = false; });

    // Controls
    prevBtn.addEventListener('click', function () { prev(); resetTimer(); });
    nextBtn.addEventListener('click', function () { next(); resetTimer(); });

    // ── Mouse drag ─────────────────────────────────────────────
    var mStartX = 0, mDragMoved = false, mDragging = false;
    wrap.addEventListener('mousedown', function (e) {
        if (e.button !== 0) return;
        mStartX    = e.clientX;
        mDragging  = true;
        mDragMoved = false;
        wrap.style.cursor = 'grabbing';
        e.preventDefault();
    });
    document.addEventListener('mousemove', function (e) {
        if (!mDragging) return;
        if (Math.abs(e.clientX - mStartX) > 5) mDragMoved = true;
    });
    document.addEventListener('mouseup', function (e) {
        if (!mDragging) return;
        mDragging = false;
        wrap.style.cursor = '';
        if (mDragMoved && Math.abs(e.clientX - mStartX) > 50) {
            e.clientX < mStartX ? next() : prev();
            resetTimer();
            var once = function (ev) {
                ev.preventDefault(); ev.stopPropagation();
                wrap.removeEventListener('click', once, true);
            };
            wrap.addEventListener('click', once, true);
        }
    });

    // ── Touch drag ─────────────────────────────────────────────
    var tStartX = 0;
    wrap.addEventListener('touchstart', function (e) {
        tStartX = e.touches[0].clientX;
    }, { passive: true });
    wrap.addEventListener('touchend', function (e) {
        var dx = tStartX - e.changedTouches[0].clientX;
        if (Math.abs(dx) > 50) {
            dx > 0 ? next() : prev();
            resetTimer();
        }
    });
})();
</script>
