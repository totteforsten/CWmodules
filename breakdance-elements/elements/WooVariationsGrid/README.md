# WooCommerce Variations Grid

A modern, highly customizable Breakdance custom element that displays WooCommerce product variations in a sortable grid layout with add-to-cart functionality.

## Features

### Core Functionality
- **Dynamic Variation Display** - Automatically displays all available variations for variable products
- **Sortable Columns** - Click column headers to sort by any field (ascending/descending)
- **AJAX Add to Cart** - Add variations to cart without page reload
- **Stock Status Display** - Shows real-time stock availability with badges
- **Quantity Selector** - Built-in quantity controls with +/- buttons
- **Guest User Controls** - Configure price visibility and purchasing for non-logged-in users

### Modern Features
- **Fully Responsive** - Mobile-first design with stacked layout option
- **Customizable Columns** - Add any product meta fields or custom fields
- **Extensive Styling Controls** - Style every aspect through Breakdance builder
- **Vanilla JavaScript** - No jQuery dependency, modern ES6+ code
- **Accessibility** - Keyboard navigation and ARIA labels
- **Performance Optimized** - Efficient DOM manipulation and event handling

## Installation

1. Place the `WooVariationsGrid` folder in:
   ```
   wp-content/plugins/breakdance-custom-elements-master/elements/
   ```

2. The element will automatically register through the main plugin file

3. Find it in the Breakdance builder under the **WooCommerce** category

## Usage

### Basic Setup

1. **Create/Edit a Product Page** in Breakdance builder
2. **Add the Element** from the WooCommerce category
3. **Configure Columns** in the Content panel
4. **Style the Grid** in the Design panel

### Content Controls

#### Columns Configuration

Configure which columns to display:

- **SKU** - Product variation SKU
- **Price** - Product variation price (respects guest settings)
- **Custom Meta Fields** - Any custom field key

Each column supports:
- `Key` - Meta field key (e.g., 'sku', 'price', 'custom_field')
- `Label` - Display name in header
- `Enabled` - Show/hide column
- `Sortable` - Enable sorting for this column

**Example Custom Column:**
```
Key: _weight
Label: Weight
Enabled: Yes
Sortable: Yes
```

#### Display Options

- **Show Stock Status** - Display in-stock/out-of-stock badges
- **Show Quantity Selector** - Enable quantity controls
- **Guest User Behavior**
  - `Hide Price` - Show "Login to see price" for guests
  - `Show Price` - Display prices to all users

#### Text Labels

Customize all text strings:
- Variant Label
- Stock Status Label
- Quantity Label
- Add to Cart Label
- In Stock Text
- Out of Stock Text
- Login to See Price Text
- Login to Buy Text
- Adding to Cart Text
- Added to Cart Text

### Design Controls

#### Layout
- **Grid Gap** - Spacing between cells (responsive)
- **Stack on Mobile** - Automatically stack columns on mobile devices
- **Max Height** - Set maximum container height with scroll

#### Header Style
- Background Color
- Text Color
- Font Size (responsive)
- Font Weight
- Padding
- Borders

#### Rows Style
- Background Color
- Alternate Background (zebra striping)
- Hover Background
- Text Color
- Font Size (responsive)
- Padding
- Borders

#### Button Style
- Background Color
- Text Color
- Hover Background
- Hover Text Color
- Disabled Background
- Disabled Text Color
- Font Size (responsive)
- Font Weight
- Padding
- Borders
- Border Radius

#### Quantity Selector Style
- Width (responsive)
- Button Background
- Button Hover Background
- Button Text Color
- Input Background
- Input Text Color
- Input Border Color

## Advanced Usage

### Adding Custom Meta Fields

To display custom product meta fields:

1. Add a new column in Content > Columns
2. Set `Key` to your meta field key (e.g., `_custom_field`)
3. Set `Label` to the display name
4. Enable `Sortable` if needed

The element will automatically fetch the meta value using `get_post_meta()`.

### Supported Meta Fields

Built-in support for:
- `sku` - Product SKU
- `price` - Product price
- Any custom meta field key

### Programmatic Access

The element exposes a global API:

```javascript
// Initialize a specific container
const container = document.querySelector('.bde-woo-variations-grid-container');
window.BDEWooVariationsGrid.init(container);

// Listen for events
container.addEventListener('bde-variations-sorted', (e) => {
    console.log('Sorted by:', e.detail.sortBy, 'Direction:', e.detail.direction);
});

container.addEventListener('bde-quantity-changed', (e) => {
    console.log('New quantity:', e.detail.quantity);
});

document.body.addEventListener('wc-added-to-cart', (e) => {
    console.log('Added to cart:', e.detail);
});
```

### Custom Styling

Add custom CSS targeting these classes:

```css
/* Container */
.bde-woo-variations-grid-container { }

/* Header */
.bde-woo-variations-grid-header { }
.bde-woo-variation-header-cell { }
.bde-sortable-header { }

/* Rows */
.bde-woo-variation-row { }
.bde-woo-variation-cell { }

/* Stock Badge */
.bde-stock-badge { }
.bde-stock-in { }
.bde-stock-out { }

/* Quantity Selector */
.bde-quantity-selector { }
.bde-qty-btn { }
.bde-variation-qty { }

/* Add to Cart Button */
.bde-add-to-cart-btn { }
.bde-add-to-cart-btn.bde-added { }
```

## Requirements

- WordPress 5.0+
- WooCommerce 5.0+
- Breakdance Builder 1.0+
- PHP 7.4+

## Browser Support

- Chrome/Edge (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- iOS Safari (latest 2 versions)
- Chrome Android (latest 2 versions)

## Troubleshooting

### Element Not Appearing

Ensure:
1. WooCommerce is active
2. You're on a variable product page
3. The product has available variations

### Sorting Not Working

Check:
1. Column has `Sortable` enabled
2. JavaScript console for errors
3. Browser console shows no conflicts

### Add to Cart Fails

Verify:
1. User is logged in (if guest behavior requires it)
2. Product is in stock
3. WooCommerce cart is functional
4. Check browser console for AJAX errors

### Styling Not Applied

1. Clear Breakdance cache
2. Regenerate CSS in Breakdance settings
3. Check for CSS conflicts with theme

## Performance Notes

- Uses native Fetch API (no jQuery dependency)
- Efficient event delegation
- Minimal DOM manipulation
- Lazy initialization
- Optimized for large product catalogs

## Comparison to Original Shortcode

### Improvements

✅ **Breakdance Native** - Built into the visual builder
✅ **Visual Styling** - No CSS editing required
✅ **Modern JavaScript** - ES6+, no jQuery dependency
✅ **Better UX** - Smooth animations, loading states
✅ **Responsive** - Mobile-first design
✅ **Accessible** - Keyboard navigation, ARIA labels
✅ **Extensible** - Event system for custom functionality
✅ **Type Safe** - Better error handling

## Future Enhancements

Potential additions:
- Bulk add to cart
- CSV export
- Variation filtering
- Image thumbnails
- Quick view modal
- Compare variations

## Support

For issues or feature requests, please check:
- Breakdance documentation
- WooCommerce documentation
- Browser console for JavaScript errors

## License

This custom element follows the same license as the parent plugin.

## Credits

Created as a modern replacement for the legacy variation grid shortcode, designed specifically for Breakdance Builder with WooCommerce integration.
