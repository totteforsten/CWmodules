<?php

namespace BreakdanceCustomElements;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;


\Breakdance\ElementStudio\registerElementForEditing(
    "BreakdanceCustomElements\\FreeShippingCounter",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class FreeShippingCounter extends \Breakdance\Elements\Element
{
    static function uiIcon()
    {
        return '<svg aria-hidden="true" focusable="false" class="svg-inline--fa fa-truck" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M624 368h-16V251.9c0-19-7.7-37.5-21.1-50.9L503 117.1C489.6 103.7 471 96 452.1 96H416V56c0-30.9-25.1-56-56-56H56C25.1 0 0 25.1 0 56v304c0 30.9 25.1 56 56 56h8c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16zm-464 96c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm208-96H242.7c-16.6-28.6-47.2-48-82.7-48s-66.1 19.4-82.7 48H56c-4.4 0-8-3.6-8-8V56c0-4.4 3.6-8 8-8h304c4.4 0 8 3.6 8 8v312zm48 96c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm160-96h-28.7c-16.6-28.6-47.2-48-82.7-48s-66.1 19.4-82.7 48H416V144h36.1c9.5 0 18.6 3.9 25.1 10.3l83.9 83.9c6.3 6.3 10.3 15.6 10.3 25.1V368z"></path></svg>';
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
        return 'Free Shipping Counter';
    }

    static function className()
    {
        return 'bde-free-shipping-counter';
    }

    static function category()
    {
        return 'woocommerce';
    }

    static function badge()
    {
        return ['backgroundColor' => 'var(--brandWooCommerceBackground)', 'textColor' => 'var(--brandWooCommerce)', 'label' => 'Woo'];
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

    static function defaultProperties()
    {
        return [
            'content' => [
                'messages' => [
                    'in_progress' => 'Add {amount} more for free shipping!',
                    'achieved' => 'You qualify for free shipping!',
                    'empty_cart' => 'Your cart is empty. Add {amount} to qualify for free shipping.'
                ],
                'currency_symbol' => 'SEK'
            ],
            'design' => [
                'animation' => [
                    'duration' => ['number' => 0.8, 'unit' => 's'],
                    'easing' => 'power3.out'
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
        return [c(
        "container",
        "Container",
        [c(
        "background",
        "Background",
        [],
        ['type' => 'color', 'layout' => 'inline', 'colorOptions' => ['type' => 'solidAndGradient']],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\borders",
      "Borders",
      "borders",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_padding_all",
      "Padding",
      "padding",
       ['type' => 'popout']
     ), c(
        "gap",
        "Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 48, 'step' => 1]],
        false,
        false,
        [],
      )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), c(
        "progress_bar",
        "Progress Bar",
        [c(
        "height",
        "Height",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['min' => 4, 'max' => 32, 'step' => 1]],
        false,
        false,
        [],
      ), c(
        "background",
        "Background",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "fill_color",
        "Fill Color",
        [],
        ['type' => 'color', 'layout' => 'inline', 'colorOptions' => ['type' => 'solidAndGradient']],
        false,
        false,
        [],
      ), c(
        "border_radius",
        "Border Radius",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px']]],
        false,
        false,
        [],
      )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), c(
        "icon",
        "Icon",
        [c(
        "disable",
        "Disable",
        [],
        ['type' => 'toggle', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "size",
        "Size",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['min' => 16, 'max' => 64, 'step' => 1]],
        false,
        false,
        [],
      ), c(
        "color",
        "Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
      )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), c(
        "typography",
        "Typography",
        [getPresetSection(
      "EssentialElements\\typography",
      "Message",
      "message",
       ['type' => 'popout']
     ), c(
        "amount_color",
        "Amount Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "amount_weight",
        "Amount Weight",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 100, 'max' => 900, 'step' => 100]],
        false,
        false,
        [],
      )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), c(
        "animation",
        "Animation",
        [c(
        "duration",
        "Duration",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['s'], 'defaultType' => 's'], 'rangeOptions' => ['min' => 0.3, 'max' => 2, 'step' => 0.1]],
        false,
        false,
        [],
      ), c(
        "easing",
        "Easing",
        [],
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
            ['value' => 'power1.out', 'text' => 'Power1 Out'],
            ['value' => 'power2.out', 'text' => 'Power2 Out'],
            ['value' => 'power3.out', 'text' => 'Power3 Out (Default)'],
            ['value' => 'power4.out', 'text' => 'Power4 Out'],
            ['value' => 'back.out', 'text' => 'Back Out'],
            ['value' => 'elastic.out', 'text' => 'Elastic Out'],
            ['value' => 'bounce.out', 'text' => 'Bounce Out'],
        ]],
        false,
        false,
        [],
      )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Spacing",
      "spacing",
       ['type' => 'popout']
     )];
    }

    static function contentControls()
    {
        return [c(
        "messages",
        "Messages",
        [c(
        "in_progress",
        "In Progress",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => 'Add {amount} more for free shipping!', 'textOptions' => ['multiline' => false]],
        false,
        false,
        [],
      ), c(
        "achieved",
        "Achieved",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => 'You qualify for free shipping!', 'textOptions' => ['multiline' => false]],
        false,
        false,
        [],
      ), c(
        "empty_cart",
        "Empty Cart",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => 'Your cart is empty. Add {amount} to qualify for free shipping.', 'textOptions' => ['multiline' => false]],
        false,
        false,
        [],
      )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), c(
        "currency_symbol",
        "Currency Symbol",
        [],
        ['type' => 'text', 'layout' => 'inline', 'placeholder' => 'SEK'],
        false,
        false,
        [],
      )];
    }

    static function settingsControls()
    {
        return [];
    }

    static function dependencies()
    {
        return [
            '0' => [
                'title' => 'GSAP Core',
                'scripts' => [
                    'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js'
                ],
                'frontendCondition' => 'return true;',
                'builderCondition' => 'return true;'
            ]
        ];
    }

    static function settings()
    {
        return ['dependsOnGlobalScripts' => true, 'requiredPlugins' => ['WooCommerce']];
    }

    static function addPanelRules()
    {
        return false;
    }

    static public function actions()
    {
        return false;
    }

    static function nestingRule()
    {
        return ['type' => 'final'];
    }

    static function spacingBars()
    {
        return [['location' => 'outside-top', 'cssProperty' => 'margin-top', 'affectedPropertyPath' => 'design.spacing.margin_top.%%BREAKPOINT%%'], ['location' => 'outside-bottom', 'cssProperty' => 'margin-bottom', 'affectedPropertyPath' => 'design.spacing.margin_bottom.%%BREAKPOINT%%']];
    }

    static function attributes()
    {
        return false;
    }

    static function experimental()
    {
        return false;
    }

    static function availableIn()
    {
        return ['breakdance'];
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
        return [['name' => 'breakdance-woocommerce', 'template' => 'yes']];
    }

    static function projectManagement()
    {
        return false;
    }

    static function propertyPathsToWhitelistInFlatProps()
    {
        return false;
    }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return false;
    }
}
