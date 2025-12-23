<?php

namespace BreakdanceCustomElements;

use function Breakdance\Elements\c;

\Breakdance\ElementStudio\registerElementForEditing(
    "BreakdanceCustomElements\\WooVariationsGrid",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class WooVariationsGrid extends \Breakdance\Elements\Element
{
    static function uiIcon()
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>';
    }

    static function tag()
    {
        return 'div';
    }

    static function tagOptions()
    {
        return ['div', 'section'];
    }

    static function tagControlPath()
    {
        return false;
    }

    static function name()
    {
        return 'WooCommerce Variations Grid';
    }

    static function className()
    {
        return 'bde-woo-variations-grid';
    }

    static function category()
    {
        return 'cwelements';
    }

    static function badge()
    {
        return false;
    }

    static function slug()
    {
        return get_class();
    }

    static function template()
    {
        return file_get_contents(__DIR__ . '/html.twig');
    }

    static function defaultCss()
    {
        return file_get_contents(__DIR__ . '/default.css');
    }

    static function defaultProperties()
    {
        return [
            'content' => [
                'columns' => [
                    'show_sku' => true,
                    'show_price' => true,
                    'show_weight' => false,
                    'show_dimensions' => false,
                    'show_stock' => true,
                    'show_quantity' => true
                ],
                'settings' => [
                    'guest_behavior' => 'show_all',
                    'sortable' => true
                ]
            ]
        ];
    }

    static function defaultChildren()
    {
        return false;
    }

    static function cssTemplate()
    {
        $template = file_get_contents(__DIR__ . '/css.twig');
        return $template;
    }

    static function designControls()
    {
        return [
            c(
                "layout",
                "Layout",
                [
                    c(
                        "width",
                        "Width",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        true,
                        false,
                        [],
                    ),
                    c(
                        "max_height",
                        "Max Height",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        true,
                        false,
                        [],
                    ),
                    c(
                        "border_radius",
                        "Border Radius",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "background",
                        "Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                [],
            ),
            c(
                "header",
                "Header",
                [
                    c(
                        "background",
                        "Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "text_color",
                        "Text Color",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "font_size",
                        "Font Size",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        true,
                        false,
                        [],
                    ),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                [],
            ),
            c(
                "rows",
                "Rows",
                [
                    c(
                        "background",
                        "Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "hover_background",
                        "Hover Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "alternate_background",
                        "Alternate Row",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "text_color",
                        "Text Color",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "font_size",
                        "Font Size",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        true,
                        false,
                        [],
                    ),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                [],
            ),
            c(
                "button",
                "Button",
                [
                    c(
                        "background",
                        "Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "text_color",
                        "Text Color",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "hover_background",
                        "Hover Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "border_radius",
                        "Border Radius",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "success_background",
                        "Success Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                [],
            ),
            c(
                "stock",
                "Stock Badge",
                [
                    c(
                        "in_stock_background",
                        "In Stock Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "in_stock_color",
                        "In Stock Text",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "out_of_stock_background",
                        "Out of Stock Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "out_of_stock_color",
                        "Out of Stock Text",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                [],
            ),
        ];
    }

    static function contentControls()
    {
        return [
            c(
                "columns",
                "Columns",
                [
                    c(
                        "show_sku",
                        "Show SKU",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "show_price",
                        "Show Price",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "show_weight",
                        "Show Weight",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "show_dimensions",
                        "Show Dimensions",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "show_stock",
                        "Show Stock Status",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "show_quantity",
                        "Show Quantity Selector",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                [],
            ),
            c(
                "settings",
                "Settings",
                [
                    c(
                        "guest_behavior",
                        "Guest User Behavior",
                        [],
                        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
                            ['value' => 'show_all', 'text' => 'Show Everything'],
                            ['value' => 'hide_price', 'text' => 'Hide Price'],
                            ['value' => 'hide_cart', 'text' => 'Hide Add to Cart'],
                        ]],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "sortable",
                        "Enable Sorting",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        [],
                    ),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                [],
            ),
            c(
                "text",
                "Text Labels",
                [
                    c(
                        "variant_label",
                        "Variant Label",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "stock_status_label",
                        "Stock Status Label",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "quantity_label",
                        "Quantity Label",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "add_to_cart_label",
                        "Add to Cart Label",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "in_stock_text",
                        "In Stock Text",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "out_of_stock_text",
                        "Out of Stock Text",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "login_to_see_price",
                        "Login to See Price Text",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "login_to_buy",
                        "Login to Buy Text",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "adding_text",
                        "Adding to Cart Text",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        [],
                    ),
                    c(
                        "added_text",
                        "Added to Cart Text",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        [],
                    ),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                [],
            ),
        ];
    }

    static function settingsControls()
    {
        return [];
    }

    static function dependencies()
    {
        return false;
    }

    static function settings()
    {
        return false;
    }

    static function addPanelRules()
    {
        return false;
    }

    static function nestingRule()
    {
        return ["type" => "final"];
    }

    static function spacingBars()
    {
        return false;
    }

    static function attributes()
    {
        return false;
    }

    static function experimental()
    {
        return false;
    }

    static function order()
    {
        return 100;
    }

    static function dynamicPropertyPaths()
    {
        return false;
    }

    static function additionalClasses()
    {
        return false;
    }

    static function projectManagement()
    {
        return false;
    }

    static function propertyPathsToWhitelistInFlatProps()
    {
        return ['design.layout.max_height'];
    }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return ['content.columns', 'content.settings', 'content.text'];
    }
}
