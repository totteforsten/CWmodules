<?php
/**
 * @var array $propertiesData
 * @var array $parentPropertiesData
 */

$productId = $parentPropertiesData['content']['content']['product'] ?? null;

\Breakdance\WooCommerce\renderProductPart($productId, function () use ($propertiesData) {
    global $product;

    if (!$product) {
        return;
    }

    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();

    // Combine main image with gallery images
    $image_ids = array_merge([$main_image_id], $attachment_ids);
    $image_ids = array_filter($image_ids); // Remove empty values

    if (empty($image_ids)) {
        return;
    }

    $element_id = $propertiesData['id'] ?? uniqid('gallery-');

    ?>
    <div class="bde-modern-gallery" data-element-id="<?php echo esc_attr($element_id); ?>">
        <div data-properties='<?php echo esc_attr(json_encode(['design' => $propertiesData['design'] ?? []])); ?>' style="display: none;"></div>
        <div class="bde-modern-gallery__main">
            <?php
            $first = true;
            foreach ($image_ids as $image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'full');
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

                if ($image_url) {
                    $active_class = $first ? ' is-active' : '';
                    ?>
                    <div class="bde-modern-gallery__slide<?php echo $active_class; ?>" data-index="<?php echo array_search($image_id, $image_ids); ?>">
                        <img src="<?php echo esc_url($image_url); ?>"
                             alt="<?php echo esc_attr($image_alt); ?>"
                             loading="lazy">
                    </div>
                    <?php
                    $first = false;
                }
            }
            ?>

            <?php if (empty($propertiesData['design']['zoom_icon']['disable'])) : ?>
                <button class="bde-modern-gallery__zoom" aria-label="Zoom image">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            <?php endif; ?>

            <?php if (empty($propertiesData['design']['sale_badge']['disable']) && $product->is_on_sale()) : ?>
                <span class="onsale"><?php esc_html_e('Sale!', 'woocommerce'); ?></span>
            <?php endif; ?>
        </div>

        <?php if (count($image_ids) > 1) : ?>
            <div class="bde-modern-gallery__thumbnails">
                <?php
                $first = true;
                foreach ($image_ids as $image_id) {
                    $thumbnail_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

                    if ($thumbnail_url) {
                        $active_class = $first ? ' is-active' : '';
                        $index = array_search($image_id, $image_ids);
                        ?>
                        <button class="bde-modern-gallery__thumbnail<?php echo $active_class; ?>"
                                data-index="<?php echo $index; ?>"
                                aria-label="View image <?php echo $index + 1; ?>">
                            <img src="<?php echo esc_url($thumbnail_url); ?>"
                                 alt="<?php echo esc_attr($image_alt); ?>">
                        </button>
                        <?php
                        $first = false;
                    }
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
});
