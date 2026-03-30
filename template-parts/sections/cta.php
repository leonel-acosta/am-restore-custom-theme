<?php

/**
 * Template Part: Global CTA
 *
 * Uses ACF field group "Global - Call To Action":
 *   cta_heading, cta_content, button_text, button_link
 */

$heading      = $args['cta_heading']  ?? get_field('cta_heading')  ?: '';
$content      = $args['cta_content']  ?? get_field('cta_content')  ?: '';
$button_text  = $args['button_text']  ?? get_field('button_text')  ?: '';
$button_link  = $args['button_link']  ?? get_field('button_link')  ?: '';

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

            <?php if ($button_text && $button_link) : ?>
                <a href="<?php echo esc_url($button_link); ?>" class="btn-cta">
                    <?php echo esc_html($button_text); ?>
                </a>
            <?php endif; ?>

        </div>
    </div>
</section>