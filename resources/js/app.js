import 'katex/dist/katex.min.css';
import renderMathInElement from 'katex/contrib/auto-render';

const MATH_DELIMITERS = [
    { left: '$$', right: '$$', display: true },
    { left: '\\[', right: '\\]', display: true },
    { left: '$', right: '$', display: false },
    { left: '\\(', right: '\\)', display: false },
];

const RENDER_OPTIONS = {
    delimiters: MATH_DELIMITERS,
    throwOnError: false,
    ignoredTags: ['script', 'noscript', 'style', 'textarea', 'pre', 'code', 'option', 'math', 'annotation'],
    ignoredClasses: ['katex', 'katex-display'],
};

let renderScheduled = false;

function renderMath() {
    if (typeof document === 'undefined' || typeof renderMathInElement !== 'function') {
        return;
    }

    renderMathInElement(document.body, RENDER_OPTIONS);
}

function scheduleMathRender() {
    if (renderScheduled) {
        return;
    }

    renderScheduled = true;
    window.requestAnimationFrame(() => {
        renderScheduled = false;
        renderMath();
    });
}

function scrollAutoscrollContainers() {
    if (typeof document === 'undefined') {
        return;
    }

    document.querySelectorAll('[data-autoscroll]').forEach((el) => {
        el.scrollTop = el.scrollHeight;
    });
}

let autoscrollScheduled = false;

function scheduleAutoscroll() {
    if (autoscrollScheduled) {
        return;
    }

    autoscrollScheduled = true;
    window.requestAnimationFrame(() => {
        window.requestAnimationFrame(() => {
            autoscrollScheduled = false;
            scrollAutoscrollContainers();
        });
    });
}

function registerLivewireHooks() {
    if (! window.Livewire) {
        return;
    }

    Livewire.hook('morphed', () => {
        scheduleMathRender();
        scheduleAutoscroll();
    });
    Livewire.hook('morph.updated', () => {
        scheduleMathRender();
        scheduleAutoscroll();
    });
}

if (window.Livewire) {
    registerLivewireHooks();
} else {
    document.addEventListener('livewire:init', registerLivewireHooks);
}

document.addEventListener('DOMContentLoaded', () => {
    scheduleMathRender();
    scheduleAutoscroll();
});

document.addEventListener('submit', scheduleAutoscroll, true);

scheduleMathRender();
scheduleAutoscroll();
