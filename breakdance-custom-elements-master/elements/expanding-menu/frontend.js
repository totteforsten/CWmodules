/* global gsap */

(function() {
  'use strict';
  
  // Initialize all expanding menus on the page
  function initExpandingMenu(wrapper) {
    const trigger = wrapper.querySelector('.exp-menu-trigger');
    const container = wrapper.querySelector('.exp-menu-container');
    const content = wrapper.querySelector('.exp-menu-content');
    const backdrop = wrapper.querySelector('.exp-menu-backdrop');
    const columns = wrapper.querySelectorAll('.exp-column');
    const featured = wrapper.querySelector('.exp-featured');
    
    if (!trigger || !container) return;

    // Get settings from data attributes or use defaults
    const settings = {
      maxWidth: container.dataset.maxWidth || '720px',
      maxHeight: container.dataset.maxHeight || '60vh',
      initialHeight: container.dataset.initialHeight || '80px',
      widthDuration: parseFloat(container.dataset.widthDuration || '0.4'),
      heightDuration: parseFloat(container.dataset.heightDuration || '0.4'),
      stagger: parseFloat(container.dataset.stagger || '0.08'),
      ease: container.dataset.ease || 'power3.out'
    };

    let isOpen = false;
    let timeline = null;

    function openMenu() {
      if (isOpen) return;
      isOpen = true;
      
      // Add is-open class to button (hamburger becomes X)
      trigger.classList.add('is-open');
      trigger.setAttribute('aria-expanded', 'true');
      
      if (timeline) timeline.kill();
      timeline = gsap.timeline();

      // 1. Fade in backdrop
      if (backdrop) {
        timeline.to(backdrop, {
          opacity: 1,
          duration: settings.widthDuration,
          ease: settings.ease,
          onStart: () => backdrop.classList.add('active')
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
      }, settings.widthDuration); // Start after width completes

      // 4. Fade in content
      timeline.to(content, {
        opacity: 1,
        duration: 0.3,
        ease: 'power2.out'
      }, settings.widthDuration + (settings.heightDuration * 0.5));

      // 5. Animate columns with stagger
      if (columns.length > 0) {
        timeline.to(columns, {
          opacity: 1,
          y: 0,
          duration: 0.5,
          stagger: settings.stagger,
          ease: 'power3.out'
        }, settings.widthDuration + (settings.heightDuration * 0.5));
      }

      // 6. Animate featured if exists
      if (featured) {
        timeline.to(featured, {
          opacity: 1,
          y: 0,
          duration: 0.5,
          ease: 'power3.out'
        }, `-=0.3`);
      }
    }

    function closeMenu() {
  if (!isOpen) return;
  isOpen = false;
  
  // Add waiting state on button for 0.5s
  trigger.classList.add('is-waiting');
  gsap.delayedCall(0.5, function() {
    trigger.classList.remove('is-waiting');
  });

  // Remove is-open class from button (X becomes hamburger)
  trigger.classList.remove('is-open');
  trigger.setAttribute('aria-expanded', 'false');

  if (timeline) timeline.kill();
  timeline = gsap.timeline();

  // Create array of elements to fade out
  const fadeElements = [content];
  if (columns.length > 0) fadeElements.push(...columns);
  if (featured) fadeElements.push(featured);

  // 1. Fade out content immediately
  timeline.to(fadeElements, {
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
      onComplete: () => backdrop.classList.remove('active')
    }, 0.1);
  }

  // Reset transforms
  const resetElements = [];
  if (columns.length > 0) resetElements.push(...columns);
  if (featured) resetElements.push(featured);
  
  if (resetElements.length > 0) {
    timeline.set(resetElements, {
      y: 10
    });
  }
}


    // Toggle on button click
// Toggle on button click
trigger.addEventListener('click', function(e) {
  e.preventDefault();

  if (isOpen) {
    // We are CLOSING -> add .is-waiting for 0.5s
    trigger.classList.add('is-waiting');
    setTimeout(() => {
      trigger.classList.remove('is-waiting');
    }, 500);

    closeMenu();
  } else {
    openMenu();
  }
});


    // Close on backdrop click
    if (backdrop) {
      backdrop.addEventListener('click', closeMenu);
    }

    // Close on ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && isOpen) {
        closeMenu();
      }
    });
  }

  // Initialize when DOM is ready
  function init() {
    const wrappers = document.querySelectorAll('.exp-menu-wrapper');
    wrappers.forEach(function(wrapper) {
      initExpandingMenu(wrapper);
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
