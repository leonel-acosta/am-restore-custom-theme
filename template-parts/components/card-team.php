<?php
/**
 * Template part for displaying team member cards.
 */

$image     = $args['image']     ?? null;
$role      = $args['role']      ?? '';
$short_bio = $args['short_bio'] ?? '';
$linkedin  = $args['linkedin']  ?? '';
$email     = $args['email']     ?? '';
?>

<div class="am-card team-card">

    <?php if ( $image && is_array( $image ) ) : ?>
        <div class="am-card-image">
            <img
                src="<?php echo esc_url( $image['sizes']['medium_large'] ); ?>"
                alt="<?php echo esc_attr( $image['alt'] ); ?>">
        </div>
    <?php endif; ?>

    <div class="am-card-content">
        <h4 class="am-card-title"><?php the_title(); ?></h4>

        <?php if ( $role ) : ?>
            <p class="am-card-subtitle"><?php echo esc_html( $role ); ?></p>
        <?php endif; ?>

        <?php if ( $short_bio ) : ?>
            <div class="am-card-meta team-bio">
                <?php echo apply_filters( 'the_content', $short_bio ); ?>
            </div>
        <?php endif; ?>

        <div class="am-card-footer team-links">
            <?php if ( $linkedin ) : ?>
                <a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                    <span class="dashicons dashicons-linkedin"></span>
                </a>
            <?php endif; ?>

            <?php if ( $email ) : ?>
                <a href="mailto:<?php echo antispambot( $email ); ?>" aria-label="Email">
                    <span class="dashicons dashicons-email-alt"></span>
                </a>
            <?php endif; ?>
        </div>
    </div>

</div>
