<?php
/**
 * Module Name: Breakdance Elements
 * Description: Custom Breakdance elements including Section, Expanding Menu, Animated Toggle Menu, Product Images, Free Shipping Counter, Mini Cart, WooVariationsGrid, Promo Banner, and Term Loop Builder.
 * Version: 1.0.0
 * Author: Colorwave Studio
 * Text Domain: breakdance-elements
 */

if (!defined('ABSPATH')) {
    exit;
}

class Colorwave_Module_Breakdance_Elements extends Colorwave_Module {

    /**
     * Available elements cache
     */
    private $available_elements = array();

    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'breakdance-elements';
        $this->name = __('Breakdance Elements', 'breakdance-elements');
        $this->version = '1.0.0';
    }

    /**
     * Initialize the module
     */
    public function init() {
        // Discover available elements
        $this->discover_elements();

        // Register elements when Breakdance is loaded
        add_action('breakdance_loaded', array($this, 'register_element_locations'), 9);

        // Load custom elements
        add_action('breakdance_register_elements', array($this, 'load_elements'));

        // Register AJAX handlers for Free Shipping Counter
        add_action('wp_ajax_get_free_shipping_data', array($this, 'get_free_shipping_data'));
        add_action('wp_ajax_nopriv_get_free_shipping_data', array($this, 'get_free_shipping_data'));

        // Admin settings page
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    /**
     * Discover all available elements
     */
    private function discover_elements() {
        $elements_dir = __DIR__ . '/elements';

        if (!is_dir($elements_dir)) {
            return;
        }

        foreach (glob($elements_dir . '/*', GLOB_ONLYDIR) as $element_folder) {
            $element_file = $element_folder . '/element.php';
            if (file_exists($element_file)) {
                $element_id = basename($element_folder);
                $element_name = $this->get_element_name($element_id, $element_file);

                $this->available_elements[$element_id] = array(
                    'id' => $element_id,
                    'name' => $element_name,
                    'file' => $element_file,
                    'folder' => $element_folder
                );
            }
        }
    }

    /**
     * Get human-readable element name from ID or file
     */
    private function get_element_name($element_id, $element_file) {
        // Try to extract class name from element file for a better name
        $content = file_get_contents($element_file);
        if (preg_match('/class\s+(\w+)/', $content, $matches)) {
            $class_name = $matches[1];
            // Convert class name to readable format
            $name = preg_replace('/([a-z])([A-Z])/', '$1 $2', $class_name);
            $name = str_replace('_', ' ', $name);
            return $name;
        }

        // Fallback: Convert ID to readable name
        $name = str_replace(array('-', '_'), ' ', $element_id);
        return ucwords($name);
    }

    /**
     * Get available elements
     */
    public function get_available_elements() {
        return $this->available_elements;
    }

    /**
     * Check if an element is enabled
     */
    public function is_element_enabled($element_id) {
        $enabled_elements = get_option('colorwave_breakdance_enabled_elements', array());

        // If no settings exist yet, all elements are enabled by default
        if (empty($enabled_elements)) {
            return true;
        }

        return isset($enabled_elements[$element_id]) && $enabled_elements[$element_id];
    }

    /**
     * Add settings page
     */
    public function add_settings_page() {
        add_submenu_page(
            'colorwave-studio',
            __('Breakdance Elements', 'breakdance-elements'),
            __('Breakdance Elements', 'breakdance-elements'),
            'manage_options',
            'colorwave-breakdance-elements',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'colorwave_breakdance_elements',
            'colorwave_breakdance_enabled_elements',
            array($this, 'sanitize_settings')
        );
    }

    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();

        foreach ($this->available_elements as $element_id => $element) {
            $sanitized[$element_id] = isset($input[$element_id]) ? (bool) $input[$element_id] : false;
        }

        return $sanitized;
    }

    /**
     * Enqueue admin styles
     */
    public function enqueue_admin_styles($hook) {
        if (strpos($hook, 'colorwave-breakdance-elements') === false) {
            return;
        }

        wp_add_inline_style('wp-admin', '
            .colorwave-elements-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 16px;
                margin-top: 20px;
            }
            .colorwave-element-card {
                background: #fff;
                border: 1px solid #c3c4c7;
                border-radius: 4px;
                padding: 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .colorwave-element-card:hover {
                border-color: #2271b1;
            }
            .colorwave-element-info h3 {
                margin: 0 0 4px 0;
                font-size: 14px;
            }
            .colorwave-element-info code {
                font-size: 11px;
                background: #f0f0f1;
                padding: 2px 6px;
                border-radius: 2px;
            }
            .colorwave-toggle {
                position: relative;
                width: 40px;
                height: 22px;
            }
            .colorwave-toggle input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            .colorwave-toggle-slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: .3s;
                border-radius: 22px;
            }
            .colorwave-toggle-slider:before {
                position: absolute;
                content: "";
                height: 16px;
                width: 16px;
                left: 3px;
                bottom: 3px;
                background-color: white;
                transition: .3s;
                border-radius: 50%;
            }
            .colorwave-toggle input:checked + .colorwave-toggle-slider {
                background-color: #2271b1;
            }
            .colorwave-toggle input:checked + .colorwave-toggle-slider:before {
                transform: translateX(18px);
            }
            .colorwave-bulk-actions {
                margin: 20px 0;
                display: flex;
                gap: 10px;
            }
        ');
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        $enabled_elements = get_option('colorwave_breakdance_enabled_elements', array());

        // If no settings saved yet, default all to enabled
        if (empty($enabled_elements)) {
            foreach ($this->available_elements as $element_id => $element) {
                $enabled_elements[$element_id] = true;
            }
        }
        ?>
        <div class="wrap">
            <h1><?php _e('Breakdance Elements', 'breakdance-elements'); ?></h1>
            <p><?php _e('Enable or disable individual Breakdance elements. Disabled elements will not be loaded or available in the Breakdance editor.', 'breakdance-elements'); ?></p>

            <form method="post" action="options.php">
                <?php settings_fields('colorwave_breakdance_elements'); ?>

                <div class="colorwave-bulk-actions">
                    <button type="button" class="button" onclick="colorwaveToggleAll(true)">
                        <?php _e('Enable All', 'breakdance-elements'); ?>
                    </button>
                    <button type="button" class="button" onclick="colorwaveToggleAll(false)">
                        <?php _e('Disable All', 'breakdance-elements'); ?>
                    </button>
                </div>

                <div class="colorwave-elements-grid">
                    <?php foreach ($this->available_elements as $element_id => $element) :
                        $is_enabled = isset($enabled_elements[$element_id]) ? $enabled_elements[$element_id] : true;
                    ?>
                        <div class="colorwave-element-card">
                            <div class="colorwave-element-info">
                                <h3><?php echo esc_html($element['name']); ?></h3>
                                <code><?php echo esc_html($element_id); ?></code>
                            </div>
                            <label class="colorwave-toggle">
                                <input type="checkbox"
                                       name="colorwave_breakdance_enabled_elements[<?php echo esc_attr($element_id); ?>]"
                                       value="1"
                                       <?php checked($is_enabled, true); ?>
                                       class="colorwave-element-toggle">
                                <span class="colorwave-toggle-slider"></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php submit_button(__('Save Changes', 'breakdance-elements')); ?>
            </form>
        </div>

        <script>
        function colorwaveToggleAll(enable) {
            document.querySelectorAll('.colorwave-element-toggle').forEach(function(checkbox) {
                checkbox.checked = enable;
            });
        }
        </script>
        <?php
    }

    /**
     * Register element save locations with Breakdance
     */
    public function register_element_locations() {
        if (!function_exists('Breakdance\Util\getDirectoryPathRelativeToPluginFolder')) {
            return;
        }

        $relative_path = \Breakdance\Util\getDirectoryPathRelativeToPluginFolder(__DIR__);

        \Breakdance\ElementStudio\registerSaveLocation(
            $relative_path . '/elements',
            'ColorwaveBreakdanceElements',
            'element',
            'Colorwave Elements',
            false
        );

        \Breakdance\ElementStudio\registerSaveLocation(
            $relative_path . '/macros',
            'ColorwaveBreakdanceElements',
            'macro',
            'Colorwave Macros',
            false
        );

        \Breakdance\ElementStudio\registerSaveLocation(
            $relative_path . '/presets',
            'ColorwaveBreakdanceElements',
            'preset',
            'Colorwave Presets',
            false
        );
    }

    /**
     * Load enabled custom elements only
     */
    public function load_elements() {
        foreach ($this->available_elements as $element_id => $element) {
            if ($this->is_element_enabled($element_id)) {
                require_once $element['file'];
            }
        }
    }

    /**
     * AJAX Handler for Free Shipping Counter
     */
    public function get_free_shipping_data() {
        // Only run if Free Shipping Counter element is enabled
        if (!$this->is_element_enabled('Free_Shipping_Counter')) {
            wp_send_json_error(array('message' => 'Element not enabled'));
            return;
        }

        if (!class_exists('WooCommerce')) {
            wp_send_json_error(array('message' => 'WooCommerce not active'));
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

        wp_send_json_success(array(
            'cart_total' => $cart_total,
            'threshold' => $threshold,
            'remaining' => max(0, $threshold - $cart_total),
            'percentage' => $threshold > 0 ? min(100, ($cart_total / $threshold) * 100) : 0
        ));
    }

    /**
     * Module activation hook
     */
    public function activate() {
        // Enable all elements by default on activation
        $enabled_elements = array();
        $this->discover_elements();

        foreach ($this->available_elements as $element_id => $element) {
            $enabled_elements[$element_id] = true;
        }

        add_option('colorwave_breakdance_enabled_elements', $enabled_elements);

        // Flush rewrite rules on activation
        flush_rewrite_rules();
    }

    /**
     * Module deactivation hook
     */
    public function deactivate() {
        // Flush rewrite rules on deactivation
        flush_rewrite_rules();
    }
}
