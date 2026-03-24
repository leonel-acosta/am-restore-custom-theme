<?php

/**
 * Template Name: Contact Page
 *
 * Sections:
 *   1. Page Title      — template-parts/page-title.php
 *   2. Contact Block   — template-parts/text-media-section.php
 *
 * ACF field group: "Contact Page" (group_contact_page_001)
 */

get_header();
the_post();

$page_id = get_the_ID();
$block   = get_field('contact_block', $page_id) ?: [];
?>

<div id="content" class="site-content">

    <?php get_template_part('template-parts/page-title'); ?>

    <main id="main" class="site-main contact-page" role="main">

        <?php if (! empty($block['heading']) || ! empty($block['text']) || ! empty($block['embed']) || ! empty($block['image'])) :
            get_template_part('template-parts/text-media-section', null, [
                'heading'     => $block['heading']     ?? '',
                'text'        => $block['text']        ?? '',
                'button_text' => $block['button_text'] ?? '',
                'button_url'  => $block['button_url']  ?? '',
                'media_type'  => $block['media_type']  ?? 'image',
                'image'       => $block['image']       ?? null,
                'embed'       => $block['embed']       ?? '',
                'reversed'    => ! empty($block['media_position']) && $block['media_position'] === 'left',
                'theme'       => $block['theme']       ?? 'light',
                'border'      => $block['border']      ?? '',
            ]);
        endif; ?>

    </main>

</div>

<?php get_footer(); ?>