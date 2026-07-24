<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MitraQrController extends Controller
{
    /**
     * Generate a composite SVG card and return it as a downloadable file.
     *
     * Layout (400 x 500 px, white background):
     *   y=52   – "SIMITRA" (bold, blue, 28px)
     *   y=80   – "Kode Referral Mitra" (gray, 14px)
     *   y=100  – QR code 300×300 (centered: translate(50,100))
     *   y=415  – thin separator line
     *   y=456  – referral code text (bold, blue, 26px)
     *   y=486  – URL hint (gray, 11px)
     */
    public function download(Request $request)
    {
        $user = auth()->user();

        if (! $user || ! $user->kode_referral) {
            abort(403, 'Tidak diizinkan.');
        }

        $kodeReferral = $user->kode_referral;
        $linkReferral = url('/daftar?ref=' . $kodeReferral);

        // 1. Generate QR SVG dari package (margin(0) = tepat 300×300 px)
        $qrRaw = (string) QrCode::format('svg')->size(300)->margin(0)->generate($linkReferral);

        // 2. Ekstrak isi di dalam <svg>…</svg> saja
        $qrInner = $qrRaw;
        if (preg_match('/<svg[^>]*>(.*)<\/svg>/s', $qrRaw, $m)) {
            $qrInner = $m[1];
        }

        // 3. Escape kode referral agar aman di dalam XML
        $kodeEsc = htmlspecialchars($kodeReferral, ENT_XML1 | ENT_QUOTES, 'UTF-8');

        // 4. Bangun kanvas SVG 400×500 dengan concatenation
        $w = 400;
        $h = 500;
        // QR 300 px, di-translate ke x=(400-300)/2=50, y=100
        $tx = 50;
        $ty = 100;

        $svg = '';
        $svg .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1"';
        $svg .= ' width="' . $w . '" height="' . $h . '"';
        $svg .= ' viewBox="0 0 ' . $w . ' ' . $h . '">' . "\n";

        // Latar putih
        $svg .= '  <rect width="' . $w . '" height="' . $h . '" fill="#ffffff"/>' . "\n";

        // Judul
        $svg .= '  <text x="200" y="52" text-anchor="middle"';
        $svg .= ' font-family="Arial, Helvetica, sans-serif"';
        $svg .= ' font-size="28" font-weight="bold" fill="#1D5FAE">SIMITRA</text>' . "\n";

        // Subjudul
        $svg .= '  <text x="200" y="80" text-anchor="middle"';
        $svg .= ' font-family="Arial, Helvetica, sans-serif"';
        $svg .= ' font-size="14" fill="#555555">Kode Referral Mitra</text>' . "\n";

        // QR code (di-center via translate)
        $svg .= '  <g transform="translate(' . $tx . ',' . $ty . ')">' . "\n";
        $svg .= '    ' . $qrInner . "\n";
        $svg .= '  </g>' . "\n";

        // Garis pemisah
        $svg .= '  <line x1="60" y1="415" x2="340" y2="415"';
        $svg .= ' stroke="#DDDDDD" stroke-width="1"/>' . "\n";

        // Kode referral (besar, bold)
        $svg .= '  <text x="200" y="456" text-anchor="middle"';
        $svg .= ' font-family="Arial, Helvetica, sans-serif"';
        $svg .= ' font-size="26" font-weight="bold" fill="#1D5FAE"';
        $svg .= ' letter-spacing="2">' . $kodeEsc . '</text>' . "\n";

        $svg .= '</svg>';

        $filename = 'QR_Referral_' . $kodeReferral . '.svg';

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
