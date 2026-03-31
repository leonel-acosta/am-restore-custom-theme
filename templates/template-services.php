<?php

/**
 * Template Name: Services Page
 *
 * Renders an optional page intro and a list of service cards via ACF repeater.
 *
 * ACF field group: "Services Page" (group_69b7cdc8a4082)
 */

get_header();
the_post();

$page_id  = get_the_ID();
$intro    = get_field('intro_section',    $page_id) ?: [];
$items    = get_field('service_items',    $page_id);
$about_us = get_field('about_us_section', $page_id) ?: [];
?>

<div id="content" class="site-content">

    <?php get_template_part('template-parts/sections/page-title'); ?>

    <main id="main" class="site-main services-page" role="main">

        <?php if (! empty($intro['title']) || ! empty($intro['content'])) :
            get_template_part('template-parts/sections/text', null, [
                'heading'     => $intro['title']       ?? '',
                'text'        => $intro['content']     ?? '',
                'button_text' => $intro['button_text'] ?? '',
                'button_url'  => $intro['button_url']  ?? '',
                'theme'       => $intro['theme']       ?? 'light',
                'border_content' => true,
            ]);
        endif; ?>

        <?php if ($items) :
            foreach ($items as $item) :
                get_template_part('template-parts/components/card-service', null, [
                    'heading'     => $item['heading']      ?? '',
                    'text'        => $item['text']         ?? '',
                    'image'       => $item['image']        ?? null,
                    'button_text' => $item['button_text']  ?? '',
                    'button_url'  => $item['button_url']   ?? '',
                    'reversed'    => ! empty($item['image_position']) && $item['image_position'] === 'left',
                    'theme'       => $item['theme']        ?? 'light',
                    'padding'     => $item['padding']      ?? 'sm',
                ]);
            endforeach;
        endif; ?>

        <?php if (! empty($about_us['title']) || ! empty($about_us['content'])) :
            get_template_part('template-parts/sections/text', null, [
                'heading'     => $about_us['title']       ?? '',
                'text'        => $about_us['content']     ?? '',
                'button_text' => $about_us['button_text'] ?? '',
                'button_url'  => $about_us['button_url']  ?? '',
                'theme'       => $about_us['theme']       ?? 'light',
                'border_content' => true,
            ]);
        endif; ?>
    </main>

</div>

<?php get_template_part('template-parts/sections/featured-projects'); ?>
<?php get_template_part('template-parts/sections/cta'); ?>
<?php get_footer(); ?>