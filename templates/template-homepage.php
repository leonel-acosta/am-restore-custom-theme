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
                    'align'      => get_sub_field('align')     ?: 'left',
                    'padding'    => get_sub_field('padding')   ?: 'md',
                ]);
            ?>

            <?php /* ── 3. Planning (Text + Image) ───────────────────────── */ ?>
            <?php elseif ($layout === 'planning') :
                get_template_part('template-parts/text-image-section', null, [
                    'heading'        => get_sub_field('section_title'),
                    'text'           => get_sub_field('section_content'),
                    'button_text'    => get_sub_field('button_text'),
                    'button_url'     => get_sub_field('button_link'),
                    'image'          => get_sub_field('image'),
                    'reversed'       => get_sub_field('image_position') === 'image_left',
                    'theme'          => get_sub_field('theme')         ?: 'dark',
                    'column_layout'  => get_sub_field('column_layout') ?: 'equal',
                    'align'          => get_sub_field('align')         ?: 'left',
                    'padding'        => get_sub_field('padding')       ?: 'md',
                    'border_content' => get_sub_field('border_content'),
                ]);
            ?>

            <?php /* ── 4. Execution (Text + Image) ──────────────────────── */ ?>
            <?php elseif ($layout === 'execution') :
                get_template_part('template-parts/text-image-section', null, [
                    'heading'        => get_sub_field('section_title'),
                    'text'           => get_sub_field('section_content'),
                    'button_text'    => get_sub_field('button_text'),
                    'button_url'     => get_sub_field('button_link'),
                    'image'          => get_sub_field('image'),
                    'reversed'       => get_sub_field('image_position') === 'image_left',
                    'theme'          => get_sub_field('theme')         ?: 'dark',
                    'column_layout'  => get_sub_field('column_layout') ?: 'equal',
                    'align'          => get_sub_field('align')         ?: 'left',
                    'padding'        => get_sub_field('padding')       ?: 'md',
                    'border_content' => get_sub_field('border_content'),
                ]);
            ?>

            <?php /* ── 5. In Numbers (multi-column) ─────────────────────── */ ?>
            <?php elseif ($layout === 'numbers') :
                get_template_part('template-parts/multicolumn', null, [
                    'section_heading'    => get_sub_field('section_heading'),
                    'section_subheading' => get_sub_field('section_subheading'),
                    'section_theme'      => get_sub_field('section_theme')   ?: 'dark',
                    'heading_weight'     => get_sub_field('heading_weight')  ?: 'normal',
                    'columns_align'      => get_sub_field('columns_align')   ?: 'left',
                    'animate'            => (bool) get_sub_field('animate'),
                    'columns'            => get_sub_field('columns') ?: [],
                ]);
            ?>

            <?php /* ── 6. Featured Projects / Gallery ──────────────────── */ ?>
            <?php elseif ($layout === 'projects_section') :
                $ps_display_mode = get_sub_field('display_mode') ?: 'featured';
                $ps_theme        = get_sub_field('theme') ?: 'dark';
                $ps_title        = get_sub_field('section_title');
                $ps_content      = get_sub_field('section_content');
                $ps_link         = get_sub_field('all_projects_link');

                if ($ps_display_mode === 'gallery') :
                    $ps_query = new WP_Query([
                        'post_type'           => 'post',
                        'posts_per_page'      => -1,
                        'post_status'         => 'publish',
                        'orderby'             => 'date',
                        'order'               => 'DESC',
                        'ignore_sticky_posts' => true,
                    ]);
                    ?>
                    <section class="featured-projects featured-projects--<?php echo esc_attr($ps_theme); ?> featured-projects--gallery-mode">

                        <?php if ($ps_title || $ps_content || $ps_link) : ?>
                        <div class="container">
                            <div class="featured-projects__header">
                                <div class="featured-projects__header-text">
                                    <?php if ($ps_title) : ?>
                                        <h2 class="featured-projects__title text-section__heading text-section__heading--md">
                                            <?php echo esc_html($ps_title); ?>
                                        </h2>
                                    <?php endif; ?>
                                    <?php if ($ps_content) : ?>
                                        <div class="featured-projects__subtitle">
                                            <?php echo wp_kses_post($ps_content); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($ps_link) : ?>
                                    <a href="<?php echo esc_url($ps_link); ?>" class="featured-projects__all-link">
                                        <?php esc_html_e('More Projects', 'am-restore'); ?>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                            <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php get_template_part('template-parts/projects-gallery', null, [
                            'posts' => $ps_query->posts,
                        ]); ?>

                    </section>
                    <?php
                    wp_reset_postdata();
                else :
                    get_template_part('template-parts/featured-projects', null, [
                        'title'    => $ps_title,
                        'content'  => $ps_content,
                        'link'     => $ps_link,
                        'theme'    => $ps_theme,
                        'autoplay' => get_sub_field('autoplay'),
                    ]);
                endif;
            ?>

            <?php /* ── 7. Video Section (title + video below) ─────────────── */ ?>
            <?php elseif ($layout === 'video_section') :
                get_template_part('template-parts/text-media-section', null, [
                    'heading'        => get_sub_field('section_title'),
                    'text'           => get_sub_field('section_content'),
                    'media_type'     => 'embed',
                    'embed'          => get_sub_field('video'),
                    'embed_type'     => 'video',
                    'theme'          => get_sub_field('theme')         ?: 'dark',
                    'column_layout'  => get_sub_field('column_layout') ?: 'equal',
                    'align'          => get_sub_field('align')         ?: 'left',
                    'padding'        => get_sub_field('padding')       ?: 'md',
                    'border_content' => get_sub_field('border_content'),
                    'class'          => 'hp-video-section',
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
                    'theme'           => get_sub_field('theme')      ?: 'dark',
                    'align'           => get_sub_field('align')      ?: 'center',
                    'padding'         => get_sub_field('padding')    ?: 'md',
                    'color_mode'      => get_sub_field('color_mode') ?: 'bw',
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
                    'heading'        => get_sub_field('section_heading'),
                    'text'           => get_sub_field('section_content'),
                    'button_text'    => get_sub_field('button_text'),
                    'button_url'     => get_sub_field('button_link'),
                    'media_type'     => 'embed',
                    'embed'          => get_sub_field('form_embed'),
                    'reversed'       => false,
                    'theme'          => get_sub_field('theme')         ?: 'dark',
                    'column_layout'  => get_sub_field('column_layout') ?: 'equal',
                    'align'          => get_sub_field('align')         ?: 'left',
                    'padding'        => get_sub_field('padding')       ?: 'md',
                    'border_content' => get_sub_field('border_content'),
                    'class'          => 'hp-contact-section',
                ]);
            ?>

            <?php endif; ?>

        <?php endwhile; ?>
        <?php endif; ?>

    </main>
</div>

<?php get_footer(); ?>
