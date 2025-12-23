# Installation Guide

## Quick Start

The WooCommerce Variations Grid element is now installed and ready to use!

## How to Use

1. **Open Breakdance Builder** on any product page template or individual product page
2. **Find the element** in the elements panel under the **WooCommerce** category
3. **Drag and drop** the "WooCommerce Variations Grid" element onto your page
4. **Configure** the element using the Content and Design panels

## Configuration

### Content Panel

**Columns Configuration:**
- Click "Add Column" to add custom columns
- Default columns (SKU, Price) are already configured
- Each column needs:
  - `Key`: The meta field key (e.g., 'sku', 'price', '_custom_field')
  - `Label`: Display name for the header
  - `Enabled`: Toggle to show/hide
  - `Sortable`: Toggle to enable sorting

**Display Options:**
- Toggle stock status display
- Toggle quantity selector
- Set guest user behavior (hide/show prices)

**Text Labels:**
- Customize all text strings for your language/brand

### Design Panel

**Layout:**
- Adjust grid gap between cells
- Set max height for scrolling
- Enable mobile stacked layout

**Styling:**
- Header colors and typography
- Row colors and hover effects
- Button styling (normal, hover, disabled)
- Quantity selector styling

## Requirements

- ✅ WooCommerce must be active
- ✅ Must be used on variable product pages
- ✅ Product must have available variations

## Troubleshooting

### Element not showing
- Clear Breakdance cache (Settings > Cache)
- Ensure WooCommerce is active

### Error saving element
- The element uses Server-Side Rendering (SSR)
- The html.twig file should only contain `%%SSR%%`
- All rendering logic is in ssr.php

### Styles not applying
- Regenerate CSS in Breakdance settings
- Clear browser cache
- Check for theme CSS conflicts

## Files Structure

```
WooVariationsGrid/
├── element.php       (Element class definition)
├── html.twig        (Template - contains %%SSR%%)
├── ssr.php          (Server-side rendering logic)
├── css.twig         (Dynamic styles)
├── default.css      (Fallback styles)
├── frontend.js      (JavaScript functionality)
├── README.md        (Full documentation)
└── INSTALL.md       (This file)
```

## Support

For detailed documentation, see [README.md](README.md)

## What's Different from the Original Shortcode?

✅ **Visual Builder** - No code needed, style in Breakdance
✅ **Modern Code** - ES6+ JavaScript, no jQuery
✅ **Responsive** - Mobile-first with auto-stacking
✅ **Accessible** - Keyboard navigation, ARIA labels
✅ **Flexible Columns** - Add any custom meta fields
✅ **Guest Controls** - Hide prices/purchasing for non-logged users
✅ **Better UX** - Loading states, smooth animations
✅ **Type Safe** - Proper error handling and validation

Enjoy your new WooCommerce Variations Grid element!
