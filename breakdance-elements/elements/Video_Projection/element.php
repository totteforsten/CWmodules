<?php

namespace BreakdanceCustomElements;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;

\Breakdance\ElementStudio\registerElementForEditing(
    "BreakdanceCustomElements\\VideoProjection",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class VideoProjection extends \Breakdance\Elements\Element
{
    static function uiIcon()
    {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><polygon points="10,8 16,11 10,14"/><line x1="2" y1="20" x2="22" y2="20"/></svg>';
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
        return 'Video Projection';
    }

    static function className()
    {
        return 'cw-video-projection';
    }

    static function category()
    {
        return 'cwelements';
    }

    static function badge()
    {
        return ['label' => 'CW', 'textColor' => '#ffffff', 'backgroundColor' => '#6366f1'];
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
                'projections' => [
                    [
                        'id' => 'projection-1',
                        'label' => 'Projection 1',
                        'mask_image' => null,
                        'video' => null,
                        'button_icon' => 'heart',
                        'theme_color' => '#1a1a2e'
                    ]
                ]
            ],
            'design' => [
                'grid' => [
                    'size' => 15,
                    'spacing' => 0.75,
                    'cube_size' => 0.5,
                    'brightness_threshold' => 128
                ],
                'camera' => [
                    'fov' => 35,
                    'distance' => 6
                ],
                'animation' => [
                    'stagger_delay' => 0.001,
                    'scale_duration' => 1,
                    'position_duration' => 1,
                    'ease_type' => 'power3.inOut'
                ],
                'wave' => [
                    'enabled' => true,
                    'speed' => 0.005,
                    'intensity' => 0.6,
                    'offset' => 0.1
                ],
                'mouse' => [
                    'enabled' => true,
                    'radius' => 2.5,
                    'intensity' => 1.5,
                    'smoothing' => 0.1
                ],
                'container' => [
                    'height' => '100vh',
                    'background_type' => 'solid',
                    'background_color' => '#1a1a2e',
                    'gradient_type' => 'linear',
                    'gradient_angle' => 135,
                    'gradient_color_1' => '#1a1a2e',
                    'gradient_color_2' => '#16213e'
                ],
                'position' => [
                    'offset_x' => 0,
                    'offset_y' => 0,
                    'scale' => 1
                ],
                'buttons' => [
                    'show_buttons' => true
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
        return file_get_contents(__DIR__ . '/css.twig');
    }

    static function designControls()
    {
        return [
            c(
                "container",
                "Container",
                [
                    c(
                        "height",
                        "Height",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        true,
                        false,
                        []
                    ),
                    c(
                        "min_height",
                        "Min Height",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        true,
                        false,
                        []
                    ),
                    c(
                        "background_type",
                        "Background Type",
                        [],
                        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
                            ['value' => 'solid', 'text' => 'Solid Color'],
                            ['value' => 'gradient', 'text' => 'Gradient']
                        ]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "background_color",
                        "Background Color",
                        [],
                        ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.container.background_type', 'operand' => 'not equals', 'value' => 'gradient']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "gradient_type",
                        "Gradient Type",
                        [],
                        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
                            ['value' => 'linear', 'text' => 'Linear'],
                            ['value' => 'radial', 'text' => 'Radial']
                        ], 'condition' => ['path' => 'design.container.background_type', 'operand' => 'equals', 'value' => 'gradient']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "gradient_angle",
                        "Gradient Angle (Linear)",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 360, 'step' => 1], 'condition' => ['path' => 'design.container.background_type', 'operand' => 'equals', 'value' => 'gradient']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "gradient_color_1",
                        "Gradient Start",
                        [],
                        ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.container.background_type', 'operand' => 'equals', 'value' => 'gradient']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "gradient_color_2",
                        "Gradient End",
                        [],
                        ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.container.background_type', 'operand' => 'equals', 'value' => 'gradient']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "border_radius",
                        "Border Radius",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        true,
                        false,
                        []
                    )
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "grid",
                "Grid Settings",
                [
                    c(
                        "size",
                        "Grid Size",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 5, 'max' => 50, 'step' => 1]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "spacing",
                        "Cube Spacing",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.5, 'max' => 3, 'step' => 0.05]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "cube_size",
                        "Cube Size",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.1, 'max' => 2, 'step' => 0.05]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "brightness_threshold",
                        "Mask Threshold",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 255, 'step' => 1]],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "camera",
                "Camera",
                [
                    c(
                        "fov",
                        "Field of View",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 15, 'max' => 90, 'step' => 1]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "distance",
                        "Camera Distance",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 2, 'max' => 20, 'step' => 0.5]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "enable_controls",
                        "Enable Orbit Controls",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "auto_rotate",
                        "Auto Rotate",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "auto_rotate_speed",
                        "Rotation Speed",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.1, 'max' => 10, 'step' => 0.1], 'condition' => ['path' => 'design.camera.auto_rotate', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "position",
                "Object Position",
                [
                    c(
                        "offset_x",
                        "Horizontal Offset",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => -5, 'max' => 5, 'step' => 0.1]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "offset_y",
                        "Vertical Offset",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => -5, 'max' => 5, 'step' => 0.1]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "scale",
                        "Scale",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.1, 'max' => 3, 'step' => 0.1]],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "lighting",
                "Lighting",
                [
                    c(
                        "ambient_intensity",
                        "Ambient Intensity",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 2, 'step' => 0.1]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "directional_intensity",
                        "Directional Intensity",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 20, 'step' => 0.5]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "light_color",
                        "Light Color",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "animation",
                "Animation",
                [
                    c(
                        "stagger_delay",
                        "Stagger Delay",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 0.01, 'step' => 0.0001]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "scale_duration",
                        "Scale Duration",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.1, 'max' => 3, 'step' => 0.1]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "position_duration",
                        "Position Duration",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.1, 'max' => 3, 'step' => 0.1]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "ease_type",
                        "Easing",
                        [],
                        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
                            ['value' => 'power2.out', 'text' => 'Power2 Out'],
                            ['value' => 'power2.inOut', 'text' => 'Power2 InOut'],
                            ['value' => 'power3.out', 'text' => 'Power3 Out'],
                            ['value' => 'power4.out', 'text' => 'Power4 Out'],
                            ['value' => 'elastic.out', 'text' => 'Elastic Out'],
                            ['value' => 'back.out', 'text' => 'Back Out'],
                            ['value' => 'bounce.out', 'text' => 'Bounce Out']
                        ]],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "wave",
                "Wave Animation",
                [
                    c(
                        "enabled",
                        "Enable Wave",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "speed",
                        "Wave Speed",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.001, 'max' => 0.02, 'step' => 0.001], 'condition' => ['path' => 'design.wave.enabled', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "intensity",
                        "Wave Intensity",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.1, 'max' => 2, 'step' => 0.1], 'condition' => ['path' => 'design.wave.enabled', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "offset",
                        "Wave Offset",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.01, 'max' => 0.5, 'step' => 0.01], 'condition' => ['path' => 'design.wave.enabled', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "mouse",
                "Mouse Interaction",
                [
                    c(
                        "enabled",
                        "Enable Mouse Effect",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "radius",
                        "Effect Radius",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.5, 'max' => 10, 'step' => 0.1], 'condition' => ['path' => 'design.mouse.enabled', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "intensity",
                        "Effect Intensity",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.1, 'max' => 5, 'step' => 0.1], 'condition' => ['path' => 'design.mouse.enabled', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "smoothing",
                        "Movement Smoothing",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.01, 'max' => 0.5, 'step' => 0.01], 'condition' => ['path' => 'design.mouse.enabled', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "buttons",
                "Navigation Buttons",
                [
                    c(
                        "show_buttons",
                        "Show Buttons",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "button_size",
                        "Button Size",
                        [],
                        ['type' => 'unit', 'layout' => 'inline', 'condition' => ['path' => 'design.buttons.show_buttons', 'operand' => 'is set', 'value' => '']],
                        true,
                        false,
                        []
                    ),
                    c(
                        "button_gap",
                        "Button Gap",
                        [],
                        ['type' => 'unit', 'layout' => 'inline', 'condition' => ['path' => 'design.buttons.show_buttons', 'operand' => 'is set', 'value' => '']],
                        true,
                        false,
                        []
                    ),
                    c(
                        "button_background",
                        "Button Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.buttons.show_buttons', 'operand' => 'is set', 'value' => '']],
                        false,
                        true,
                        []
                    ),
                    c(
                        "button_icon_color",
                        "Button Icon Color",
                        [],
                        ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.buttons.show_buttons', 'operand' => 'is set', 'value' => '']],
                        false,
                        true,
                        []
                    ),
                    c(
                        "button_position",
                        "Button Position",
                        [],
                        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
                            ['value' => 'bottom-center', 'text' => 'Bottom Center'],
                            ['value' => 'bottom-left', 'text' => 'Bottom Left'],
                            ['value' => 'bottom-right', 'text' => 'Bottom Right'],
                            ['value' => 'top-center', 'text' => 'Top Center'],
                            ['value' => 'top-left', 'text' => 'Top Left'],
                            ['value' => 'top-right', 'text' => 'Top Right']
                        ], 'condition' => ['path' => 'design.buttons.show_buttons', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            getPresetSection(
                "EssentialElements\\spacing_margin_y",
                "Spacing",
                "spacing"
            )
        ];
    }

    static function contentControls()
    {
        return [
            c(
                "projections",
                "Projections",
                [
                    c(
                        "id",
                        "ID",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "label",
                        "Label",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "mask_image",
                        "Mask Image",
                        [],
                        ['type' => 'wpmedia', 'layout' => 'vertical', 'mediaOptions' => ['acceptedFileTypes' => ['image']]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "video",
                        "Video",
                        [],
                        ['type' => 'wpmedia', 'layout' => 'vertical', 'mediaOptions' => ['acceptedFileTypes' => ['video']]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "video_url",
                        "Or Video URL",
                        [],
                        ['type' => 'url', 'layout' => 'vertical'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "button_icon",
                        "Button Icon",
                        [],
                        ['type' => 'dropdown', 'layout' => 'vertical', 'items' => [
                            ['value' => 'heart', 'text' => 'Heart'],
                            ['value' => 'star', 'text' => 'Star'],
                            ['value' => 'circle', 'text' => 'Circle'],
                            ['value' => 'square', 'text' => 'Square'],
                            ['value' => 'play', 'text' => 'Play'],
                            ['value' => 'diamond', 'text' => 'Diamond'],
                            ['value' => 'triangle', 'text' => 'Triangle'],
                            ['value' => 'custom', 'text' => 'Custom SVG']
                        ]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "custom_icon_svg",
                        "Custom SVG",
                        [],
                        ['type' => 'code', 'layout' => 'vertical', 'codeOptions' => ['language' => 'html'], 'condition' => ['path' => '%%CURRENTPATH%%.button_icon', 'operand' => 'equals', 'value' => 'custom']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "theme_type",
                        "Theme Background Type",
                        [],
                        ['type' => 'dropdown', 'layout' => 'vertical', 'items' => [
                            ['value' => 'solid', 'text' => 'Solid Color'],
                            ['value' => 'gradient', 'text' => 'Gradient']
                        ]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "theme_color",
                        "Theme Color",
                        [],
                        ['type' => 'color', 'layout' => 'vertical', 'condition' => ['path' => '%%CURRENTPATH%%.theme_type', 'operand' => 'not equals', 'value' => 'gradient']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "theme_gradient_type",
                        "Gradient Type",
                        [],
                        ['type' => 'dropdown', 'layout' => 'vertical', 'items' => [
                            ['value' => 'linear', 'text' => 'Linear'],
                            ['value' => 'radial', 'text' => 'Radial']
                        ], 'condition' => ['path' => '%%CURRENTPATH%%.theme_type', 'operand' => 'equals', 'value' => 'gradient']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "theme_gradient_angle",
                        "Gradient Angle",
                        [],
                        ['type' => 'number', 'layout' => 'vertical', 'rangeOptions' => ['min' => 0, 'max' => 360, 'step' => 1], 'condition' => ['path' => '%%CURRENTPATH%%.theme_type', 'operand' => 'equals', 'value' => 'gradient']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "theme_gradient_color_1",
                        "Gradient Start",
                        [],
                        ['type' => 'color', 'layout' => 'vertical', 'condition' => ['path' => '%%CURRENTPATH%%.theme_type', 'operand' => 'equals', 'value' => 'gradient']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "theme_gradient_color_2",
                        "Gradient End",
                        [],
                        ['type' => 'color', 'layout' => 'vertical', 'condition' => ['path' => '%%CURRENTPATH%%.theme_type', 'operand' => 'equals', 'value' => 'gradient']],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'repeater', 'layout' => 'vertical', 'repeaterOptions' => ['titleTemplate' => '{label}', 'defaultTitle' => 'Projection', 'buttonName' => 'Add Projection']],
                false,
                false,
                []
            ),
            c(
                "default_projection",
                "Default Projection",
                [],
                ['type' => 'text', 'layout' => 'vertical'],
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
        // Get the module root directory
        $module_root = dirname(dirname(__DIR__));

        return [
            '0' => [
                'title' => 'Three.js',
                'scripts' => [
                    'https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js'
                ],
                'builderCondition' => 'return true;',
                'frontendCondition' => 'return true;'
            ],
            '1' => [
                'title' => 'GSAP',
                'scripts' => [
                    'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js'
                ],
                'builderCondition' => 'return true;',
                'frontendCondition' => 'return true;'
            ],
            '2' => [
                'title' => 'OrbitControls',
                'scripts' => [
                    'https://cdn.jsdelivr.net/npm/three@0.134.0/examples/js/controls/OrbitControls.js'
                ],
                'builderCondition' => '{% if design.camera.enable_controls %}return true;{% else %}return false;{% endif %}',
                'frontendCondition' => '{% if design.camera.enable_controls %}return true;{% else %}return false;{% endif %}'
            ],
            '3' => [
                'title' => 'Video Projection',
                'scripts' => [
                    plugins_url('elements/Video_Projection/js/video-projection.js', $module_root . '/breakdance-elements.php')
                ],
                'inlineScripts' => [
                    '(function() {
                        if (!window.CWVideoProjection) {
                            console.warn("CWVideoProjection not loaded");
                            return;
                        }

                        window.CWVideoProjection.init(
                            "%%SELECTOR%%",
                            %%ID%%,
                            {
                                projections: {{ content.projections|json_encode|raw }},
                                defaultProjection: "{{ content.default_projection ?? content.projections[0].id ?? \'projection-1\' }}",
                                grid: {
                                    size: {{ design.grid.size ?? 15 }},
                                    spacing: {{ design.grid.spacing ?? 1.1 }},
                                    cubeSize: {{ design.grid.cube_size ?? 1 }},
                                    brightnessThreshold: {{ design.grid.brightness_threshold ?? 128 }}
                                },
                                camera: {
                                    fov: {{ design.camera.fov ?? 35 }},
                                    distance: {{ design.camera.distance ?? 6 }},
                                    enableControls: {{ design.camera.enable_controls ? \'true\' : \'false\' }},
                                    autoRotate: {{ design.camera.auto_rotate ? \'true\' : \'false\' }},
                                    autoRotateSpeed: {{ design.camera.auto_rotate_speed ?? 2 }}
                                },
                                lighting: {
                                    ambientIntensity: {{ design.lighting.ambient_intensity ?? 1 }},
                                    directionalIntensity: {{ design.lighting.directional_intensity ?? 10 }},
                                    lightColor: "{{ design.lighting.light_color ?? \'#ffffff\' }}"
                                },
                                animation: {
                                    staggerDelay: {{ design.animation.stagger_delay ?? 0.001 }},
                                    scaleDuration: {{ design.animation.scale_duration ?? 1 }},
                                    positionDuration: {{ design.animation.position_duration ?? 1 }},
                                    easeType: "{{ design.animation.ease_type ?? \'power3.inOut\' }}"
                                },
                                wave: {
                                    enabled: {{ design.wave.enabled ? \'true\' : \'false\' }},
                                    speed: {{ design.wave.speed ?? 0.005 }},
                                    intensity: {{ design.wave.intensity ?? 0.6 }},
                                    offset: {{ design.wave.offset ?? 0.1 }}
                                },
                                mouse: {
                                    enabled: {{ design.mouse.enabled ? \'true\' : \'false\' }},
                                    radius: {{ design.mouse.radius ?? 2.5 }},
                                    intensity: {{ design.mouse.intensity ?? 1.5 }},
                                    smoothing: {{ design.mouse.smoothing ?? 0.1 }}
                                },
                                position: {
                                    offsetX: {{ design.position.offset_x ?? 0 }},
                                    offsetY: {{ design.position.offset_y ?? 0 }},
                                    scale: {{ design.position.scale ?? 1 }}
                                },
                                showButtons: {{ design.buttons.show_buttons ? \'true\' : \'false\' }}
                            }
                        );
                    })();'
                ],
                'builderCondition' => 'return true;',
                'frontendCondition' => 'return true;'
            ]
        ];
    }

    static function settings()
    {
        return false;
    }

    static function addPanelRules()
    {
        return false;
    }

    static public function actions()
    {
        return [
            'onPropertyChange' => [
                [
                    'script' => '
                        if (window.CWVideoProjection) {
                            window.CWVideoProjection.update(
                                %%ID%%,
                                {
                                    projections: {{ content.projections|json_encode|raw }},
                                    defaultProjection: "{{ content.default_projection ?? content.projections[0].id ?? \'projection-1\' }}",
                                    grid: {
                                        size: {{ design.grid.size ?? 15 }},
                                        spacing: {{ design.grid.spacing ?? 1.1 }},
                                        cubeSize: {{ design.grid.cube_size ?? 1 }},
                                        brightnessThreshold: {{ design.grid.brightness_threshold ?? 128 }}
                                    },
                                    camera: {
                                        fov: {{ design.camera.fov ?? 35 }},
                                        distance: {{ design.camera.distance ?? 6 }},
                                        enableControls: {{ design.camera.enable_controls ? \'true\' : \'false\' }},
                                        autoRotate: {{ design.camera.auto_rotate ? \'true\' : \'false\' }},
                                        autoRotateSpeed: {{ design.camera.auto_rotate_speed ?? 2 }}
                                    },
                                    lighting: {
                                        ambientIntensity: {{ design.lighting.ambient_intensity ?? 1 }},
                                        directionalIntensity: {{ design.lighting.directional_intensity ?? 10 }},
                                        lightColor: "{{ design.lighting.light_color ?? \'#ffffff\' }}"
                                    },
                                    animation: {
                                        staggerDelay: {{ design.animation.stagger_delay ?? 0.001 }},
                                        scaleDuration: {{ design.animation.scale_duration ?? 1 }},
                                        positionDuration: {{ design.animation.position_duration ?? 1 }},
                                        easeType: "{{ design.animation.ease_type ?? \'power3.inOut\' }}"
                                    },
                                    wave: {
                                        enabled: {{ design.wave.enabled ? \'true\' : \'false\' }},
                                        speed: {{ design.wave.speed ?? 0.005 }},
                                        intensity: {{ design.wave.intensity ?? 0.6 }},
                                        offset: {{ design.wave.offset ?? 0.1 }}
                                    },
                                    mouse: {
                                        enabled: {{ design.mouse.enabled ? \'true\' : \'false\' }},
                                        radius: {{ design.mouse.radius ?? 2.5 }},
                                        intensity: {{ design.mouse.intensity ?? 1.5 }},
                                        smoothing: {{ design.mouse.smoothing ?? 0.1 }}
                                    },
                                    position: {
                                        offsetX: {{ design.position.offset_x ?? 0 }},
                                        offsetY: {{ design.position.offset_y ?? 0 }},
                                        scale: {{ design.position.scale ?? 1 }}
                                    },
                                    showButtons: {{ design.buttons.show_buttons ? \'true\' : \'false\' }}
                                }
                            );
                        }
                    '
                ]
            ],
            'onBeforeDeletingElement' => [
                [
                    'script' => '
                        if (window.CWVideoProjection) {
                            window.CWVideoProjection.destroy(%%ID%%);
                        }
                    '
                ]
            ],
            'onMountedElement' => [
                [
                    'script' => '
                        if (window.CWVideoProjection) {
                            window.CWVideoProjection.init(
                                "%%SELECTOR%%",
                                %%ID%%,
                                {
                                    projections: {{ content.projections|json_encode|raw }},
                                    defaultProjection: "{{ content.default_projection ?? content.projections[0].id ?? \'projection-1\' }}",
                                    grid: {
                                        size: {{ design.grid.size ?? 15 }},
                                        spacing: {{ design.grid.spacing ?? 1.1 }},
                                        cubeSize: {{ design.grid.cube_size ?? 1 }},
                                        brightnessThreshold: {{ design.grid.brightness_threshold ?? 128 }}
                                    },
                                    camera: {
                                        fov: {{ design.camera.fov ?? 35 }},
                                        distance: {{ design.camera.distance ?? 6 }},
                                        enableControls: {{ design.camera.enable_controls ? \'true\' : \'false\' }},
                                        autoRotate: {{ design.camera.auto_rotate ? \'true\' : \'false\' }},
                                        autoRotateSpeed: {{ design.camera.auto_rotate_speed ?? 2 }}
                                    },
                                    lighting: {
                                        ambientIntensity: {{ design.lighting.ambient_intensity ?? 1 }},
                                        directionalIntensity: {{ design.lighting.directional_intensity ?? 10 }},
                                        lightColor: "{{ design.lighting.light_color ?? \'#ffffff\' }}"
                                    },
                                    animation: {
                                        staggerDelay: {{ design.animation.stagger_delay ?? 0.001 }},
                                        scaleDuration: {{ design.animation.scale_duration ?? 1 }},
                                        positionDuration: {{ design.animation.position_duration ?? 1 }},
                                        easeType: "{{ design.animation.ease_type ?? \'power3.inOut\' }}"
                                    },
                                    wave: {
                                        enabled: {{ design.wave.enabled ? \'true\' : \'false\' }},
                                        speed: {{ design.wave.speed ?? 0.005 }},
                                        intensity: {{ design.wave.intensity ?? 0.6 }},
                                        offset: {{ design.wave.offset ?? 0.1 }}
                                    },
                                    mouse: {
                                        enabled: {{ design.mouse.enabled ? \'true\' : \'false\' }},
                                        radius: {{ design.mouse.radius ?? 2.5 }},
                                        intensity: {{ design.mouse.intensity ?? 1.5 }},
                                        smoothing: {{ design.mouse.smoothing ?? 0.1 }}
                                    },
                                    position: {
                                        offsetX: {{ design.position.offset_x ?? 0 }},
                                        offsetY: {{ design.position.offset_y ?? 0 }},
                                        scale: {{ design.position.scale ?? 1 }}
                                    },
                                    showButtons: {{ design.buttons.show_buttons ? \'true\' : \'false\' }}
                                }
                            );
                        }
                    '
                ]
            ]
        ];
    }

    static function nestingRule()
    {
        return ['type' => 'final'];
    }

    static function spacingBars()
    {
        return [];
    }

    static function attributes()
    {
        return [];
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
        return [
            'design.container.height',
            'design.container.min_height',
            'design.container.background_color',
            'design.buttons.button_size',
            'design.buttons.button_gap',
            'design.buttons.button_position'
        ];
    }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return false;
    }
}
