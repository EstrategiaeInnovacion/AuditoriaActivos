/**
 * Chart.js loader — loaded only on pages that use charts.
 * This file is a Vite entry point that imports Chart.js and exposes it globally.
 */
import Chart from 'chart.js/auto';
window.Chart = Chart;
