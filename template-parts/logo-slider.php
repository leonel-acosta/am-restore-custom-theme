<?php
/**
 * Template Part: Logo Slider
 *
 * Auto-advances one item at a time.
 *
 * Optional args:
 *   post_id   int    post to read partners_logos from (default: current page)
 *   interval  int    ms between slides (default: 2000)
 */

$post_id   = $args['post_id']  ?? get_the_ID();
$interval  = intval( $args['interval'] ?? 2000 );
$slider_id = 'logo-slider-' . uniqid();

$logos = get_field( 'partners_logos', $post_id );

if ( empty( $logos ) ) {
    $logos = get_field( 'partners_logos', 2 );
}

// Fallback: repeat site logo for layout testing
if ( empty( $logos ) ) {
    $logo_id  = get_theme_mod( 'custom_logo' );
    $logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';
    if ( $logo_url ) {
        $logos = array_fill( 0, 8, [
            'url'   => $logo_url,
            'alt'   => get_bloginfo( 'name' ),
            'sizes' => [ 'medium' => $logo_url ],
        ] );
    } else {
        return;
    }
}
?>

<div id="<?php echo esc_attr( $slider_id ); ?>" class="logo-slider">
    <div class="logo-track">
        <?php foreach ( $logos as $logo ) :
            $src = $logo['sizes']['medium'] ?? $logo['url'];
            $alt = $logo['alt'] ?: $logo['title'] ?: '';
        ?>
            <div class="logo-item">
                <img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>">
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
(function () {
    var slider   = document.getElementById('<?php echo esc_js( $slider_id ); ?>');
    var track    = slider.querySelector('.logo-track');
    var items    = track.querySelectorAll('.logo-item');
    var total    = items.length;
    var current  = 0;
    var paused   = false;
    var interval = <?php echo intval( $interval ); ?>;

    track.style.transition = 'transform 0.5s ease';

    function getVisible() {
        var w = window.innerWidth;
        if (w >= 1024) return 6;
        if (w >= 640)  return 3;
        return 2;
    }

    function slide() {
        if (paused) return;

        var visible  = getVisible();
        var maxIndex = total - visible;

        current++;

        if (current > maxIndex) {
            // Snap back without transition, then re-enable
            track.style.transition = 'none';
            current = 0;
            track.style.transform = 'translateX(0)';
            // Force reflow before re-enabling transition
            track.offsetHeight;
            track.style.transition = 'transform 0.5s ease';
            return;
        }

        var itemWidth = items[0].offsetWidth;
        track.style.transform = 'translateX(-' + (current * itemWidth) + 'px)';
    }

    slider.addEventListener('mouseenter', function () { paused = true; });
    slider.addEventListener('mouseleave', function () { paused = false; });

    setInterval(slide, interval);
})();
</script>
