/**
 * QR Scanner loader — robust initialization with camera support detection and error handling.
 */
import { Html5QrcodeScanner } from 'html5-qrcode';

window.Html5QrcodeScanner = Html5QrcodeScanner;

/**
 * Check if the browser supports camera access via getUserMedia.
 * @returns {{ supported: boolean, error: string|null }}
 */
export function checkCameraSupport() {
    if (!navigator.mediaDevices) {
        return {
            supported: false,
            error: 'Tu navegador no soporta acceso a dispositivos multimedia. Actualiza a una versión moderna.'
        };
    }
    
    if (!navigator.mediaDevices.getUserMedia) {
        return {
            supported: false,
            error: 'Tu navegador no soporta la API getUserMedia necesaria para acceder a la cámara.'
        };
    }
    
    return { supported: true, error: null };
}

/**
 * Convert camera error codes to user-friendly messages.
 * @param {DOMException|string} error - The error from getUserMedia or scanner
 * @returns {string} User-friendly error message
 */
export function getCameraErrorMessage(error) {
    if (!error) {
        return 'Error desconocido al acceder a la cámara.';
    }
    
    const errorName = error.name || (typeof error === 'string' ? error : 'UnknownError');
    const errorMessage = error.message || String(error);
    
    const errorMap = {
        'NotAllowedError': 'Permiso denegado. Por favor permite el acceso a la cámara en la configuración del navegador.',
        'PermissionDeniedError': 'Permiso denegado. Por favor permite el acceso a la cámara en la configuración del navegador.',
        'NotFoundError': 'No se encontró ninguna cámara. Verifica que tu dispositivo tenga una cámara conectada.',
        'DevicesNotFoundError': 'No se encontró ninguna cámara. Verifica que tu dispositivo tenga una cámara conectada.',
        'NotReadableError': 'La cámara está siendo utilizada por otra aplicación. Cierra otras apps que usen la cámara e intenta de nuevo.',
        'TrackStartError': 'La cámara está siendo utilizada por otra aplicación. Cierra otras apps que usen la cámara e intenta de nuevo.',
        'OverconstrainedError': 'La cámara no cumple con los requisitos necesarios. Intenta con otra cámara.',
        'ConstraintNotSatisfiedError': 'La configuración de cámara solicitada no es soportada. Intenta con otra cámara.',
        'NotSupportedError': 'El acceso a la cámara no está soportado en este contexto (HTTP sin SSL).',
        'SecurityError': 'El acceso a la cámara fue bloqueado por razones de seguridad. Asegúrate de usar HTTPS.',
        'AbortError': 'La solicitud de cámara fue abortada. Intenta de nuevo.',
        'SourceUnavailable': 'La fuente de cámara no está disponible. Verifica que no esté en uso por otra aplicación.'
    };
    
    if (errorMap[errorName]) {
        return errorMap[errorName];
    }
    
    if (errorMessage.includes('Permission') || errorMessage.includes('permission')) {
        return 'Permiso denegado. Por favor permite el acceso a la cámara en la configuración del navegador.';
    }
    
    if (errorMessage.includes('NotFound') || errorMessage.includes('Not found')) {
        return 'No se encontró ninguna cámara. Verifica que tu dispositivo tenga una cámara conectada.';
    }
    
    if (errorMessage.includes('NotAllowed') || errorMessage.includes('Denied')) {
        return 'Permiso denegado. Por favor permite el acceso a la cámara en la configuración del navegador.';
    }
    
    if (errorMessage.includes('HTTPS') || errorMessage.includes('secure context')) {
        return 'El acceso a la cámara requiere una conexión segura (HTTPS).';
    }
    
    return `Error al acceder a la cámara: ${errorMessage}`;
}

/**
 * Initialize QR Scanner with robust configuration and error handling.
 * @param {Object} config - Configuration options
 * @param {HTMLElement|string} config.containerElement - Element or selector for the scanner
 * @param {number} [config.fps=10] - Frames per second for scanning
 * @param {number|{width:number,height:number}} [config.qrbox=250] - QR box size
 * @param {Function} [config.onScanSuccess] - Callback when QR is scanned successfully
 * @param {Function} [config.onScanFailure] - Callback when scan fails
 * @param {Function} [config.onCameraError] - Callback when camera error occurs
 * @param {Function} [config.onReady] - Callback when scanner is ready
 * @param {number} [config.maxRetries=5] - Maximum retry attempts
 * @param {number} [config.retryDelay=500] - Delay between retries in ms
 * @returns {Object} Scanner controller object
 */
export function initQrScanner(config) {
    const {
        containerElement,
        fps = 10,
        qrbox = 250,
        onScanSuccess = () => {},
        onScanFailure = () => {},
        onCameraError = () => {},
        onReady = () => {},
        maxRetries = 5,
        retryDelay = 500
    } = config;
    
    const container = typeof containerElement === 'string' 
        ? document.querySelector(containerElement) 
        : containerElement;
    
    if (!container) {
        const error = 'Contenedor del scanner no encontrado';
        onCameraError({ name: 'ContainerNotFound', message: error });
        return { 
            destroy: () => {}, 
            isReady: false,
            error 
        };
    }
    
    const state = {
        scanner: null,
        isReady: false,
        retryCount: 0,
        retryTimeout: null,
        destroyed: false
    };
    
    const qrboxConfig = typeof qrbox === 'number' 
        ? { width: qrbox, height: qrbox } 
        : qrbox;
    
    function startScanner() {
        if (state.destroyed) return;
        
        if (typeof Html5QrcodeScanner === 'undefined') {
            if (state.retryCount < maxRetries) {
                state.retryCount++;
                console.log(`Html5QrcodeScanner not loaded, retry ${state.retryCount}/${maxRetries}`);
                state.retryTimeout = setTimeout(startScanner, retryDelay);
            } else {
                onCameraError({ 
                    name: 'LibraryNotLoaded', 
                    message: 'La librería del scanner no se cargó correctamente.' 
                });
            }
            return;
        }
        
        if (state.scanner) {
            onReady();
            state.isReady = true;
            return;
        }
        
        try {
            state.scanner = new Html5QrcodeScanner(
                container.id || container,
                {
                    fps,
                    qrbox: qrboxConfig,
                    supportedScanMethods: [0, 1],
                    rememberLastUsedCamera: true,
                    showTorchButtonIfSupported: true,
                    showZoomButtonIfSupported: true
                },
                false
            );
            
            state.scanner.render(
                (decodedText, decodedResult) => {
                    if (!state.destroyed) {
                        onScanSuccess(decodedText, decodedResult);
                    }
                },
                (error, errorContext) => {
                    if (!state.destroyed) {
                        onScanFailure(error, errorContext);
                    }
                }
            );
            
            state.isReady = true;
            state.retryCount = 0;
            console.log('QR Scanner initialized successfully');
            onReady();
            
        } catch (error) {
            console.error('Error initializing QR scanner:', error);
            onCameraError(error);
        }
    }
    
    function stopScanner() {
        state.destroyed = true;
        
        if (state.retryTimeout) {
            clearTimeout(state.retryTimeout);
            state.retryTimeout = null;
        }
        
        if (state.scanner) {
            state.scanner.clear()
                .then(() => {
                    state.scanner = null;
                    state.isReady = false;
                    console.log('QR Scanner stopped successfully');
                })
                .catch((error) => {
                    console.error('Error clearing scanner:', error);
                    state.scanner = null;
                    state.isReady = false;
                });
        }
    }
    
    function destroy() {
        stopScanner();
    }
    
    function reset() {
        state.retryCount = 0;
        state.isReady = false;
    }
    
    startScanner();
    
    return {
        start: startScanner,
        stop: stopScanner,
        destroy,
        reset,
        get isReady() { return state.isReady; },
        get retryCount() { return state.retryCount; }
    };
}

window.QrScannerHelpers = {
    checkCameraSupport,
    getCameraErrorMessage,
    initQrScanner
};
