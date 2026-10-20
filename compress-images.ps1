param(
    [switch]$DryRun
)

Add-Type -AssemblyName System.Drawing

$dirs = @(
    "imagenes",
    "imagendes de productos",
    "fotos_portada",
    "fotos_perfil",
    "fotos de usuarios",
    "comprobantes",
    "hogar_y_decoracion_img"
)

$exts = @(".jpg", ".jpeg", ".png")
$maxDim = 1600
$quality = 78
$threshold = 150KB

$jpegCodec = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object { $_.MimeType -eq 'image/jpeg' }
$pngCodec  = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object { $_.MimeType -eq 'image/png' }

$totalOrig = 0
$totalNew  = 0
$count = 0
$ok = 0
$err = 0

foreach ($d in $dirs) {
    if (-not (Test-Path $d)) { continue }
    Get-ChildItem -Path $d -Recurse -File | ForEach-Object {
        $ext = $_.Extension.ToLower()
        if ($exts -notcontains $ext) { return }
        if ($_.Length -lt $threshold) { return }

        $count++
        $orig = $_.Length
        $totalOrig += $orig
        $path = $_.FullName

        if ($DryRun) {
            Write-Output ("DRY  {0,-55} {1,8:N0} KB" -f $path, ($orig/1KB))
            return
        }

        $img = $null
        try {
            # Leer bytes a memoria para NO bloquear el archivo
            $bytes = [System.IO.File]::ReadAllBytes($path)
            $ms = New-Object System.IO.MemoryStream(,$bytes)
            try {
                $src = [System.Drawing.Image]::FromStream($ms)
            } catch {
                $ms.Dispose()
                throw
            }

            # Copiar a un Bitmap nuevo: se desvincula del stream y libera memoria
            $bmp = New-Object System.Drawing.Bitmap($src.Width, $src.Height, [System.Drawing.Imaging.PixelFormat]::Format24bppRgb)
            $g0 = [System.Drawing.Graphics]::FromImage($bmp)
            $g0.DrawImage($src, 0, 0, $src.Width, $src.Height)
            $g0.Dispose()
            $src.Dispose()
            $ms.Dispose()

            $w = $bmp.Width
            $h = $bmp.Height

            $target = $bmp
            $tmpBmp = $null
            if ($w -gt $maxDim -or $h -gt $maxDim) {
                if ($w -ge $h) { $newW = $maxDim; $newH = [int]($h * $maxDim / $w) }
                else          { $newH = $maxDim; $newW = [int]($w * $maxDim / $h) }
                $tmpBmp = New-Object System.Drawing.Bitmap($newW, $newH, [System.Drawing.Imaging.PixelFormat]::Format24bppRgb)
                $g = [System.Drawing.Graphics]::FromImage($tmpBmp)
                $g.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
                $g.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
                $g.PixelOffsetMode = [System.Drawing.Drawing2D.PixelOffsetMode]::HighQuality
                $g.DrawImage($bmp, 0, 0, $newW, $newH)
                $g.Dispose()
                $target = $tmpBmp
            }

            # Guardar a temporal y reemplazar (archivo original ya liberado)
            $tmp = $path + ".tmp"
            if ($ext -eq ".png") {
                $target.Save($tmp, $pngCodec, $null)
            } else {
                $ep = New-Object System.Drawing.Imaging.EncoderParameters(1)
                $ep.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter([System.Drawing.Imaging.Encoder]::Quality, $quality)
                $target.Save($tmp, $jpegCodec, $ep)
            }

            if ($tmpBmp) { $tmpBmp.Dispose() }
            $bmp.Dispose()

            Move-Item -Path $tmp -Destination $path -Force

            $new = (Get-Item $path).Length
            $totalNew += $new
            $ok++
            Write-Output ("OK   {0,-55} {1,8:N0} KB -> {2,8:N0} KB" -f $path, ($orig/1KB), ($new/1KB))
        } catch {
            $err++
            if ($tmp -and (Test-Path $tmp)) { Remove-Item $tmp -Force -ErrorAction SilentlyContinue }
            Write-Output ("ERR  {0,-55} {1}" -f $path, $_.Exception.Message)
        } finally {
            if ($img) { $img.Dispose() }
        }
    }
}

Write-Output "---------------------------------------------"
if ($DryRun) {
    Write-Output ("Candidatos: {0} archivos, {1:N2} MB" -f $count, ($totalOrig/1MB))
} else {
    Write-Output ("Procesados: {0} archivos | OK: {1} | ERR: {2}" -f $count, $ok, $err)
    Write-Output ("Antes:  {0:N2} MB" -f ($totalOrig/1MB))
    Write-Output ("Despues:{0:N2} MB" -f ($totalNew/1MB))
    if ($totalOrig -gt 0 -and $totalNew -gt 0) {
        Write-Output ("Ahorro: {0:N1}%" -f ((1 - $totalNew/$totalOrig)*100))
    }
}
