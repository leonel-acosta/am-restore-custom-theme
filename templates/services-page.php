<?php

/**
 * Template Name: Services Page
 *
 * Renders a list of service cards via ACF repeater "service_items".
 * Each item outputs a service-card component.
 *
 * ACF field group: "Services Page"
 */

get_header();
the_post();

$page_id = get_the_ID();
$items   = get_field('service_items', $page_id);
?>

<div id="content" class="site-content">

    <?php get_template_part('template-parts/page-title'); ?>

    <main id="main" class="site-main services-page" role="main">
        <?php if ($items) :
            foreach ($items as $item) :
                get_template_part('template-parts/components/service-card', null, [
                    'heading'     => $item['heading']      ?? '',
                    'text'        => $item['text']         ?? '',
                    'image'       => $item['image']        ?? null,
                    'button_text' => $item['button_text']  ?? '',
                    'button_url'  => $item['button_url']   ?? '',
                    'reversed'    => ! empty($item['image_position']) && $item['image_position'] === 'left',
                    'theme'       => $item['theme']        ?? 'light',
                ]);
            endforeach;
        endif; ?>
    </main>

</div>

<?php get_template_part('template-parts/cta'); ?>
<?php get_footer(); ?>
