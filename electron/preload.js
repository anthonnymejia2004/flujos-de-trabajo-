// Preload script para Electron
// Este script se ejecuta antes de que se cargue la página web

const { contextBridge } = require('electron');

// Exponer APIs seguras al renderer process si es necesario
contextBridge.exposeInMainWorld('electron', {
  // Aquí puedes exponer funciones seguras si las necesitas
  platform: process.platform,
  version: process.versions.electron
});

console.log('✅ Preload script cargado');
