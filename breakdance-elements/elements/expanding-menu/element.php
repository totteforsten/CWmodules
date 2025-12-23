<?php

namespace BreakdanceCustomElements;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;


\Breakdance\ElementStudio\registerElementForEditing(
    "BreakdanceToggleMenu\\ExpandingMenu",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class ExpandingMenu extends \Breakdance\Elements\Element
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
        return 'Expanding Menu';
    }

    static function className()
    {
        return 'bde-expanding-menu';
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
        "position",
        "Position",
        [c(
        "top",
        "Top",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 0, 'max' => 500, 'step' => 1]],
        false,
        false,
        [],
        
      ), c(
        "left",
        "Left",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px'], 'rangeOptions' => ['min' => 0, 'max' => 500, 'step' => 1]],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "button",
        "Button",
        [c(
        "padding",
        "Padding",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => '14px 24px'],
        false,
        false,
        [],
        
      ), c(
        "gap",
        "Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
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
        "hover_background",
        "Hover Background",
        [],
        ['type' => 'color', 'layout' => 'inline'],
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
        
      ), c(
        "radius",
        "Border Radius",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      ), c(
        "font_size",
        "Font Size",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      ), c(
        "font_weight",
        "Font Weight",
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
        "menu",
        "Menu Container",
        [c(
        "max_width",
        "Max Width",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => '720px'],
        false,
        false,
        [],
        
      ), c(
        "max_height",
        "Max Height",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => '60vh'],
        false,
        false,
        [],
        
      ), c(
        "initial_height",
        "Initial Height",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => '46px'],
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
        "radius",
        "Border Radius",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      ), c(
        "shadow",
        "Shadow (CSS)",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => '0 20px 60px rgba(0, 0, 0, 0.3)'],
        false,
        false,
        [],
        
      ), c(
        "padding",
        "Padding",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => '80px 40px 40px'],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "layout",
        "Layout",
        [c(
        "columns",
        "Grid Columns",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => '1fr'],
        false,
        false,
        [],
        
      ), c(
        "gap",
        "Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      ), c(
        "column_min",
        "Column Min Width",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => '180px'],
        false,
        false,
        [],
        
      ), c(
        "column_gap",
        "Column Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "column",
        "Column Style",
        [c(
        "title_size",
        "Title Size",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      ), c(
        "title_weight",
        "Title Weight",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 100, 'max' => 900, 'step' => 100]],
        false,
        false,
        [],
        
      ), c(
        "title_color",
        "Title Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "title_margin",
        "Title Margin",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "items",
        "Menu Items",
        [c(
        "gap",
        "Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      ), c(
        "padding",
        "Padding",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => '6px 0'],
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
        
      ), c(
        "hover_color",
        "Hover Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "font_size",
        "Font Size",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      ), c(
        "font_weight",
        "Font Weight",
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
        "badge",
        "Badge",
        [c(
        "background",
        "Background",
        [],
        ['type' => 'color', 'layout' => 'inline'],
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
        "featured",
        "Featured",
        [c(
        "padding",
        "Padding",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => '32px'],
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
        "radius",
        "Radius",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      ), c(
        "badge_bg",
        "Badge Background",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "badge_color",
        "Badge Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "title_size",
        "Title Size",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
        false,
        false,
        [],
        
      ), c(
        "title_weight",
        "Title Weight",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 100, 'max' => 900, 'step' => 100]],
        false,
        false,
        [],
        
      ), c(
        "title_color",
        "Title Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "link_bg",
        "Link Background",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "link_color",
        "Link Color",
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
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px'], 'defaultType' => 'px']],
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
        "width_duration",
        "Width Duration (s)",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.1, 'max' => 2, 'step' => 0.1]],
        false,
        false,
        [],
        
      ), c(
        "height_duration",
        "Height Duration (s)",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0.1, 'max' => 2, 'step' => 0.1]],
        false,
        false,
        [],
        
      ), c(
        "stagger",
        "Stagger (s)",
        [],
        ['type' => 'number', 'layout' => 'inline', 'rangeOptions' => ['min' => 0, 'max' => 0.3, 'step' => 0.01]],
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
        
      )];
    }

    static function contentControls()
    {
        return [c(
        "button",
        "Button",
        [c(
        "text",
        "Text",
        [],
        ['type' => 'text', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "columns",
        "Menu Columns",
        [c(
        "columns",
        "Columns",
        [c(
        "title",
        "Title",
        [],
        ['type' => 'text', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      ), c(
        "items",
        "Items",
        [c(
        "text",
        "Text",
        [],
        ['type' => 'text', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      ), c(
        "url",
        "URL",
        [],
        ['type' => 'url', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      ), c(
        "badge",
        "Badge",
        [],
        ['type' => 'text', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      )],
        ['type' => 'repeater', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      )],
        ['type' => 'repeater', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), c(
        "featured",
        "Featured Section",
        [c(
        "enabled",
        "Enable Featured",
        [],
        ['type' => 'toggle', 'layout' => 'inline'],
        false,
        false,
        [],
        
      ), c(
        "badge",
        "Badge",
        [],
        ['type' => 'text', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      ), c(
        "title",
        "Title",
        [],
        ['type' => 'text', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      ), c(
        "link_text",
        "Link Text",
        [],
        ['type' => 'text', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      ), c(
        "link_url",
        "Link URL",
        [],
        ['type' => 'url', 'layout' => 'vertical'],
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
        return ['type' => 'final'];
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
        return false;
    }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return false;
    }
}
