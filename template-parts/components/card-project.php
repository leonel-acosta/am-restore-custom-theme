<?php
/**
 * Template part for displaying project cards.
 */

$img_src  = $args['img_src']  ?? '';
$img_alt  = $args['img_alt']  ?? get_the_title();
$img_w    = $args['img_w']    ?? 0;
$img_h    = $args['img_h']    ?? 0;
$type     = $args['type']     ?? '';
$client   = $args['client']   ?? '';
$location = $args['location'] ?? '';
$year     = $args['year']     ?? '';
?>

<a href="<?php the_permalink(); ?>" class="am-card project-card">

    <div class="am-card-image">
        <?php if ( $img_src ) : ?>
            <img
                src="<?php echo esc_url( $img_src ); ?>"
                alt="<?php echo esc_attr( $img_alt ); ?>"
                loading="lazy"
                decoding="async"
                <?php if ( $img_w && $img_h ) : ?>
                width="<?php echo (int) $img_w; ?>"
                height="<?php echo (int) $img_h; ?>"
                <?php endif; ?>>
        <?php else : ?>
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                <span style="color:#595959;font-size:0.875rem;"><?php esc_html_e( 'No image', 'am-restore' ); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="am-card-content">
        <h4 class="am-card-title"><?php the_title(); ?></h4>

        <?php if ( $type ) : ?>
            <p class="am-card-subtitle"><?php echo esc_html( $type ); ?></p>
        <?php endif; ?>

        <?php if ( $client ) : ?>
            <p class="am-card-meta"><?php echo esc_html( $client ); ?></p>
        <?php endif; ?>

        <?php if ( $location || $year ) : ?>
            <p class="am-card-meta am-card-footer">
                <?php echo esc_html( implode( ' — ', array_filter( [ $location, $year ] ) ) ); ?>
            </p>
        <?php endif; ?>
    </div>

</a>
