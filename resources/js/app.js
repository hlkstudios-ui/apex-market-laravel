const toggle = document.querySelector('.mobile-toggle');
const menu = document.querySelector('.nav-secondary');

toggle?.addEventListener('click', () => {
    const open = menu.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && menu?.classList.contains('is-open')) {
        menu.classList.remove('is-open');
        toggle?.setAttribute('aria-expanded', 'false');
        toggle?.setAttribute('aria-label', 'Open navigation');
        toggle?.focus();
    }
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
        let visiblePixels = 0;
        let luminanceTotal = 0;
        let foregroundLeft = sampleWidth;
        let foregroundRight = 0;
        let foregroundTop = sampleHeight;
        let foregroundBottom = 0;
        let foregroundPixels = 0;

        for (let row = 0; row < sampleHeight; row += 1) {
            for (let column = 0; column < sampleWidth; column += 1) {
                const alpha = pixels[((row * sampleWidth) + column) * 4 + 3];

                if (alpha > 24) {
                    const pixel = (row * sampleWidth) + column;
                    const red = pixels[pixel * 4];
                    const green = pixels[(pixel * 4) + 1];
                    const blue = pixels[(pixel * 4) + 2];

                    left = Math.min(left, column);
                    right = Math.max(right, column);
                    top = Math.min(top, row);
                    bottom = Math.max(bottom, row);
                    visiblePixels += 1;
                    const luminance = ((0.2126 * red) + (0.7152 * green) + (0.0722 * blue)) / 255;

                    luminanceTotal += luminance;

                    if (luminance < 0.72) {
                        foregroundLeft = Math.min(foregroundLeft, column);
                        foregroundRight = Math.max(foregroundRight, column);
                        foregroundTop = Math.min(foregroundTop, row);
                        foregroundBottom = Math.max(foregroundBottom, row);
                        foregroundPixels += 1;
                    }
                }
            }
        }

        if (right > left && bottom > top) {
            const visibleBoundsArea = (right - left + 1) * (bottom - top + 1);
            const artworkDensity = visiblePixels / visibleBoundsArea;
            const averageLuminance = luminanceTotal / visiblePixels;
            const hasDarkForeground = foregroundPixels > 20
                && foregroundRight > foregroundLeft
                && foregroundBottom > foregroundTop;
            const artworkLeft = hasDarkForeground ? foregroundLeft : left;
            const artworkRight = hasDarkForeground ? foregroundRight : right;
            const artworkTop = hasDarkForeground ? foregroundTop : top;
            const artworkBottom = hasDarkForeground ? foregroundBottom : bottom;
            const visibleWidth = (artworkRight - artworkLeft + 1) / sampleWidth;
            const visibleHeight = (artworkBottom - artworkTop + 1) / sampleHeight;
            const widthScale = opticalWidth / visibleWidth;
            const heightScale = opticalHeight / visibleHeight;
            const opticalScale = Math.min(widthScale, heightScale);
            const fittedScale = Math.max(minimumScale, Math.min(maximumScale, opticalScale));
            const visibleCenterX = (artworkLeft + artworkRight) / 2;
            const visibleCenterY = (artworkTop + artworkBottom) / 2;
            const shiftX = ((sampleWidth / 2 - visibleCenterX) / sampleWidth) * 100 * fittedScale;
            const shiftY = ((sampleHeight / 2 - visibleCenterY) / sampleHeight) * 100 * fittedScale;

            stage.style.setProperty('--brand-logo-scale', fittedScale.toFixed(3));
            stage.style.setProperty('--brand-logo-shift-x', `${shiftX.toFixed(2)}%`);
            stage.style.setProperty('--brand-logo-shift-y', `${shiftY.toFixed(2)}%`);

            if (!hasDarkForeground && averageLuminance > 0.82 && artworkDensity < 0.7) {
                stage.classList.add('is-light-artwork');
            }
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
