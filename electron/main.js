const { app, BrowserWindow } = require('electron');
const { spawn } = require('child_process');
const path = require('path');
const http = require('http');
const { setupApplication } = require('./setup');

let phpServer = null;
let mainWindow = null;

// Configuración
const PHP_PORT = 8000;
const APP_URL = `http://127.0.0.1:${PHP_PORT}`;

function checkServerReady() {
  return new Promise((resolve) => {
    const checkConnection = () => {
      const req = http.get(APP_URL, (res) => {
        if (res.statusCode === 200 || res.statusCode === 302 || res.statusCode === 404) {
          console.log('Servidor Laravel está listo');
          resolve(true);
        } else {
          setTimeout(checkConnection, 500);
        }
      });

      req.on('error', () => {
        setTimeout(checkConnection, 500);
      });

      req.setTimeout(1000);
    };

    checkConnection();
  });
}

function startLaravelServer() {
  return new Promise((resolve, reject) => {
    console.log('Iniciando servidor Laravel...');
    
    // Inicia el servidor PHP de Laravel
    phpServer = spawn('php', ['artisan', 'serve', `--port=${PHP_PORT}`, '--host=127.0.0.1'], {
      cwd: path.join(__dirname, '..'),
      shell: true,
      stdio: ['ignore', 'pipe', 'pipe']
    });

    let serverReady = false;

    phpServer.stdout.on('data', (data) => {
      const output = data.toString();
      console.log(`Laravel: ${output}`);
      if (output.includes('started') || output.includes('listening')) {
        serverReady = true;
        resolve();
      }
    });

    phpServer.stderr.on('data', (data) => {
      console.error(`Laravel Error: ${data}`);
    });

    phpServer.on('error', (error) => {
      console.error('Error al iniciar Laravel:', error);
      reject(error);
    });

    // Timeout de 15 segundos
    setTimeout(() => {
      if (!serverReady) {
        console.log('Servidor Laravel iniciado (timeout)');
        resolve();
      }
    }, 15000);
  });
}

function createWindow() {
  mainWindow = new BrowserWindow({
    width: 1400,
    height: 900,
    minWidth: 1000,
    minHeight: 600,
    icon: path.join(__dirname, 'icon.ico'),
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      preload: path.join(__dirname, 'preload.js')
    },
    autoHideMenuBar: true,
    title: 'Pharma-Sync'
  });

  // Carga la aplicación Laravel
  mainWindow.loadURL(APP_URL);

  // Abre DevTools en desarrollo
  if (process.env.NODE_ENV === 'development') {
    mainWindow.webContents.openDevTools();
  }

  mainWindow.on('closed', () => {
    mainWindow = null;
  });
}

app.whenReady().then(async () => {
  try {
    console.log('🚀 Iniciando Pharma-Sync...');
    
    // Configurar aplicación
    await setupApplication();
    
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

app.on('window-all-closed', () => {
  if (phpServer) {
    phpServer.kill();
  }
  app.quit();
});

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
