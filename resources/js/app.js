const toggle = document.querySelector('.mobile-toggle');
const menu = document.querySelector('.nav-secondary');

toggle?.addEventListener('click', () => {
    const open = menu.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(open));
});

const fitBrandLogo = (stage) => {
    const image = stage.querySelector('.brand-logo__image');

    if (!image || !image.naturalWidth || !image.naturalHeight) {
        return;
    }

    const sampleWidth = Math.max(Math.round(image.clientWidth * 2), 200);
    const sampleHeight = Math.max(Math.round(image.clientHeight * 2), 100);
    const designTokens = getComputedStyle(document.documentElement);
    const opticalWidth = parseFloat(designTokens.getPropertyValue('--brand-logo-optical-width')) / 100 || 0.76;
    const opticalHeight = parseFloat(designTokens.getPropertyValue('--brand-logo-optical-height')) / 100 || 0.38;
    const minimumScale = parseFloat(designTokens.getPropertyValue('--brand-logo-scale-min')) || 0.72;
    const maximumScale = parseFloat(designTokens.getPropertyValue('--brand-logo-scale-max')) || 1.9;
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d', { willReadFrequently: true });

    if (!context) {
        stage.classList.add('is-fitted');
        return;
    }

    canvas.width = sampleWidth;
    canvas.height = sampleHeight;

    const baseScale = Math.min(sampleWidth / image.naturalWidth, sampleHeight / image.naturalHeight);
    const width = image.naturalWidth * baseScale;
    const height = image.naturalHeight * baseScale;
    const x = (sampleWidth - width) / 2;
    const y = (sampleHeight - height) / 2;

    context.drawImage(image, x, y, width, height);

    try {
        const pixels = context.getImageData(0, 0, sampleWidth, sampleHeight).data;
        let left = sampleWidth;
        let right = 0;
        let top = sampleHeight;
        let bottom = 0;

        for (let row = 0; row < sampleHeight; row += 1) {
            for (let column = 0; column < sampleWidth; column += 1) {
                const alpha = pixels[((row * sampleWidth) + column) * 4 + 3];

                if (alpha > 24) {
                    left = Math.min(left, column);
                    right = Math.max(right, column);
                    top = Math.min(top, row);
                    bottom = Math.max(bottom, row);
                }
            }
        }

        if (right > left && bottom > top) {
            const visibleWidth = (right - left + 1) / sampleWidth;
            const visibleHeight = (bottom - top + 1) / sampleHeight;
            const widthScale = opticalWidth / visibleWidth;
            const heightScale = opticalHeight / visibleHeight;
            const opticalScale = Math.min(widthScale, heightScale);
            const fittedScale = Math.max(minimumScale, Math.min(maximumScale, opticalScale));
            const visibleCenterX = (left + right) / 2;
            const visibleCenterY = (top + bottom) / 2;
            const shiftX = ((sampleWidth / 2 - visibleCenterX) / sampleWidth) * 100 * fittedScale;
            const shiftY = ((sampleHeight / 2 - visibleCenterY) / sampleHeight) * 100 * fittedScale;

            stage.style.setProperty('--brand-logo-scale', fittedScale.toFixed(3));
            stage.style.setProperty('--brand-logo-shift-x', `${shiftX.toFixed(2)}%`);
            stage.style.setProperty('--brand-logo-shift-y', `${shiftY.toFixed(2)}%`);
        }
    } catch {
        // The unscaled contain fit remains a safe fallback.
    }

    stage.classList.add('is-fitted');
};

document.querySelectorAll('[data-brand-logo]').forEach((stage) => {
    const image = stage.querySelector('.brand-logo__image');

    if (image.complete) {
        fitBrandLogo(stage);
    } else {
        image.addEventListener('load', () => fitBrandLogo(stage), { once: true });
    }
});
