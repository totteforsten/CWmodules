# Animated Toggle Menu - Breakdance Element

A modern, fully customizable toggle menu element for Breakdance Builder with GSAP-powered animations.

## Features

✨ **Icon Options**
- Choose between Hamburger or Plus icon
- Smooth animated transitions (Hamburger → X or Plus → X)
- Fully customizable size, colors, and styling

🎨 **Flexible Content**
- Add unlimited content containers
- Each container accepts ANY Breakdance elements
- Add headings, text, links, images, forms - whatever you need!

⚡ **Modern Animations**
- GSAP-powered smooth animations
- Top-down staggered entrance
- Reverse animation on close
- Customizable duration, stagger, and easing

🎯 **Full Control**
- Button styling (size, colors, shadows, radius)
- Container styling (padding, background, radius, gap)
- Backdrop with blur support
- Animation timing controls

## Installation

1. Upload the `animated-toggle-menu` folder to:
   `/wp-content/plugins/breakdance/plugin/custom-elements/`

2. Refresh Breakdance Builder

3. Find "Animated Toggle Menu" in the Elements panel under Navigation

## Usage

### Basic Setup

1. **Add the Element**
   - Drag "Animated Toggle Menu" to your page
   - It appears as a toggle button

2. **Choose Icon Type**
   - Go to Content → Icon Settings
   - Select "Hamburger" or "Plus"

3. **Add Content Containers**
   - Simply click the **blue + button** in the element tree (in the left panel)
   - Or use the "Add Element" option when the Animated Toggle Menu is selected
   - Add ANY Breakdance elements as children:
     - Sections
     - Divs
     - Text
     - Headings
     - Links
     - Images
     - Forms
     - Etc.
   - Each direct child element becomes a container that animates in

4. **Style Your Menu**
   - Design → Toggle Button: Customize the icon button
   - Design → Content Containers: Style your containers  
   - Design → Animation: Control timing and effects

### Design Tips

**Button Styling:**
- Size: 40-60px works well for most designs
- Icon Size: 20-28px for optimal clarity
- Use shadow for depth: `0 2px 12px rgba(0,0,0,0.1)`
- Rounded: Use 50% for circle, 8-12px for rounded square

**Container Styling:**
- Gap: 20-30px for comfortable spacing
- Padding: 20-40px depending on content
- Use subtle backgrounds for better readability
- Border radius: 8-12px for modern look

**Animation:**
- Duration: 0.4-0.6s for snappy feel
- Stagger: 0.08-0.15s for smooth cascade
- Ease: "Power3 Out" for smooth, "Back Out" for bounce

### Animation Flow

**Opening:**
1. Backdrop fades in
2. Containers animate from top down (staggered)
3. Each container: opacity 0→1, translateY -30px→0

**Closing:**
1. Containers fade out (slightly reversed stagger)
2. Backdrop fades out
3. All animations play in reverse

### Accessibility

- Keyboard accessible (ESC to close)
- ARIA labels and states
- Focus management
- Backdrop click to close

## Customization Examples

### Full-Screen Overlay Menu
```
Toggle Button:
- Position: Fixed top-right
- Size: 60px
- Background: White
- Shadow: Heavy

Content Containers:
- Max width: 600px
- Center aligned
- Large padding
- Semi-transparent background

Backdrop:
- Dark color: rgba(0,0,0,0.8)
- Blur: 8px
```

### Side Navigation
```
Content Containers:
- Align left or right
- Full height
- Navigation links as children
- Minimal padding
```

### Quick Links Menu
```
Toggle Button:
- Small size: 40px
- Minimal shadow
- Icon only

Content Containers:
- Tight gap: 12px
- Compact padding: 16px
- Quick stagger: 0.05s
```

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Requires GSAP (included with Breakdance)
- Mobile responsive
- Touch-friendly

## Troubleshooting

**Menu not appearing?**
- Check that GSAP is loaded (it's included with Breakdance)
- Ensure containers have content

**Animation not smooth?**
- Reduce number of containers
- Adjust stagger timing
- Try different easing functions

**Button not visible?**
- Check background color contrast
- Verify icon color is different from background
- Check z-index if overlapping issues

## Credits

Built for Breakdance Builder by Colorwave Studio
Uses GSAP for smooth animations
