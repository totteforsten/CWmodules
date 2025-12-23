(function() {
    'use strict';

    window.BreakdanceModernGallery = {
        instances: {},

        init: function(selector, elementId) {
            const container = document.querySelector(selector);
            if (!container) return;

            const gallery = container.querySelector('.bde-modern-gallery');
            if (!gallery) return;

            // Get properties from element data attributes (set by PHP)
            const propertiesEl = gallery.querySelector('[data-properties]');
            const properties = propertiesEl ? JSON.parse(propertiesEl.dataset.properties) : {};

            console.log('Modern Gallery Init:', elementId, properties);

            // Check if GSAP is available
            if (typeof gsap === 'undefined') {
                console.error('GSAP is not loaded! Gallery animations will not work.');
                return;
            }

            const instance = {
                container: gallery,
                slides: gallery.querySelectorAll('.bde-modern-gallery__slide'),
                thumbnails: gallery.querySelectorAll('.bde-modern-gallery__thumbnail'),
                currentIndex: 0,
                properties: properties,
                animations: []
            };

            console.log('Found slides:', instance.slides.length, 'thumbnails:', instance.thumbnails.length);

            // Set up thumbnail click handlers
            instance.thumbnails.forEach((thumb, index) => {
                thumb.addEventListener('click', () => {
                    console.log('Thumbnail clicked:', index);
                    this.goToSlide(instance, index);
                });
            });

            // Set up zoom button
            const zoomBtn = gallery.querySelector('.bde-modern-gallery__zoom');
            if (zoomBtn) {
                zoomBtn.addEventListener('click', () => {
                    console.log('Zoom clicked');
                    this.openLightbox(instance);
                });
            }

            // Add keyboard navigation
            gallery.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    this.goToSlide(instance, instance.currentIndex - 1);
                } else if (e.key === 'ArrowRight') {
                    this.goToSlide(instance, instance.currentIndex + 1);
                }
            });

            // Initialize first slide
            this.setInitialState(instance);

            this.instances[elementId] = instance;
            console.log('Gallery initialized successfully');
        },

        setInitialState: function(instance) {
            instance.slides.forEach((slide, index) => {
                if (index === 0) {
                    gsap.set(slide, {
                        clipPath: 'circle(150% at 50% 50%)',
                        opacity: 1,
                        zIndex: 2
                    });
                } else {
                    gsap.set(slide, {
                        clipPath: 'circle(0% at 50% 50%)',
                        opacity: 0,
                        zIndex: 1
                    });
                }
            });
        },

        goToSlide: function(instance, targetIndex) {
            // Wrap around
            if (targetIndex < 0) targetIndex = instance.slides.length - 1;
            if (targetIndex >= instance.slides.length) targetIndex = 0;

            if (targetIndex === instance.currentIndex) return;

            const currentSlide = instance.slides[instance.currentIndex];
            const nextSlide = instance.slides[targetIndex];

            // Get animation settings from properties or use defaults
            let duration = 1.2;
            let easing = 'power3.inOut';
            let clipPathType = 'circle';

            if (instance.properties?.design?.animation) {
                const animProps = instance.properties.design.animation;

                // Parse duration - might be an object with number property
                if (animProps.duration) {
                    if (typeof animProps.duration === 'object' && animProps.duration.number) {
                        duration = parseFloat(animProps.duration.number);
                    } else if (typeof animProps.duration === 'number') {
                        duration = animProps.duration;
                    } else if (typeof animProps.duration === 'string') {
                        duration = parseFloat(animProps.duration);
                    }
                }

                if (animProps.easing) {
                    easing = animProps.easing;
                }

                if (animProps.clippath_type) {
                    clipPathType = animProps.clippath_type;
                }
            }

            console.log('Animation settings:', { duration, easing, clipPathType });

            // Kill all animations on all slides first
            gsap.killTweensOf(instance.slides);

            // Reset all slides to hidden state except current and next
            instance.slides.forEach((slide, index) => {
                if (index !== instance.currentIndex && index !== targetIndex) {
                    gsap.set(slide, {
                        opacity: 0,
                        zIndex: 1,
                        clipPath: this.getClipPath(clipPathType, 0)
                    });
                }
            });

            // Set current slide to visible
            gsap.set(currentSlide, {
                opacity: 1,
                zIndex: 2,
                clipPath: this.getClipPath(clipPathType, 150)
            });

            // Prepare next slide
            gsap.set(nextSlide, {
                opacity: 1,
                zIndex: 3,
                clipPath: this.getClipPath(clipPathType, 0)
            });

            // Create animation timeline
            const tl = gsap.timeline({
                onComplete: () => {
                    // Clean up after animation - hide current, show next
                    gsap.set(currentSlide, {
                        opacity: 0,
                        zIndex: 1,
                        clipPath: this.getClipPath(clipPathType, 0)
                    });
                    gsap.set(nextSlide, {
                        zIndex: 2,
                        clipPath: this.getClipPath(clipPathType, 150)
                    });
                }
            });

            // Animate the clip path to reveal the next image
            tl.to(nextSlide, {
                clipPath: this.getClipPath(clipPathType, 150),
                duration: duration,
                ease: easing
            });

            // Update active states
            this.updateActiveStates(instance, targetIndex);
            instance.currentIndex = targetIndex;
        },

        getClipPath: function(type, percentage) {
            switch(type) {
                case 'circle':
                    return `circle(${percentage}% at 50% 50%)`;
                case 'circle-top-left':
                    return `circle(${percentage}% at 0% 0%)`;
                case 'circle-top-right':
                    return `circle(${percentage}% at 100% 0%)`;
                case 'circle-bottom-left':
                    return `circle(${percentage}% at 0% 100%)`;
                case 'circle-bottom-right':
                    return `circle(${percentage}% at 100% 100%)`;
                case 'ellipse-h':
                    return `ellipse(${percentage}% ${percentage * 0.7}% at 50% 50%)`;
                case 'ellipse-v':
                    return `ellipse(${percentage * 0.7}% ${percentage}% at 50% 50%)`;
                case 'polygon-center':
                    const p = percentage;
                    return `polygon(${50 - p/2}% ${50 - p/2}%, ${50 + p/2}% ${50 - p/2}%, ${50 + p/2}% ${50 + p/2}%, ${50 - p/2}% ${50 + p/2}%)`;
                case 'polygon-diamond':
                    return `polygon(50% ${50 - p/2}%, ${50 + p/2}% 50%, 50% ${50 + p/2}%, ${50 - p/2}% 50%)`;
                case 'inset-h':
                    return `inset(0% ${100 - percentage}% 0% ${100 - percentage}%)`;
                case 'inset-v':
                    return `inset(${100 - percentage}% 0% ${100 - percentage}% 0%)`;
                default:
                    return `circle(${percentage}% at 50% 50%)`;
            }
        },

        updateActiveStates: function(instance, index) {
            // Update slides
            instance.slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.add('is-active');
                } else {
                    slide.classList.remove('is-active');
                }
            });

            // Update thumbnails
            instance.thumbnails.forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.add('is-active');
                } else {
                    thumb.classList.remove('is-active');
                }
            });
        },

        openLightbox: function(instance) {
            // Simple lightbox implementation
            const currentSlide = instance.slides[instance.currentIndex];
            const img = currentSlide.querySelector('img');
            if (!img) return;

            const lightbox = document.createElement('div');
            lightbox.className = 'bde-modern-gallery__lightbox';
            lightbox.innerHTML = `
                <div class="bde-modern-gallery__lightbox-overlay"></div>
                <div class="bde-modern-gallery__lightbox-content">
                    <button class="bde-modern-gallery__lightbox-close" aria-label="Close">&times;</button>
                    <img src="${img.src}" alt="${img.alt}">
                </div>
            `;

            document.body.appendChild(lightbox);

            // Animate in
            gsap.fromTo(lightbox,
                { opacity: 0 },
                { opacity: 1, duration: 0.3 }
            );

            gsap.fromTo(lightbox.querySelector('.bde-modern-gallery__lightbox-content'),
                { scale: 0.9, opacity: 0 },
                { scale: 1, opacity: 1, duration: 0.4, ease: 'back.out(1.7)' }
            );

            // Close handlers
            const closeBtn = lightbox.querySelector('.bde-modern-gallery__lightbox-close');
            const overlay = lightbox.querySelector('.bde-modern-gallery__lightbox-overlay');

            const closeLightbox = () => {
                gsap.to(lightbox, {
                    opacity: 0,
                    duration: 0.3,
                    onComplete: () => {
                        lightbox.remove();
                    }
                });
            };

            closeBtn.addEventListener('click', closeLightbox);
            overlay.addEventListener('click', closeLightbox);

            document.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') {
                    closeLightbox();
                    document.removeEventListener('keydown', escHandler);
                }
            });
        },

        update: function(elementId) {
            // Destroy and reinitialize
            this.destroy(elementId);
            // Note: The selector will be passed by the PHP actions
            const instance = this.instances[elementId];
            if (instance && instance.container) {
                const selector = instance.container.closest('[data-element-id]').getAttribute('data-element-id');
                this.init(`[data-element-id="${selector}"]`, elementId);
            }
        },

        destroy: function(elementId) {
            const instance = this.instances[elementId];
            if (instance) {
                // Kill any running animations
                if (instance.animations) {
                    instance.animations.forEach(anim => {
                        if (anim.kill) anim.kill();
                    });
                }

                // Remove event listeners if needed
                delete this.instances[elementId];
            }
        },

        initDirect: function(gallery, elementId) {
            console.log('Direct init for gallery:', elementId);

            // Check if GSAP is available
            if (typeof gsap === 'undefined') {
                console.error('GSAP is not loaded! Gallery animations will not work.');
                return;
            }

            // Get properties from element data attributes (set by PHP)
            const propertiesEl = gallery.querySelector('[data-properties]');
            const properties = propertiesEl ? JSON.parse(propertiesEl.dataset.properties) : {};

            const instance = {
                container: gallery,
                slides: gallery.querySelectorAll('.bde-modern-gallery__slide'),
                thumbnails: gallery.querySelectorAll('.bde-modern-gallery__thumbnail'),
                currentIndex: 0,
                properties: properties,
                animations: []
            };

            console.log('Found slides:', instance.slides.length, 'thumbnails:', instance.thumbnails.length);

            // Set up thumbnail click handlers
            instance.thumbnails.forEach((thumb, index) => {
                thumb.addEventListener('click', () => {
                    console.log('Thumbnail clicked:', index);
                    this.goToSlide(instance, index);
                });
            });

            // Set up zoom button
            const zoomBtn = gallery.querySelector('.bde-modern-gallery__zoom');
            if (zoomBtn) {
                zoomBtn.addEventListener('click', () => {
                    console.log('Zoom clicked');
                    this.openLightbox(instance);
                });
            }

            // Add keyboard navigation
            gallery.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    this.goToSlide(instance, instance.currentIndex - 1);
                } else if (e.key === 'ArrowRight') {
                    this.goToSlide(instance, instance.currentIndex + 1);
                }
            });

            // Initialize first slide
            this.setInitialState(instance);

            this.instances[elementId] = instance;
            console.log('Gallery initialized successfully via direct init');
        }
    };

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.bde-modern-gallery[data-element-id]').forEach(gallery => {
                const elementId = gallery.getAttribute('data-element-id');
                // Since the gallery itself has the data-element-id, we need to find its parent wrapper
                const wrapper = gallery.closest('.bde-wooproductimages');
                if (wrapper) {
                    const selector = `.bde-wooproductimages`;
                    window.BreakdanceModernGallery.init(selector, elementId);
                } else {
                    // Fallback: init directly with gallery
                    console.log('No wrapper found, initializing gallery directly');
                    window.BreakdanceModernGallery.initDirect(gallery, elementId);
                }
            });
        });
    } else {
        document.querySelectorAll('.bde-modern-gallery[data-element-id]').forEach(gallery => {
            const elementId = gallery.getAttribute('data-element-id');
            const wrapper = gallery.closest('.bde-wooproductimages');
            if (wrapper) {
                const selector = `.bde-wooproductimages`;
                window.BreakdanceModernGallery.init(selector, elementId);
            } else {
                console.log('No wrapper found, initializing gallery directly');
                window.BreakdanceModernGallery.initDirect(gallery, elementId);
            }
        });
    }
})();
