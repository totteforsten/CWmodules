<?php

namespace BreakdanceCustomElements;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;


\Breakdance\ElementStudio\registerElementForEditing(
    "BreakdanceCustomElements\\AnimatedToggleMenu",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class AnimatedToggleMenu extends \Breakdance\Elements\Element
{
    static function uiIcon()
    {
        return 'MenuIcon';
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
        return 'Animated Toggle Menu';
    }

    static function className()
    {
        return 'bde-animated-toggle-menu';
    }

    static function category()
    {
        return 'navigation';
    }

    static function badge()
    {
        return false;
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
        return ['content' => ['button' => ['text' => 'Menu']]];
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
        "toggle_button",
        "Toggle Button",
        [c(
        "size",
        "Size",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 20, 'max' => 200, 'step' => 1]],
        true,
        false,
        [],
        
      ), c(
        "icon_size",
        "Icon Size",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 10, 'max' => 100, 'step' => 1]],
        true,
        false,
        [],
        
      ), c(
        "background",
        "Background",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        true,
        [],
        
      ), c(
        "icon_color",
        "Icon Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "radius",
        "Border Radius",
        [],
        ['type' => 'border_radius', 'layout' => 'vertical'],
        true,
        false,
        [],
        
      ), c(
        "shadow",
        "Shadow",
        [],
        ['type' => 'shadow', 'layout' => 'vertical'],
        true,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "children_layout",
        "Children Layout",
        [c(
        "direction",
        "Direction",
        [],
        ['type' => 'button_bar', 'layout' => 'inline', 'items' => [['text' => 'Left', 'value' => 'left', 'icon' => 'AlignLeftIcon'], ['text' => 'Right', 'value' => 'right', 'icon' => 'AlignRightIcon']]],
        false,
        false,
        [],
        
      ), c(
        "width",
        "Width",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', '%', 'vw'], 'defaultType' => 'px']],
        true,
        false,
        [],
        
      ), getPresetSection(
      "EssentialElements\\layout",
      "Layout",
      "layout",
       ['type' => 'popout']
     ), c(
        "gap",
        "Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', 'em', 'rem'], 'defaultType' => 'px']],
        true,
        false,
        [],
        
      ), c(
        "padding",
        "Padding",
        [],
        ['type' => 'spacing_complex', 'layout' => 'vertical'],
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
        
      ), c(
        "radius",
        "Border Radius",
        [],
        ['type' => 'border_radius', 'layout' => 'vertical'],
        true,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "backdrop",
        "Backdrop",
        [c(
        "color",
        "Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "blur",
        "Blur",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 0, 'max' => 50, 'step' => 1]],
        true,
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
        "Duration (s)",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.1, 'max' => 2, 'step' => 0.1]],
        false,
        false,
        [],
        
      ), c(
        "stagger",
        "Stagger (s)",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 0.5, 'step' => 0.01]],
        false,
        false,
        [],
        
      ), c(
        "ease",
        "Easing",
        [],
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [['text' => 'Power3 Out', 'value' => 'power3.out'], ['text' => 'Power2 Out', 'value' => 'power2.out'], ['text' => 'Power4 Out', 'value' => 'power4.out'], ['text' => 'Expo Out', 'value' => 'expo.out'], ['text' => 'Back Out', 'value' => 'back.out']]],
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
        "icon",
        "Icon",
        [c(
        "type",
        "Icon Type",
        [],
        ['type' => 'button_bar', 'layout' => 'inline', 'items' => [['text' => 'Hamburger', 'value' => 'hamburger'], ['text' => 'Plus', 'value' => 'plus']]],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
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
        return ['0' =>  ['scripts' => ['%%BREAKDANCE_REUSABLE_GSAP%%'],],];
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
        return false;
    }

    static function nestingRule()
    {
        return ['type' => 'container'];
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

    static function availableIn()
    {
        return ['breakdance'];
    }


    static function order()
    {
        return 0;
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
        return ['design.toggle_button.size', 'design.toggle_button.icon_size', 'design.children_layout.direction', 'design.children_layout.width', 'design.children_layout.gap', 'design.children_layout.layout.layout', 'design.children_layout.layout.h_vertical_at', 'design.children_layout.layout.h_alignment_when_vertical', 'design.children_layout.layout.a_display'];
    }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return false;
    }
}
