const logo = document.getElementById('liquid-logo');
const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
const reducedTransparency = matchMedia('(prefers-reduced-transparency: reduce)');

if (logo && !reducedMotion.matches) {
    const mountLogo = async () => {
        try {
            const { ShaderMount, liquidMetalFragmentShader, toProcessedLiquidMetal } = await import('@paper-design/shaders');
            const { pngBlob } = await toProcessedLiquidMetal(logo.dataset.image);
            const image = new Image();
            const imageUrl = URL.createObjectURL(pngBlob);
            image.src = imageUrl;
            await image.decode();
            const shader = new ShaderMount(logo, liquidMetalFragmentShader, {
                u_image: image, u_isImage: true, u_shape: 0,
                u_colorBack: [0, 0, 0, 0], u_colorTint: [0.72, 0.88, 0.84, 1],
                u_repetition: 2.2, u_softness: 0.18, u_shiftRed: 0.04, u_shiftBlue: 0.04,
                u_distortion: 0.08, u_contour: 0.35, u_angle: 70,
                u_fit: 1, u_scale: 0.88, u_rotation: 0,
                u_originX: 0.5, u_originY: 0.5, u_offsetX: 0, u_offsetY: 0,
                u_worldWidth: 0, u_worldHeight: 0,
            }, { alpha: true, antialias: false }, 0.18, 1800, 1, 400000);
            const stage = logo.closest('.logo-stage');
            requestAnimationFrame(() => requestAnimationFrame(() => stage.classList.add('is-rendered')));
            const toggle = document.getElementById('logo-motion');
            toggle.hidden = false;
            let playing = true;
            const pause = () => { playing = false; shader.setSpeed(0); toggle.setAttribute('aria-label', 'Play logo animation'); toggle.firstElementChild.textContent = '\u25b6'; };
            toggle.addEventListener('click', () => {
                if (playing) pause();
                else { playing = true; shader.setSpeed(0.18); toggle.setAttribute('aria-label', 'Pause logo animation'); toggle.firstElementChild.textContent = 'II'; }
            });
            reducedMotion.addEventListener('change', () => { if (reducedMotion.matches) pause(); });
            logo.querySelector('canvas')?.addEventListener('webglcontextlost', () => { stage.classList.remove('is-rendered'); toggle.hidden = true; });
            window.addEventListener('pagehide', () => { stage.classList.remove('is-rendered'); toggle.hidden = true; shader.dispose(); URL.revokeObjectURL(imageUrl); }, { once: true });
        } catch {
            // The original SVG remains visible when WebGL is unavailable.
            logo.closest('.logo-stage')?.classList.remove('is-rendered');
        }
    };
    if ('requestIdleCallback' in window) requestIdleCallback(mountLogo, { timeout: 1500 });
    else setTimeout(mountLogo, 0);
}
if (!reducedTransparency.matches) {
    import('./vendor/liquid-glass.js').then(({ applyGlass }) => {
        document.querySelectorAll('[data-glass-surface]').forEach(applyGlass);
    }).catch(() => {});
}
