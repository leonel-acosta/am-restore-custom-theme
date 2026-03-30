<?php

/**
 * Template part for displaying posts and pages title.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

?>

<section id="post-<?php the_ID(); ?>" class="entry-header page-section--primary title-section pt-20 lg:pt-40 pb-10">
    <div class="container">
        <?php
        $parent_id = wp_get_post_parent_id( get_the_ID() );
        if ( $parent_id ) : ?>
            <h4 class="entry-parent-title">
                <a href="<?php echo esc_url( get_permalink( $parent_id ) ); ?>">
                    <?php echo esc_html( get_the_title( $parent_id ) ); ?>
                </a>
            </h4>
        <?php endif; ?>

        <h2 class="entry-title"><?php the_title(); ?></h2>

        <?php if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta">
                <?php screenr_posted_on(); ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </div>
</section>