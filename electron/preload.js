// Preload script para seguridad de Electron
// Este archivo se ejecuta antes de cargar la página web

const { contextBridge } = require('electron');

// Expone APIs seguras al renderer process si es necesario
contextBridge.exposeInMainWorld('electron', {
  platform: process.platform,
  version: process.versions.electron
});
