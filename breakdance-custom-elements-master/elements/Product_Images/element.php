<?php

namespace BreakdanceCustomElements;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;


\Breakdance\ElementStudio\registerElementForEditing(
    "BreakdanceCustomElements\\ProductImages",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class ProductImages extends \Breakdance\Elements\Element
{
    static function uiIcon()
    {
        return '<svg aria-hidden="true" focusable="false"   class="svg-inline--fa fa-images" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M464 448H96c-35.35 0-64-28.65-64-64V112C32 103.2 24.84 96 16 96S0 103.2 0 112V384c0 53.02 42.98 96 96 96h368c8.836 0 16-7.164 16-16S472.8 448 464 448zM224 152c13.26 0 24-10.75 24-24s-10.74-24-24-24c-13.25 0-24 10.75-24 24S210.8 152 224 152zM410.6 139.9c-11.28-15.81-38.5-15.94-49.1-.0313l-44.03 61.43l-6.969-8.941c-11.44-14.46-36.97-14.56-48.4 .0313L198.2 272.8C191 281.9 190 294.3 195.5 304.3C200.8 313.1 211.1 320 222.4 320h259.2c11 0 21.17-5.805 26.54-15.09c0-.0313-.0313 .0313 0 0c5.656-9.883 5.078-21.84-1.578-31.15L410.6 139.9zM226.2 287.9l58.25-75.61l20.09 25.66c4.348 5.545 17.6 10.65 25.59-.5332l54.44-78.75l92.68 129.2H226.2zM512 32H160c-35.35 0-64 28.65-64 64v224c0 35.35 28.65 64 64 64H512c35.35 0 64-28.65 64-64V96C576 60.65 547.3 32 512 32zM544 320c0 17.64-14.36 32-32 32H160c-17.64 0-32-14.36-32-32V96c0-17.64 14.36-32 32-32h352c17.64 0 32 14.36 32 32V320z"></path></svg>';
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
        return 'Product Images';
    }

    static function className()
    {
        return 'bde-wooproductimages';
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
            'design' => [
                'animation' => [
                    'duration' => ['number' => 1.2, 'unit' => 's'],
                    'easing' => 'power3.inOut',
                    'clippath_type' => 'circle'
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
        "animation",
        "Animation",
        [c(
        "duration",
        "Duration",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['s'], 'defaultType' => 's'], 'rangeOptions' => ['min' => 0.3, 'max' => 3, 'step' => 0.1]],
        false,
        false,
        [],

      ), c(
        "easing",
        "Easing",
        [],
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
            ['value' => 'power1.inOut', 'text' => 'Power1 In-Out'],
            ['value' => 'power2.inOut', 'text' => 'Power2 In-Out'],
            ['value' => 'power3.inOut', 'text' => 'Power3 In-Out (Default)'],
            ['value' => 'power4.inOut', 'text' => 'Power4 In-Out'],
            ['value' => 'back.inOut', 'text' => 'Back In-Out'],
            ['value' => 'elastic.out', 'text' => 'Elastic Out'],
            ['value' => 'bounce.out', 'text' => 'Bounce Out'],
            ['value' => 'circ.inOut', 'text' => 'Circular In-Out'],
            ['value' => 'expo.inOut', 'text' => 'Exponential In-Out'],
        ]],
        false,
        false,
        [],

      ), c(
        "clippath_type",
        "Clip Path Type",
        [],
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
            ['value' => 'circle', 'text' => 'Circle (Center)'],
            ['value' => 'circle-top-left', 'text' => 'Circle (Top Left)'],
            ['value' => 'circle-top-right', 'text' => 'Circle (Top Right)'],
            ['value' => 'circle-bottom-left', 'text' => 'Circle (Bottom Left)'],
            ['value' => 'circle-bottom-right', 'text' => 'Circle (Bottom Right)'],
            ['value' => 'ellipse-h', 'text' => 'Ellipse Horizontal'],
            ['value' => 'ellipse-v', 'text' => 'Ellipse Vertical'],
            ['value' => 'polygon-center', 'text' => 'Polygon (Square)'],
            ['value' => 'polygon-diamond', 'text' => 'Polygon (Diamond)'],
            ['value' => 'inset-h', 'text' => 'Inset Horizontal'],
            ['value' => 'inset-v', 'text' => 'Inset Vertical'],
        ]],
        false,
        false,
        [],

      )],
        ['type' => 'section'],
        false,
        false,
        [],

      ), c(
        "size",
        "Size",
        [c(
        "width",
        "Width",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        true,
        false,
        [],

      )],
        ['type' => 'section'],
        false,
        false,
        [],

      ), c(
        "sale_badge",
        "Sale Badge",
        [c(
        "disable",
        "Disable",
        [],
        ['type' => 'toggle', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "position",
        "Position",
        [],
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [['value' => 'top-left', 'text' => 'Top Left'], ['text' => 'Top Right', 'value' => 'top-right']]],
        true,
        false,
        [],
        
      ), c(
        "background",
        "Background",
        [],
        ['type' => 'color', 'layout' => 'inline', 'colorOptions' => ['type' => 'solidAndGradient']],
        false,
        false,
        [],
        
      ), getPresetSection(
      "EssentialElements\\typography",
      "Typography",
      "typography",
       ['type' => 'popout']
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
        "nudge",
        "Nudge",
        [c(
        "x",
        "X",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['min' => -48, 'max' => 48, 'step' => 1]],
        true,
        false,
        [],
        
      ), c(
        "y",
        "Y",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['min' => -48, 'max' => 48, 'step' => 1]],
        true,
        false,
        [],
        
      )],
        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "zoom_icon",
        "Zoom Icon",
        [c(
        "disable",
        "Disable",
        [],
        ['type' => 'toggle', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "position",
        "Position",
        [],
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [['value' => 'top-left', 'text' => 'Top Left'], ['text' => 'Top Right', 'value' => 'top-right']]],
        true,
        false,
        [],
        
      ), c(
        "background",
        "Background",
        [],
        ['type' => 'color', 'layout' => 'inline', 'colorOptions' => ['type' => 'solidAndGradient']],
        false,
        true,
        [],
        
      ), c(
        "icon",
        "Icon",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        true,
        [],
        
      ), c(
        "icon_size",
        "Icon Size",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['min' => 20, 'max' => 120, 'step' => 1]],
        false,
        false,
        [],
        
      ), c(
        "padding",
        "Padding",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['min' => 20, 'max' => 120, 'step' => 1]],
        false,
        false,
        [],
        
      ), getPresetSection(
      "EssentialElements\\borders",
      "Borders",
      "borders",
       ['type' => 'popout']
     ), c(
        "nudge",
        "Nudge",
        [c(
        "x",
        "X",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['min' => -48, 'max' => 48, 'step' => 1]],
        true,
        false,
        [],
        
      ), c(
        "y",
        "Y",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['min' => -48, 'max' => 48, 'step' => 1]],
        true,
        false,
        [],
        
      )],
        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "image",
        "Image",
        [getPresetSection(
      "EssentialElements\\borders",
      "Borders",
      "borders",
       ['type' => 'popout']
     )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "thumbnails",
        "Thumbnails",
        [c(
        "per_row",
        "Per Row",
        [],
        ['type' => 'dropdown', 'layout' => 'inline', 'rangeOptions' => ['min' => 2, 'max' => 6, 'step' => 1], 'items' => [['value' => '2', 'text' => '2'], ['text' => '3', 'value' => '3'], ['text' => '4', 'value' => '4'], ['text' => '5', 'value' => '5'], ['text' => '6', 'value' => '6']]],
        false,
        false,
        [],
        
      ), c(
        "transition_duration",
        "Transition Duration",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['ms'], 'defaultType' => 'ms'], 'rangeOptions' => ['min' => 0, 'max' => 1000, 'step' => 10]],
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
        
      ), c(
        "border_color",
        "Border Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "border_width",
        "Border Width",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "opacity",
        "Opacity",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 1, 'step' => 0.1]],
        false,
        false,
        [],
        
      ), c(
        "hover",
        "Hover",
        [c(
        "border_color",
        "Border Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "opacity",
        "Opacity",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 1, 'step' => 0.1]],
        false,
        false,
        [],
        
      )],
        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
        
      ), c(
        "active",
        "Active",
        [c(
        "border_color",
        "Border Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "opacity",
        "Opacity",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 1, 'step' => 0.1]],
        false,
        false,
        [],
        
      )],
        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "spacing",
        "Spacing",
        [c(
        "between_images",
        "Between Images",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 64, 'step' => 1]],
        true,
        false,
        [],
        
      ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Container",
      "container",
       ['type' => 'popout']
     )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      )];
    }

    static function contentControls()
    {
        return [];
    }

    static function settingsControls()
    {
        return [];
    }

    static function dependencies()
    {
        $plugin_root = dirname(dirname(dirname(__FILE__)));

        return [
            '0' => [
                'title' => 'GSAP Core',
                'scripts' => [
                    'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js'
                ],
                'frontendCondition' => 'return true;',
                'builderCondition' => 'return true;'
            ],
            '1' => [
                'title' => 'Modern Gallery Scripts',
                'scripts' => [
                    plugins_url('elements/Product_Images/js/modern-gallery.js', $plugin_root . '/plugin.php')
                ],
                'frontendCondition' => 'return true;',
                'builderCondition' => 'return true;'
            ],
        ];
    }

    static function settings()
    {
        return ['dependsOnGlobalScripts' => true, 'bypassPointerEvents' => true, 'requiredPlugins' => ['WooCommerce']];
    }

    static function addPanelRules()
    {
        return false;
    }

    static public function actions()
    {
        return [
            'onMountedElement' => [
                ['script' => '
                    if (window.BreakdanceModernGallery) {
                        window.BreakdanceModernGallery.init("%%SELECTOR%%", %%ID%%);
                    }
                '],
            ],
            'onPropertyChange' => [
                ['script' => '
                    if (window.BreakdanceModernGallery) {
                        window.BreakdanceModernGallery.update(%%ID%%);
                    }
                '],
            ],
            'onBeforeDeletingElement' => [
                ['script' => '
                    if (window.BreakdanceModernGallery) {
                        window.BreakdanceModernGallery.destroy(%%ID%%);
                    }
                '],
            ],
        ];
    }

    static function nestingRule()
    {
        return ['type' => 'final', 'restrictedToBeADescendantOf' => ['EssentialElements\Productbuilder']];
    }

    static function spacingBars()
    {
        return [['location' => 'outside-top', 'cssProperty' => 'margin-top', 'affectedPropertyPath' => 'design.spacing.container.margin_top.%%BREAKPOINT%%'], ['location' => 'outside-bottom', 'cssProperty' => 'margin-bottom', 'affectedPropertyPath' => 'design.spacing.container.margin_bottom.%%BREAKPOINT%%']];
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
        return 50;
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
        return ['none', 'design.size.width'];
    }
}
