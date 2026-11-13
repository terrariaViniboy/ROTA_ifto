$root = $env:CURSOR_PROJECT_DIR
if ([string]::IsNullOrWhiteSpace($root)) {
    $root = (Resolve-Path (Join-Path $PSScriptRoot "..\..")).Path
}

$plugin = Join-Path $root ".cursor\plugins\superpowers"

@{ pluginPaths = @($plugin) } | ConvertTo-Json -Compress
