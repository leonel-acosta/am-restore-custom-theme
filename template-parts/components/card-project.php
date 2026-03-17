<?php

/**
 * Template part for displaying project cards.
 */

$img_src  = $args['img_src']  ?? '';
$img_alt  = $args['img_alt']  ?? get_the_title();
$type     = $args['type']     ?? '';
$client   = $args['client']   ?? '';
$location = $args['location'] ?? '';
$year = $args['year'] ?? '';
?>

<a href="<?php the_permalink(); ?>" class="project-card flex-1 flex flex-col border-b-[3px] page-section--gray border-primary transition-all duration-200 hover:brightness-95 hover:shadow-md no-underline">

    <div class="project-card w-full aspect-square overflow-hidden">
        <?php if ($img_src) : ?>
            <img
                src="<?php echo esc_url($img_src); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                class="project-image w-full h-full object-cover object-center">
        <?php else : ?>
            <div class="w-full h-full flex items-center justify-center">
                <span class="text-light text-sm"><?php esc_html_e('No image', 'am-restore'); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="project-content flex flex-col flex-1 text-left p-5">
        <h4 class="project-title text-lg font-semibold mb-2 uppercase"><?php the_title(); ?></h4>

        <!-- <?php if ($type) : ?>
            <p class="project-type text-sm font-medium mb-1"><?php echo esc_html($type); ?></p>
        <?php endif; ?> -->

        <?php if ($client) ?>
        <div class="flex flex-row justify-start">
            <?php if ($location) : ?>
                <h5 class="project-location text-sm  mt-auto"><?php echo esc_html($location); ?> -</h5>
            <?php endif; ?>

            <?php if ($client) : ?>
                <h5 class="project-year text-sm  mb-1 mr-1"><?php echo esc_html($year); ?></h5>
            <?php endif; ?>
        </div>
    </div>

</a>