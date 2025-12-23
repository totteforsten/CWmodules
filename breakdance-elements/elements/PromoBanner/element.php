<?php

namespace BreakdanceCustomElements;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;

\Breakdance\ElementStudio\registerElementForEditing(
    "BreakdanceCustomElements\\PromoBanner",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class PromoBanner extends \Breakdance\Elements\Element
{
    static function uiIcon()
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="6" rx="1"/><line x1="6" y1="7" x2="14" y2="7"/><circle cx="18" cy="7" r="1"/></svg>';
    }

    static function tag()
    {
        return 'div';
    }

    static function tagOptions()
    {
        return [];
    }

    static function tagControlPath()
    {
        return false;
    }

    static function name()
    {
        return 'Promo Banner';
    }

    static function className()
    {
        return 'bde-promo-banner';
    }

    static function category()
    {
        return 'cwelements';
    }

    static function badge()
    {
        return ['backgroundColor' => 'var(--brandInfoBackground)', 'textColor' => 'var(--brandInfo)', 'label' => 'Pro'];
    }

    static function slug()
    {
        return __CLASS__;
    }

    static function template()
    {
        return file_get_contents(__DIR__ . '/html.twig');
    }

    static function defaultCss()
    {
        return file_get_contents(__DIR__ . '/default.css');
    }

    static function cssTemplate()
    {
        return file_get_contents(__DIR__ . '/css.twig');
    }

    static function defaultProperties()
    {
        return [
            'content' => [
                'banner_mode' => 'countdown',
                'countdown' => [
                    'text_before' => 'Get 50% off today! Claim your discount within',
                    'text_after' => '',
                    'end_date' => '',
                    'end_time' => '23:59',
                    'hours' => 24,
                    'countdown_type' => 'duration',
                    'expired_text' => 'Offer has expired!',
                    'link' => [
                        'url' => '#',
                        'openInNewTab' => false
                    ]
                ],
                'marquee' => [
                    'items' => [
                        ['text' => 'Free Shipping on orders over $50', 'icon' => 'TruckIcon'],
                        ['text' => '30-Day Money Back Guarantee', 'icon' => 'ShieldCheckIcon'],
                        ['text' => '24/7 Customer Support', 'icon' => 'ChatIcon']
                    ],
                    'speed' => 30,
                    'pause_on_hover' => true,
                    'direction' => 'left'
                ],
                'close_button' => [
                    'show' => true,
                    'cookie_name' => 'promo_banner_closed',
                    'cookie_days' => 1
                ]
            ],
            'design' => [
                'banner' => [
                    'background' => '#1a1a2e',
                    'height' => ['number' => 45, 'unit' => 'px', 'style' => '45px']
                ],
                'text' => [
                    'color' => '#ffffff',
                    'font_size' => ['number' => 14, 'unit' => 'px', 'style' => '14px']
                ],
                'countdown' => [
                    'background' => '#e94560',
                    'color' => '#ffffff',
                    'border_radius' => ['number' => 4, 'unit' => 'px', 'style' => '4px']
                ],
                'close_button' => [
                    'color' => '#ffffff',
                    'hover_color' => '#e94560',
                    'size' => ['number' => 20, 'unit' => 'px', 'style' => '20px']
                ],
                'marquee' => [
                    'gap' => ['number' => 60, 'unit' => 'px', 'style' => '60px'],
                    'icon_size' => ['number' => 18, 'unit' => 'px', 'style' => '18px'],
                    'icon_color' => '#e94560',
                    'fade_edges' => false,
                    'fade_width' => ['number' => 60, 'unit' => 'px', 'style' => '60px']
                ]
            ]
        ];
    }

    static function defaultChildren()
    {
        return false;
    }

    static function cssIdRecursive()
    {
        return true;
    }

    static function designControls()
    {
        return [
            c(
                "banner",
                "Banner",
                [
                    c("background", "Background", [], ['type' => 'color', 'layout' => 'inline', 'colorOptions' => ['type' => 'solidAndGradient']], false, false, []),
                    c("height", "Height", [], ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', 'em', 'rem'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 30, 'max' => 100, 'step' => 1]], true, false, []),
                    getPresetSection("EssentialElements\\spacing_padding_all", "Padding", "padding", ['type' => 'popout'])
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "text",
                "Text",
                [
                    c("color", "Color", [], ['type' => 'color', 'layout' => 'inline'], false, false, []),
                    getPresetSection("EssentialElements\\typography", "Typography", "typography", ['type' => 'popout'])
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "countdown",
                "Countdown Timer",
                [
                    c("background", "Background", [], ['type' => 'color', 'layout' => 'inline'], false, false, []),
                    c("color", "Text Color", [], ['type' => 'color', 'layout' => 'inline'], false, false, []),
                    c("border_radius", "Border Radius", [], ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', 'em', '%'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 0, 'max' => 20, 'step' => 1]], false, false, []),
                    c("padding", "Padding", [], ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', 'em'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 0, 'max' => 20, 'step' => 1]], false, false, []),
                    getPresetSection("EssentialElements\\typography", "Typography", "typography", ['type' => 'popout'])
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "marquee",
                "Marquee/Slider",
                [
                    c("gap", "Item Gap", [], ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', 'em', 'rem'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 20, 'max' => 150, 'step' => 5]], false, false, []),
                    c("icon_size", "Icon Size", [], ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', 'em', 'rem'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 12, 'max' => 40, 'step' => 1]], false, false, []),
                    c("icon_color", "Icon Color", [], ['type' => 'color', 'layout' => 'inline'], false, false, []),
                    c("separator_show", "Show Separator", [], ['type' => 'toggle', 'layout' => 'inline'], false, false, []),
                    c("separator_char", "Separator", [], ['type' => 'text', 'layout' => 'inline', 'condition' => ['path' => 'design.marquee.separator_show', 'operand' => 'is set', 'value' => '']], false, false, []),
                    c("separator_color", "Separator Color", [], ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.marquee.separator_show', 'operand' => 'is set', 'value' => '']], false, false, []),
                    c("fade_edges", "Fade Edges", [], ['type' => 'toggle', 'layout' => 'inline'], false, false, []),
                    c("fade_width", "Fade Width", [], ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', '%'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 10, 'max' => 200, 'step' => 5], 'condition' => ['path' => 'design.marquee.fade_edges', 'operand' => 'is set', 'value' => '']], false, false, []),
                    c("fade_color", "Fade Color", [], ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.marquee.fade_edges', 'operand' => 'is set', 'value' => '']], false, false, [])
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "close_button",
                "Close Button",
                [
                    c("color", "Color", [], ['type' => 'color', 'layout' => 'inline'], false, false, []),
                    c("hover_color", "Hover Color", [], ['type' => 'color', 'layout' => 'inline'], false, false, []),
                    c("size", "Size", [], ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', 'em', 'rem'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 12, 'max' => 30, 'step' => 1]], false, false, []),
                    c("background", "Background", [], ['type' => 'color', 'layout' => 'inline'], false, false, []),
                    c("background_hover", "Background Hover", [], ['type' => 'color', 'layout' => 'inline'], false, false, []),
                    c("border_radius", "Border Radius", [], ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', '%'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 0, 'max' => 50, 'step' => 1]], false, false, [])
                ],
                ['type' => 'section', 'condition' => ['path' => 'content.close_button.show', 'operand' => 'is set', 'value' => '']],
                false,
                false,
                []
            )
        ];
    }

    static function contentControls()
    {
        return [
            c(
                "banner_mode",
                "Banner Mode",
                [],
                [
                    'type' => 'dropdown',
                    'layout' => 'vertical',
                    'items' => [
                        ['value' => 'countdown', 'text' => 'Countdown Timer'],
                        ['value' => 'marquee', 'text' => 'Sliding Marquee'],
                        ['value' => 'both', 'text' => 'Both (Countdown + Marquee)']
                    ]
                ],
                false,
                false,
                []
            ),
            c(
                "countdown",
                "Countdown Settings",
                [
                    c("text_before", "Text Before Timer", [], ['type' => 'text', 'layout' => 'vertical', 'textOptions' => ['multiline' => false]], false, false, []),
                    c("text_after", "Text After Timer", [], ['type' => 'text', 'layout' => 'vertical', 'textOptions' => ['multiline' => false]], false, false, []),
                    c("countdown_type", "Countdown Type", [], [
                        'type' => 'dropdown',
                        'layout' => 'vertical',
                        'items' => [
                            ['value' => 'duration', 'text' => 'Duration (hours from now)'],
                            ['value' => 'fixed', 'text' => 'Fixed Date & Time'],
                            ['value' => 'daily', 'text' => 'Daily (resets each day)']
                        ]
                    ], false, false, []),
                    c("hours", "Hours", [], ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 1, 'max' => 168, 'step' => 1], 'condition' => ['path' => 'content.countdown.countdown_type', 'operand' => 'equals', 'value' => 'duration']], false, false, []),
                    c("end_date", "End Date", [], ['type' => 'text', 'layout' => 'inline', 'placeholder' => 'YYYY-MM-DD', 'condition' => ['path' => 'content.countdown.countdown_type', 'operand' => 'equals', 'value' => 'fixed']], false, false, []),
                    c("end_time", "End Time (for Fixed/Daily)", [], ['type' => 'text', 'layout' => 'inline', 'placeholder' => 'HH:MM'], false, false, []),
                    c("expired_text", "Expired Text", [], ['type' => 'text', 'layout' => 'vertical'], false, false, []),
                    c("hide_when_expired", "Hide When Expired", [], ['type' => 'toggle', 'layout' => 'inline'], false, false, []),
                    c("link", "Button/Link", [], ['type' => 'link', 'layout' => 'vertical'], false, false, []),
                    c("link_text", "Link Text", [], ['type' => 'text', 'layout' => 'vertical', 'placeholder' => 'Shop Now'], false, false, [])
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "marquee",
                "Marquee Settings",
                [
                    c(
                        "items",
                        "Sliding Items",
                        [
                            c("icon", "Icon", [], [
                                'type' => 'dropdown',
                                'layout' => 'vertical',
                                'items' => [
                                    ['value' => '', 'text' => 'None'],
                                    ['value' => 'TruckIcon', 'text' => 'Truck (Shipping)'],
                                    ['value' => 'ShieldCheckIcon', 'text' => 'Shield (Guarantee)'],
                                    ['value' => 'ChatIcon', 'text' => 'Chat (Support)'],
                                    ['value' => 'StarIcon', 'text' => 'Star (Rating)'],
                                    ['value' => 'GiftIcon', 'text' => 'Gift'],
                                    ['value' => 'TagIcon', 'text' => 'Tag (Sale)'],
                                    ['value' => 'ClockIcon', 'text' => 'Clock (Time)'],
                                    ['value' => 'HeartIcon', 'text' => 'Heart'],
                                    ['value' => 'CheckIcon', 'text' => 'Check'],
                                    ['value' => 'PercentIcon', 'text' => 'Percent (Discount)'],
                                    ['value' => 'CreditCardIcon', 'text' => 'Credit Card (Payment)'],
                                    ['value' => 'LockIcon', 'text' => 'Lock (Secure)'],
                                    ['value' => 'custom', 'text' => 'Custom SVG']
                                ]
                            ], false, false, []),
                            c("custom_icon", "Custom Icon SVG", [], ['type' => 'text', 'layout' => 'vertical', 'textOptions' => ['multiline' => true], 'condition' => ['path' => '%%CURRENTPATH%%.icon', 'operand' => 'equals', 'value' => 'custom']], false, false, []),
                            c("text", "Text", [], ['type' => 'text', 'layout' => 'vertical'], false, false, []),
                            c("link", "Link (optional)", [], ['type' => 'link', 'layout' => 'vertical'], false, false, [])
                        ],
                        ['type' => 'repeater', 'layout' => 'vertical'],
                        false,
                        false,
                        []
                    ),
                    c("speed", "Animation Speed (seconds)", [], ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 5, 'max' => 120, 'step' => 5]], false, false, []),
                    c("direction", "Direction", [], [
                        'type' => 'button_bar',
                        'layout' => 'inline',
                        'items' => [
                            ['text' => 'Left', 'value' => 'left'],
                            ['text' => 'Right', 'value' => 'right']
                        ]
                    ], false, false, []),
                    c("pause_on_hover", "Pause on Hover", [], ['type' => 'toggle', 'layout' => 'inline'], false, false, [])
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "close_button",
                "Close Button",
                [
                    c("show", "Show Close Button", [], ['type' => 'toggle', 'layout' => 'inline'], false, false, []),
                    c("cookie_name", "Cookie Name", [], ['type' => 'text', 'layout' => 'vertical', 'condition' => ['path' => 'content.close_button.show', 'operand' => 'is set', 'value' => '']], false, false, []),
                    c("cookie_days", "Cookie Duration (days)", [], ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 1, 'max' => 30, 'step' => 1], 'condition' => ['path' => 'content.close_button.show', 'operand' => 'is set', 'value' => '']], false, false, [])
                ],
                ['type' => 'section'],
                false,
                false,
                []
            )
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
        return ["type" => "section"];
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
        return [];
    }

    static function additionalClasses()
    {
        return false;
    }

    static function project498LegacyMode()
    {
        return false;
    }

    static function propertyPathsToWhitelistInFlatProps()
    {
        return [
            'content.countdown.link',
            'content.marquee.items'
        ];
    }

    static function propertyPathsToSsrRecursively()
    {
        return [];
    }
}
