<?php

/**
 * Template Part: Project Masonry Gallery
 *
 * Args:
 *   post_id   int   Post ID to pull ACF fields from
 *
 * Grid: 3 cols × 2 rows per block. Big image = 2 cols × 2 rows.
 *
 *   Block A (even — big left):
 *     [    big    ] [ sm0 ]
 *     [    big    ] [ sm1 ]
 *
 *   Block B (odd — big right):
 *     [ sm0 ] [    big    ]
 *     [ sm1 ] [    big    ]
 *
 * If additional_info exists, one small cell is replaced by a text panel.
 * Slot (0 or 1) is seeded by post ID for consistency per post.
 */

$post_id         = $args['post_id'] ?? get_the_ID();
$data            = get_field('project_page', $post_id) ?: [];
$images          = $data['gallery']         ?? [];
$additional_info = $data['additional_info'] ?? '';

if (empty($images)) return;

$all_images = array_values(array_map(fn($img) => [
    'url' => $img['url'] ?? '',
    'alt' => $img['alt'] ?? '',
], $images));

$text_block_index = -1;
$text_slot        = 0;

if ($additional_info) {
    $full_blocks      = max(1, intdiv(count($images), 3));
    $text_block_index = $post_id % $full_blocks;
    $text_slot        = 1; // always the second (bottom) small square
}

$image_index = 0;
$block_index = 0;
$modal_index = 0;
$uid         = 'pm-' . $post_id;
?>

<div class="project-masonry" id="<?php echo esc_attr($uid); ?>">

    <?php // Full blocks: require 1 big + 2 smalls (3 images) ?>
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

            <div class="project-masonry__block <?php echo esc_attr($block_class); ?>">

                <button class="project-masonry__cell project-masonry__big"
                        data-modal="<?php echo esc_attr($uid); ?>"
                        data-index="<?php echo $modal_index; ?>"
                        aria-label="<?php echo esc_attr($big['alt'] ?? ''); ?>">
                    <img src="<?php echo esc_url($big['url']); ?>"
                         alt="<?php echo esc_attr($big['alt'] ?? ''); ?>"
                         loading="lazy">
                </button>
                <?php $modal_index++; ?>

                <?php foreach ([0, 1] as $slot) :
                    if ($is_text && $slot === $text_slot) : ?>
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

            </div>

    <?php
        $image_index += ($is_text ? 2 : 3); // text block only consumes big + 1 small
        $block_index++;
    endwhile; ?>

    <?php // Remainder: 1 or 2 leftover images shown as small squares ?>
    <?php $remainder = array_slice($images, $image_index);
    if (!empty($remainder)) : ?>
        <div class="project-masonry__remainder">
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

<div class="pm-modal" id="<?php echo esc_attr($uid); ?>-modal" role="dialog" aria-modal="true" hidden>
    <button class="pm-modal__close" aria-label="<?php esc_attr_e('Close', 'am-restore'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    </button>
    <button class="pm-modal__nav pm-modal__prev" aria-label="<?php esc_attr_e('Previous', 'am-restore'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
    <div class="pm-modal__img-wrap">
        <img class="pm-modal__img" src="" alt="">
    </div>
    <button class="pm-modal__nav pm-modal__next" aria-label="<?php esc_attr_e('Next', 'am-restore'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
</div>

<script>
(function () {
    var images  = <?php echo wp_json_encode($all_images); ?>;
    var uid     = <?php echo wp_json_encode($uid); ?>;
    var gallery = document.getElementById(uid);
    var modal   = document.getElementById(uid + '-modal');
    if (!gallery || !modal) return;

    var modalImg = modal.querySelector('.pm-modal__img');
    var current  = 0;

    function open(index) {
        current = ((index % images.length) + images.length) % images.length;
        modalImg.src = images[current].url;
        modalImg.alt = images[current].alt;
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
    }
    function close() {
        modal.hidden = true;
        document.body.style.overflow = '';
    }

    gallery.querySelectorAll('[data-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () { open(parseInt(btn.dataset.index, 10)); });
    });

    modal.querySelector('.pm-modal__close').addEventListener('click', close);
    modal.querySelector('.pm-modal__prev').addEventListener('click', function () { open(current - 1); });
    modal.querySelector('.pm-modal__next').addEventListener('click', function () { open(current + 1); });
    modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
    document.addEventListener('keydown', function (e) {
        if (modal.hidden) return;
        if (e.key === 'Escape')     close();
        if (e.key === 'ArrowLeft')  open(current - 1);
        if (e.key === 'ArrowRight') open(current + 1);
    });
})();
</script>
