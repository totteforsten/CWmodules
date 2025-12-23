/**
 * Topographic Background with Three.js
 * Configurable parameters for Breakdance Builder
 */

(function() {
  'use strict';

  window.BreakdanceTopographicBackground = {
    instances: {},
    
    // Helper function to strip alpha channel from hex colors
    sanitizeColor: function(color) {
      if (!color) return '#c1c1c1';
      // If color has 8 characters (#RRGGBBAA), strip the alpha channel
      if (color.length === 9 && color.startsWith('#')) {
        return color.substring(0, 7);
      }
      return color;
    },

    init: function(selector, id, settings) {
      // Destroy existing instance if it exists
      if (this.instances[id]) {
        this.destroy(id);
      }

      // Get the section element itself (not the inner wrapper)
      const sectionEl = document.querySelector(selector);
      if (!sectionEl) {
        console.error('Section element not found:', selector);
        return;
      }

      const container = sectionEl.querySelector('.topo-wrapper');
      if (!container) {
        console.error('Topographic container not found');
        return;
      }

      const demoEl = container.querySelector('.topo-demo');
      if (!demoEl) {
        console.error('Topographic demo element not found');
        return;
      }

      if (typeof THREE === 'undefined') {
        console.error('THREE.js not loaded');
        return;
      }

      // Default settings
      const defaults = {
        lineColor: '#c1c1c1',
        opacity: 0.4,
        lineThickness: 0.003,
        contourLevels: 10.0,
        noiseScale: 0.0014,
        animationSpeed: 0.020,
        enableMouseEffect: true,
        mouseInfluence: 3.0,
        radialPush: 0.25,
        swirlStrength: 0.35,
        followFactor: 0.030,
        driftRadius: 20.0
      };

      const config = Object.assign({}, defaults, settings);

      let width = container.clientWidth;
      let height = container.clientHeight;

      const scene = new THREE.Scene();
      const camera = new THREE.OrthographicCamera(0, width, 0, height, 1, 2);
      camera.position.z = 2;

      const renderer = new THREE.WebGLRenderer({ alpha: true });
      renderer.setClearColor(0, 0);
      renderer.setPixelRatio(window.devicePixelRatio || 1);
      renderer.setSize(width, height);

      const clock = new THREE.Clock();

      const vs = `
        void main() {
          gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
      `;

      // Simplex noise
      const snoise = `
        vec3 mod289(vec3 x) { return x - floor(x * (1.0 / 289.0)) * 289.0; }
        vec4 mod289(vec4 x) { return x - floor(x * (1.0 / 289.0)) * 289.0; }
        vec4 permute(vec4 x) { return mod289(((x*34.0)+10.0)*x); }
        vec4 taylorInvSqrt(vec4 r) { return 1.79284291400159 - 0.85373472095314 * r; }
        float snoise(vec3 v) { 
          const vec2  C = vec2(1.0/6.0, 1.0/3.0) ;
          const vec4  D = vec4(0.0, 0.5, 1.0, 2.0);
          vec3 i  = floor(v + dot(v, C.yyy) );
          vec3 x0 =   v - i + dot(i, C.xxx) ;
          vec3 g = step(x0.yzx, x0.xyz);
          vec3 l = 1.0 - g;
          vec3 i1 = min( g.xyz, l.zxy );
          vec3 i2 = max( g.xyz, l.zxy );
          vec3 x1 = x0 - i1 + C.xxx;
          vec3 x2 = x0 - i2 + C.yyy;
          vec3 x3 = x0 - D.yyy;
          i = mod289(i); 
          vec4 p = permute( permute( permute( i.z + vec4(0.0, i1.z, i2.z, 1.0 ))
            + i.y + vec4(0.0, i1.y, i2.y, 1.0 ))
            + i.x + vec4(0.0, i1.x, i2.x, 1.0 ));
          float n_ = 0.142857142857;
          vec3  ns = n_ * D.wyz - D.xzx;
          vec4 j = p - 49.0 * floor(p * ns.z * ns.z);
          vec4 x_ = floor(j * ns.z);
          vec4 y_ = floor(j - 7.0 * x_ );
          vec4 x = x_ *ns.x + ns.yyyy;
          vec4 y = y_ *ns.x + ns.yyyy;
          vec4 h = 1.0 - abs(x) - abs(y);
          vec4 b0 = vec4( x.xy, y.xy );
          vec4 b1 = vec4( x.zw, y.zw );
          vec4 s0 = floor(b0)*2.0 + 1.0;
          vec4 s1 = floor(b1)*2.0 + 1.0;
          vec4 sh = -step(h, vec4(0.0));
          vec4 a0 = b0.xzyw + s0.xzyw*sh.xxyy ;
          vec4 a1 = b1.xzyw + s1.xzyw*sh.zzww ;
          vec3 p0 = vec3(a0.xy,h.x);
          vec3 p1 = vec3(a0.zw,h.y);
          vec3 p2 = vec3(a1.xy,h.z);
          vec3 p3 = vec3(a1.zw,h.w);
          vec4 norm = taylorInvSqrt(vec4(dot(p0,p0), dot(p1,p1),
            dot(p2,p2), dot(p3,p3)));
          p0 *= norm.x;
          p1 *= norm.y;
          p2 *= norm.z;
          p3 *= norm.w;
          vec4 m = max(0.5 - vec4(dot(x0,x0), dot(x1,x1),
            dot(x2,x2), dot(x3,x3)), 0.0);
          m = m * m;
          return 105.0 * dot( m*m, vec4( dot(p0,x0), dot(p1,x1),
            dot(p2,x2), dot(p3,x3) ) );
        }
      `;

      // Fragment shader
      const fs = `
        uniform vec3  color;
        uniform float time;
        uniform vec2  resolution;
        uniform vec2  mouse;
        uniform float levels;
        uniform float scale;
        uniform float lineThickness;
        uniform float animSpeed;
        uniform bool enableMouse;
        uniform float mouseInfluence;
        uniform float radialPush;
        uniform float swirlStrength;

        void main() {
          vec2 frag = gl_FragCoord.xy;
          vec2 coord = frag * scale;

          if (enableMouse) {
            vec2 mouseCoord = mouse * scale;
            vec2 diff = coord - mouseCoord;
            float dist = length(diff);
            vec2 dir = dist > 0.0 ? diff / dist : vec2(0.0);
            float influence = exp(-dist * mouseInfluence);
            vec2 swirlDir = vec2(-dir.y, dir.x);
            vec2 offset = dir * radialPush * influence + swirlDir * swirlStrength * influence * sin(time * 1.5);
            coord += offset;
          }

          float noise = snoise(vec3(coord, time * animSpeed));
          noise = (noise + 1.0) / 2.0;
          float lower = floor(noise * levels) / levels;
          float lowerDiff = noise - lower;

          if (lowerDiff > lineThickness)
            discard;

          gl_FragColor = vec4(color, 1.0);
        }
      `;

      // Mouse state
      const mouse = new THREE.Vector2(width * 0.5, height * 0.5);
      const mouseTarget = mouse.clone();

      // Uniforms
      const uniforms = {
        color: { value: new THREE.Color(this.sanitizeColor(config.lineColor)) },
        time: { value: 0 },
        resolution: { value: new THREE.Vector2(width, height) },
        mouse: { value: mouse.clone() },
        levels: { value: config.contourLevels },
        scale: { value: config.noiseScale },
        lineThickness: { value: config.lineThickness },
        animSpeed: { value: config.animationSpeed },
        enableMouse: { value: config.enableMouseEffect },
        mouseInfluence: { value: config.mouseInfluence },
        radialPush: { value: config.radialPush },
        swirlStrength: { value: config.swirlStrength }
      };

      const material = new THREE.ShaderMaterial({
        uniforms: uniforms,
        vertexShader: vs,
        fragmentShader: snoise + fs,
        side: THREE.BackSide
      });

      let mesh = new THREE.Mesh(new THREE.PlaneGeometry(width, height), material);
      mesh.geometry.translate(width / 2, height / 2, 0);
      scene.add(mesh);

      demoEl.appendChild(renderer.domElement);

      const onResize = () => {
        width = container.clientWidth;
        height = container.clientHeight;

        renderer.setSize(width, height);

        camera.left = 0;
        camera.right = width;
        camera.top = 0;
        camera.bottom = height;
        camera.updateProjectionMatrix();

        uniforms.resolution.value.set(width, height);

        mesh.geometry.dispose();
        mesh.geometry = new THREE.PlaneGeometry(width, height);
        mesh.geometry.translate(width / 2, height / 2, 0);

        mouse.set(width * 0.5, height * 0.5);
        mouseTarget.copy(mouse);
      };

      const onMouseMove = (e) => {
        const rect = sectionEl.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = height - (e.clientY - rect.top);
        mouseTarget.set(x, y);
      };

      let animationId;
      const frame = () => {
        animationId = requestAnimationFrame(frame);

        const t = clock.getElapsedTime();
        uniforms.time.value = t;

        if (config.enableMouseEffect) {
          mouse.lerp(mouseTarget, config.followFactor);
          const dx = Math.cos(t * 0.25) * config.driftRadius;
          const dy = Math.sin(t * 0.2) * config.driftRadius;
          uniforms.mouse.value.set(mouse.x + dx, mouse.y + dy);
        }

        renderer.render(scene, camera);
      };

      // Event listeners - attach to section element
      window.addEventListener('resize', onResize);
      if (config.enableMouseEffect) {
        sectionEl.addEventListener('pointermove', onMouseMove);
        sectionEl.addEventListener('mousemove', onMouseMove);
      }

      // Start animation
      onResize();
      frame();

      // Store instance
      this.instances[id] = {
        sectionEl: sectionEl,
        container: container,
        demoEl: demoEl,
        renderer: renderer,
        scene: scene,
        material: material,
        mesh: mesh,
        uniforms: uniforms,
        config: config,
        onResize: onResize,
        onMouseMove: onMouseMove,
        animationId: animationId,
        destroy: () => {
          cancelAnimationFrame(animationId);
          window.removeEventListener('resize', onResize);
          sectionEl.removeEventListener('pointermove', onMouseMove);
          sectionEl.removeEventListener('mousemove', onMouseMove);
          mesh.geometry.dispose();
          material.dispose();
          renderer.dispose();
          if (demoEl && renderer.domElement && demoEl.contains(renderer.domElement)) {
            demoEl.removeChild(renderer.domElement);
          }
        }
      };
    },

    update: function(id, settings) {
      const instance = this.instances[id];
      if (!instance) return;

      const { uniforms, config, sectionEl } = instance;

      if (settings.lineColor !== undefined) {
        const sanitizedColor = this.sanitizeColor(settings.lineColor);
        uniforms.color.value.setStyle(sanitizedColor);
        config.lineColor = sanitizedColor;
      }
      if (settings.contourLevels !== undefined) {
        uniforms.levels.value = settings.contourLevels;
        config.contourLevels = settings.contourLevels;
      }
      if (settings.noiseScale !== undefined) {
        uniforms.scale.value = settings.noiseScale;
        config.noiseScale = settings.noiseScale;
      }
      if (settings.lineThickness !== undefined) {
        uniforms.lineThickness.value = settings.lineThickness;
        config.lineThickness = settings.lineThickness;
      }
      if (settings.animationSpeed !== undefined) {
        uniforms.animSpeed.value = settings.animationSpeed;
        config.animationSpeed = settings.animationSpeed;
      }
      if (settings.enableMouseEffect !== undefined) {
        uniforms.enableMouse.value = settings.enableMouseEffect;
        config.enableMouseEffect = settings.enableMouseEffect;
        
        if (settings.enableMouseEffect) {
          sectionEl.addEventListener('pointermove', instance.onMouseMove);
          sectionEl.addEventListener('mousemove', instance.onMouseMove);
        } else {
          sectionEl.removeEventListener('pointermove', instance.onMouseMove);
          sectionEl.removeEventListener('mousemove', instance.onMouseMove);
        }
      }
      if (settings.mouseInfluence !== undefined) {
        uniforms.mouseInfluence.value = settings.mouseInfluence;
        config.mouseInfluence = settings.mouseInfluence;
      }
      if (settings.radialPush !== undefined) {
        uniforms.radialPush.value = settings.radialPush;
        config.radialPush = settings.radialPush;
      }
      if (settings.swirlStrength !== undefined) {
        uniforms.swirlStrength.value = settings.swirlStrength;
        config.swirlStrength = settings.swirlStrength;
      }
      if (settings.followFactor !== undefined) {
        config.followFactor = settings.followFactor;
      }
      if (settings.driftRadius !== undefined) {
        config.driftRadius = settings.driftRadius;
      }
    },

    destroy: function(id) {
      const instance = this.instances[id];
      if (instance) {
        instance.destroy();
        delete this.instances[id];
      }
    }
  };
})();
