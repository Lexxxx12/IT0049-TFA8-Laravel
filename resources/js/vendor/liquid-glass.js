/**
 * SVG displacement technique adapted from Shu Ding's liquid-glass (MIT).
 * https://github.com/shuding/liquid-glass
 * Adaptation: fixed surfaces, bounded resolution, resize cleanup, no draggable overlay.
 * Full license: /vendor/liquid-glass/LICENSE
 */
export function applyGlass(element) {
    if (!CSS.supports('backdrop-filter', 'url("#glass")')) return;
    const svgNS = 'http://www.w3.org/2000/svg';
    const id = 'glass-' + Math.random().toString(36).slice(2);
    const svg = document.createElementNS(svgNS, 'svg');
    svg.setAttribute('width', '0'); svg.setAttribute('height', '0'); svg.setAttribute('aria-hidden', 'true');
    svg.style.cssText = 'position:fixed;pointer-events:none';
    const filter = document.createElementNS(svgNS, 'filter');
    filter.id = id; filter.setAttribute('color-interpolation-filters', 'sRGB');
    const source = document.createElementNS(svgNS, 'feImage'); source.setAttribute('result', 'map');
    const displacement = document.createElementNS(svgNS, 'feDisplacementMap');
    displacement.setAttribute('in', 'SourceGraphic'); displacement.setAttribute('in2', 'map');
    displacement.setAttribute('xChannelSelector', 'R'); displacement.setAttribute('yChannelSelector', 'G');
    displacement.setAttribute('scale', '12');
    filter.append(source, displacement); svg.append(filter); document.body.append(svg);
    let lastWidth = 0, lastHeight = 0;
    const update = () => {
        const rect = element.getBoundingClientRect();
        if (!rect.width || !rect.height || (rect.width === lastWidth && rect.height === lastHeight)) return;
        lastWidth = rect.width; lastHeight = rect.height;
        const width = Math.min(480, Math.ceil(rect.width));
        const height = Math.max(1, Math.round(width * rect.height / rect.width));
        const canvas = document.createElement('canvas'); canvas.width = width; canvas.height = height;
        const context = canvas.getContext('2d'); const image = context.createImageData(width, height);
        for (let y = 0; y < height; y++) for (let x = 0; x < width; x++) {
            const edge = Math.min(x, y, width - x - 1, height - y - 1);
            let t = Math.min(1, edge / 18); t = t * t * (3 - 2 * t);
            const bend = (1 - t) * 0.32; const i = (y * width + x) * 4;
            image.data[i] = 128 + (x < width / 2 ? 1 : -1) * bend * 128;
            image.data[i + 1] = 128 + (y < height / 2 ? 1 : -1) * bend * 128;
            image.data[i + 2] = 0; image.data[i + 3] = 255;
        }
        context.putImageData(image, 0, 0); source.setAttribute('href', canvas.toDataURL());
        element.style.backdropFilter = 'url("#' + id + '") blur(14px) saturate(1.08)';
    };
    const observer = new ResizeObserver(update); observer.observe(element); update();
    window.addEventListener('pagehide', () => { observer.disconnect(); svg.remove(); }, { once: true });
}
