const { app, BrowserWindow, protocol } = require('electron');
const { spawn } = require('child_process');
const path = require('path');
const http = require('http');

let phpServer = null;
let mainWindow = null;

// Configuración
const PHP_PORT = 8000;
const APP_URL = `http://127.0.0.1:${PHP_PORT}`;
const isDev = process.env.NODE_ENV === 'development' || process.argv.includes('--dev');

// Función para verificar si el servidor está listo
function checkServerReady() {
  return new Promise((resolve) => {
    const maxAttempts = 30;
    let attempts = 0;

    const checkConnection = () => {
      attempts++;
      
      const req = http.get(APP_URL, (res) => {
        if (res.statusCode === 200 || res.statusCode === 302 || res.statusCode === 404) {
          console.log('✅ Servidor Laravel está listo');
          resolve(true);
        } else if (attempts < maxAttempts) {
          setTimeout(checkConnection, 500);
        } else {
          console.log('⚠️ Timeout esperando servidor, continuando...');
          resolve(true);
        }
      });

      req.on('error', () => {
        if (attempts < maxAttempts) {
          setTimeout(checkConnection, 500);
        } else {
          console.log('⚠️ No se pudo conectar al servidor, continuando...');
          resolve(true);
        }
      });

      req.setTimeout(1000);
    };

    checkConnection();
  });
}

// Función para iniciar el servidor Laravel
function startLaravelServer() {
  return new Promise((resolve, reject) => {
    console.log('🚀 Iniciando servidor Laravel...');
    
    phpServer = spawn('php', ['artisan', 'serve', `--port=${PHP_PORT}`, '--host=127.0.0.1'], {
      cwd: path.join(__dirname, '..'),
      shell: true,
      stdio: ['ignore', 'pipe', 'pipe'],
      env: {
        ...process.env,
        APP_ENV: 'production',
        VITE_DEV_SERVER_URL: '', // Desactiva Vite dev server en producción
      }
    });

    let serverReady = false;

    phpServer.stdout.on('data', (data) => {
      const output = data.toString();
      console.log(`[Laravel] ${output.trim()}`);
      if ((output.includes('started') || output.includes('listening')) && !serverReady) {
        serverReady = true;
        resolve();
      }
    });

    phpServer.stderr.on('data', (data) => {
      console.error(`[Laravel Error] ${data.toString().trim()}`);
    });

    phpServer.on('error', (error) => {
      console.error('❌ Error al iniciar Laravel:', error);
      reject(error);
    });

    // Timeout de 15 segundos
    setTimeout(() => {
      if (!serverReady) {
        console.log('⏱️ Timeout alcanzado, asumiendo que el servidor está listo');
        resolve();
      }
    }, 15000);
  });
}

// Función para crear la ventana principal
function createWindow() {
  mainWindow = new BrowserWindow({
    width: 1400,
    height: 900,
    minWidth: 1000,
    minHeight: 600,
    icon: path.join(__dirname, 'assets', 'icon.png'),
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      preload: path.join(__dirname, 'preload.js'),
      webSecurity: true,
    },
    autoHideMenuBar: false,
    title: 'Pharma-Sync - Sistema de Gestión de Farmacia',
    backgroundColor: '#F8F9FC'
  });

  // ✅ CONFIGURAR CSP PARA PERMITIR ESTILOS
  mainWindow.webContents.session.webRequest.onHeadersReceived((details, callback) => {
    callback({
      responseHeaders: {
        ...details.responseHeaders,
        'Content-Security-Policy': [
          "default-src 'self' http://127.0.0.1:* http://localhost:*; " +
          "style-src 'self' 'unsafe-inline' http://127.0.0.1:* http://localhost:*; " +
          "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://127.0.0.1:* http://localhost:*; " +
          "img-src 'self' data: http://127.0.0.1:* http://localhost:*; " +
          "font-src 'self' data: http://127.0.0.1:* http://localhost:*; " +
          "connect-src 'self' http://127.0.0.1:* http://localhost:*;"
        ]
      }
    });
  });

  // Cargar la aplicación Laravel
  console.log(`📱 Cargando aplicación desde: ${APP_URL}`);
  mainWindow.loadURL(APP_URL);

  // Abrir DevTools en desarrollo
  if (isDev) {
    mainWindow.webContents.openDevTools();
  }

  // Manejar errores de carga
  mainWindow.webContents.on('did-fail-load', (event, errorCode, errorDescription) => {
    console.error('❌ Error al cargar:', errorCode, errorDescription);
    
    // Reintentar después de 2 segundos
    setTimeout(() => {
      console.log('🔄 Reintentando cargar...');
      mainWindow.loadURL(APP_URL);
    }, 2000);
  });

  // Confirmar cuando la página se carga
  mainWindow.webContents.on('did-finish-load', () => {
    console.log('✅ Aplicación cargada correctamente');
  });

  mainWindow.on('closed', () => {
    mainWindow = null;
  });
}

// Cuando la aplicación está lista
app.whenReady().then(async () => {
  try {
    console.log('🎯 Pharma-Sync iniciando...');
    
    // Iniciar servidor Laravel
    await startLaravelServer();
    
    // Esperar a que el servidor esté listo
    await checkServerReady();
    
    // Crear ventana
    createWindow();
    
    console.log('✅ Pharma-Sync iniciado correctamente');
  } catch (error) {
    console.error('❌ Error al iniciar la aplicación:', error);
    app.quit();
  }
});

// Cerrar cuando todas las ventanas están cerradas
app.on('window-all-closed', () => {
  if (phpServer) {
    console.log('🛑 Deteniendo servidor Laravel...');
    phpServer.kill();
  }
  app.quit();
});

// Activar en macOS
app.on('activate', () => {
  if (mainWindow === null) {
    createWindow();
  }
});

// Limpieza al cerrar
app.on('before-quit', () => {
  if (phpServer) {
    phpServer.kill();
  }
});

// Manejar errores no capturados
process.on('uncaughtException', (error) => {
  console.error('❌ Error no capturado:', error);
});
