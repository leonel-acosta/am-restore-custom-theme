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

<div class="team-card flex-1 flex flex-col border-b-[3px] bg-secondary border-primary transition-all duration-200 hover:brightness-95 hover:shadow-md">

    <?php if ($image && is_array($image)) : ?>
        <div class="team-image-wrapper w-full aspect-square overflow-hidden">
            <img
                src="<?php echo esc_url($image['sizes']['medium_large']); ?>"
                alt="<?php echo esc_attr($image['alt']); ?>"
                class="team-image w-full h-full object-cover object-top">
        </div>
    <?php endif; ?>

    <div class="team-content flex flex-col flex-1 text-left content-end align-bottom p-4">
        <h4 class="team-name text-lg font-semibold mb-2 text-left"><?php the_title(); ?></h4>

        <?php if ($role) : ?>
            <p class="team-role text-sm text-secondary font-medium mb-2"><?php echo esc_html($role); ?></p>
        <?php endif; ?>

        <?php if ($short_bio) : ?>
            <div class="team-bio text-sm text-secondary mb-2">
                <?php echo apply_filters('the_content', $short_bio); ?>
            </div>
        <?php endif; ?>

        <div class="team-links flex gap-3 mt-auto">
            <?php if ($linkedin) : ?>
                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="text-secondary hover:text-primary transition-colors">
                    <span class="dashicons dashicons-linkedin"></span>
                </a>
            <?php endif; ?>

            <?php if ($email) : ?>
                <a href="mailto:<?php echo antispambot($email); ?>" aria-label="Email" class="text-secondary hover:text-primary transition-colors">
                    <span class="dashicons dashicons-email-alt"></span>
                </a>
            <?php endif; ?>
        </div>
    </div>

</div>