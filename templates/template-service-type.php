<?php

/**
 * Template Name: Service Type Page
 *
 * Renders a service type detail page (e.g. Planning, Execution).
 *
 * Sections:
 *   1. Page Title              — template-parts/sections/page-title.php
 *   2. Intro Section           — template-parts/sections/text.php
 *   3. Service Items           — template-parts/sections/text-image.php (repeater)
 *   4. Additional Information  — template-parts/sections/text.php
 *   5. Featured Projects       — template-parts/sections/featured-projects.php
 *
 * ACF field group: "Page: Service Type" (group_69c268386be0e)
 */

get_header();
the_post();

$page_id    = get_the_ID();
$intro      = get_field('intro_section',                  $page_id) ?: [];
$items      = get_field('service_items',                  $page_id);
$additional = get_field('additional_information_section', $page_id) ?: [];
?>

<div id="content" class="site-content">

    <?php get_template_part('template-parts/sections/page-title'); ?>

    <main id="main" class="site-main service-type-page" role="main">

        <?php if (! empty($intro['heading']) || ! empty($intro['content'])) :
            get_template_part('template-parts/sections/text', null, [
                'heading'        => $intro['heading']        ?? '',
                'text'           => $intro['content']        ?? '',
                'button_text'    => $intro['button_text']    ?? '',
                'button_url'     => $intro['button_url']     ?? '',
                'theme'          => $intro['theme']          ?? 'light',
                'direction'      => $intro['direction']      ?? 'row',
                'align'          => $intro['align']          ?? 'left',
                'padding'        => $intro['padding']        ?? 'md',
                'border_content' => $intro['border_content'] ?? true,
            ]);
        endif; ?>

        <?php if ($items) :
            foreach ($items as $item) :
                get_template_part('template-parts/sections/text-image', null, [
                    'heading'     => $item['heading']      ?? '',
                    'text'        => $item['content']      ?? '',
                    'image'       => $item['image']        ?? null,
                    'button_text' => $item['button_text']  ?? '',
                    'button_url'  => $item['button_url']   ?? '',
                    'reversed'    => ! empty($item['image_position']) && $item['image_position'] === 'left',
                    'theme'       => $item['theme']        ?? 'light',
                    'border'      => ! empty($item['border']),
                    'padding'     => $item['padding']      ?? 'sm',
                ]);
            endforeach;
        endif; ?>

        <?php if (! empty($additional['heading']) || ! empty($additional['content'])) :
            get_template_part('template-parts/sections/text', null, [
                'heading'        => $additional['heading']        ?? '',
                'text'           => $additional['content']        ?? '',
                'button_text'    => $additional['button_text']    ?? '',
                'button_url'     => $additional['button_url']     ?? '',
                'theme'          => $additional['theme']          ?? 'light',
                'direction'      => $additional['direction']      ?? 'row',
                'align'          => $additional['align']          ?? 'left',
                'padding'        => $additional['padding']        ?? 'md',
                'border_content' => $additional['border_content'] ?? true,
            ]);
        endif; ?>

    </main>

</div>

<?php if ( get_post_meta( $page_id, 'enabled', true ) !== '0' ) : ?>
<?php get_template_part('template-parts/sections/featured-projects'); ?>
<?php endif; ?>
<?php get_template_part('template-parts/sections/cta'); ?>
<?php get_footer(); ?>
