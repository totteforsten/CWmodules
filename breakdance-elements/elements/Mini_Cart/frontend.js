/* global gsap */

(function() {
  'use strict';

  // Initialize all expanding mini carts on the page
  function initExpandingMiniCart(wrapper) {
    const trigger = wrapper.querySelector('.bde-mini-cart-toggle');
    const container = wrapper.querySelector('.exp-cart-container');
    const content = wrapper.querySelector('.exp-cart-content');
    const backdrop = wrapper.querySelector('.exp-cart-backdrop');
    const closeButton = wrapper.querySelector('.bde-mini-cart-offcanvas__close-button');

    if (!trigger || !container) return;

    // Get settings from data attributes or use defaults
    const settings = {
      maxWidth: container.dataset.maxWidth || '450px',
      maxHeight: container.dataset.maxHeight || '60vh',
      initialHeight: container.dataset.initialHeight || '60px',
      widthDuration: parseFloat(container.dataset.widthDuration || '0.4'),
      heightDuration: parseFloat(container.dataset.heightDuration || '0.4'),
      ease: container.dataset.ease || 'power3.out'
    };

    let isOpen = false;
    let timeline = null;

    function ensureGSAP(callback) {
      if (typeof gsap !== 'undefined') {
        callback();
      } else {
        setTimeout(function() { ensureGSAP(callback); }, 100);
      }
    }

    function openCart() {
      if (isOpen) return;
      isOpen = true;

      // Add is-open class to button
      trigger.classList.add('is-open');
      trigger.setAttribute('aria-expanded', 'true');

      ensureGSAP(function() {
        if (timeline) timeline.kill();
        timeline = gsap.timeline();

        // 1. Fade in backdrop
        if (backdrop) {
          timeline.to(backdrop, {
            opacity: 1,
            duration: settings.widthDuration,
            ease: settings.ease,
            onStart: function() { backdrop.classList.add('active'); }
          }, 0);
        }

        // 2. FIRST: Expand width (horizontal expansion)
        timeline.to(container, {
          width: settings.maxWidth,
          duration: settings.widthDuration,
          ease: settings.ease
        }, 0);

        // 3. THEN: Expand height (vertical expansion)
        timeline.to(container, {
          height: settings.maxHeight,
          duration: settings.heightDuration,
          ease: settings.ease
        }, settings.widthDuration);

        // 4. Fade in content
        timeline.to(content, {
          opacity: 1,
          duration: 0.3,
          ease: 'power2.out'
        }, settings.widthDuration + (settings.heightDuration * 0.5));
      });
    }

    function closeCart() {
      if (!isOpen) return;
      isOpen = false;

      // Add waiting state on button for 0.5s
      trigger.classList.add('is-waiting');
      setTimeout(function() {
        trigger.classList.remove('is-waiting');
      }, 500);

      // Remove is-open class from button
      trigger.classList.remove('is-open');
      trigger.setAttribute('aria-expanded', 'false');

      ensureGSAP(function() {
        if (timeline) timeline.kill();
        timeline = gsap.timeline();

        // 1. Fade out content immediately
        timeline.to(content, {
          opacity: 0,
          duration: 0.2,
          ease: 'power2.in'
        });

        // 2. Collapse height first
        timeline.to(container, {
          height: settings.initialHeight,
          duration: settings.heightDuration * 0.7,
          ease: 'power2.in'
        }, 0.1);

        // 3. Then collapse width
        timeline.to(container, {
          width: 0,
          duration: settings.widthDuration * 0.7,
          ease: 'power2.in'
        }, 0.1 + (settings.heightDuration * 0.7));

        // 4. Fade out backdrop
        if (backdrop) {
          timeline.to(backdrop, {
            opacity: 0,
            duration: settings.widthDuration * 0.7,
            ease: 'power2.in',
            onComplete: function() { backdrop.classList.remove('active'); }
          }, 0.1);
        }
      });
    }

    // Toggle on button click
    trigger.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      if (isOpen) {
        // We are CLOSING -> add .is-waiting for 0.5s
        trigger.classList.add('is-waiting');
        setTimeout(() => {
          trigger.classList.remove('is-waiting');
        }, 500);

        closeCart();
      } else {
        openCart();
      }
    });

    // Close on backdrop click
    if (backdrop) {
      backdrop.addEventListener('click', closeCart);
    }

    // Close on close button click
    if (closeButton) {
      closeButton.addEventListener('click', function(e) {
        e.preventDefault();
        closeCart();
      });
    }

    // Close on ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && isOpen) {
        closeCart();
      }
    });

    // Listen for WooCommerce cart updates and re-initialize
    if (typeof jQuery !== 'undefined') {
      jQuery(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function() {
        // Re-query elements after fragments update
        const newContainer = wrapper.querySelector('.exp-cart-container');
        const newContent = wrapper.querySelector('.exp-cart-content');
        const newCloseButton = wrapper.querySelector('.bde-mini-cart-offcanvas__close-button');

        if (newContainer) {
          // Update references
          Object.assign(settings, {
            maxWidth: newContainer.dataset.maxWidth || '450px',
            maxHeight: newContainer.dataset.maxHeight || '60vh',
            initialHeight: newContainer.dataset.initialHeight || '60px',
            widthDuration: parseFloat(newContainer.dataset.widthDuration || '0.4'),
            heightDuration: parseFloat(newContainer.dataset.heightDuration || '0.4'),
            ease: newContainer.dataset.ease || 'power3.out'
          });
        }
      });

      jQuery(document.body).on('added_to_cart', function() {
        // Optionally auto-open cart on add to cart
        const autoOpen = wrapper.dataset.openOnAdd === 'true';
        if (autoOpen && !isOpen) {
          setTimeout(() => openCart(), 100);
        }
      });
    }
  }

  // Initialize when DOM is ready
  function init() {
    const wrappers = document.querySelectorAll('.exp-cart-wrapper');
    wrappers.forEach(function(wrapper) {
      initExpandingMiniCart(wrapper);
    });
  }

  // Run on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Also run when Breakdance loads elements (for builder preview)
  if (window.BreakdanceFrontend) {
    window.BreakdanceFrontend.on('element-rendered', function() {
      init();
    });
  }

})();
