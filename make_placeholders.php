<?php
// Create simple 1x1 pixel JPEG placeholder images for panduan
// Real images will be uploaded by project owner
$files = ['ktp-valid', 'ktp-invalid', 'npwp-valid', 'npwp-invalid', 'bangunan-valid', 'bangunan-invalid'];

// Minimal valid JPEG (1x1 white pixel)
$jpeg_hex = 'FFD8FFE000104A46494600010100000100010000FFDB004300080606070605080707070909080A0C140D0C0B0B0C1912130F141D1A1F1E1D1A1C1C20242E2720222C231C1C2837292C30313434341F27393D38323C2E333432FFDB0043010909090C0B0C180D0D1832211C213232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232FFC0001108000100010301220002110103110FFFDC40014000100000000000000000000000000000000FFFDC4001401000000000000000000000000000000000FFDA000C030102001100021101033F00FFD9';

foreach ($files as $f) {
    $dest = __DIR__ . '/public/images/panduan/' . $f . '.jpg';
    if (!file_exists($dest)) {
        file_put_contents($dest, hex2bin($jpeg_hex));
        echo "Created: $f.jpg\n";
    } else {
        echo "Exists: $f.jpg\n";
    }
}
echo "Done.\n";
