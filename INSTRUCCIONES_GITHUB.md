# 📦 Instrucciones para Subir a GitHub

## Paso 1: Crear el Repositorio en GitHub

1. Ve a [GitHub](https://github.com)
2. Haz clic en el botón **"+"** en la esquina superior derecha
3. Selecciona **"New repository"**
4. Completa los datos:
   - **Repository name**: `pharma-sync`
   - **Description**: `Sistema de Farmacia moderno con Laravel 12, Tailwind CSS 4 y UI moderna`
   - **Visibility**: Elige **Public** o **Private**
   - **NO** marques "Initialize this repository with a README" (ya tenemos uno)
5. Haz clic en **"Create repository"**

---

## Paso 2: Conectar tu Repositorio Local con GitHub

GitHub te mostrará una página con instrucciones. Copia la URL del repositorio (algo como `https://github.com/TU-USUARIO/pharma-sync.git`).

Luego ejecuta estos comandos en tu terminal:

```bash
# Agregar el repositorio remoto
git remote add origin https://github.com/TU-USUARIO/pharma-sync.git

# Cambiar el nombre de la rama a main (si es necesario)
git branch -M main

# Subir el código a GitHub
git push -u origin main
```

**Reemplaza `TU-USUARIO` con tu nombre de usuario de GitHub.**

---

## Paso 3: Verificar

1. Refresca la página de tu repositorio en GitHub
2. Deberías ver todos los archivos del proyecto
3. El README.md se mostrará automáticamente en la página principal

---

## 🎉 ¡Listo!

Tu proyecto ahora está en GitHub y puedes:
- Compartirlo con otros
- Clonarlo en otras computadoras
- Hacer backups automáticos
- Colaborar con otros desarrolladores

---

## 📝 Comandos Útiles para el Futuro

### Subir cambios nuevos:
```bash
git add .
git commit -m "Descripción de los cambios"
git push
```

### Ver el estado:
```bash
git status
```

### Ver el historial:
```bash
git log --oneline
```

### Crear una nueva rama:
```bash
git checkout -b nombre-de-la-rama
```

---

## 🔐 Nota sobre Archivos Sensibles

El archivo `.gitignore` ya está configurado para NO subir:
- `.env` (variables de entorno con contraseñas)
- `node_modules/` (dependencias de Node)
- `vendor/` (dependencias de PHP)
- `database/database.sqlite` (base de datos local)

Esto protege tu información sensible.

---

**Fecha**: 18 de Febrero de 2026
**Estado**: Listo para subir a GitHub
