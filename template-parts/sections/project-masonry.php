<?php

/**
 * Template Part: Project Masonry Gallery
 *
 * Args:
 *   post_id   int   Post ID to pull ACF fields from
 *
 * Process row (3 cols):
 *   [ label ] [ content ] [ image 0 ]
 *   Long content (>400 chars) → [ label ] [ content ×2 ] [ image 0 ]
 *
 * Masonry blocks (each 3 cols × 2 rows, alternating big-left / big-right):
 *   Block A (even):  [    big    ] [ sm0 ]
 *                    [    big    ] [ sm1 ]
 *
 *   Block B (odd):   [ sm0 ] [    big    ]
 *                    [ sm1 ] [    big    ]
 *
 * additional_info text cell replaces sm1 by default.
 * Long additional_info (>150 chars) → text spans both rows (sm0 + sm1 replaced).
 */

$post_id         = $args['post_id'] ?? get_the_ID();
$data            = get_field('project_page', $post_id) ?: [];
$images          = $data['gallery']                ?? [];
$additional_info = $data['additional_info']        ?? '';
$process         = $data['second_section_content'] ?? '';
$process_label   = $data['second_section_label']   ?? 'Process';

if (empty($images)) return;

$all_images = array_values(array_map(fn($img) => [
    'url' => $img['url'] ?? '',
    'alt' => $img['alt'] ?? '',
], $images));

// Long-text flags
$process_long = $process && strlen(strip_tags($process)) > 300;
$info_long    = $additional_info && strlen(strip_tags($additional_info)) > 300;

// Which masonry block gets the additional_info text cell
$text_block_index = -1;
if ($additional_info) {
    $start            = $process ? 1 : 0;
    $full_blocks      = max(1, intdiv(count($images) - $start, 3));
    $text_block_index = $post_id % $full_blocks;
}

$image_index = 0;
$block_index = 0;
$modal_index = 0;
$uid         = 'pm-' . $post_id;
?>

<div class="container">
    <div class="project-masonry" id="<?php echo esc_attr($uid); ?>">

        <?php if ($process) : ?>
            <section class="project-process<?php echo $process_long ? ' project-process--wide' : ''; ?>" data-aos="fade-up">

                <div class="project-process__label">
                    <h2 class="project-process__heading text-section__heading text-section__heading--md">
                        <?php echo esc_html($process_label); ?>
                    </h2>
                </div>

                <div class="project-process__content project-masonry__text">
                    <div class="project-process__body">
                        <?php echo wp_kses_post($process); ?>
                    </div>
                </div>

                <?php if (!empty($images[$image_index])) :
                    $proc_img = $images[$image_index]; ?>
                    <div class="project-process__image">
                        <button class="project-masonry__cell"
                            data-modal="<?php echo esc_attr($uid); ?>"
                            data-index="<?php echo $modal_index; ?>"
                            aria-label="<?php echo esc_attr($proc_img['alt'] ?? ''); ?>">
                            <img src="<?php echo esc_url($proc_img['url']); ?>"
                                alt="<?php echo esc_attr($proc_img['alt'] ?? ''); ?>"
                                loading="lazy">
                        </button>
                    </div>
                    <?php $modal_index++;
                    $image_index++; ?>
                <?php endif; ?>

            </section>
        <?php endif; ?>

        <?php while (count($images) - $image_index >= 3) :
            $big_left    = ($block_index % 2 === 0);
            $block_class = $big_left ? 'project-masonry__block--big-left' : 'project-masonry__block--big-right';
            $is_text     = ($block_index === $text_block_index);

            $big = $images[$image_index];
            $sm  = [
                $images[$image_index + 1] ?? null,
                $images[$image_index + 2] ?? null,
            ];
        ?>

            <div class="project-masonry__block <?php echo esc_attr($block_class); ?>" data-aos="fade-up">

                <button class="project-masonry__cell project-masonry__big"
                    data-modal="<?php echo esc_attr($uid); ?>"
                    data-index="<?php echo $modal_index; ?>"
                    aria-label="<?php echo esc_attr($big['alt'] ?? ''); ?>">
                    <img src="<?php echo esc_url($big['url']); ?>"
                        alt="<?php echo esc_attr($big['alt'] ?? ''); ?>"
                        loading="lazy">
                </button>
                <?php $modal_index++; ?>

                <?php if ($is_text && $info_long) : ?>
                    <?php /* Long text: spans both small rows, no small images */ ?>
                    <div class="project-masonry__cell project-masonry__text project-masonry__text--tall">
                        <div class="project-masonry__text-inner">
                            <?php echo wp_kses_post($additional_info); ?>
                        </div>
                    </div>
                <?php else : ?>
                    <?php foreach ([0, 1] as $slot) :
                        if ($is_text && $slot === 1) : ?>
                            <div class="project-masonry__cell project-masonry__text">
                                <div class="project-masonry__text-inner">
                                    <?php echo wp_kses_post($additional_info); ?>
                                </div>
                            </div>
                        <?php elseif ($sm[$slot]) : ?>
                            <button class="project-masonry__cell project-masonry__small"
                                data-modal="<?php echo esc_attr($uid); ?>"
                                data-index="<?php echo $modal_index; ?>"
                                aria-label="<?php echo esc_attr($sm[$slot]['alt'] ?? ''); ?>">
                                <img src="<?php echo esc_url($sm[$slot]['url']); ?>"
                                    alt="<?php echo esc_attr($sm[$slot]['alt'] ?? ''); ?>"
                                    loading="lazy">
                            </button>
                            <?php $modal_index++; ?>
                    <?php endif;
                    endforeach; ?>
                <?php endif; ?>

            </div>

        <?php
            // Long text only consumes big image; normal text consumes big + sm0
            $image_index += ($is_text ? ($info_long ? 1 : 2) : 3);
            $block_index++;
        endwhile; ?>

        <?php $remainder = array_slice($images, $image_index);
        if (!empty($remainder)) : ?>
            <div class="project-masonry__remainder" data-aos="fade-up">
                <?php foreach ($remainder as $img) : ?>
                    <button class="project-masonry__cell project-masonry__small"
                        data-modal="<?php echo esc_attr($uid); ?>"
                        data-index="<?php echo $modal_index; ?>"
                        aria-label="<?php echo esc_attr($img['alt'] ?? ''); ?>">
                        <img src="<?php echo esc_url($img['url']); ?>"
                            alt="<?php echo esc_attr($img['alt'] ?? ''); ?>"
                            loading="lazy">
                    </button>
                    <?php $modal_index++; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<div class="pm-modal" id="<?php echo esc_attr($uid); ?>-modal" role="dialog" aria-modal="true" hidden>
    <button class="pm-modal__close" aria-label="<?php esc_attr_e('Close', 'am-restore'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
    </button>
    <button class="pm-modal__nav pm-modal__prev" aria-label="<?php esc_attr_e('Previous', 'am-restore'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>
    <div class="pm-modal__img-wrap">
        <img class="pm-modal__img" src="" alt="">
    </div>
    <button class="pm-modal__nav pm-modal__next" aria-label="<?php esc_attr_e('Next', 'am-restore'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>
</div>

<script>
    (function() {
        var images = <?php echo wp_json_encode($all_images); ?>;
        var uid = <?php echo wp_json_encode($uid); ?>;
        var gallery = document.getElementById(uid);
        var modal = document.getElementById(uid + '-modal');
        if (!gallery || !modal) return;

        var modalImg = modal.querySelector('.pm-modal__img');
        var closeBtn = modal.querySelector('.pm-modal__close');
        var focusableSelectors = 'button:not([disabled])';
        var current = 0;
        var opener = null;

        function getFocusable() {
            return Array.prototype.slice.call(modal.querySelectorAll(focusableSelectors));
        }

        function trapFocus(e) {
            if (e.key !== 'Tab') return;
            var focusable = getFocusable();
            var first = focusable[0];
            var last  = focusable[focusable.length - 1];
            if (e.shiftKey) {
                if (document.activeElement === first) { e.preventDefault(); last.focus(); }
            } else {
                if (document.activeElement === last)  { e.preventDefault(); first.focus(); }
            }
        }

        function open(index) {
            current = ((index % images.length) + images.length) % images.length;
            modalImg.src = images[current].url;
            modalImg.alt = images[current].alt;
            modal.hidden = false;
            document.body.style.overflow = 'hidden';
            closeBtn.focus();
            modal.addEventListener('keydown', trapFocus);
        }

        function close() {
            modal.hidden = true;
            document.body.style.overflow = '';
            modal.removeEventListener('keydown', trapFocus);
            if (opener) opener.focus();
        }

        gallery.querySelectorAll('[data-modal]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                opener = btn;
                open(parseInt(btn.dataset.index, 10));
            });
        });

        modal.querySelector('.pm-modal__close').addEventListener('click', close);
        modal.querySelector('.pm-modal__prev').addEventListener('click', function() {
            open(current - 1);
        });
        modal.querySelector('.pm-modal__next').addEventListener('click', function() {
            open(current + 1);
        });
        modal.addEventListener('click', function(e) {
            if (e.target === modal) close();
        });
        document.addEventListener('keydown', function(e) {
            if (modal.hidden) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowLeft') open(current - 1);
            if (e.key === 'ArrowRight') open(current + 1);
        });
    })();
</script>
