# Script de Verificación de Build CSS para Electron
# Uso: .\verify-css-build.ps1

Write-Host "🔍 Verificando build de CSS..." -ForegroundColor Cyan

$errors = @()
$warnings = @()

# 1. Verificar manifest.json
Write-Host "`n📋 Verificando manifest.json..." -ForegroundColor Yellow
if (Test-Path "public/build/.vite/manifest.json") {
    Write-Host "✅ manifest.json existe" -ForegroundColor Green
    
    # Leer y validar JSON
    try {
        $manifest = Get-Content "public/build/.vite/manifest.json" | ConvertFrom-Json
        Write-Host "✅ manifest.json es válido" -ForegroundColor Green
        
        # Contar entradas
        $count = $manifest.PSObject.Properties.Count
        Write-Host "   Entradas en manifest: $count" -ForegroundColor Gray
    } catch {
        $errors += "manifest.json no es válido: $_"
        Write-Host "❌ manifest.json no es válido" -ForegroundColor Red
    }
} else {
    $errors += "manifest.json no existe"
    Write-Host "❌ manifest.json NO existe" -ForegroundColor Red
}

# 2. Verificar CSS compilado
Write-Host "`n📦 Verificando CSS compilado..." -ForegroundColor Yellow
$cssFiles = Get-ChildItem "public/build/assets/*.css" -ErrorAction SilentlyContinue
if ($cssFiles) {
    Write-Host "✅ CSS compilado existe" -ForegroundColor Green
    
    foreach ($file in $cssFiles) {
        $sizeKB = [math]::Round($file.Length / 1KB, 2)
        Write-Host "   Archivo: $($file.Name)" -ForegroundColor Gray
        Write-Host "   Tamaño: $sizeKB KB" -ForegroundColor Gray
        
        # Verificar tamaño
        if ($sizeKB -gt 500) {
            $warnings += "CSS muy grande: $sizeKB KB (máximo recomendado: 500 KB)"
            Write-Host "   ⚠️ Tamaño grande" -ForegroundColor Yellow
        }
    }
} else {
    $errors += "CSS compilado no existe"
    Write-Host "❌ CSS compilado NO existe" -ForegroundColor Red
}

# 3. Verificar que CSS tiene Tailwind
Write-Host "`n🎨 Verificando Tailwind en CSS..." -ForegroundColor Yellow
$cssFile = Get-ChildItem "public/build/assets/app-*.css" -ErrorAction SilentlyContinue | Select-Object -First 1
if ($cssFile) {
    $content = Get-Content $cssFile.FullName -Raw
    
    if ($content -match "tailwind") {
        Write-Host "✅ Tailwind incluido en CSS" -ForegroundColor Green
    } else {
        $warnings += "Tailwind no encontrado en CSS"
        Write-Host "⚠️ Tailwind no encontrado" -ForegroundColor Yellow
    }
    
    # Contar clases
    $classCount = ([regex]::Matches($content, "\.[a-z-]+")).Count
    Write-Host "   Clases CSS: $classCount" -ForegroundColor Gray
} else {
    Write-Host "⚠️ No se pudo verificar contenido de CSS" -ForegroundColor Yellow
}

# 4. Verificar vite.config.js
Write-Host "`n⚙️ Verificando vite.config.js..." -ForegroundColor Yellow
$viteConfig = Get-Content "vite.config.js" -Raw
if ($viteConfig -match "base:\s*['\"]\.\/['\"]") {
    Write-Host "✅ base: './' configurado" -ForegroundColor Green
} else {
    $errors += "base: './' no configurado en vite.config.js"
    Write-Host "❌ base: './' NO configurado" -ForegroundColor Red
}

if ($viteConfig -match "manifest:\s*true") {
    Write-Host "✅ manifest: true configurado" -ForegroundColor Green
} else {
    $errors += "manifest: true no configurado en vite.config.js"
    Write-Host "❌ manifest: true NO configurado" -ForegroundColor Red
}

# 5. Verificar electron/main.js
Write-Host "`n🖥️ Verificando electron/main.js..." -ForegroundColor Yellow
$electronConfig = Get-Content "electron/main.js" -Raw
if ($electronConfig -match "Content-Security-Policy") {
    Write-Host "✅ CSP configurado" -ForegroundColor Green
} else {
    $errors += "CSP no configurado en electron/main.js"
    Write-Host "❌ CSP NO configurado" -ForegroundColor Red
}

if ($electronConfig -match "style-src.*unsafe-inline") {
    Write-Host "✅ style-src 'unsafe-inline' permitido" -ForegroundColor Green
} else {
    $errors += "style-src 'unsafe-inline' no permitido"
    Write-Host "❌ style-src 'unsafe-inline' NO permitido" -ForegroundColor Red
}

# 6. Verificar tailwind.config.js
Write-Host "`n🎨 Verificando tailwind.config.js..." -ForegroundColor Yellow
$tailwindConfig = Get-Content "tailwind.config.js" -Raw
if ($tailwindConfig -match "resources/\*\*/\*\.blade\.php") {
    Write-Host "✅ Paths de Blade configurados" -ForegroundColor Green
} else {
    $warnings += "Paths de Blade no configurados en tailwind.config.js"
    Write-Host "⚠️ Paths de Blade no configurados" -ForegroundColor Yellow
}

# 7. Verificar resources/js/app.js
Write-Host "`n📝 Verificando resources/js/app.js..." -ForegroundColor Yellow
$appJs = Get-Content "resources/js/app.js" -Raw
if ($appJs -match "import.*desktop") {
    $errors += "resources/js/app.js importa 'desktop' (no existe)"
    Write-Host "❌ Import de 'desktop' encontrado" -ForegroundColor Red
} else {
    Write-Host "✅ No hay imports de archivos no existentes" -ForegroundColor Green
}

# Resumen
Write-Host "`n" -ForegroundColor Gray
Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "RESUMEN DE VERIFICACIÓN" -ForegroundColor Cyan
Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan

if ($errors.Count -eq 0 -and $warnings.Count -eq 0) {
    Write-Host "`n✅ BUILD VERIFICADO CORRECTAMENTE" -ForegroundColor Green
    Write-Host "`nTodo está configurado correctamente. Puedes ejecutar:" -ForegroundColor Green
    Write-Host "  npm run electron:dev" -ForegroundColor Cyan
    exit 0
} else {
    if ($errors.Count -gt 0) {
        Write-Host "`n❌ ERRORES ENCONTRADOS ($($errors.Count)):" -ForegroundColor Red
        foreach ($error in $errors) {
            Write-Host "   • $error" -ForegroundColor Red
        }
    }
    
    if ($warnings.Count -gt 0) {
        Write-Host "`n⚠️ ADVERTENCIAS ($($warnings.Count)):" -ForegroundColor Yellow
        foreach ($warning in $warnings) {
            Write-Host "   • $warning" -ForegroundColor Yellow
        }
    }
    
    if ($errors.Count -gt 0) {
        Write-Host "`n❌ Build NO verificado. Corrige los errores antes de continuar." -ForegroundColor Red
        exit 1
    } else {
        Write-Host "`n⚠️ Build verificado con advertencias. Procede con cuidado." -ForegroundColor Yellow
        exit 0
    }
}
