<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Common\ErrorCorrectionLevel;
use Intervention\Image\ImageManagerStatic as Image;

class MitraQrController extends Controller
{
    /**
     * Generate a composite PNG card and return it as a downloadable file.
     *
     * Layout (400 x 500 px PNG card, white background):
     *   y=48   – "SIMITRA" (bold, blue, 28px)
     *   y=80   – "Kode Referral Mitra" (gray, 14px)
     *   y=100  – QR code 300×300 (centered: x=50, y=100)
     *   y=415  – thin separator line
     *   y=440  – referral code text (bold, blue, 26px)
     */
    public function download(Request $request)
    {
        $user = auth()->user();

        if (! $user || ! $user->kode_referral) {
            abort(403, 'Tidak diizinkan.');
        }

        $kodeReferral = $user->kode_referral;
        $linkReferral = url('/daftar?ref=' . $kodeReferral);
        $filename = 'QR_Referral_' . $kodeReferral . '.png';

        try {
            // 1. Generate QR Code matrix using BaconQrCode Encoder (works natively with GD)
            $qrCode = Encoder::encode($linkReferral, ErrorCorrectionLevel::L());
            $matrix = $qrCode->getMatrix();
            $matrixSize = $matrix->getWidth();

            $qrPixelSize = 300;
            $marginModules = 1;
            $totalModules = $matrixSize + ($marginModules * 2);
            $modulePixelSize = (int) floor($qrPixelSize / $totalModules);
            $actualQrSize = $modulePixelSize * $totalModules;

            $qrImg = imagecreatetruecolor($actualQrSize, $actualQrSize);
            $white = imagecolorallocate($qrImg, 255, 255, 255);
            $black = imagecolorallocate($qrImg, 0, 0, 0);
            imagefill($qrImg, 0, 0, $white);

            for ($y = 0; $y < $matrixSize; $y++) {
                for ($x = 0; $x < $matrixSize; $x++) {
                    if ($matrix->get($x, $y) === 1) {
                        $px = ($x + $marginModules) * $modulePixelSize;
                        $py = ($y + $marginModules) * $modulePixelSize;
                        imagefilledrectangle($qrImg, $px, $py, $px + $modulePixelSize - 1, $py + $modulePixelSize - 1, $black);
                    }
                }
            }

            // 2. Build Card Canvas 400x500 using Intervention Image
            $canvas = Image::canvas(400, 500, '#ffffff');

            ob_start();
            imagepng($qrImg);
            $qrPngData = ob_get_clean();
            imagedestroy($qrImg);

            $qrIntervention = Image::make($qrPngData)->resize(300, 300);

            // Insert QR Code at x=50, y=100
            $canvas->insert($qrIntervention, 'top-left', 50, 100);

            // Locate system TTF fonts if available
            $fontPath = null;
            $possibleFonts = [
                'C:\\Windows\\Fonts\\arial.ttf',
                'C:\\Windows\\Fonts\\segoeui.ttf',
                '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            ];
            foreach ($possibleFonts as $f) {
                if (file_exists($f)) {
                    $fontPath = $f;
                    break;
                }
            }

            $fontBoldPath = null;
            $possibleBoldFonts = [
                'C:\\Windows\\Fonts\\arialbd.ttf',
                'C:\\Windows\\Fonts\\segoeuib.ttf',
                '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            ];
            foreach ($possibleBoldFonts as $f) {
                if (file_exists($f)) {
                    $fontBoldPath = $f;
                    break;
                }
            }
            if (! $fontBoldPath) {
                $fontBoldPath = $fontPath;
            }

            // Header "SIMITRA"
            $canvas->text('SIMITRA', 200, 48, function ($font) use ($fontBoldPath) {
                if ($fontBoldPath) {
                    $font->file($fontBoldPath);
                }
                $font->size(28);
                $font->color('#1D5FAE');
                $font->align('center');
                $font->valign('top');
            });

            // Subtitle "Kode Referral Mitra"
            $canvas->text('Kode Referral Mitra', 200, 80, function ($font) use ($fontPath) {
                if ($fontPath) {
                    $font->file($fontPath);
                }
                $font->size(14);
                $font->color('#555555');
                $font->align('center');
                $font->valign('top');
            });

            // Line separator y=415
            $canvas->line(60, 415, 340, 415, function ($draw) {
                $draw->color('#DDDDDD');
            });

            // Kode Referral text y=440
            $canvas->text($kodeReferral, 200, 440, function ($font) use ($fontBoldPath) {
                if ($fontBoldPath) {
                    $font->file($fontBoldPath);
                }
                $font->size(26);
                $font->color('#1D5FAE');
                $font->align('center');
                $font->valign('top');
            });

            $pngContent = (string) $canvas->encode('png');

            return response($pngContent)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Throwable $e) {
            // Fallback to simple QrCode PNG if available
            try {
                $qrPng = QrCode::format('png')->size(400)->margin(1)->generate($linkReferral);
                return response($qrPng)
                    ->header('Content-Type', 'image/png')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            } catch (\Throwable $ex) {
                abort(500, 'Gagal mendownload QR PNG: ' . $ex->getMessage());
            }
        }
    }
}
