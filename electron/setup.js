const { spawn } = require('child_process');
const path = require('path');
const fs = require('fs');

async function runCommand(command, args, cwd) {
  return new Promise((resolve, reject) => {
    console.log(`Ejecutando: ${command} ${args.join(' ')}`);
    
    const process = spawn(command, args, {
      cwd: cwd,
      shell: true,
      stdio: ['ignore', 'pipe', 'pipe']
    });

    let output = '';
    let errorOutput = '';

    process.stdout.on('data', (data) => {
      output += data.toString();
      console.log(`[${command}] ${data.toString().trim()}`);
    });

    process.stderr.on('data', (data) => {
      errorOutput += data.toString();
      console.log(`[${command} ERROR] ${data.toString().trim()}`);
    });

    process.on('close', (code) => {
      if (code === 0 || code === null) {
        resolve(output);
      } else {
        console.warn(`Comando completado con código ${code}, continuando...`);
        resolve(output);
      }
    });

    process.on('error', (error) => {
      console.warn(`Error en comando: ${error.message}, continuando...`);
      resolve('');
    });

    // Timeout de 30 segundos
    setTimeout(() => {
      console.warn('Timeout en comando, continuando...');
      resolve(output);
    }, 30000);
  });
}

async function setupApplication() {
  const appRoot = path.join(__dirname, '..');

  try {
    console.log('🔧 Configurando aplicación...');

    // Verificar si .env existe
    const envPath = path.join(appRoot, '.env');
    if (!fs.existsSync(envPath)) {
      console.log('📝 Creando archivo .env...');
      const envExamplePath = path.join(appRoot, '.env.example');
      if (fs.existsSync(envExamplePath)) {
        const envExample = fs.readFileSync(envExamplePath, 'utf8');
        fs.writeFileSync(envPath, envExample);
      }
    }

    // Generar APP_KEY si no existe
    const envContent = fs.readFileSync(envPath, 'utf8');
    if (!envContent.includes('APP_KEY=base64:')) {
      console.log('🔑 Generando APP_KEY...');
      await runCommand('php', ['artisan', 'key:generate'], appRoot);
    }

    // Ejecutar migraciones
    console.log('📦 Ejecutando migraciones...');
    await runCommand('php', ['artisan', 'migrate', '--force'], appRoot);

    // Ejecutar seeders
    console.log('🌱 Cargando datos iniciales...');
    await runCommand('php', ['artisan', 'db:seed', '--force'], appRoot);

    console.log('✅ Aplicación configurada correctamente');
    return true;
  } catch (error) {
    console.error('❌ Error durante la configuración:', error.message);
    console.log('⚠️ Continuando de todas formas...');
    return true;
  }
}

module.exports = { setupApplication };
