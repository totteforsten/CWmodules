/* global gsap */

(function() {
  'use strict';
  
  function initAnimatedToggleMenu(wrapper) {
    const toggle = wrapper.querySelector('.atm-toggle');
    const content = wrapper.querySelector('.atm-content');
    const containers = wrapper.querySelectorAll('.atm-containers > *');
    const backdrop = wrapper.querySelector('.atm-backdrop');
    
    if (!toggle || !content) return;

    const duration = parseFloat(toggle.dataset.duration) || 0.5;
    const stagger = parseFloat(toggle.dataset.stagger) || 0.1;
    const ease = toggle.dataset.ease || 'power3.out';
    
    let isOpen = false;
    let timeline = null;

    function openMenu() {
      if (isOpen) return;
      isOpen = true;
      
      toggle.classList.add('is-open');
      toggle.setAttribute('aria-expanded', 'true');
      content.classList.add('is-open');
      backdrop.classList.add('is-active');
      
      if (timeline) timeline.kill();
      timeline = gsap.timeline();
      
      timeline.to(backdrop, {
        opacity: 1,
        duration: duration * 0.6,
        ease: 'power2.out'
      }, 0);
      
      if (containers.length > 0) {
        timeline.fromTo(containers,
          {
            opacity: 0,
            y: -30,
          },
          {
            opacity: 1,
            y: 0,
            duration: duration,
            stagger: stagger,
            ease: ease
          },
          duration * 0.2
        );
      }
    }

    function closeMenu() {
      if (!isOpen) return;
      isOpen = false;
      
      toggle.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      
      if (timeline) timeline.kill();
      timeline = gsap.timeline({
        onComplete: () => {
          content.classList.remove('is-open');
          backdrop.classList.remove('is-active');
        }
      });
      
      if (containers.length > 0) {
        timeline.to(containers, {
          opacity: 0,
          y: -30,
          duration: duration * 0.8,
          stagger: stagger * 0.5,
          ease: 'power2.in'
        }, 0);
      }
      
      timeline.to(backdrop, {
        opacity: 0,
        duration: duration * 0.6,
        ease: 'power2.in'
      }, duration * 0.2);
    }

    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      if (isOpen) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    if (backdrop) {
      backdrop.addEventListener('click', closeMenu);
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && isOpen) {
        closeMenu();
      }
    });
  }

  function init() {
    const wrappers = document.querySelectorAll('.atm-wrapper');
    wrappers.forEach(function(wrapper) {
      if (wrapper.dataset.atmInitialized) return;
      wrapper.dataset.atmInitialized = 'true';
      
      initAnimatedToggleMenu(wrapper);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  if (window.BreakdanceFrontend) {
    window.BreakdanceFrontend.on('element-rendered', function() {
      init();
    });
  }

})();
