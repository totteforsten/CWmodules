<?php

/**
 * Plugin Name: Breakdance Custom Elements
 * Plugin URI: https://breakdance.com/
 * Description: Boilerplate plugin to save your custom elements created with Element Studio.
 * Author: Breakdance
 * Author URI: https://breakdance.com/
 * License: GPLv2
 * Text Domain: breakdance
 * Domain Path: /languages/
 * Version: 0.0.1
 */

namespace BreakdanceCustomElements;

use function Breakdance\Util\getDirectoryPathRelativeToPluginFolder;

add_action('breakdance_loaded', function () {
    \Breakdance\ElementStudio\registerSaveLocation(
        getDirectoryPathRelativeToPluginFolder(__DIR__) . '/elements',
        'BreakdanceCustomElements',
        'element',
        'Custom Elements',
        false
    );

    \Breakdance\ElementStudio\registerSaveLocation(
        getDirectoryPathRelativeToPluginFolder(__DIR__) . '/macros',
        'BreakdanceCustomElements',
        'macro',
        'Custom Macros',
        false,
    );

    \Breakdance\ElementStudio\registerSaveLocation(
        getDirectoryPathRelativeToPluginFolder(__DIR__) . '/presets',
        'BreakdanceCustomElements',
        'preset',
        'Custom Presets',
        false,
    );
},
    // register elements before loading them
    9
);

// Load custom elements
add_action('breakdance_register_elements', function() {
    $elements_dir = __DIR__ . '/elements';

    if (is_dir($elements_dir)) {
        foreach (glob($elements_dir . '/*/element.php') as $element_file) {
            require_once $element_file;
        }
    }
});

// AJAX Handler for Free Shipping Counter
add_action('wp_ajax_get_free_shipping_data', __NAMESPACE__ . '\\get_free_shipping_data');
add_action('wp_ajax_nopriv_get_free_shipping_data', __NAMESPACE__ . '\\get_free_shipping_data');

function get_free_shipping_data() {
    if (!class_exists('WooCommerce')) {
        wp_send_json_error(['message' => 'WooCommerce not active']);
        return;
    }

    $cart_total = 0;
    $threshold = 0;

    // Get cart total
    if (WC()->cart) {
        $cart_total = WC()->cart->get_subtotal();
    }

    // Get free shipping threshold from WooCommerce settings
    $shipping_zones = \WC_Shipping_Zones::get_zones();

    foreach ($shipping_zones as $zone) {
        foreach ($zone['shipping_methods'] as $method) {
            if ($method->id === 'free_shipping' && $method->enabled === 'yes') {
                $min_amount = $method->get_option('min_amount');
                if ($min_amount && is_numeric($min_amount)) {
                    $threshold = floatval($min_amount);
                    break 2;
                }
            }
        }
    }

    // If no threshold found in zones, check default zone
    if ($threshold === 0) {
        $default_zone = new \WC_Shipping_Zone(0);
        $shipping_methods = $default_zone->get_shipping_methods();

        foreach ($shipping_methods as $method) {
            if ($method->id === 'free_shipping' && $method->enabled === 'yes') {
                $min_amount = $method->get_option('min_amount');
                if ($min_amount && is_numeric($min_amount)) {
                    $threshold = floatval($min_amount);
                    break;
                }
            }
        }
    }

    wp_send_json_success([
        'cart_total' => $cart_total,
        'threshold' => $threshold,
        'remaining' => max(0, $threshold - $cart_total),
        'percentage' => $threshold > 0 ? min(100, ($cart_total / $threshold) * 100) : 0
    ]);
}
