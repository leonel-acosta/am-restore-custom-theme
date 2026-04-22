<?php

/**
 * Template Part: Global CTA
 *
 * Uses ACF field group "Global: Call To Action":
 *   heading, content, button_text, button_url
 */

// Respect per-page toggle: null/missing = show (backward compat); false/0 = hide
$show = $args['show_cta'] ?? get_field('show_cta');
if ($show !== null && $show !== '' && ! $show) return;

$heading     = $args['heading']      ?? get_field('heading')      ?: '';
$content     = $args['content']      ?? get_field('content')      ?: '';
$button_text = $args['button_text']  ?? get_field('button_text')  ?: '';
$button_url  = $args['button_url']   ?? get_field('button_url')   ?: '';

if (! $heading && ! $content) return;
?>

<section class="cta-section page-section--dark py-10">
    <div class="container">
        <div class="cta-inner" data-aos="fade-up">

            <?php if ($heading) : ?>
                <h2 class="cta-heading"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>

            <?php if ($content) : ?>
                <p class="cta-content"><?php echo esc_html($content); ?></p>
            <?php endif; ?>

            <?php if ($button_text && $button_url) : ?>
                <a href="<?php echo esc_url($button_url); ?>" class="btn-cta">
                    <?php echo esc_html($button_text); ?>
                </a>
            <?php endif; ?>

        </div>
    </div>
</section>
