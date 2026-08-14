import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

function registerAlpineData() {
    if (typeof Alpine === 'undefined') { setTimeout(registerAlpineData, 10); return; }

    Alpine.data('chart', (el) => ({
        chart: null,
        init() {
            this.$nextTick(() => this.renderChart());
            const observer = new MutationObserver(() => this.renderChart());
            observer.observe(this.$el, { attributes: true, attributeFilter: ['data-chart'] });
        },
        renderChart() {
            const canvas = this.$el.querySelector('canvas');
            if (! canvas) return;
            const raw = this.$el.dataset.chart;
            if (! raw) return;

            if (this.chart) { this.chart.destroy(); this.chart = null; }

            let config;
            try { config = JSON.parse(raw); } catch { return; }
            if (! config || ! config.labels || ! config.datasets) return;

            this.chart = new Chart(canvas, {
                type: config.type || 'line',
                data: { labels: config.labels, datasets: config.datasets },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: config.legend ?? true, position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 } } },
                        tooltip: { backgroundColor: '#1f2937', titleFont: { size: 12 }, bodyFont: { size: 11 }, padding: 10, cornerRadius: 8 },
                    },
                    scales: ['doughnut', 'pie'].includes(config.type) ? undefined : {
                        x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#9ca3af', maxRotation: 0 } },
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 }, color: '#9ca3af', maxTicksLimit: 6, callback: (v) => v >= 1000 ? (v / 1000).toFixed(0) + 'k' : v } },
                    },
                    elements: { line: { tension: 0.35, borderWidth: 2, fill: true }, bar: { borderRadius: 4, borderSkipped: false }, point: { radius: 3, hitRadius: 8, hoverRadius: 5 } },
                    interaction: { intersect: false, mode: 'index' },
                    ...(config.options || {}),
                },
            });
        },
        destroy() { if (this.chart) { this.chart.destroy(); } },
    }));

    Alpine.data('exportDropdown', () => ({
        open: false,
        toggle() { this.open = !this.open; },
        close() { this.open = false; },
    }));
}

registerAlpineData();
