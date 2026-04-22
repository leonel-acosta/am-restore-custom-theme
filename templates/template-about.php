<?php

/**
 * Template Name: About Us Page
 *
 * ACF Field Groups on this page:
 *   - Page: About (group_69b448e0c57e3)
 *       intro_section | continuity_section | methodology_section | results_section | team_section
 *   - Page: About - Partners (group_69b43227e20a2)
 *       section_title, section_heading, section_content, partners_logos, theme, align, padding, color_mode
 *   - Global: Call To Action (group_69b4475ecc19d)
 *       heading, content, button_text, button_url
 */

get_header();
the_post();

$page_id = get_the_ID();
?>

<div id="content" class="site-content">
    <?php get_template_part('template-parts/sections/page-title'); ?>
    <main id="main" class="site-main" role="main">

        <?php
        $intro = get_field('intro_section', $page_id) ?: [];
        if ($intro) :
            get_template_part('template-parts/sections/text', null, [
                'heading'        => $intro['heading']        ?? '',
                'text'           => $intro['content']        ?? '',
                'button_text'    => $intro['button_text']    ?? '',
                'button_url'     => $intro['button_url']     ?? '',
                'heading_size'   => 'md',
                'direction'      => $intro['direction']      ?? 'row',
                'layout'         => 'equal',
                'align'          => $intro['align']          ?? 'left',
                'theme'          => $intro['theme']          ?? 'light',
                'padding'        => $intro['padding']        ?? 'lg',
                'border'         => $intro['border']         ?? false,
                'border_content' => $intro['border_content'] ?? true,
            ]);
        endif;
        ?>

        <?php
        $continuity = get_field('continuity_section', $page_id) ?: [];
        if ($continuity) :
            get_template_part('template-parts/sections/text-image', null, [
                'heading'  => $continuity['heading']                        ?? '',
                'text'     => $continuity['content']                        ?? '',
                'image'    => $continuity['image']                          ?? null,
                'reversed' => ($continuity['image_position'] ?? 'right') === 'left',
                'theme'    => $continuity['theme']                          ?? 'dark',
                'padding'  => $continuity['padding']                        ?? 'md',
                'border'   => $continuity['border']                         ?? true,
            ]);
        endif;
        ?>

        <?php
        $methodology = get_field('methodology_section', $page_id) ?: [];
        if ($methodology) :
            get_template_part('template-parts/sections/text-image', null, [
                'heading'  => $methodology['heading']                         ?? '',
                'text'     => $methodology['content']                         ?? '',
                'image'    => $methodology['image']                           ?? null,
                'reversed' => ($methodology['image_position'] ?? 'left') === 'left',
                'theme'    => $methodology['theme']                           ?? 'dark',
                'padding'  => $methodology['padding']                         ?? 'md',
                'border'   => $methodology['border']                          ?? true,
            ]);
        endif;
        ?>

        <?php
        $results = get_field('results_section', $page_id) ?: [];
        if ($results) :
        ?>
            <div class="page-section--darker">
                <?php
                get_template_part('template-parts/sections/text', null, [
                    'heading'        => $results['heading']        ?? '',
                    'text'           => $results['content']        ?? '',
                    'button_text'    => $results['button_text']    ?? '',
                    'button_url'     => $results['button_url']     ?? '',
                    'heading_size'   => 'lg',
                    'direction'      => 'row',
                    'layout'         => 'equal',
                    'theme'          => $results['theme']          ?? 'dark',
                    'padding'        => $results['padding']        ?? 'md',
                    'border'         => $results['border']         ?? false,
                    'border_content' => $results['border_content'] ?? true,
                ]);

                $posts = $results['gallery'] ?? [];
                if (! empty($posts)) :
                    get_template_part('template-parts/sections/projects-gallery', null, ['posts' => $posts]);
                endif;
                ?>
            </div>
        <?php endif; ?>

        <?php
        $team = get_field('team_section', $page_id) ?: [];
        get_template_part('template-parts/sections/team-section', null, [
            'heading'        => $team['heading']        ?? '',
            'text'           => $team['content']        ?? '',
            'button_text'    => $team['button_text']    ?? '',
            'button_url'     => $team['button_url']     ?? '',
            'theme'          => $team['theme']          ?? 'light',
            'direction'      => $team['direction']      ?? 'row',
            'align'          => $team['align']          ?? 'left',
            'padding'        => $team['padding']        ?? 'md',
            'border_content' => $team['border_content'] ?? false,
        ]);
        ?>

    </main>
</div>
<?php
        get_template_part('template-parts/sections/logo-slider', null, [
            'logos'           => get_field('partners_logos',  $page_id),
            'section_title'   => get_field('section_title',   $page_id),
            'section_heading' => get_field('section_heading', $page_id),
            'section_content' => get_field('section_content', $page_id),
            'theme'           => get_field('theme',           $page_id) ?: 'white',
            'align'           => get_field('align',           $page_id) ?: 'center',
            'padding'         => get_field('padding',         $page_id) ?: 'md',
            'color_mode'      => get_field('color_mode',      $page_id) ?: 'bw',
        ]);
        ?>
<?php get_template_part('template-parts/sections/cta'); ?>

<?php get_footer(); ?>
