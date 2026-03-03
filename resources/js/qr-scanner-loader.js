/**
 * QR Scanner loader — loaded only on pages that use the QR scanner.
 * This file is a Vite entry point that imports html5-qrcode and exposes it globally.
 */
import { Html5QrcodeScanner } from 'html5-qrcode';
window.Html5QrcodeScanner = Html5QrcodeScanner;
