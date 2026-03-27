<?php

/**
 * Template Name: Home Page
 *
 * Sections are managed via the "Home Page" ACF flexible_content field (group_69b7d300000001).
 *
 * Layout order:
 *   1. hero_slider      — MetaSlider shortcode
 *   2. text_section     — Reusable text section (intro, about us, etc.)
 *   3. planning         — Text + Image Left
 *   4. execution        — Text + Image Right
 *   5. numbers          — In Numbers (placeholder, TBD)
 *   6. projects_section — Featured Projects
 *   7. video_section    — Text + Video (text-media-section)
 *   8. our_team         — Service-card style (bg on container, flush image)
 *   9. partners         — Clients / Logo Slider
 *  10. contact_cta      — Text left + Contact Form right
 */

get_header();
the_post();

$page_id = get_the_ID();
?>

<div id="content" class="site-content homepage">
    <main id="main" class="site-main" role="main">

        <?php if (have_rows('sections', $page_id)) :
            while (have_rows('sections', $page_id)) : the_row();
                $layout = get_row_layout();
        ?>

            <?php /* ── 1. Hero Slider ─────────────────────────────────────── */ ?>
            <?php if ($layout === 'hero_slider') :
                get_template_part('template-parts/hero-slider', null, [
                    'slides' => get_sub_field('slides') ?: [],
                ]);
            ?>

            <?php /* ── 2/5. Text Section (intro, about us) ────────────────── */ ?>
            <?php elseif ($layout === 'text_section') :
                get_template_part('template-parts/text-section', null, [
                    'heading'    => get_sub_field('section_heading'),
                    'text'       => get_sub_field('section_content'),
                    'button_text'=> get_sub_field('button_text'),
                    'button_url' => get_sub_field('button_url'),
                    'theme'      => get_sub_field('theme')     ?: 'dark',
                    'direction'  => get_sub_field('direction') ?: 'row',
                ]);
            ?>

            <?php /* ── 3. Planning (Text + Image) ───────────────────────── */ ?>
            <?php elseif ($layout === 'planning') :
                get_template_part('template-parts/text-image-section', null, [
                    'heading'       => get_sub_field('section_title'),
                    'text'          => get_sub_field('section_content'),
                    'button_text'   => get_sub_field('button_text'),
                    'button_url'    => get_sub_field('button_link'),
                    'image'         => get_sub_field('image'),
                    'reversed'      => get_sub_field('image_position') === 'image_left',
                    'theme'         => get_sub_field('theme')         ?: 'dark',
                    'column_layout' => get_sub_field('column_layout') ?: 'equal',
                ]);
            ?>

            <?php /* ── 4. Execution (Text + Image) ──────────────────────── */ ?>
            <?php elseif ($layout === 'execution') :
                get_template_part('template-parts/text-image-section', null, [
                    'heading'       => get_sub_field('section_title'),
                    'text'          => get_sub_field('section_content'),
                    'button_text'   => get_sub_field('button_text'),
                    'button_url'    => get_sub_field('button_link'),
                    'image'         => get_sub_field('image'),
                    'reversed'      => get_sub_field('image_position') === 'image_left',
                    'theme'         => get_sub_field('theme')         ?: 'dark',
                    'column_layout' => get_sub_field('column_layout') ?: 'equal',
                ]);
            ?>

            <?php /* ── 5. In Numbers (placeholder) ──────────────────────── */ ?>
            <?php elseif ($layout === 'numbers') : ?>
                <section class="hp-numbers-section">
                    <!-- Numbers section: to be built -->
                </section>

            <?php /* ── 6. Featured Projects / Gallery ──────────────────── */ ?>
            <?php elseif ($layout === 'projects_section') :
                $ps_display_mode = get_sub_field('display_mode') ?: 'featured';
                if ($ps_display_mode === 'gallery') :
                    $ps_query = new WP_Query([
                        'post_type'           => 'post',
                        'posts_per_page'      => -1,
                        'post_status'         => 'publish',
                        'orderby'             => 'date',
                        'order'               => 'DESC',
                        'ignore_sticky_posts' => true,
                    ]);
                    get_template_part('template-parts/projects-gallery', null, [
                        'posts' => $ps_query->posts,
                    ]);
                    wp_reset_postdata();
                else :
                    get_template_part('template-parts/featured-projects', null, [
                        'title'   => get_sub_field('section_title'),
                        'content' => get_sub_field('section_content'),
                        'link'    => get_sub_field('all_projects_link'),
                        'theme'   => get_sub_field('theme') ?: 'dark',
                    ]);
                endif;
            ?>

            <?php /* ── 7. Video Section (title + video below) ─────────────── */ ?>
            <?php elseif ($layout === 'video_section') :
                get_template_part('template-parts/text-media-section', null, [
                    'heading'       => get_sub_field('section_title'),
                    'text'          => get_sub_field('section_content'),
                    'media_type'    => 'embed',
                    'embed'         => get_sub_field('video'),
                    'theme'         => get_sub_field('theme')         ?: 'dark',
                    'column_layout' => get_sub_field('column_layout') ?: 'equal',
                    'class'         => 'hp-video-section',
                ]);
            ?>

            <?php /* ── 8. Our Team (service-card style) ───────────────────── */ ?>
            <?php elseif ($layout === 'our_team') :
                get_template_part('template-parts/components/service-card', null, [
                    'heading'     => get_sub_field('section_heading'),
                    'text'        => get_sub_field('section_content'),
                    'button_text' => get_sub_field('button_text'),
                    'button_url'  => get_sub_field('button_link'),
                    'image'       => get_sub_field('image'),
                    'reversed'    => get_sub_field('image_position') === 'image_left',
                    'theme'       => get_sub_field('theme') ?: 'dark',
                ]);
            ?>

            <?php /* ── 9. Partners / Clients (logo slider) ───────────────── */ ?>
            <?php elseif ($layout === 'partners') :
                get_template_part('template-parts/logo-slider', null, [
                    'logos'           => get_sub_field('partners_logos'),
                    'section_title'   => get_sub_field('section_title'),
                    'section_heading' => get_sub_field('section_heading'),
                    'section_content' => get_sub_field('section_content'),
                ]);
            ?>

            <?php /* ── CTA (simple, reusable anywhere) ───────────────────── */ ?>
            <?php elseif ($layout === 'cta') :
                get_template_part('template-parts/cta', null, [
                    'cta_heading' => get_sub_field('cta_heading'),
                    'cta_content' => get_sub_field('cta_content'),
                    'button_text' => get_sub_field('button_text'),
                    'button_link' => get_sub_field('button_link'),
                ]);
            ?>

            <?php /* ── 10. Contact CTA (text left + form right) ──────────── */ ?>
            <?php elseif ($layout === 'contact_cta') :
                get_template_part('template-parts/text-media-section', null, [
                    'heading'       => get_sub_field('section_heading'),
                    'text'          => get_sub_field('section_content'),
                    'media_type'    => 'embed',
                    'embed'         => get_sub_field('form_embed'),
                    'reversed'      => false,
                    'theme'         => get_sub_field('theme')         ?: 'dark',
                    'column_layout' => get_sub_field('column_layout') ?: 'equal',
                    'class'         => 'hp-contact-section',
                ]);
            ?>

            <?php endif; ?>

        <?php endwhile; ?>
        <?php endif; ?>

    </main>
</div>

<?php get_footer(); ?>
