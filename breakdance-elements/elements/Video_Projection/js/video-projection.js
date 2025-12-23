/**
 * CW Video Projection - Three.js Video Projection Mapping
 * Based on Codrops tutorial by VWLAB
 * https://tympanus.net/codrops/2025/08/28/interactive-video-projection-mapping-with-three-js/
 */

(function() {
    'use strict';

    const instances = {};

    class VideoProjection {
        constructor(selector, id, options) {
            this.selector = selector;
            this.id = id;
            this.options = Object.assign({
                projections: [],
                defaultProjection: 'projection-1',
                grid: {
                    size: 15,
                    spacing: 0.75,
                    cubeSize: 0.5,
                    brightnessThreshold: 128
                },
                camera: {
                    fov: 35,
                    distance: 6,
                    enableControls: false,
                    autoRotate: false,
                    autoRotateSpeed: 2
                },
                lighting: {
                    ambientIntensity: 1,
                    directionalIntensity: 10,
                    lightColor: '#ffffff'
                },
                animation: {
                    staggerDelay: 0.001,
                    scaleDuration: 1,
                    positionDuration: 1,
                    easeType: 'power3.inOut'
                },
                wave: {
                    enabled: true,
                    speed: 0.005,
                    intensity: 0.6,
                    offset: 0.1
                },
                mouse: {
                    enabled: true,
                    radius: 2.5,
                    intensity: 1.5,
                    smoothing: 0.1
                },
                position: {
                    offsetX: 0,
                    offsetY: 0,
                    scale: 1
                },
                showButtons: true
            }, options);

            this.container = document.querySelector(selector);
            if (!this.container) {
                console.error('CWVideoProjection: Container not found', selector);
                return;
            }

            this.canvas = this.container.querySelector('.cw-video-projection-canvas');
            if (!this.canvas) {
                console.error('CWVideoProjection: Canvas not found');
                return;
            }

            this.scene = null;
            this.camera = null;
            this.renderer = null;
            this.controls = null;
            this.mainGroup = null;
            this.grids = [];
            this.gridsByName = {};
            this.current = null;
            this.old = null;
            this.isAnimating = false;
            this.animationFrameId = null;
            this.isReady = false;
            this.loadedCount = 0;
            this.totalToLoad = 0;

            // Mouse tracking
            this.mouse = new THREE.Vector2(-1000, -1000);
            this.targetMouse = new THREE.Vector2(-1000, -1000);
            this.raycaster = new THREE.Raycaster();
            this.mouseWorldPos = new THREE.Vector3();
            this.plane = new THREE.Plane(new THREE.Vector3(0, 0, 1), 0);

            this.init();
        }

        init() {
            this.setupRenderer();
            this.setupCamera();
            this.setupLighting();
            this.setupControls();
            this.mainGroup = new THREE.Group();
            this.scene.add(this.mainGroup);
            this.applyPosition();
            this.loadProjections();
            this.bindEvents();
            this.animate();
        }

        setupRenderer() {
            this.scene = new THREE.Scene();

            this.renderer = new THREE.WebGLRenderer({
                canvas: this.canvas,
                antialias: true,
                alpha: true
            });

            this.renderer.setSize(this.container.offsetWidth, this.container.offsetHeight);
            this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            this.renderer.setClearColor(0x000000, 0);
        }

        setupCamera() {
            const aspect = this.container.offsetWidth / this.container.offsetHeight;
            this.camera = new THREE.PerspectiveCamera(
                this.options.camera.fov,
                aspect,
                0.1,
                1000
            );
            this.camera.position.z = this.options.camera.distance;
        }

        setupLighting() {
            const lightColor = new THREE.Color(this.options.lighting.lightColor);

            const ambientLight = new THREE.AmbientLight(lightColor, this.options.lighting.ambientIntensity);
            this.scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(lightColor, this.options.lighting.directionalIntensity);
            directionalLight.position.set(5, 5, 5);
            this.scene.add(directionalLight);

            this.ambientLight = ambientLight;
            this.directionalLight = directionalLight;
        }

        setupControls() {
            if (this.options.camera.enableControls && typeof THREE.OrbitControls !== 'undefined') {
                this.controls = new THREE.OrbitControls(this.camera, this.renderer.domElement);
                this.controls.enableDamping = true;
                this.controls.dampingFactor = 0.05;
                this.controls.autoRotate = this.options.camera.autoRotate;
                this.controls.autoRotateSpeed = this.options.camera.autoRotateSpeed;
            }
        }

        applyPosition() {
            if (!this.mainGroup) return;

            const { offsetX, offsetY, scale } = this.options.position;
            this.mainGroup.position.x = offsetX;
            this.mainGroup.position.y = offsetY;
            this.mainGroup.scale.setScalar(scale);
        }

        // Convert 8-character hex (#RRGGBBAA) to rgba() for better browser support
        hexToRgba(hex) {
            if (!hex) return null;
            // Remove # if present
            hex = hex.replace('#', '');

            // Handle 8-character hex with alpha
            if (hex.length === 8) {
                const r = parseInt(hex.substring(0, 2), 16);
                const g = parseInt(hex.substring(2, 4), 16);
                const b = parseInt(hex.substring(4, 6), 16);
                const a = parseInt(hex.substring(6, 8), 16) / 255;
                return `rgba(${r}, ${g}, ${b}, ${a.toFixed(2)})`;
            }
            // Handle 6-character hex
            if (hex.length === 6) {
                const r = parseInt(hex.substring(0, 2), 16);
                const g = parseInt(hex.substring(2, 4), 16);
                const b = parseInt(hex.substring(4, 6), 16);
                return `rgb(${r}, ${g}, ${b})`;
            }
            // Return original if format not recognized
            return '#' + hex;
        }

        getThemeBackground(themeData) {
            if (!themeData) return null;

            console.log('CWVideoProjection: getThemeBackground called with:', themeData);

            if (themeData.type === 'gradient' && themeData.gradientColor1 && themeData.gradientColor2) {
                const color1 = this.hexToRgba(themeData.gradientColor1);
                const color2 = this.hexToRgba(themeData.gradientColor2);

                let gradient;
                if (themeData.gradientType === 'radial') {
                    gradient = `radial-gradient(circle, ${color1} 0%, ${color2} 100%)`;
                } else {
                    const angle = themeData.gradientAngle || 135;
                    gradient = `linear-gradient(${angle}deg, ${color1} 0%, ${color2} 100%)`;
                }
                console.log('CWVideoProjection: Generated gradient:', gradient);
                return gradient;
            } else if (themeData.color) {
                const color = this.hexToRgba(themeData.color);
                console.log('CWVideoProjection: Using solid color:', color);
                return color;
            }

            return null;
        }

        applyThemeBackground(themeData) {
            const background = this.getThemeBackground(themeData);
            console.log('CWVideoProjection: applyThemeBackground - background value:', background);

            // Apply to wrapper element (not canvas) so it shows behind the transparent WebGL render
            const wrapper = this.container.querySelector('.cw-video-projection-wrapper');
            const targetEl = wrapper || this.container;

            if (background) {
                // Check if it's a gradient (contains 'gradient') or solid color
                if (background.includes('gradient')) {
                    targetEl.style.backgroundColor = '';
                    targetEl.style.background = background;
                    console.log('CWVideoProjection: Applied gradient to wrapper:', background);
                } else {
                    targetEl.style.background = '';
                    targetEl.style.backgroundColor = background;
                    console.log('CWVideoProjection: Applied solid color to wrapper:', background);
                }
            }
        }

        extractMediaUrl(media) {
            if (!media) return null;

            // String URL
            if (typeof media === 'string') return media;

            // Object with url property
            if (typeof media === 'object') {
                // Direct url property
                if (media.url) return media.url;

                // Sizes object (Breakdance format)
                if (media.sizes) {
                    // Try full size first, then large, then any available
                    if (media.sizes.full?.url) return media.sizes.full.url;
                    if (media.sizes.large?.url) return media.sizes.large.url;
                    const firstSize = Object.values(media.sizes)[0];
                    if (firstSize?.url) return firstSize.url;
                }

                // rawUrl property
                if (media.rawUrl) return media.rawUrl;
            }

            return null;
        }

        loadProjections() {
            const projections = this.options.projections;

            // Debug: Log what we received
            console.log('CWVideoProjection: Received projections:', JSON.stringify(projections, null, 2));

            this.totalToLoad = projections.filter(p => {
                const maskUrl = this.extractMediaUrl(p.mask_image);
                const videoUrl = p.video_url || this.extractMediaUrl(p.video);
                console.log('CWVideoProjection: Checking projection:', p.id, '| maskUrl:', maskUrl, '| videoUrl:', videoUrl);
                return maskUrl && videoUrl;
            }).length;

            if (this.totalToLoad === 0) {
                console.warn('CWVideoProjection: No valid projections to load. Please add a mask image and video.');
                console.log('CWVideoProjection: Raw projection data for debugging:', projections);
                this.isReady = true;
                return;
            }

            projections.forEach((projection, index) => {
                const maskUrl = this.extractMediaUrl(projection.mask_image);
                const videoUrl = projection.video_url || this.extractMediaUrl(projection.video);

                if (maskUrl && videoUrl) {
                    console.log('CWVideoProjection: Loading projection:', projection.id, '| mask:', maskUrl, '| video:', videoUrl);
                    // Build theme data object with all gradient properties
                    const themeData = {
                        type: projection.theme_type || 'solid',
                        color: projection.theme_color,
                        gradientType: projection.theme_gradient_type || 'linear',
                        gradientAngle: projection.theme_gradient_angle || 135,
                        gradientColor1: projection.theme_gradient_color_1,
                        gradientColor2: projection.theme_gradient_color_2
                    };
                    this.createMask(projection.id, maskUrl, videoUrl, themeData, index);
                }
            });
        }

        createMask(id, maskUrl, videoUrl, themeData, index) {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            const maskImage = new Image();
            maskImage.crossOrigin = 'anonymous';

            maskImage.onload = () => {
                console.log('CWVideoProjection: Mask image loaded:', maskUrl, '| Size:', maskImage.width, 'x', maskImage.height);

                const originalWidth = maskImage.width;
                const originalHeight = maskImage.height;
                const aspectRatio = originalWidth / originalHeight;

                let gridWidth, gridHeight;
                const gridSize = this.options.grid.size;

                // Calculate grid dimensions based on mask aspect ratio
                if (aspectRatio > 1) {
                    gridWidth = gridSize;
                    gridHeight = Math.round(gridSize / aspectRatio);
                } else {
                    gridHeight = gridSize;
                    gridWidth = Math.round(gridSize * aspectRatio);
                }

                console.log('CWVideoProjection: Grid dimensions:', gridWidth, 'x', gridHeight, '| Threshold:', this.options.grid.brightnessThreshold);

                canvas.width = gridWidth;
                canvas.height = gridHeight;
                ctx.drawImage(maskImage, 0, 0, gridWidth, gridHeight);

                const imageData = ctx.getImageData(0, 0, gridWidth, gridHeight);
                const data = imageData.data;

                // Debug: Sample some pixel values
                let opaqueCount = 0;
                let transparentCount = 0;
                for (let i = 0; i < data.length; i += 4) {
                    const alpha = data[i + 3];
                    if (alpha > 128) {
                        opaqueCount++;
                    } else {
                        transparentCount++;
                    }
                }
                console.log('CWVideoProjection: Mask analysis - Opaque pixels (will show cubes):', opaqueCount, '| Transparent pixels (hidden):', transparentCount);

                this.createGrid(id, data, gridWidth, gridHeight, videoUrl, themeData, index);
            };

            maskImage.onerror = (err) => {
                console.error('CWVideoProjection: Failed to load mask image', maskUrl, err);
                this.loadedCount++;
                this.checkReady();
            };

            maskImage.src = maskUrl;
        }

        createVideoTexture(videoUrl) {
            const video = document.createElement('video');
            video.src = videoUrl;
            video.crossOrigin = 'anonymous';
            video.loop = true;
            video.muted = true;
            video.playsInline = true;
            video.play().catch(() => {});

            const texture = new THREE.VideoTexture(video);
            texture.minFilter = THREE.LinearFilter;
            texture.magFilter = THREE.LinearFilter;
            texture.colorSpace = THREE.SRGBColorSpace;
            texture.wrapS = THREE.ClampToEdgeWrapping;
            texture.wrapT = THREE.ClampToEdgeWrapping;

            return { texture, video };
        }

        createGrid(id, maskData, gridWidth, gridHeight, videoUrl, themeData, index) {
            console.log('CWVideoProjection: Creating grid:', id, '| Dimensions:', gridWidth, 'x', gridHeight);

            const { texture: videoTexture, video } = this.createVideoTexture(videoUrl);

            const gridGroup = new THREE.Group();
            gridGroup.name = id;
            gridGroup.userData = { id, themeData, video, videoTexture };

            const { spacing, cubeSize, brightnessThreshold } = this.options.grid;
            let cubeCount = 0;

            // Create shared material with video texture
            const material = new THREE.MeshBasicMaterial({
                map: videoTexture,
                side: THREE.FrontSide
            });

            const halfGridX = (gridWidth - 1) / 2;
            const halfGridY = (gridHeight - 1) / 2;

            for (let x = 0; x < gridWidth; x++) {
                for (let y = 0; y < gridHeight; y++) {
                    // Flip Y when reading mask (critical for correct orientation!)
                    const flippedY = gridHeight - 1 - y;
                    const pixelIndex = (flippedY * gridWidth + x) * 4;
                    const r = maskData[pixelIndex];
                    const g = maskData[pixelIndex + 1];
                    const b = maskData[pixelIndex + 2];
                    const a = maskData[pixelIndex + 3];

                    const brightness = (r + g + b) / 3;

                    // Masking logic:
                    // - For transparent PNGs: show cube where alpha > 128 (opaque areas)
                    // - For solid images: show cube where brightness < threshold (dark areas)
                    // Combined: show if opaque AND (has transparency in image OR is dark)
                    const isOpaque = a > 128;
                    const isDark = brightness < brightnessThreshold;

                    // Primary: use alpha channel (works for transparent PNGs like shapes)
                    // Secondary: for fully opaque images, also use brightness threshold
                    if (isOpaque) {
                        const geometry = new THREE.BoxGeometry(cubeSize, cubeSize, cubeSize);

                        // Calculate UV coordinates for this cube's portion of the video
                        const uvX = x / gridWidth;
                        const uvY = y / gridHeight;
                        const uvWidth = 1 / gridWidth;
                        const uvHeight = 1 / gridHeight;

                        const uvAttribute = geometry.attributes.uv;
                        const uvArray = uvAttribute.array;

                        // Map each UV coordinate to the specific portion of the video
                        for (let i = 0; i < uvArray.length; i += 2) {
                            uvArray[i] = uvX + (uvArray[i] * uvWidth);
                            uvArray[i + 1] = uvY + (uvArray[i + 1] * uvHeight);
                        }
                        uvAttribute.needsUpdate = true;

                        const mesh = new THREE.Mesh(geometry, material);
                        // Store base position for animation calculations
                        mesh.userData.baseX = (x - halfGridX) * spacing;
                        mesh.userData.baseY = (y - halfGridY) * spacing;
                        mesh.userData.baseZ = 0;
                        mesh.userData.targetZ = 0;
                        mesh.userData.currentZ = 0;

                        mesh.position.x = mesh.userData.baseX;
                        mesh.position.y = mesh.userData.baseY;
                        mesh.position.z = -6; // Start hidden behind camera

                        // Start with scale 0 (hidden)
                        mesh.scale.set(0, 0, 0);

                        gridGroup.add(mesh);
                        cubeCount++;
                    }
                }
            }

            console.log('CWVideoProjection: Created', cubeCount, 'cubes for grid:', id);

            // Scale down the whole group to fit nicely in view
            gridGroup.scale.setScalar(0.5);

            this.grids.push(gridGroup);
            this.gridsByName[id] = gridGroup;
            this.mainGroup.add(gridGroup);

            this.loadedCount++;
            this.checkReady();
        }

        checkReady() {
            if (this.loadedCount >= this.totalToLoad) {
                this.isReady = true;
                this.initInteractions();
            }
        }

        initInteractions() {
            // Set default current projection
            const defaultId = this.options.defaultProjection;
            if (this.gridsByName[defaultId]) {
                this.current = defaultId;
            } else if (this.grids.length > 0) {
                this.current = this.grids[0].name;
            }

            if (!this.current) return;

            // Set initial background (solid or gradient)
            const currentGrid = this.gridsByName[this.current];
            if (currentGrid && currentGrid.userData.themeData) {
                this.applyThemeBackground(currentGrid.userData.themeData);
            }

            // Update canvas data attribute
            this.canvas.dataset.current = this.current;

            // Update active button
            const buttons = this.container.querySelectorAll('.cw-video-projection-btn');
            buttons.forEach(btn => {
                btn.classList.toggle('active', btn.dataset.id === this.current);
            });

            // Reveal the current grid immediately (no animation on init)
            this.revealGridInstant(this.current);
        }

        revealGridInstant(id) {
            const grid = this.gridsByName[id];
            if (!grid) return;

            grid.children.forEach(child => {
                child.scale.set(1, 1, 1);
                child.position.z = child.userData.baseZ;
                child.userData.currentZ = child.userData.baseZ;
                child.userData.targetZ = child.userData.baseZ;
            });
        }

        showGrid(id) {
            if (!this.gridsByName[id] || this.isAnimating) return;
            if (this.current === id) return;

            this.isAnimating = true;
            this.old = this.current;
            this.current = id;

            // Update canvas data attribute
            this.canvas.dataset.current = id;

            // Update background (solid or gradient)
            const newGrid = this.gridsByName[id];
            if (newGrid && newGrid.userData.themeData) {
                this.applyThemeBackground(newGrid.userData.themeData);
            }

            // Update active button
            const buttons = this.container.querySelectorAll('.cw-video-projection-btn');
            buttons.forEach(btn => {
                btn.classList.toggle('active', btn.dataset.id === id);
            });

            // Animate transitions
            this.hideGrid();
            this.revealGrid();
        }

        revealGrid() {
            const grid = this.gridsByName[this.current];
            if (!grid) {
                this.isAnimating = false;
                return;
            }

            const { staggerDelay, scaleDuration, easeType } = this.options.animation;

            const tl = gsap.timeline({
                delay: scaleDuration * 0.25,
                defaults: { ease: easeType, duration: scaleDuration }
            });

            grid.children.forEach((child, index) => {
                // Reset position to behind
                child.position.z = -6;
                child.userData.currentZ = -6;
                child.userData.targetZ = child.userData.baseZ;
                child.scale.set(0, 0, 0);

                tl.to(child.scale, { x: 1, y: 1, z: 1 }, index * staggerDelay)
                  .to(child.userData, {
                      currentZ: child.userData.baseZ,
                      onUpdate: function() {
                          child.position.z = child.userData.currentZ;
                      }
                  }, '<');
            });
        }

        hideGrid() {
            const grid = this.gridsByName[this.old];
            if (!grid) return;

            const { staggerDelay, scaleDuration, easeType } = this.options.animation;

            const tl = gsap.timeline({
                defaults: { ease: easeType, duration: scaleDuration },
                onComplete: () => {
                    this.isAnimating = false;
                }
            });

            grid.children.forEach((child, index) => {
                tl.to(child.scale, { x: 0, y: 0, z: 0 }, index * staggerDelay)
                  .to(child.userData, {
                      currentZ: 6,
                      onUpdate: function() {
                          child.position.z = child.userData.currentZ;
                      },
                      onComplete: () => {
                          gsap.set(child.scale, { x: 0, y: 0, z: 0 });
                          child.userData.currentZ = -6;
                          child.position.z = -6;
                      }
                  }, '<');
            });
        }

        onMouseMove(e) {
            const rect = this.canvas.getBoundingClientRect();
            // Normalize mouse coordinates to -1 to 1
            this.targetMouse.x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            this.targetMouse.y = -((e.clientY - rect.top) / rect.height) * 2 + 1;
        }

        onMouseLeave() {
            // Move mouse position far away when leaving
            this.targetMouse.x = -1000;
            this.targetMouse.y = -1000;
        }

        updateMouseWorldPosition() {
            // Smooth mouse movement
            const smoothing = this.options.mouse.smoothing;
            this.mouse.x += (this.targetMouse.x - this.mouse.x) * smoothing;
            this.mouse.y += (this.targetMouse.y - this.mouse.y) * smoothing;

            // Convert mouse position to world coordinates on the Z=0 plane
            this.raycaster.setFromCamera(this.mouse, this.camera);
            this.raycaster.ray.intersectPlane(this.plane, this.mouseWorldPos);
        }

        bindEvents() {
            // Button clicks
            const buttons = this.container.querySelectorAll('.cw-video-projection-btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const id = btn.dataset.id;
                    if (id && this.gridsByName[id]) {
                        this.showGrid(id);
                    }
                });
            });

            // Mouse tracking
            this.mouseMoveHandler = (e) => this.onMouseMove(e);
            this.mouseLeaveHandler = () => this.onMouseLeave();
            this.canvas.addEventListener('mousemove', this.mouseMoveHandler);
            this.canvas.addEventListener('mouseleave', this.mouseLeaveHandler);

            // Resize handler
            this.resizeHandler = () => this.onResize();
            window.addEventListener('resize', this.resizeHandler);
        }

        onResize() {
            const width = this.container.offsetWidth;
            const height = this.container.offsetHeight;

            this.camera.aspect = width / height;
            this.camera.updateProjectionMatrix();

            this.renderer.setSize(width, height);
        }

        animate() {
            this.animationFrameId = requestAnimationFrame(() => this.animate());

            if (this.controls) {
                this.controls.update();
            }

            // Update video textures for all grids
            this.grids.forEach(grid => {
                if (grid.userData.videoTexture) {
                    grid.userData.videoTexture.needsUpdate = true;
                }
            });

            // Update mouse world position
            if (this.options.mouse.enabled) {
                this.updateMouseWorldPosition();
            }

            // Apply animations to current grid cubes
            if (this.isReady && !this.isAnimating) {
                const currentGrid = this.gridsByName[this.current];
                if (currentGrid) {
                    const { speed, intensity, offset } = this.options.wave;
                    const { radius, intensity: mouseIntensity, enabled: mouseEnabled } = this.options.mouse;
                    const time = Date.now();
                    const waveEnabled = this.options.wave.enabled;

                    // Account for all group transforms when calculating mouse distance
                    // Grid group scale (0.5 default, set in createGrid)
                    const gridScale = currentGrid.scale.x;
                    // Main group scale and position (from position options)
                    const mainScale = this.mainGroup.scale.x;
                    const mainOffsetX = this.mainGroup.position.x;
                    const mainOffsetY = this.mainGroup.position.y;

                    currentGrid.children.forEach((cube, index) => {
                        // Only animate if cube is visible (scale > 0)
                        if (cube.scale.x > 0) {
                            let targetZ = cube.userData.baseZ;

                            // Wave animation
                            if (waveEnabled) {
                                targetZ += Math.sin(time * speed + index * offset) * intensity;
                            }

                            // Mouse proximity effect
                            if (mouseEnabled && this.mouse.x > -100) {
                                // Get cube world position (transformed through grid group -> main group)
                                const cubeWorldX = (cube.userData.baseX * gridScale * mainScale) + mainOffsetX;
                                const cubeWorldY = (cube.userData.baseY * gridScale * mainScale) + mainOffsetY;

                                const dx = this.mouseWorldPos.x - cubeWorldX;
                                const dy = this.mouseWorldPos.y - cubeWorldY;
                                const distance = Math.sqrt(dx * dx + dy * dy);

                                if (distance < radius) {
                                    // Cubes push out towards the camera when mouse is near
                                    const force = (1 - distance / radius) * mouseIntensity;
                                    targetZ += force;
                                }
                            }

                            // Smooth Z position transition
                            cube.userData.targetZ = targetZ;
                            cube.userData.currentZ += (cube.userData.targetZ - cube.userData.currentZ) * 0.15;
                            cube.position.z = cube.userData.currentZ;
                        }
                    });
                }
            }

            this.renderer.render(this.scene, this.camera);
        }

        update(options) {
            // Update wave options
            if (options.wave) {
                Object.assign(this.options.wave, options.wave);
            }

            // Update mouse options
            if (options.mouse) {
                Object.assign(this.options.mouse, options.mouse);
            }

            // Only update certain options without rebuilding grids
            if (options.camera) {
                if (options.camera.fov !== undefined) {
                    this.camera.fov = options.camera.fov;
                }
                if (options.camera.distance !== undefined) {
                    this.camera.position.z = options.camera.distance;
                }
                this.camera.updateProjectionMatrix();
            }

            if (options.lighting) {
                if (this.ambientLight && options.lighting.ambientIntensity !== undefined) {
                    this.ambientLight.intensity = options.lighting.ambientIntensity;
                }
                if (this.directionalLight && options.lighting.directionalIntensity !== undefined) {
                    this.directionalLight.intensity = options.lighting.directionalIntensity;
                }
                if (options.lighting.lightColor) {
                    const color = new THREE.Color(options.lighting.lightColor);
                    if (this.ambientLight) this.ambientLight.color = color;
                    if (this.directionalLight) this.directionalLight.color = color;
                }
            }

            if (this.controls && options.camera) {
                if (options.camera.autoRotate !== undefined) {
                    this.controls.autoRotate = options.camera.autoRotate;
                }
                if (options.camera.autoRotateSpeed !== undefined) {
                    this.controls.autoRotateSpeed = options.camera.autoRotateSpeed;
                }
            }

            // Update position options
            if (options.position) {
                Object.assign(this.options.position, options.position);
                this.applyPosition();
            }
        }

        destroy() {
            // Stop animation loop
            if (this.animationFrameId) {
                cancelAnimationFrame(this.animationFrameId);
            }

            // Remove event listeners
            if (this.resizeHandler) {
                window.removeEventListener('resize', this.resizeHandler);
            }
            if (this.mouseMoveHandler) {
                this.canvas.removeEventListener('mousemove', this.mouseMoveHandler);
            }
            if (this.mouseLeaveHandler) {
                this.canvas.removeEventListener('mouseleave', this.mouseLeaveHandler);
            }

            // Stop all videos and dispose of Three.js resources
            this.grids.forEach(grid => {
                if (grid.userData.video) {
                    grid.userData.video.pause();
                    grid.userData.video.src = '';
                }
                if (grid.userData.videoTexture) {
                    grid.userData.videoTexture.dispose();
                }
                grid.children.forEach(child => {
                    if (child.geometry) child.geometry.dispose();
                    if (child.material) {
                        if (child.material.map) child.material.map.dispose();
                        child.material.dispose();
                    }
                });
                this.mainGroup.remove(grid);
            });

            if (this.renderer) {
                this.renderer.dispose();
            }

            if (this.controls) {
                this.controls.dispose();
            }

            this.grids = [];
            this.gridsByName = {};
            this.current = null;
            this.old = null;
        }
    }

    // Global API
    window.CWVideoProjection = {
        init: function(selector, id, options) {
            // Destroy existing instance
            if (instances[id]) {
                instances[id].destroy();
            }

            instances[id] = new VideoProjection(selector, id, options);
            return instances[id];
        },

        update: function(id, options) {
            if (instances[id]) {
                instances[id].update(options);
            }
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
