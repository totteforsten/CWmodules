<?php

namespace BreakdanceCustomElements;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;

\Breakdance\ElementStudio\registerElementForEditing(
    "BreakdanceCustomElements\\ImageReveal",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class ImageReveal extends \Breakdance\Elements\Element
{
    static function uiIcon()
    {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>';
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
        return 'Image Reveal';
    }

    static function className()
    {
        return 'cw-image-reveal';
    }

    static function category()
    {
        return 'cwelements';
    }

    static function badge()
    {
        return ['label' => 'CW', 'textColor' => '#ffffff', 'backgroundColor' => '#8b5cf6'];
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

    static function cssTemplate()
    {
        return file_get_contents(__DIR__ . '/css.twig');
    }

    static function defaultProperties()
    {
        return [
            'content' => [
                'mode' => 'single',
                'image' => null,
                'images' => [],
                'trigger' => 'hover',
                'scroll_out' => true,
                'auto_play_delay' => 1000,
                'loop' => false,
                'loop_delay' => 2000,
                'gallery' => [
                    'autoplay' => false,
                    'autoplay_speed' => 4000,
                    'pause_on_hover' => true,
                    'transition_style' => 'dissolve'
                ]
            ],
            'design' => [
                'layout' => [
                    'width' => '100%',
                    'max_width' => '500px',
                    'height' => '600px',
                    'aspect_ratio' => 'custom',
                    'object_fit' => 'cover'
                ],
                'appearance' => [
                    'border_radius' => '16px',
                    'background_color' => '#0a0a0f',
                    'overflow' => 'hidden'
                ],
                'effect' => [
                    'preset' => 'organic',
                    'noise_scale' => 5,
                    'radial_intensity' => 12.5,
                    'radial_offset' => 7,
                    'wave_enabled' => true,
                    'wave_intensity' => 0.3,
                    'wave_frequency' => 20,
                    'wave_speed' => 5
                ],
                'animation' => [
                    'duration' => 1.5,
                    'ease_type' => 'power3.inOut',
                    'time_speed' => 0.1,
                    'reveal_delay' => 0
                ],
                'navigation' => [
                    'show_dots' => true,
                    'show_arrows' => false,
                    'dots_position' => 'bottom-center',
                    'dots_size' => '10px',
                    'dots_gap' => '8px',
                    'dots_color' => 'rgba(255,255,255,0.4)',
                    'dots_active_color' => '#ffffff',
                    'arrows_size' => '40px',
                    'arrows_color' => '#ffffff',
                    'arrows_background' => 'rgba(0,0,0,0.3)'
                ]
            ]
        ];
    }

    static function defaultChildren()
    {
        return false;
    }

    static function cssIdSelectors()
    {
        return [];
    }

    static function nestingRule()
    {
        return ["type" => "final"];
    }

    static function designControls()
    {
        return [
            // Layout Section
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
                        []
                    ),
                    c(
                        "max_width",
                        "Max Width",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        true,
                        false,
                        []
                    ),
                    c(
                        "aspect_ratio",
                        "Aspect Ratio",
                        [],
                        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
                            ['value' => 'custom', 'text' => 'Custom Height'],
                            ['value' => '1/1', 'text' => '1:1 Square'],
                            ['value' => '4/3', 'text' => '4:3'],
                            ['value' => '3/4', 'text' => '3:4 Portrait'],
                            ['value' => '16/9', 'text' => '16:9 Widescreen'],
                            ['value' => '9/16', 'text' => '9:16 Vertical'],
                            ['value' => '21/9', 'text' => '21:9 Ultrawide'],
                            ['value' => '3/2', 'text' => '3:2'],
                            ['value' => '2/3', 'text' => '2:3']
                        ]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "height",
                        "Height",
                        [],
                        ['type' => 'unit', 'layout' => 'inline', 'condition' => ['path' => 'design.layout.aspect_ratio', 'operand' => 'equals', 'value' => 'custom']],
                        true,
                        false,
                        []
                    ),
                    c(
                        "object_fit",
                        "Image Fit",
                        [],
                        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
                            ['value' => 'cover', 'text' => 'Cover'],
                            ['value' => 'contain', 'text' => 'Contain'],
                            ['value' => 'fill', 'text' => 'Fill']
                        ]],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section', 'layout' => 'vertical', 'sectionOptions' => ['type' => 'popout']],
                false,
                false,
                []
            ),

            // Appearance Section
            c(
                "appearance",
                "Appearance",
                [
                    c(
                        "border_radius",
                        "Border Radius",
                        [],
                        ['type' => 'unit', 'layout' => 'inline'],
                        true,
                        false,
                        []
                    ),
                    c(
                        "background_color",
                        "Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "box_shadow",
                        "Box Shadow",
                        [],
                        ['type' => 'shadow', 'layout' => 'vertical'],
                        true,
                        false,
                        []
                    )
                ],
                ['type' => 'section', 'layout' => 'vertical', 'sectionOptions' => ['type' => 'popout']],
                false,
                false,
                []
            ),

            // Reveal Effect Section
            c(
                "effect",
                "Reveal Effect",
                [
                    c(
                        "preset",
                        "Effect Preset",
                        [],
                        ['type' => 'dropdown', 'layout' => 'vertical', 'items' => [
                            ['value' => 'organic', 'text' => 'Organic Dissolve'],
                            ['value' => 'circular', 'text' => 'Circular Reveal'],
                            ['value' => 'wave', 'text' => 'Wave Sweep'],
                            ['value' => 'noise', 'text' => 'Noise Fade'],
                            ['value' => 'custom', 'text' => 'Custom']
                        ]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "noise_scale",
                        "Noise Scale",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 1, 'max' => 20, 'step' => 0.5], 'condition' => ['path' => 'design.effect.preset', 'operand' => 'equals', 'value' => 'custom']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "radial_intensity",
                        "Radial Intensity",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 1, 'max' => 30, 'step' => 0.5], 'condition' => ['path' => 'design.effect.preset', 'operand' => 'equals', 'value' => 'custom']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "radial_offset",
                        "Radial Offset",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 1, 'max' => 15, 'step' => 0.5], 'condition' => ['path' => 'design.effect.preset', 'operand' => 'equals', 'value' => 'custom']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "wave_enabled",
                        "Enable Wave",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "wave_intensity",
                        "Wave Intensity",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 2, 'step' => 0.05], 'condition' => ['path' => 'design.effect.wave_enabled', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "wave_frequency",
                        "Wave Frequency",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 5, 'max' => 50, 'step' => 1], 'condition' => ['path' => 'design.effect.wave_enabled', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "wave_speed",
                        "Wave Speed",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 1, 'max' => 15, 'step' => 0.5], 'condition' => ['path' => 'design.effect.wave_enabled', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section', 'layout' => 'vertical', 'sectionOptions' => ['type' => 'popout']],
                false,
                false,
                []
            ),

            // Animation Section
            c(
                "animation",
                "Animation",
                [
                    c(
                        "duration",
                        "Duration",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.3, 'max' => 5, 'step' => 0.1]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "ease_type",
                        "Easing",
                        [],
                        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
                            ['value' => 'power1.inOut', 'text' => 'Smooth'],
                            ['value' => 'power2.inOut', 'text' => 'Medium'],
                            ['value' => 'power3.inOut', 'text' => 'Pronounced'],
                            ['value' => 'power4.inOut', 'text' => 'Strong'],
                            ['value' => 'expo.inOut', 'text' => 'Exponential'],
                            ['value' => 'circ.inOut', 'text' => 'Circular'],
                            ['value' => 'sine.inOut', 'text' => 'Sine'],
                            ['value' => 'elastic.out(1, 0.3)', 'text' => 'Elastic'],
                            ['value' => 'back.inOut(1.7)', 'text' => 'Back']
                        ]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "reveal_delay",
                        "Reveal Delay (ms)",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 2000, 'step' => 100]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "time_speed",
                        "Noise Animation Speed",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 0.5, 'step' => 0.01]],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section', 'layout' => 'vertical', 'sectionOptions' => ['type' => 'popout']],
                false,
                false,
                []
            ),

            // Navigation Section (for gallery mode)
            c(
                "navigation",
                "Gallery Navigation",
                [
                    c(
                        "show_dots",
                        "Show Dots",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "dots_position",
                        "Dots Position",
                        [],
                        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
                            ['value' => 'bottom-center', 'text' => 'Bottom Center'],
                            ['value' => 'bottom-left', 'text' => 'Bottom Left'],
                            ['value' => 'bottom-right', 'text' => 'Bottom Right'],
                            ['value' => 'top-center', 'text' => 'Top Center'],
                            ['value' => 'outside-bottom', 'text' => 'Outside Bottom']
                        ], 'condition' => ['path' => 'design.navigation.show_dots', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "dots_size",
                        "Dots Size",
                        [],
                        ['type' => 'unit', 'layout' => 'inline', 'condition' => ['path' => 'design.navigation.show_dots', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "dots_gap",
                        "Dots Gap",
                        [],
                        ['type' => 'unit', 'layout' => 'inline', 'condition' => ['path' => 'design.navigation.show_dots', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "dots_color",
                        "Dots Color",
                        [],
                        ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.navigation.show_dots', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "dots_active_color",
                        "Dots Active",
                        [],
                        ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.navigation.show_dots', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "show_arrows",
                        "Show Arrows",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "arrows_size",
                        "Arrows Size",
                        [],
                        ['type' => 'unit', 'layout' => 'inline', 'condition' => ['path' => 'design.navigation.show_arrows', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "arrows_color",
                        "Arrows Color",
                        [],
                        ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.navigation.show_arrows', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "arrows_background",
                        "Arrows Background",
                        [],
                        ['type' => 'color', 'layout' => 'inline', 'condition' => ['path' => 'design.navigation.show_arrows', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section', 'layout' => 'vertical', 'sectionOptions' => ['type' => 'popout']],
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
            // Mode Selection
            c(
                "mode",
                "Mode",
                [],
                ['type' => 'button_bar', 'layout' => 'vertical', 'items' => [
                    ['value' => 'single', 'text' => 'Single Image'],
                    ['value' => 'gallery', 'text' => 'Gallery']
                ]],
                false,
                false,
                []
            ),

            // Single Image
            c(
                "image",
                "Image",
                [],
                ['type' => 'wpmedia', 'layout' => 'vertical', 'mediaOptions' => ['acceptedFileTypes' => ['image']], 'condition' => ['path' => 'content.mode', 'operand' => 'not equals', 'value' => 'gallery']],
                false,
                false,
                []
            ),

            // Gallery Images
            c(
                "images",
                "Gallery Images",
                [
                    c(
                        "image",
                        "Image",
                        [],
                        ['type' => 'wpmedia', 'layout' => 'vertical', 'mediaOptions' => ['acceptedFileTypes' => ['image']]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "alt",
                        "Alt Text",
                        [],
                        ['type' => 'text', 'layout' => 'vertical'],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'repeater', 'layout' => 'vertical', 'repeaterOptions' => ['titleTemplate' => 'Image {itemNumber}', 'defaultTitle' => 'Image', 'buttonName' => 'Add Image'], 'condition' => ['path' => 'content.mode', 'operand' => 'equals', 'value' => 'gallery']],
                false,
                false,
                []
            ),

            // Trigger Options
            c(
                "trigger_section",
                "Trigger",
                [
                    c(
                        "trigger",
                        "Reveal On",
                        [],
                        ['type' => 'dropdown', 'layout' => 'vertical', 'items' => [
                            ['value' => 'hover', 'text' => 'Hover'],
                            ['value' => 'click', 'text' => 'Click to Toggle'],
                            ['value' => 'scroll', 'text' => 'Scroll Into View'],
                            ['value' => 'auto', 'text' => 'Auto Play']
                        ]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "scroll_out",
                        "Hide on Scroll Out",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline', 'condition' => ['path' => 'content.trigger_section.trigger', 'operand' => 'equals', 'value' => 'scroll']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "scroll_threshold",
                        "Scroll Threshold (%)",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 10, 'max' => 90, 'step' => 5], 'condition' => ['path' => 'content.trigger_section.trigger', 'operand' => 'equals', 'value' => 'scroll']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "auto_play_delay",
                        "Initial Delay (ms)",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 5000, 'step' => 100], 'condition' => ['path' => 'content.trigger_section.trigger', 'operand' => 'equals', 'value' => 'auto']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "loop",
                        "Loop Animation",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline', 'condition' => ['path' => 'content.trigger_section.trigger', 'operand' => 'equals', 'value' => 'auto']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "loop_delay",
                        "Loop Delay (ms)",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 500, 'max' => 10000, 'step' => 100], 'condition' => ['path' => 'content.trigger_section.loop', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                []
            ),

            // Gallery Options
            c(
                "gallery",
                "Gallery Options",
                [
                    c(
                        "autoplay",
                        "Auto Advance",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        "autoplay_speed",
                        "Slide Duration (ms)",
                        [],
                        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 1000, 'max' => 10000, 'step' => 500], 'condition' => ['path' => 'content.gallery.autoplay', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "pause_on_hover",
                        "Pause on Hover",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline', 'condition' => ['path' => 'content.gallery.autoplay', 'operand' => 'is set', 'value' => '']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "transition_style",
                        "Transition",
                        [],
                        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [
                            ['value' => 'dissolve', 'text' => 'Dissolve'],
                            ['value' => 'morph', 'text' => 'Morph'],
                            ['value' => 'fade', 'text' => 'Fade Through']
                        ]],
                        false,
                        false,
                        []
                    ),
                    c(
                        "loop_gallery",
                        "Loop Gallery",
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    )
                ],
                ['type' => 'section', 'layout' => 'vertical', 'condition' => ['path' => 'content.mode', 'operand' => 'equals', 'value' => 'gallery']],
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
                    'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js'
                ],
                'builderCondition' => 'return true;',
                'frontendCondition' => 'return true;'
            ],
            '2' => [
                'title' => 'Image Reveal',
                'scripts' => [
                    plugins_url('elements/Image_Reveal/js/image-reveal.js', $module_root . '/breakdance-elements.php')
                ],
                'inlineScripts' => [
                    '(function() {
                        if (!window.CWImageReveal) {
                            console.warn("CWImageReveal not loaded");
                            return;
                        }

                        {% set mode = content.mode ?? "single" %}
                        {% set trigger = content.trigger_section.trigger ?? content.trigger ?? "hover" %}

                        {% if mode == "gallery" %}
                            {% set imageUrls = [] %}
                            {% for item in content.images %}
                                {% set url = item.image.url ?? item.image.sizes.full.url ?? item.image.rawUrl ?? "" %}
                                {% if url %}
                                    {% set imageUrls = imageUrls|merge([url]) %}
                                {% endif %}
                            {% endfor %}
                        {% else %}
                            {% set singleUrl = content.image.url ?? content.image.sizes.full.url ?? content.image.rawUrl ?? "" %}
                            {% set imageUrls = singleUrl ? [singleUrl] : [] %}
                        {% endif %}

                        CWImageReveal.init(
                            "%%SELECTOR%%",
                            "%%ID%%",
                            {
                                mode: "{{ mode }}",
                                images: {{ imageUrls|json_encode|raw }},
                                trigger: "{{ trigger }}",
                                scrollOut: {{ content.trigger_section.scroll_out ?? content.scroll_out ? "true" : "false" }},
                                scrollThreshold: {{ content.trigger_section.scroll_threshold ?? 30 }} / 100,
                                autoPlayDelay: {{ content.trigger_section.auto_play_delay ?? content.auto_play_delay ?? 1000 }},
                                loop: {{ content.trigger_section.loop ?? content.loop ? "true" : "false" }},
                                loopDelay: {{ content.trigger_section.loop_delay ?? content.loop_delay ?? 2000 }},
                                gallery: {
                                    autoplay: {{ content.gallery.autoplay ? "true" : "false" }},
                                    autoplaySpeed: {{ content.gallery.autoplay_speed ?? 4000 }},
                                    pauseOnHover: {{ content.gallery.pause_on_hover ? "true" : "false" }},
                                    transitionStyle: "{{ content.gallery.transition_style ?? "dissolve" }}",
                                    loopGallery: {{ content.gallery.loop_gallery ? "true" : "false" }}
                                },
                                effect: {
                                    preset: "{{ design.effect.preset ?? "organic" }}",
                                    noiseScale: {{ design.effect.noise_scale ?? 5 }},
                                    radialIntensity: {{ design.effect.radial_intensity ?? 12.5 }},
                                    radialOffset: {{ design.effect.radial_offset ?? 7 }},
                                    waveEnabled: {{ design.effect.wave_enabled ? "true" : "false" }},
                                    waveIntensity: {{ design.effect.wave_intensity ?? 0.05 }},
                                    waveFrequency: {{ design.effect.wave_frequency ?? 15 }},
                                    waveSpeed: {{ design.effect.wave_speed ?? 8 }}
                                },
                                animation: {
                                    duration: {{ design.animation.duration ?? 1.5 }},
                                    easeType: "{{ design.animation.ease_type ?? "power3.inOut" }}",
                                    timeSpeed: {{ design.animation.time_speed ?? 0.1 }},
                                    revealDelay: {{ design.animation.reveal_delay ?? 0 }}
                                },
                                navigation: {
                                    showDots: {{ design.navigation.show_dots ? "true" : "false" }},
                                    showArrows: {{ design.navigation.show_arrows ? "true" : "false" }}
                                }
                            }
                        );
                    })();'
                ],
                'builderCondition' => 'return true;',
                'frontendCondition' => 'return true;'
            ]
        ];
    }

    static function actions()
    {
        return [
            'onPropertyChange' => [
                [
                    'script' => '(function() {
                        if (!window.CWImageReveal) return;

                        {% set mode = content.mode ?? "single" %}
                        {% set trigger = content.trigger_section.trigger ?? content.trigger ?? "hover" %}

                        {% if mode == "gallery" %}
                            {% set imageUrls = [] %}
                            {% for item in content.images %}
                                {% set url = item.image.url ?? item.image.sizes.full.url ?? item.image.rawUrl ?? "" %}
                                {% if url %}
                                    {% set imageUrls = imageUrls|merge([url]) %}
                                {% endif %}
                            {% endfor %}
                        {% else %}
                            {% set singleUrl = content.image.url ?? content.image.sizes.full.url ?? content.image.rawUrl ?? "" %}
                            {% set imageUrls = singleUrl ? [singleUrl] : [] %}
                        {% endif %}

                        CWImageReveal.destroy("%%ID%%");
                        CWImageReveal.init(
                            "%%SELECTOR%%",
                            "%%ID%%",
                            {
                                mode: "{{ mode }}",
                                images: {{ imageUrls|json_encode|raw }},
                                trigger: "{{ trigger }}",
                                scrollOut: {{ content.trigger_section.scroll_out ?? content.scroll_out ? "true" : "false" }},
                                scrollThreshold: {{ content.trigger_section.scroll_threshold ?? 30 }} / 100,
                                autoPlayDelay: {{ content.trigger_section.auto_play_delay ?? content.auto_play_delay ?? 1000 }},
                                loop: {{ content.trigger_section.loop ?? content.loop ? "true" : "false" }},
                                loopDelay: {{ content.trigger_section.loop_delay ?? content.loop_delay ?? 2000 }},
                                gallery: {
                                    autoplay: {{ content.gallery.autoplay ? "true" : "false" }},
                                    autoplaySpeed: {{ content.gallery.autoplay_speed ?? 4000 }},
                                    pauseOnHover: {{ content.gallery.pause_on_hover ? "true" : "false" }},
                                    transitionStyle: "{{ content.gallery.transition_style ?? "dissolve" }}",
                                    loopGallery: {{ content.gallery.loop_gallery ? "true" : "false" }}
                                },
                                effect: {
                                    preset: "{{ design.effect.preset ?? "organic" }}",
                                    noiseScale: {{ design.effect.noise_scale ?? 5 }},
                                    radialIntensity: {{ design.effect.radial_intensity ?? 12.5 }},
                                    radialOffset: {{ design.effect.radial_offset ?? 7 }},
                                    waveEnabled: {{ design.effect.wave_enabled ? "true" : "false" }},
                                    waveIntensity: {{ design.effect.wave_intensity ?? 0.05 }},
                                    waveFrequency: {{ design.effect.wave_frequency ?? 15 }},
                                    waveSpeed: {{ design.effect.wave_speed ?? 8 }}
                                },
                                animation: {
                                    duration: {{ design.animation.duration ?? 1.5 }},
                                    easeType: "{{ design.animation.ease_type ?? "power3.inOut" }}",
                                    timeSpeed: {{ design.animation.time_speed ?? 0.1 }},
                                    revealDelay: {{ design.animation.reveal_delay ?? 0 }}
                                },
                                navigation: {
                                    showDots: {{ design.navigation.show_dots ? "true" : "false" }},
                                    showArrows: {{ design.navigation.show_arrows ? "true" : "false" }}
                                }
                            }
                        );
                    })();'
                ]
            ],
            'onMountedElement' => [
                [
                    'script' => '(function() {
                        if (!window.CWImageReveal) return;
                        if (CWImageReveal.getInstance("%%ID%%")) {
                            CWImageReveal.destroy("%%ID%%");
                        }

                        {% set mode = content.mode ?? "single" %}
                        {% set trigger = content.trigger_section.trigger ?? content.trigger ?? "hover" %}

                        {% if mode == "gallery" %}
                            {% set imageUrls = [] %}
                            {% for item in content.images %}
                                {% set url = item.image.url ?? item.image.sizes.full.url ?? item.image.rawUrl ?? "" %}
                                {% if url %}
                                    {% set imageUrls = imageUrls|merge([url]) %}
                                {% endif %}
                            {% endfor %}
                        {% else %}
                            {% set singleUrl = content.image.url ?? content.image.sizes.full.url ?? content.image.rawUrl ?? "" %}
                            {% set imageUrls = singleUrl ? [singleUrl] : [] %}
                        {% endif %}

                        CWImageReveal.init(
                            "%%SELECTOR%%",
                            "%%ID%%",
                            {
                                mode: "{{ mode }}",
                                images: {{ imageUrls|json_encode|raw }},
                                trigger: "{{ trigger }}",
                                scrollOut: {{ content.trigger_section.scroll_out ?? content.scroll_out ? "true" : "false" }},
                                scrollThreshold: {{ content.trigger_section.scroll_threshold ?? 30 }} / 100,
                                autoPlayDelay: {{ content.trigger_section.auto_play_delay ?? content.auto_play_delay ?? 1000 }},
                                loop: {{ content.trigger_section.loop ?? content.loop ? "true" : "false" }},
                                loopDelay: {{ content.trigger_section.loop_delay ?? content.loop_delay ?? 2000 }},
                                gallery: {
                                    autoplay: {{ content.gallery.autoplay ? "true" : "false" }},
                                    autoplaySpeed: {{ content.gallery.autoplay_speed ?? 4000 }},
                                    pauseOnHover: {{ content.gallery.pause_on_hover ? "true" : "false" }},
                                    transitionStyle: "{{ content.gallery.transition_style ?? "dissolve" }}",
                                    loopGallery: {{ content.gallery.loop_gallery ? "true" : "false" }}
                                },
                                effect: {
                                    preset: "{{ design.effect.preset ?? "organic" }}",
                                    noiseScale: {{ design.effect.noise_scale ?? 5 }},
                                    radialIntensity: {{ design.effect.radial_intensity ?? 12.5 }},
                                    radialOffset: {{ design.effect.radial_offset ?? 7 }},
                                    waveEnabled: {{ design.effect.wave_enabled ? "true" : "false" }},
                                    waveIntensity: {{ design.effect.wave_intensity ?? 0.05 }},
                                    waveFrequency: {{ design.effect.wave_frequency ?? 15 }},
                                    waveSpeed: {{ design.effect.wave_speed ?? 8 }}
                                },
                                animation: {
                                    duration: {{ design.animation.duration ?? 1.5 }},
                                    easeType: "{{ design.animation.ease_type ?? "power3.inOut" }}",
                                    timeSpeed: {{ design.animation.time_speed ?? 0.1 }},
                                    revealDelay: {{ design.animation.reveal_delay ?? 0 }}
                                },
                                navigation: {
                                    showDots: {{ design.navigation.show_dots ? "true" : "false" }},
                                    showArrows: {{ design.navigation.show_arrows ? "true" : "false" }}
                                }
                            }
                        );
                    })();'
                ]
            ],
            'onBeforeDeletingElement' => [
                [
                    'script' => '(function() {
                        if (window.CWImageReveal) {
                            CWImageReveal.destroy("%%ID%%");
                        }
                    })();'
                ]
            ]
        ];
    }

    static function nestable()
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

    static function propertyPathsToWhitelistInFlat498()
    {
        return [
            'design.layout.width',
            'design.layout.max_width',
            'design.layout.height',
            'design.layout.aspect_ratio',
            'design.appearance.border_radius',
            'design.appearance.background_color',
            'design.appearance.box_shadow',
            'design.navigation.dots_size',
            'design.navigation.dots_gap',
            'design.navigation.dots_color',
            'design.navigation.dots_active_color',
            'design.navigation.arrows_size',
            'design.navigation.arrows_color',
            'design.navigation.arrows_background'
        ];
    }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return ['content.mode', 'design.navigation.show_dots', 'design.navigation.show_arrows', 'design.navigation.dots_position'];
    }
}
