/**
 * CW Image Reveal - Three.js Shader-Based Image Reveal Effect
 * Based on Codrops tutorial by Colin Music
 * https://tympanus.net/codrops/2024/12/02/how-to-code-a-shader-based-reveal-effect-with-react-three-fiber-glsl/
 */

(function() {
    'use strict';

    const instances = {};

    // Effect Presets
    const PRESETS = {
        organic: {
            noiseScale: 5,
            radialIntensity: 12.5,
            radialOffset: 7
        },
        circular: {
            noiseScale: 1,
            radialIntensity: 20,
            radialOffset: 10
        },
        wave: {
            noiseScale: 8,
            radialIntensity: 8,
            radialOffset: 5
        },
        noise: {
            noiseScale: 12,
            radialIntensity: 5,
            radialOffset: 3
        }
    };

    // Classic Perlin Noise 3D (from Stefan Gustavson)
    const noiseGLSL = `
        vec4 permute(vec4 x) {
            return mod(((x*34.0)+1.0)*x, 289.0);
        }

        vec4 taylorInvSqrt(vec4 r) {
            return 1.79284291400159 - 0.85373472095314 * r;
        }

        vec3 fade(vec3 t) {
            return t*t*t*(t*(t*6.0-15.0)+10.0);
        }

        float cnoise(vec3 P) {
            vec3 Pi0 = floor(P);
            vec3 Pi1 = Pi0 + vec3(1.0);
            Pi0 = mod(Pi0, 289.0);
            Pi1 = mod(Pi1, 289.0);
            vec3 Pf0 = fract(P);
            vec3 Pf1 = Pf0 - vec3(1.0);
            vec4 ix = vec4(Pi0.x, Pi1.x, Pi0.x, Pi1.x);
            vec4 iy = vec4(Pi0.yy, Pi1.yy);
            vec4 iz0 = Pi0.zzzz;
            vec4 iz1 = Pi1.zzzz;

            vec4 ixy = permute(permute(ix) + iy);
            vec4 ixy0 = permute(ixy + iz0);
            vec4 ixy1 = permute(ixy + iz1);

            vec4 gx0 = ixy0 / 7.0;
            vec4 gy0 = fract(floor(gx0) / 7.0) - 0.5;
            gx0 = fract(gx0);
            vec4 gz0 = vec4(0.5) - abs(gx0) - abs(gy0);
            vec4 sz0 = step(gz0, vec4(0.0));
            gx0 -= sz0 * (step(0.0, gx0) - 0.5);
            gy0 -= sz0 * (step(0.0, gy0) - 0.5);

            vec4 gx1 = ixy1 / 7.0;
            vec4 gy1 = fract(floor(gx1) / 7.0) - 0.5;
            gx1 = fract(gx1);
            vec4 gz1 = vec4(0.5) - abs(gx1) - abs(gy1);
            vec4 sz1 = step(gz1, vec4(0.0));
            gx1 -= sz1 * (step(0.0, gx1) - 0.5);
            gy1 -= sz1 * (step(0.0, gy1) - 0.5);

            vec3 g000 = vec3(gx0.x,gy0.x,gz0.x);
            vec3 g100 = vec3(gx0.y,gy0.y,gz0.y);
            vec3 g010 = vec3(gx0.z,gy0.z,gz0.z);
            vec3 g110 = vec3(gx0.w,gy0.w,gz0.w);
            vec3 g001 = vec3(gx1.x,gy1.x,gz1.x);
            vec3 g101 = vec3(gx1.y,gy1.y,gz1.y);
            vec3 g011 = vec3(gx1.z,gy1.z,gz1.z);
            vec3 g111 = vec3(gx1.w,gy1.w,gz1.w);

            vec4 norm0 = taylorInvSqrt(vec4(dot(g000, g000), dot(g010, g010), dot(g100, g100), dot(g110, g110)));
            g000 *= norm0.x;
            g010 *= norm0.y;
            g100 *= norm0.z;
            g110 *= norm0.w;
            vec4 norm1 = taylorInvSqrt(vec4(dot(g001, g001), dot(g011, g011), dot(g101, g101), dot(g111, g111)));
            g001 *= norm1.x;
            g011 *= norm1.y;
            g101 *= norm1.z;
            g111 *= norm1.w;

            float n000 = dot(g000, Pf0);
            float n100 = dot(g100, vec3(Pf1.x, Pf0.yz));
            float n010 = dot(g010, vec3(Pf0.x, Pf1.y, Pf0.z));
            float n110 = dot(g110, vec3(Pf1.xy, Pf0.z));
            float n001 = dot(g001, vec3(Pf0.xy, Pf1.z));
            float n101 = dot(g101, vec3(Pf1.x, Pf0.y, Pf1.z));
            float n011 = dot(g011, vec3(Pf0.x, Pf1.yz));
            float n111 = dot(g111, Pf1);

            vec3 fade_xyz = fade(Pf0);
            vec4 n_z = mix(vec4(n000, n100, n010, n110), vec4(n001, n101, n011, n111), fade_xyz.z);
            vec2 n_yz = mix(n_z.xy, n_z.zw, fade_xyz.y);
            float n_xyz = mix(n_yz.x, n_yz.y, fade_xyz.x);
            return 2.2 * n_xyz;
        }
    `;

    // Vertex Shader
    const vertexShader = `
        uniform float uProgress;
        uniform float uWaveIntensity;
        uniform float uWaveFrequency;
        uniform float uWaveSpeed;
        uniform float uWaveEnabled;

        varying vec2 vUv;

        void main() {
            vec3 newPosition = position;

            // Wave displacement - displace x/y for visible effect with orthographic camera
            if (uWaveEnabled > 0.5) {
                // Center UV around 0 for direction calculation
                vec2 centeredUv = uv - vec2(0.5);
                float distanceToCenter = length(centeredUv);

                // Wave intensity fades as reveal progresses
                float waveFade = 1.0 - uProgress;

                // Radial wave emanating from center
                float wave = sin(distanceToCenter * uWaveFrequency - uProgress * uWaveSpeed) * uWaveIntensity * waveFade;

                // Displace outward from center (radial ripple)
                vec2 direction = distanceToCenter > 0.001 ? normalize(centeredUv) : vec2(0.0);
                newPosition.xy += direction * wave;
            }

            gl_Position = projectionMatrix * modelViewMatrix * vec4(newPosition, 1.0);
            vUv = uv;
        }
    `;

    // Fragment Shader
    const fragmentShader = noiseGLSL + `
        uniform sampler2D uTexture;
        uniform float uTime;
        uniform float uProgress;
        uniform float uNoiseScale;
        uniform float uRadialIntensity;
        uniform float uRadialOffset;
        uniform float uMixProgress;
        uniform sampler2D uTextureNext;

        varying vec2 vUv;

        void main() {
            // Displaced UVs using noise for organic movement
            vec2 displacedUv = vUv + cnoise(vec3(vUv * uNoiseScale, uTime * 0.1)) * 0.1;

            // Noise pattern for dissolution
            float strength = cnoise(vec3(displacedUv * uNoiseScale, uTime * 0.2));

            // Radial gradient centered at 0.5, 0.5
            float radialGradient = distance(vUv, vec2(0.5)) * uRadialIntensity - uRadialOffset * uProgress;

            // Combine noise and radial gradient
            strength += radialGradient;

            // Clamp and invert for reveal effect
            strength = clamp(strength, 0.0, 1.0);
            strength = 1.0 - strength;

            // Get texture color - support for gallery morphing
            vec3 currentColor = texture2D(uTexture, vUv).rgb;
            vec3 nextColor = texture2D(uTextureNext, vUv).rgb;
            vec3 textureColor = mix(currentColor, nextColor, uMixProgress);

            // Smooth opacity transition
            float opacityProgress = smoothstep(0.0, 0.7, uProgress);

            gl_FragColor = vec4(textureColor, strength * opacityProgress);
        }
    `;

    class ImageReveal {
        constructor(selector, id, options) {
            this.selector = selector;
            this.id = id;
            this.options = Object.assign({
                mode: 'single',
                images: [],
                trigger: 'hover',
                scrollOut: true,
                scrollThreshold: 0.3,
                autoPlayDelay: 1000,
                loop: false,
                loopDelay: 2000,
                gallery: {
                    autoplay: false,
                    autoplaySpeed: 4000,
                    pauseOnHover: true,
                    transitionStyle: 'dissolve',
                    loopGallery: true
                },
                effect: {
                    preset: 'organic',
                    noiseScale: 5,
                    radialIntensity: 12.5,
                    radialOffset: 7,
                    waveEnabled: true,
                    waveIntensity: 0.05,
                    waveFrequency: 15,
                    waveSpeed: 8
                },
                animation: {
                    duration: 1.5,
                    easeType: 'power3.inOut',
                    timeSpeed: 0.1,
                    revealDelay: 0
                },
                navigation: {
                    showDots: true,
                    showArrows: false
                }
            }, options);

            // Apply preset if not custom
            if (this.options.effect.preset !== 'custom' && PRESETS[this.options.effect.preset]) {
                Object.assign(this.options.effect, PRESETS[this.options.effect.preset]);
            }

            this.container = document.querySelector(selector);
            if (!this.container) {
                console.error('CWImageReveal: Container not found', selector);
                return;
            }

            this.canvas = this.container.querySelector('.cw-image-reveal-canvas');
            if (!this.canvas) {
                console.error('CWImageReveal: Canvas not found');
                return;
            }

            // Get the wrapper element for sizing (CSS styles are applied here)
            this.wrapper = this.container.querySelector('.cw-image-reveal-wrapper') || this.container;

            this.scene = null;
            this.camera = null;
            this.renderer = null;
            this.mesh = null;
            this.material = null;
            this.animationFrameId = null;
            this.progress = 0;
            this.mixProgress = 0;
            this.isRevealed = false;
            this.isAnimating = false;
            this.clock = new THREE.Clock();
            this.intersectionObserver = null;
            this.autoPlayTimeout = null;
            this.loopTimeout = null;
            this.galleryInterval = null;
            this.isPaused = false;

            // Gallery state
            this.textures = [];
            this.currentIndex = 0;
            this.nextIndex = 0;
            this.loadedCount = 0;

            if (this.options.images.length > 0) {
                this.init();
            } else {
                console.warn('CWImageReveal: No images provided');
            }
        }

        init() {
            this.setupRenderer();
            this.setupCamera();
            this.loadTextures();
        }

        setupRenderer() {
            this.scene = new THREE.Scene();

            this.renderer = new THREE.WebGLRenderer({
                canvas: this.canvas,
                antialias: true,
                alpha: true
            });

            // Get dimensions from wrapper (where CSS styles are applied)
            let width = this.wrapper.offsetWidth;
            let height = this.wrapper.offsetHeight;

            // Safety check: ensure we have valid dimensions
            if (width <= 0 || height <= 0) {
                // Try computed style as fallback
                const computed = window.getComputedStyle(this.wrapper);
                width = parseFloat(computed.width) || 500;
                height = parseFloat(computed.height) || 600;
            }

            // Final fallback to prevent crashes
            width = Math.max(width, 100);
            height = Math.max(height, 100);

            // Set canvas buffer size without updating CSS (3rd param = false)
            // This allows CSS to control the visual size while we set the render resolution
            this.renderer.setSize(width, height, false);
            this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            this.renderer.setClearColor(0x000000, 0);

            // Apply CSS sizing to let the wrapper control dimensions
            this.canvas.style.width = '100%';
            this.canvas.style.height = '100%';
        }

        setupCamera() {
            this.camera = new THREE.OrthographicCamera(-0.5, 0.5, 0.5, -0.5, 0.1, 10);
            this.camera.position.z = 1;
        }

        loadTextures() {
            const loader = new THREE.TextureLoader();
            loader.crossOrigin = 'anonymous';

            this.options.images.forEach((url, index) => {
                loader.load(
                    url,
                    (texture) => {
                        texture.minFilter = THREE.LinearFilter;
                        texture.magFilter = THREE.LinearFilter;
                        this.textures[index] = texture;
                        this.loadedCount++;

                        if (this.loadedCount === this.options.images.length) {
                            this.onTexturesLoaded();
                        }
                    },
                    undefined,
                    (error) => {
                        console.error('CWImageReveal: Failed to load texture', url, error);
                        this.loadedCount++;
                        if (this.loadedCount === this.options.images.length) {
                            this.onTexturesLoaded();
                        }
                    }
                );
            });
        }

        onTexturesLoaded() {
            if (this.textures.filter(t => t).length === 0) {
                console.error('CWImageReveal: No textures loaded successfully');
                return;
            }

            this.createMesh();
            this.bindEvents();
            this.setupNavigation();
            this.animate();

            // Start gallery autoplay if enabled
            if (this.options.mode === 'gallery' && this.options.gallery.autoplay) {
                this.startGalleryAutoplay();
            }
        }

        createMesh() {
            const geometry = new THREE.PlaneGeometry(1, 1, 32, 32);

            const firstTexture = this.textures[0] || this.createPlaceholderTexture();
            const secondTexture = this.textures[1] || firstTexture;

            this.material = new THREE.ShaderMaterial({
                vertexShader: vertexShader,
                fragmentShader: fragmentShader,
                uniforms: {
                    uTexture: { value: firstTexture },
                    uTextureNext: { value: secondTexture },
                    uTime: { value: 0 },
                    uProgress: { value: 0 },
                    uMixProgress: { value: 0 },
                    uNoiseScale: { value: this.options.effect.noiseScale },
                    uRadialIntensity: { value: this.options.effect.radialIntensity },
                    uRadialOffset: { value: this.options.effect.radialOffset },
                    uWaveEnabled: { value: this.options.effect.waveEnabled ? 1.0 : 0.0 },
                    uWaveIntensity: { value: this.options.effect.waveIntensity },
                    uWaveFrequency: { value: this.options.effect.waveFrequency },
                    uWaveSpeed: { value: this.options.effect.waveSpeed }
                },
                transparent: true,
                side: THREE.DoubleSide
            });

            this.mesh = new THREE.Mesh(geometry, this.material);
            this.scene.add(this.mesh);
        }

        createPlaceholderTexture() {
            const canvas = document.createElement('canvas');
            canvas.width = 2;
            canvas.height = 2;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#1a1a2e';
            ctx.fillRect(0, 0, 2, 2);
            return new THREE.CanvasTexture(canvas);
        }

        setupNavigation() {
            // Setup dot navigation
            const dotsContainer = this.container.querySelector('.cw-image-reveal-dots');
            if (dotsContainer && this.options.mode === 'gallery') {
                dotsContainer.innerHTML = '';
                this.options.images.forEach((_, index) => {
                    const dot = document.createElement('button');
                    dot.className = 'cw-image-reveal-dot' + (index === 0 ? ' active' : '');
                    dot.dataset.index = index;
                    dot.addEventListener('click', () => this.goToSlide(index));
                    dotsContainer.appendChild(dot);
                });
            }

            // Setup arrow navigation
            const prevBtn = this.container.querySelector('.cw-image-reveal-prev');
            const nextBtn = this.container.querySelector('.cw-image-reveal-next');

            if (prevBtn) {
                prevBtn.addEventListener('click', () => this.prevSlide());
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', () => this.nextSlide());
            }
        }

        updateDots() {
            const dots = this.container.querySelectorAll('.cw-image-reveal-dot');
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === this.currentIndex);
            });
        }

        bindEvents() {
            // Resize handler
            this.resizeHandler = () => this.onResize();
            window.addEventListener('resize', this.resizeHandler);

            // Trigger-based events
            switch (this.options.trigger) {
                case 'hover':
                    this.mouseEnterHandler = () => this.reveal();
                    this.mouseLeaveHandler = () => this.hide();
                    this.container.addEventListener('mouseenter', this.mouseEnterHandler);
                    this.container.addEventListener('mouseleave', this.mouseLeaveHandler);
                    break;

                case 'click':
                    this.clickHandler = () => this.toggle();
                    this.container.addEventListener('click', this.clickHandler);
                    break;

                case 'scroll':
                    this.setupIntersectionObserver();
                    break;

                case 'auto':
                    this.autoPlayTimeout = setTimeout(() => {
                        this.reveal();
                        if (this.options.loop) {
                            this.startLoop();
                        }
                    }, this.options.autoPlayDelay);
                    break;
            }

            // Gallery pause on hover
            if (this.options.mode === 'gallery' && this.options.gallery.pauseOnHover) {
                this.container.addEventListener('mouseenter', () => {
                    this.isPaused = true;
                });
                this.container.addEventListener('mouseleave', () => {
                    this.isPaused = false;
                });
            }
        }

        setupIntersectionObserver() {
            const threshold = this.options.scrollThreshold;

            this.intersectionObserver = new IntersectionObserver(
                (entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            if (!this.isRevealed) {
                                setTimeout(() => this.reveal(), this.options.animation.revealDelay);
                            }
                        } else if (this.options.scrollOut && this.isRevealed) {
                            this.hide();
                        }
                    });
                },
                { threshold: threshold }
            );
            this.intersectionObserver.observe(this.container);
        }

        startLoop() {
            const loopAnimation = () => {
                this.loopTimeout = setTimeout(() => {
                    if (this.isRevealed) {
                        this.hide();
                        this.loopTimeout = setTimeout(() => {
                            this.reveal();
                            loopAnimation();
                        }, this.options.loopDelay);
                    } else {
                        this.reveal();
                        loopAnimation();
                    }
                }, this.options.loopDelay);
            };
            loopAnimation();
        }

        startGalleryAutoplay() {
            this.galleryInterval = setInterval(() => {
                if (!this.isPaused && !this.isAnimating) {
                    this.nextSlide();
                }
            }, this.options.gallery.autoplaySpeed);
        }

        stopGalleryAutoplay() {
            if (this.galleryInterval) {
                clearInterval(this.galleryInterval);
                this.galleryInterval = null;
            }
        }

        goToSlide(index) {
            if (this.isAnimating || index === this.currentIndex) return;
            if (index < 0 || index >= this.textures.length) return;

            this.nextIndex = index;
            this.transitionToSlide();
        }

        nextSlide() {
            if (this.isAnimating) return;

            let next = this.currentIndex + 1;
            if (next >= this.textures.length) {
                if (this.options.gallery.loopGallery) {
                    next = 0;
                } else {
                    return;
                }
            }

            this.nextIndex = next;
            this.transitionToSlide();
        }

        prevSlide() {
            if (this.isAnimating) return;

            let prev = this.currentIndex - 1;
            if (prev < 0) {
                if (this.options.gallery.loopGallery) {
                    prev = this.textures.length - 1;
                } else {
                    return;
                }
            }

            this.nextIndex = prev;
            this.transitionToSlide();
        }

        transitionToSlide() {
            if (!this.textures[this.nextIndex]) return;

            this.isAnimating = true;
            const style = this.options.gallery.transitionStyle;

            // Set next texture
            this.material.uniforms.uTextureNext.value = this.textures[this.nextIndex];

            if (style === 'dissolve') {
                // Dissolve out, then dissolve in with new image
                gsap.to(this, {
                    progress: 0,
                    duration: this.options.animation.duration * 0.5,
                    ease: this.options.animation.easeType,
                    onUpdate: () => {
                        this.material.uniforms.uProgress.value = this.progress;
                    },
                    onComplete: () => {
                        // Swap textures
                        this.material.uniforms.uTexture.value = this.textures[this.nextIndex];
                        this.currentIndex = this.nextIndex;
                        this.updateDots();

                        gsap.to(this, {
                            progress: 1,
                            duration: this.options.animation.duration * 0.5,
                            ease: this.options.animation.easeType,
                            onUpdate: () => {
                                this.material.uniforms.uProgress.value = this.progress;
                            },
                            onComplete: () => {
                                this.isAnimating = false;
                            }
                        });
                    }
                });
            } else if (style === 'morph') {
                // Cross-fade between images
                gsap.to(this, {
                    mixProgress: 1,
                    duration: this.options.animation.duration,
                    ease: this.options.animation.easeType,
                    onUpdate: () => {
                        this.material.uniforms.uMixProgress.value = this.mixProgress;
                    },
                    onComplete: () => {
                        this.material.uniforms.uTexture.value = this.textures[this.nextIndex];
                        this.material.uniforms.uMixProgress.value = 0;
                        this.mixProgress = 0;
                        this.currentIndex = this.nextIndex;
                        this.updateDots();
                        this.isAnimating = false;
                    }
                });
            } else {
                // Fade through (hide then show)
                gsap.to(this, {
                    progress: 0,
                    duration: this.options.animation.duration * 0.4,
                    ease: 'power2.in',
                    onUpdate: () => {
                        this.material.uniforms.uProgress.value = this.progress;
                    },
                    onComplete: () => {
                        this.material.uniforms.uTexture.value = this.textures[this.nextIndex];
                        this.currentIndex = this.nextIndex;
                        this.updateDots();

                        gsap.to(this, {
                            progress: 1,
                            duration: this.options.animation.duration * 0.6,
                            ease: 'power2.out',
                            onUpdate: () => {
                                this.material.uniforms.uProgress.value = this.progress;
                            },
                            onComplete: () => {
                                this.isAnimating = false;
                            }
                        });
                    }
                });
            }
        }

        reveal() {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.isRevealed = true;

            gsap.to(this, {
                progress: 1,
                duration: this.options.animation.duration,
                ease: this.options.animation.easeType,
                onUpdate: () => {
                    if (this.material) {
                        this.material.uniforms.uProgress.value = this.progress;
                    }
                },
                onComplete: () => {
                    this.isAnimating = false;
                }
            });
        }

        hide() {
            if (this.isAnimating) return;
            this.isAnimating = true;
            this.isRevealed = false;

            gsap.to(this, {
                progress: 0,
                duration: this.options.animation.duration,
                ease: this.options.animation.easeType,
                onUpdate: () => {
                    if (this.material) {
                        this.material.uniforms.uProgress.value = this.progress;
                    }
                },
                onComplete: () => {
                    this.isAnimating = false;
                }
            });
        }

        toggle() {
            if (this.isRevealed) {
                this.hide();
            } else {
                this.reveal();
            }
        }

        onResize() {
            // Get dimensions from wrapper (where CSS styles are applied)
            let width = this.wrapper.offsetWidth;
            let height = this.wrapper.offsetHeight;

            // Safety check: ensure we have valid dimensions
            if (width <= 0 || height <= 0) {
                const computed = window.getComputedStyle(this.wrapper);
                width = parseFloat(computed.width) || 500;
                height = parseFloat(computed.height) || 600;
            }

            // Don't update if dimensions are invalid
            if (width <= 0 || height <= 0) return;

            this.camera.updateProjectionMatrix();
            // Update buffer size without changing CSS (3rd param = false)
            this.renderer.setSize(width, height, false);
        }

        animate() {
            this.animationFrameId = requestAnimationFrame(() => this.animate());

            // Update time uniform
            if (this.material) {
                this.material.uniforms.uTime.value = this.clock.getElapsedTime() * this.options.animation.timeSpeed;
            }

            this.renderer.render(this.scene, this.camera);
        }

        destroy() {
            // Stop animation loop
            if (this.animationFrameId) {
                cancelAnimationFrame(this.animationFrameId);
            }

            // Clear timeouts/intervals
            if (this.autoPlayTimeout) clearTimeout(this.autoPlayTimeout);
            if (this.loopTimeout) clearTimeout(this.loopTimeout);
            if (this.galleryInterval) clearInterval(this.galleryInterval);

            // Remove event listeners
            if (this.resizeHandler) {
                window.removeEventListener('resize', this.resizeHandler);
            }
            if (this.mouseEnterHandler) {
                this.container.removeEventListener('mouseenter', this.mouseEnterHandler);
            }
            if (this.mouseLeaveHandler) {
                this.container.removeEventListener('mouseleave', this.mouseLeaveHandler);
            }
            if (this.clickHandler) {
                this.container.removeEventListener('click', this.clickHandler);
            }

            // Disconnect intersection observer
            if (this.intersectionObserver) {
                this.intersectionObserver.disconnect();
            }

            // Dispose textures
            this.textures.forEach(texture => {
                if (texture) texture.dispose();
            });

            // Dispose Three.js resources
            if (this.mesh) {
                if (this.mesh.geometry) this.mesh.geometry.dispose();
                if (this.mesh.material) this.mesh.material.dispose();
                this.scene.remove(this.mesh);
            }

            if (this.renderer) {
                this.renderer.dispose();
            }

            this.textures = [];
            this.mesh = null;
            this.material = null;
            this.scene = null;
            this.camera = null;
            this.renderer = null;
        }
    }

    // Global API
    window.CWImageReveal = {
        init: function(selector, id, options) {
            if (instances[id]) {
                instances[id].destroy();
            }

            instances[id] = new ImageReveal(selector, id, options);
            return instances[id];
        },

        reveal: function(id) {
            if (instances[id]) instances[id].reveal();
        },

        hide: function(id) {
            if (instances[id]) instances[id].hide();
        },

        toggle: function(id) {
            if (instances[id]) instances[id].toggle();
        },

        goToSlide: function(id, index) {
            if (instances[id]) instances[id].goToSlide(index);
        },

        nextSlide: function(id) {
            if (instances[id]) instances[id].nextSlide();
        },

        prevSlide: function(id) {
            if (instances[id]) instances[id].prevSlide();
        },

        destroy: function(id) {
            if (instances[id]) {
                instances[id].destroy();
                delete instances[id];
            }
        },

        getInstance: function(id) {
            return instances[id] || null;
        }
    };

})();
