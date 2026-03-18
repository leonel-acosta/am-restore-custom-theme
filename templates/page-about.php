<?php

/**
 * Template Name: About Us Page
 *
 * ACF Field Groups on this page:
 *   - About Us Page (groups: intro_section | continuity_section | methodology_section | results_section | team_section)
 *   - Global - Partners Section (section_title, section_heading, section_content, partners_logos)
 *   - Global - Call To Action (cta_heading, cta_content, button_text, button_link)
 */

get_header();
the_post();

$page_id = get_the_ID();
?>

<div id="content" class="site-content">
    <?php get_template_part('template-parts/page-title'); ?>
    <main id="main" class="site-main" role="main">

        <?php
        $intro = get_field('intro_section', $page_id) ?: [];
        if ($intro) :
            get_template_part('template-parts/text-section', null, [
                'heading'        => $intro['title']       ?? '',
                'text'           => $intro['content']     ?? '',
                'button_text'    => $intro['button_text'] ?? '',
                'button_url'     => $intro['button_url']  ?? '',
                'heading_size'   => 'md',
                'direction'      => 'row',
                'layout'         => 'equal',
                'theme'          => 'light',
                'border_content' => true,
                'padding'        => 'lg',
            ]);
        endif;
        ?>

        <?php
        $continuity = get_field('continuity_section', $page_id) ?: [];
        if ($continuity) :
            get_template_part('template-parts/text-image-section', null, [
                'heading'  => $continuity['title']   ?? '',
                'text'     => $continuity['content'] ?? '',
                'image'    => $continuity['image']   ?? null,
                'reversed' => false,
                'theme'    => 'dark',
                'border'   => true,
            ]);
        endif;
        ?>

        <?php
        $methodology = get_field('methodology_section', $page_id) ?: [];
        if ($methodology) :
            get_template_part('template-parts/text-image-section', null, [
                'heading'  => $methodology['title']   ?? '',
                'text'     => $methodology['content'] ?? '',
                'image'    => $methodology['image']   ?? null,
                'reversed' => true,
                'theme'    => 'dark',
                'border'   => true,
            ]);
        endif;
        ?>

        <?php
        $results = get_field('results_section', $page_id) ?: [];
        if ($results) :
        ?>
            <div class="about-results-section pb-10">
                <?php
                get_template_part('template-parts/text-section', null, [
                    'heading'        => $results['title']       ?? '',
                    'text'           => $results['content']     ?? '',
                    'button_text'    => $results['button_text'] ?? '',
                    'button_url'     => $results['button_link'] ?? '',
                    'heading_size'   => 'lg',
                    'direction'      => 'row',
                    'layout'         => 'equal',
                    'theme'          => 'dark',
                    'padding'        => 'md',
                    'border_content' => true,
                ]);

                $posts = $results['gallery'] ?? [];
                if (! empty($posts)) :
                    get_template_part('template-parts/projects-gallery', null, ['posts' => $posts]);
                endif;
                ?>
            </div>
        <?php endif; ?>

        <?php
        $team = get_field('team_section', $page_id) ?: [];
        ?>
        <div class="about-team-section page-section--light" id="team">
            <?php
            get_template_part('template-parts/text-section', null, [
                'heading'        => $team['title']   ?? '',
                'text'           => $team['content'] ?? '',
                'button_text'    => '',
                'button_url'     => '',
                'heading_size'   => 'lg',
                'direction'      => 'row',
                'layout'         => 'equal',
                'theme'          => 'white',
                'padding'        => 'md',
                'border_content' => true,
            ]);
            ?>
            <div class="container pb-16">
                <?php get_template_part('template-parts/team-grid'); ?>
            </div>
        </div>

    </main>
</div>

<?php
// Partners logos — reads partners_logos + section fields from this page (ID 2873)
get_template_part('template-parts/logo-slider', null, ['post_id' => $page_id]);
?>

<?php get_template_part('template-parts/cta'); ?>

<?php get_footer(); ?>