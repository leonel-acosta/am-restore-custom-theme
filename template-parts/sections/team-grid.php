<?php
/**
 * Template Part: Team Grid
 *
 * Loops through team-member posts and renders each via
 * template-parts/components/card-team.php.
 *
 * Usage: get_template_part( 'template-parts/sections/team-grid' );
 *
 * Optional args (via $args):
 *   posts_per_page  int     default -1
 *   order           string  default 'ASC'
 *   orderby         string  default 'menu_order'
 *   columns         int     default 2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$defaults = [
    'posts_per_page' => -1,
    'order'          => 'ASC',
    'orderby'        => 'menu_order',
    'columns'        => 2,
];

$args    = wp_parse_args( $args ?? [], $defaults );
$columns = max( 1, intval( $args['columns'] ) );

$team_query = new WP_Query( [
    'post_type'      => 'team-member',
    'posts_per_page' => intval( $args['posts_per_page'] ),
    'post_status'    => 'publish',
    'orderby'        => sanitize_text_field( $args['orderby'] ),
    'order'          => sanitize_text_field( $args['order'] ),
] );

if ( $team_query->have_posts() ) : ?>

    <div class="flex flex-wrap -mx-4">

        <?php while ( $team_query->have_posts() ) : $team_query->the_post(); ?>

            <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-8 flex flex-col">
                <?php get_template_part( 'template-parts/components/card-team', null, [
                    'image'     => get_field( 'image' ),
                    'role'      => get_field( 'role' ),
                    'short_bio' => get_field( 'short_bio' ),
                    'linkedin'  => get_field( 'linkedin' ),
                    'email'     => get_field( 'email' ),
                ] ); ?>
            </div>

        <?php endwhile;
        wp_reset_postdata(); ?>

    </div>

<?php else : ?>
    <p><?php esc_html_e( 'No team members found.', 'am-restore' ); ?></p>
<?php endif;