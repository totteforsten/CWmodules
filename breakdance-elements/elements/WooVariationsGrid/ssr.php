<?php
/**
 * WooCommerce Variations Grid - Server Side Render
 * @var array $propertiesData
 * @var array $parentPropertiesData
 */

$productId = $parentPropertiesData['content']['content']['product'] ?? null;

\Breakdance\WooCommerce\renderProductPart($productId, function () use ($propertiesData) {
    global $product;

    if (!$product || !is_object($product) || !method_exists($product, 'is_type')) {
        echo '<div class="bde-woo-variations-grid-notice">Place this element on a single product page.</div>';
        return;
    }

    if (!$product->is_type('variable')) {
        echo '<div class="bde-woo-variations-grid-notice">This product has no variations.</div>';
        return;
    }

    $variations = $product->get_available_variations();
    if (empty($variations)) {
        echo '<div class="bde-woo-variations-grid-notice">No variations available.</div>';
        return;
    }

    // Get settings from the new simplified structure
    $content = $propertiesData['content'] ?? [];
    $columns = $content['columns'] ?? [];
    $settings = $content['settings'] ?? [];
    $text = $content['text'] ?? [];

    // Column visibility
    $showSku = $columns['show_sku'] ?? true;
    $showPrice = $columns['show_price'] ?? true;
    $showWeight = $columns['show_weight'] ?? false;
    $showDimensions = $columns['show_dimensions'] ?? false;
    $showStock = $columns['show_stock'] ?? true;
    $showQuantity = $columns['show_quantity'] ?? true;

    // Settings
    $guestBehavior = $settings['guest_behavior'] ?? 'show_all';
    $sortable = $settings['sortable'] ?? true;
    $isLoggedIn = is_user_logged_in();

    // Text labels with defaults
    $addToCartLabel = $text['add_to_cart_label'] ?? 'Add to Cart';
    $inStockText = $text['in_stock_text'] ?? 'In Stock';
    $outOfStockText = $text['out_of_stock_text'] ?? 'Out of Stock';
    $addingText = $text['adding_text'] ?? 'Adding...';
    $addedText = $text['added_text'] ?? 'Added!';

    // Build columns array for rendering
    $visibleColumns = [];
    if ($showSku) $visibleColumns[] = ['key' => 'sku', 'label' => 'SKU'];
    if ($showPrice) $visibleColumns[] = ['key' => 'price', 'label' => 'Price'];
    if ($showWeight) $visibleColumns[] = ['key' => 'weight', 'label' => 'Weight'];
    if ($showDimensions) $visibleColumns[] = ['key' => 'dimensions', 'label' => 'Dimensions'];

    // Calculate grid columns
    $gridCols = 1 + count($visibleColumns); // Variant name + visible columns
    if ($showStock) $gridCols++;
    if ($showQuantity) $gridCols++;
    $gridCols++; // Add to cart button
    ?>

    <div class="bde-woo-variations-grid-container"
         data-adding-text="<?php echo esc_attr($addingText); ?>"
         data-added-text="<?php echo esc_attr($addedText); ?>"
         data-add-to-cart-text="<?php echo esc_attr($addToCartLabel); ?>"
         style="--grid-cols: <?php echo $gridCols; ?>;">

        <div class="bde-woo-variations-grid-header">
            <div class="bde-header-cell <?php echo $sortable ? 'bde-sortable' : ''; ?>" data-sort="name">
                <span>Variant</span>
                <?php if ($sortable): ?>
                <svg class="bde-sort-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M7 10l5-5 5 5M7 14l5 5 5-5"/>
                </svg>
                <?php endif; ?>
            </div>

            <?php foreach ($visibleColumns as $col): ?>
            <div class="bde-header-cell <?php echo $sortable ? 'bde-sortable' : ''; ?>" data-sort="<?php echo esc_attr($col['key']); ?>">
                <span><?php echo esc_html($col['label']); ?></span>
                <?php if ($sortable): ?>
                <svg class="bde-sort-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M7 10l5-5 5 5M7 14l5 5 5-5"/>
                </svg>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

            <?php if ($showStock): ?>
            <div class="bde-header-cell">Status</div>
            <?php endif; ?>

            <?php if ($showQuantity): ?>
            <div class="bde-header-cell">Qty</div>
            <?php endif; ?>

            <div class="bde-header-cell"></div>
        </div>

        <div class="bde-woo-variations-grid-body">
            <?php foreach ($variations as $index => $variation):
                $variationId = $variation['variation_id'];
                $variationObj = wc_get_product($variationId);
                if (!$variationObj) continue;

                $attributes = $variation['attributes'];
                $attrNames = [];
                foreach ($attributes as $attrKey => $attrValue) {
                    if ($attrValue) {
                        $taxonomy = str_replace('attribute_', '', $attrKey);
                        $term = get_term_by('slug', $attrValue, $taxonomy);
                        $attrNames[] = $term ? $term->name : $attrValue;
                    }
                }
                $variantName = implode(' / ', $attrNames) ?: 'Variation ' . ($index + 1);
                $inStock = $variationObj->is_in_stock();
                $canPurchase = $inStock && ($isLoggedIn || $guestBehavior !== 'hide_cart');
            ?>
            <div class="bde-row <?php echo $inStock ? '' : 'bde-out-of-stock'; ?>"
                 data-variation-id="<?php echo esc_attr($variationId); ?>"
                 data-product-id="<?php echo esc_attr($product->get_id()); ?>">

                <div class="bde-cell bde-variant-name" data-sort-value="<?php echo esc_attr(strtolower($variantName)); ?>">
                    <?php echo esc_html($variantName); ?>
                </div>

                <?php foreach ($visibleColumns as $col):
                    $value = '';
                    $sortValue = '';

                    switch ($col['key']) {
                        case 'sku':
                            $value = $variationObj->get_sku() ?: '—';
                            $sortValue = $value;
                            break;
                        case 'price':
                            if ($isLoggedIn || $guestBehavior === 'show_all') {
                                $value = $variationObj->get_price_html();
                                $sortValue = $variationObj->get_price();
                            } else {
                                $value = '<span class="bde-login-required">Login to view</span>';
                                $sortValue = 0;
                            }
                            break;
                        case 'weight':
                            $weight = $variationObj->get_weight();
                            $value = $weight ? $weight . ' ' . get_option('woocommerce_weight_unit') : '—';
                            $sortValue = $weight ?: 0;
                            break;
                        case 'dimensions':
                            $dims = $variationObj->get_dimensions(false);
                            if ($dims['length'] || $dims['width'] || $dims['height']) {
                                $unit = get_option('woocommerce_dimension_unit');
                                $value = $dims['length'] . ' × ' . $dims['width'] . ' × ' . $dims['height'] . ' ' . $unit;
                                $sortValue = $dims['length'] * $dims['width'] * $dims['height'];
                            } else {
                                $value = '—';
                                $sortValue = 0;
                            }
                            break;
                    }
                ?>
                <div class="bde-cell" data-sort-value="<?php echo esc_attr($sortValue); ?>">
                    <?php echo $value; ?>
                </div>
                <?php endforeach; ?>

                <?php if ($showStock): ?>
                <div class="bde-cell">
                    <span class="bde-badge <?php echo $inStock ? 'bde-badge-success' : 'bde-badge-danger'; ?>">
                        <?php echo $inStock ? esc_html($inStockText) : esc_html($outOfStockText); ?>
                    </span>
                </div>
                <?php endif; ?>

                <?php if ($showQuantity): ?>
                <div class="bde-cell bde-qty-cell">
                    <div class="bde-qty-wrapper">
                        <button type="button" class="bde-qty-btn bde-qty-minus" <?php echo !$canPurchase ? 'disabled' : ''; ?>>−</button>
                        <input type="number" class="bde-qty-input" value="1" min="1" <?php echo !$canPurchase ? 'disabled' : ''; ?>>
                        <button type="button" class="bde-qty-btn bde-qty-plus" <?php echo !$canPurchase ? 'disabled' : ''; ?>>+</button>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bde-cell bde-action-cell">
                    <?php if ($canPurchase): ?>
                    <button type="button" class="bde-cart-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 6h15l-1.5 9h-12z M9 20a1 1 0 100-2 1 1 0 000 2z M18 20a1 1 0 100-2 1 1 0 000 2z"/>
                        </svg>
                        <span><?php echo esc_html($addToCartLabel); ?></span>
                    </button>
                    <?php elseif (!$inStock): ?>
                    <button type="button" class="bde-cart-btn" disabled>
                        <?php echo esc_html($outOfStockText); ?>
                    </button>
                    <?php else: ?>
                    <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="bde-cart-btn bde-login-btn">
                        Login to Buy
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    (function() {
        const containers = document.querySelectorAll('.bde-woo-variations-grid-container');
        containers.forEach(container => {
            if (container.dataset.initialized) return;
            container.dataset.initialized = 'true';

            const addingText = container.dataset.addingText;
            const addedText = container.dataset.addedText;
            const defaultText = container.dataset.addToCartText;

            // Quantity buttons
            container.querySelectorAll('.bde-qty-wrapper').forEach(wrapper => {
                const input = wrapper.querySelector('.bde-qty-input');
                const minus = wrapper.querySelector('.bde-qty-minus');
                const plus = wrapper.querySelector('.bde-qty-plus');

                minus?.addEventListener('click', () => {
                    const val = parseInt(input.value) || 1;
                    if (val > 1) input.value = val - 1;
                });

                plus?.addEventListener('click', () => {
                    input.value = (parseInt(input.value) || 0) + 1;
                });
            });

            // Add to cart
            container.querySelectorAll('.bde-cart-btn:not([disabled]):not(.bde-login-btn)').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const row = this.closest('.bde-row');
                    const productId = row.dataset.productId;
                    const variationId = row.dataset.variationId;
                    const qtyInput = row.querySelector('.bde-qty-input');
                    const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
                    const textSpan = this.querySelector('span');
                    const originalText = textSpan?.textContent || defaultText;

                    this.disabled = true;
                    this.classList.add('bde-loading');
                    if (textSpan) textSpan.textContent = addingText;

                    try {
                        const formData = new FormData();
                        formData.append('add-to-cart', productId);
                        formData.append('product_id', productId);
                        formData.append('variation_id', variationId);
                        formData.append('quantity', qty);

                        const response = await fetch('?wc-ajax=add_to_cart', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();

                        if (data.error) {
                            throw new Error(data.error);
                        }

                        // Update cart fragments
                        if (typeof jQuery !== 'undefined' && data.fragments) {
                            jQuery(document.body).trigger('added_to_cart', [data.fragments, data.cart_hash, jQuery(this)]);
                        }

                        this.classList.remove('bde-loading');
                        this.classList.add('bde-success');
                        if (textSpan) textSpan.textContent = addedText;

                        setTimeout(() => {
                            this.classList.remove('bde-success');
                            this.disabled = false;
                            if (textSpan) textSpan.textContent = originalText;
                        }, 2000);

                    } catch (error) {
                        console.error('Add to cart error:', error);
                        this.classList.remove('bde-loading');
                        this.disabled = false;
                        if (textSpan) textSpan.textContent = originalText;
                    }
                });
            });

            // Sorting
            container.querySelectorAll('.bde-sortable').forEach(header => {
                header.addEventListener('click', function() {
                    const sortKey = this.dataset.sort;
                    const isAsc = this.classList.contains('bde-sort-asc');
                    const body = container.querySelector('.bde-woo-variations-grid-body');
                    const rows = Array.from(body.querySelectorAll('.bde-row'));

                    // Reset all headers
                    container.querySelectorAll('.bde-sortable').forEach(h => {
                        h.classList.remove('bde-sort-asc', 'bde-sort-desc');
                    });

                    // Set new sort direction
                    this.classList.add(isAsc ? 'bde-sort-desc' : 'bde-sort-asc');
                    const direction = isAsc ? -1 : 1;

                    // Get column index
                    const headers = Array.from(container.querySelectorAll('.bde-header-cell'));
                    const colIndex = headers.indexOf(this);

                    rows.sort((a, b) => {
                        const aCell = a.querySelectorAll('.bde-cell')[colIndex];
                        const bCell = b.querySelectorAll('.bde-cell')[colIndex];

                        let aVal = aCell?.dataset.sortValue || aCell?.textContent.trim().toLowerCase() || '';
                        let bVal = bCell?.dataset.sortValue || bCell?.textContent.trim().toLowerCase() || '';

                        // Numeric sort
                        const aNum = parseFloat(aVal);
                        const bNum = parseFloat(bVal);
                        if (!isNaN(aNum) && !isNaN(bNum)) {
                            return (aNum - bNum) * direction;
                        }

                        // String sort
                        return aVal.localeCompare(bVal) * direction;
                    });

                    rows.forEach(row => body.appendChild(row));
                });
            });
        });
    })();
    </script>
    <?php
});
