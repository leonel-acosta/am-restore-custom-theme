<?php

/**
 * Template Name: Projects - All Categories
 *
 * Each section is managed via ACF flexible content field "category_sections".
 * ACF field group: "Projects - All Categories Page"
 */

get_header();
the_post();

$page_id  = get_the_ID();
$sections = get_field('category_sections', $page_id);
?>

<div id="content" class="site-content">

    <?php get_template_part('template-parts/sections/page-title'); ?>

    <main id="main" class="site-main" role="main">
        <?php if ($sections) :
            // ── Flexible content sections (manually configured) ──────────────
            foreach ($sections as $section) :
                $cat_term  = $section['category']         ?? null;
                $cat_slug  = ($cat_term instanceof WP_Term) ? $cat_term->slug : '';
                $title     = $section['section_title']    ?? '';
                $subtitle  = $section['section_subtitle'] ?? '';
                $more_link = $section['more_link']        ?? '';
                $theme     = $section['theme']            ?? 'light';
                $limit     = max(1, intval($section['posts_limit'] ?? 9));

                get_template_part('template-parts/sections/projects-category', null, [
                    'category_slug'      => $cat_slug,
                    'category_label'     => $title,
                    'subtitle'           => $subtitle,
                    'more_link_override' => $more_link,
                    'limit'              => $limit,
                    'theme'              => $theme,
                    'show_heading'       => true,
                ]);
            endforeach;

        else :
            // ── Fallback: auto-render every non-empty category ───────────────
            $project_cats = get_terms([
                'taxonomy'   => 'category',
                'hide_empty' => true,
                'exclude'    => [ get_option('default_category') ],
                'orderby'    => 'name',
                'order'      => 'ASC',
            ]);

            if (! is_wp_error($project_cats) && ! empty($project_cats)) :
                $themes = ['light', 'white', 'dark'];
                $i = 0;
                foreach ($project_cats as $cat) :
                    get_template_part('template-parts/sections/projects-category', null, [
                        'category_slug'  => $cat->slug,
                        'category_label' => $cat->name,
                        'limit'          => 9,
                        'theme'          => $themes[ $i % 3 ],
                        'show_heading'   => true,
                    ]);
                    $i++;
                endforeach;
            endif;
        endif; ?>
    </main>

</div>

<?php get_template_part('template-parts/sections/cta'); ?>
<?php get_footer(); ?>
