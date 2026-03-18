<?php

/**
 * Template Part: Projects Grid
 *
 * Loops through project posts filtered by category and renders each via
 * template-parts/components/card-project.php.
 *
 * Usage: get_template_part( 'template-parts/projects-grid' );
 *
 * Optional args (via $args):
 *   posts_per_page  int     default -1
 *   order           string  default 'ASC'
 *   orderby         string  default 'menu_order'
 *   category        string  term slug to filter by (default '')
 *   taxonomy        string  taxonomy name         (default 'project-category')
 */

if (! defined('ABSPATH')) exit;

$defaults = [
    'posts_per_page' => -1,
    'order'          => 'ASC',
    'orderby'        => 'year',
    'category'       => '',
    'taxonomy'       => 'category',
];

$args = wp_parse_args($args ?? [], $defaults);

$query_args = [
    'post_type'      => 'post',
    'posts_per_page' => intval($args['posts_per_page']),
    'post_status'    => 'publish',
    'orderby'        => sanitize_text_field($args['orderby']),
    'order'          => sanitize_text_field($args['order']),
];

if (! empty($args['category'])) {
    $query_args['tax_query'] = [[
        'taxonomy' => sanitize_text_field($args['taxonomy']),
        'field'    => 'slug',
        'terms'    => sanitize_text_field($args['category']),
    ]];
}

// Fallback: site logo URL
$logo_id  = get_theme_mod('custom_logo');
$fallback = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium_large') : '';

$projects_query = new WP_Query($query_args);

if ($projects_query->have_posts()) : ?>

    <div class="flex flex-wrap -mx-4">

        <?php while ($projects_query->have_posts()) : $projects_query->the_post();

            // Extract fields from the project_details flexible content layout
            $type = $client = $location = '';
            $sections = get_field('sections');
            if ($sections) {
                foreach ($sections as $section) {
                    if ($section['acf_fc_layout'] === 'project_details') {
                        $type     = $section['type']     ?? '';
                        $client   = $section['client']   ?? '';
                        $location = $section['location'] ?? '';
                        $year = $section['year'] ?? '';
                        break;
                    }
                }
            }

            // Image: featured image → first gallery image → site logo fallback
            $img_src = get_the_post_thumbnail_url(null, 'medium_large');
            if (! $img_src && $sections) {
                foreach ($sections as $section) {
                    if ($section['acf_fc_layout'] === 'project_gallery' && ! empty($section['gallery'])) {
                        $img_src = $section['gallery'][0]['sizes']['medium_large'] ?? $section['gallery'][0]['url'] ?? '';
                        break;
                    }
                }
            }
            if (! $img_src) $img_src = $fallback;
        ?>

            <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-8 flex flex-col">
                <?php get_template_part('template-parts/components/card-project', null, [
                    'img_src'  => $img_src,
                    'img_alt'  => get_the_title(),
                    'type'     => $type,
                    'client'   => $client,
                    'location' => $location,
                    'year' => $year,
                ]); ?>
            </div>

        <?php endwhile;
        wp_reset_postdata(); ?>

    </div>

<?php else : ?>
    <p><?php esc_html_e('No projects found.', 'am-restore'); ?></p>
<?php endif;
