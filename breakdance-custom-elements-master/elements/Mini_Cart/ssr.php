<?php

/**
 * @var array $propertiesData
 */

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('wc-cart-fragments');
});

$hidden = apply_filters('woocommerce_widget_cart_is_hidden', false);

if (WC()->cart === null) {
    echo '<!-- Cart is not available -->';
} else if ($hidden) {
    echo '<!-- Cart is hidden -->';
} else {
    $content = $propertiesData['content']['content'] ?? [];
    $design = $propertiesData['design'] ?? [];

    $primaryButton = $content['cart']['primary_button'] ?? 'cart';

    // Continue Link
    $continueLinkType = $content['cart']['continue_shopping_link'] ?? null;
    $continueEnabled = $continueLinkType && $continueLinkType !== 'disabled';

    $continueLinks = [
        'homepage' => get_home_url(),
        'shop' => get_permalink(wc_get_page_id('shop')),
        'custom' => $content['cart']['url'] ?? '',
    ];

    $continueShoppingLink = $continueLinks[$continueLinkType] ?? null;
    $continueShoppingText = __('Continue shopping', 'woocommerce');

    $cartText = __('Cart', 'woocommerce');

    $headerBlockId = $content['after_title_bar'] ?? null;
    $footerBlockId = $content['after_footer'] ?? null;

    $subtotal = WC()->cart->get_cart_subtotal();
    $count = WC()->cart->get_cart_contents_count();

    // Link attributes
    $attributes = $content['link']['attributes'] ?? [];

    $formattedAttributes = implode(
        ' ',
        array_map(
            fn ($attr) => sprintf('%s="%s"', $attr['name'], htmlspecialchars($attr['value'])),
            $attributes
        )
    );

    // Open cart on add setting
    $openOnAdd = isset($content['cart']['open_cart_on_add']) && $content['cart']['open_cart_on_add'] ? 'true' : 'false';
?>

<div class="exp-cart-wrapper" data-open-on-add="<?php echo $openOnAdd; ?>">
  <!-- Mini Cart Toggle Button (stays visible, acts as hamburger) -->
  <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="bde-mini-cart-toggle" aria-label="<?php esc_attr_e( 'View cart', 'woocommerce' ); ?>" aria-expanded="false" <?php echo $formattedAttributes; ?>>
    <span class="bde-mini-cart-toggle__icon"></span>

    <?php if (!isset($content['link']['hide_subtotal']) || !$content['link']['hide_subtotal']) : ?>
    <span class="bde-mini-cart-toggle__subtotal" data-count="<?php echo $count; ?>">
      <?php echo $subtotal; ?>
    </span>
    <?php endif; ?>

    <?php if (!isset($content['link']['hide_count']) || !$content['link']['hide_count']) : ?>
    <span class="bde-mini-cart-toggle__counter" data-count="<?php echo $count; ?>">
      <?php echo $count; ?>
    </span>
    <?php endif; ?>
  </a>

  <!-- Expanding Cart Container -->
  <div class="exp-cart-container bde-mini-cart-offcanvas-body"
       data-max-width="<?php echo esc_attr($design['animation']['max_width'] ?? '450px'); ?>"
       data-max-height="<?php echo esc_attr($design['animation']['max_height'] ?? '60vh'); ?>"
       data-initial-height="<?php echo esc_attr($design['animation']['initial_height'] ?? '60px'); ?>"
       data-width-duration="<?php echo esc_attr($design['animation']['width_duration'] ?? '0.4'); ?>"
       data-height-duration="<?php echo esc_attr($design['animation']['height_duration'] ?? '0.4'); ?>"
       data-ease="<?php echo esc_attr($design['animation']['ease'] ?? 'power3.out'); ?>">

    <!-- Cart Content -->
    <div class="exp-cart-content">
      <!-- Top Bar -->
      <?php if (!isset($content['cart']['top_bar']) || $content['cart']['top_bar'] !== 'disable') : ?>
      <div class="bde-mini-cart-offcanvas-topbar">
        <h3 class="bde-mini-cart-offcanvas-title"><?php echo $cartText; ?></h3>
        <button class="bde-mini-cart-offcanvas__close-button" aria-label="<?php esc_attr_e('Close cart', 'woocommerce'); ?>">×</button>
      </div>
      <?php endif; ?>

      <!-- After Title Bar Global Block -->
      <?php
      if ($headerBlockId) {
          echo \Breakdance\Render\render($headerBlockId);
      }
      ?>

      <!-- Cart Items List -->
      <div class="bde-mini-cart-offcanvas-contents">
        <div class="widget_shopping_cart_content">
          <?php woocommerce_mini_cart(); ?>
        </div>

        <?php if ($continueEnabled && $continueShoppingLink) : ?>
          <a class="bde-mini-cart-continue-link" href="<?php echo esc_url($continueShoppingLink); ?>">
            <?php echo $continueShoppingText; ?>
          </a>
        <?php endif; ?>
      </div>

      <!-- After Footer Global Block -->
      <?php
      if ($footerBlockId) {
          echo \Breakdance\Render\render($footerBlockId);
      }
      ?>
    </div>
  </div>

  <!-- Optional Backdrop -->
  <div class="exp-cart-backdrop"></div>
</div>
<?php
}
