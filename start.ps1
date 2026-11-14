$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot
$runtimeDir = Join-Path $env:LOCALAPPDATA 'IT0049\php85'
if (Test-Path (Join-Path $runtimeDir 'php.exe')) { $env:PATH = $runtimeDir + ';' + $env:PATH }
php artisan serve --host=127.0.0.1 --port=8000 --no-reload
