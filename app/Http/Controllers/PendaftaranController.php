<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Mail\PendaftaranPelangganBaru;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class PendaftaranController extends Controller
{
    public function create(Request $request)
    {
        // Resolusi kode referral: URL menang atas session
        $ref = $request->query('ref');

        if ($ref) {
            // Simpan/timpa ke session (link terbaru selalu menang)
            session(['referral_code' => $ref]);
        } else {
            // Tidak ada di URL, ambil dari session jika ada
            $ref = session('referral_code');
        }

        // Cari mitra valid berdasarkan kode referral
        $mitra = null;
        if ($ref) {
            $mitra = User::where('kode_referral', $ref)
                ->where('role', 'mitra')
                ->where('status_aktif', true)
                ->where('status_pendaftaran', 'disetujui')
                ->first();

            // Kode ada tapi mitra tidak valid — hapus dari session agar tidak dipakai
            if (!$mitra) {
                session()->forget('referral_code');
            }
        }

        $paketGrouped = Paket::where('aktif', true)->get()->groupBy('kategori');

        return response()
            ->view('pendaftaran.create', compact('mitra', 'paketGrouped'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemilik'        => 'required|string|max:255',
            'nama_usaha'          => 'required|string|max:255',
            'no_hp'               => 'required|regex:/^0[0-9]{9,14}$/',
            'cp_alternatif'       => 'nullable|string|max:20',
            'alamat_instalasi'    => 'required|string',
            'kota'                => 'required|string|max:100',
            'jenis_usaha'         => 'required|in:sekolah,ruko,hotel,kesehatan,kuliner,ekspedisi,pertambangan,energi,agrikultur,media,lainnya',
            'jenis_usaha_lainnya' => 'required_if:jenis_usaha,lainnya|nullable|string|max:255',
            'foto_ktp'            => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_nib_npwp'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_lokasi'         => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'latitude'            => 'required|numeric',
            'longitude'           => 'required|numeric',
            'kode_referral'       => 'nullable|string',
            'paket_id'            => 'required',
        ], [
            'nama_pemilik.required'        => 'Nama pemilik wajib diisi.',
            'nama_pemilik.max'             => 'Nama pemilik maksimal 255 karakter.',
            'nama_usaha.required'          => 'Nama usaha wajib diisi.',
            'nama_usaha.max'               => 'Nama usaha maksimal 255 karakter.',
            'no_hp.required'               => 'Nomor HP wajib diisi.',
            'no_hp.regex'                  => 'Nomor HP tidak valid (contoh: 081234567890).',
            'cp_alternatif.max'            => 'CP Alternatif maksimal 20 karakter.',
            'alamat_instalasi.required'    => 'Alamat instalasi wajib diisi.',
            'kota.required'                => 'Kota wajib diisi.',
            'kota.max'                     => 'Kota maksimal 100 karakter.',
            'jenis_usaha.required'         => 'Jenis usaha wajib dipilih.',
            'jenis_usaha.in'               => 'Pilihan jenis usaha tidak valid.',
            'jenis_usaha_lainnya.required_if' => 'Jenis usaha lainnya wajib diisi jika Anda memilih Lainnya.',
            'jenis_usaha_lainnya.max'      => 'Jenis usaha lainnya maksimal 255 karakter.',
            'foto_ktp.required'            => 'Foto KTP wajib diunggah.',
            'foto_ktp.file'                => 'Foto KTP harus berupa file.',
            'foto_ktp.mimes'               => 'Format Foto KTP harus JPG, JPEG, PNG, atau PDF.',
            'foto_ktp.max'                 => 'Ukuran Foto KTP maksimal 2MB.',
            'foto_nib_npwp.required'       => 'Foto NPWP/NIB/Dokumen Usaha wajib diunggah.',
            'foto_nib_npwp.file'           => 'Foto NPWP/NIB/Dokumen Usaha harus berupa file.',
            'foto_nib_npwp.mimes'          => 'Format Foto NPWP/NIB/Dokumen Usaha harus JPG, JPEG, PNG, atau PDF.',
            'foto_nib_npwp.max'            => 'Ukuran Foto NPWP/NIB/Dokumen Usaha maksimal 2MB.',
            'foto_lokasi.required'         => 'Foto Tampak Depan Usaha wajib diunggah.',
            'foto_lokasi.file'             => 'Foto Tampak Depan Usaha harus berupa file.',
            'foto_lokasi.mimes'            => 'Format Foto Tampak Depan Usaha harus JPG, JPEG, PNG, atau PDF.',
            'foto_lokasi.max'              => 'Ukuran Foto Tampak Depan Usaha maksimal 2MB.',
            'latitude.required'            => 'Titik lokasi belum ditentukan. Silakan klik tombol Ambil Lokasi Saat Ini atau ketuk peta.',
            'longitude.required'           => 'Titik lokasi belum ditentukan. Silakan klik tombol Ambil Lokasi Saat Ini atau ketuk peta.',
            'latitude.numeric'             => 'Format lokasi tidak valid.',
            'longitude.numeric'            => 'Format lokasi tidak valid.',
            'paket_id.required'            => 'Silakan pilih paket atau opsi konsultasi.',
        ]);

        // Resolusi mitra: prioritaskan kode dari form (hidden input), fallback ke session
        $kodeReferral = $request->input('kode_referral') ?: session('referral_code');

        $mitra = null;
        if ($kodeReferral) {
            $mitra = User::where('kode_referral', $kodeReferral)
                ->where('role', 'mitra')
                ->where('status_aktif', true)
                ->where('status_pendaftaran', 'disetujui')
                ->first();
        }
        // Mitra boleh null — pendaftaran tanpa mitra tetap diperbolehkan

        $latitude  = $request->input('latitude');
        $longitude = $request->input('longitude');

        $pendaftaran = new Pendaftaran();
        $pendaftaran->nama_pemilik         = $request->input('nama_pemilik');
        $pendaftaran->nama_usaha           = $request->input('nama_usaha');
        $pendaftaran->no_hp                = $request->input('no_hp');
        $pendaftaran->cp_alternatif        = $request->input('cp_alternatif') ?: null;
        $pendaftaran->alamat_instalasi     = $request->input('alamat_instalasi');
        $pendaftaran->kota                 = $request->input('kota');
        $pendaftaran->jenis_usaha          = $request->input('jenis_usaha');
        $pendaftaran->jenis_usaha_lainnya  = $request->input('jenis_usaha') === 'lainnya' ? $request->input('jenis_usaha_lainnya') : null;
        $pendaftaran->latitude             = $latitude;
        $pendaftaran->longitude            = $longitude;
        $pendaftaran->link_maps            = "https://www.google.com/maps?q={$latitude},{$longitude}";
        $pendaftaran->mitra_id             = $mitra->id;
        $pendaftaran->sumber_input         = 'pelanggan';
        $pendaftaran->status               = 'pending';
        $pendaftaran->foto_izin_usaha      = null;

        // Upload files
        $pendaftaran->foto_ktp       = $request->file('foto_ktp')->store('pendaftaran', 'public');
        $pendaftaran->foto_nib_npwp  = $request->file('foto_nib_npwp')->store('pendaftaran', 'public');
        $pendaftaran->foto_lokasi    = $request->file('foto_lokasi')->store('pendaftaran', 'public');

        // Paket handling
        $paketInput = $request->input('paket_id');
        if ($paketInput === 'konsultasi') {
            $pendaftaran->paket_id = null;
            $pendaftaran->konsultasi_paket = true;
        } else {
            // allow numeric IDs only
            if (!empty($paketInput) && is_numeric($paketInput)) {
                $paket = Paket::where('id', $paketInput)->where('aktif', true)->first();
                if ($paket) {
                    $pendaftaran->paket_id = $paket->id;
                    $pendaftaran->konsultasi_paket = false;
                } else {
                    return back()->withInput()->withErrors(['paket_id' => 'Paket yang dipilih tidak ditemukan atau tidak aktif.']);
                }
            } else {
                return back()->withInput()->withErrors(['paket_id' => 'Nilai paket tidak valid. Silakan pilih paket atau opsi konsultasi.']);
            }
        }

        $pendaftaran->save();

        try {
            $adminEmail = config('mail.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new PendaftaranPelangganBaru($pendaftaran));
            }

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::make()
                    ->title('Pendaftaran Pelanggan Baru')
                    ->body("{$pendaftaran->nama_pemilik} ({$pendaftaran->nama_usaha}) telah mendaftar.")
                    ->info()
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->label('Lihat')
                            ->url('/admin/pendaftarans')
                            ->button(),
                    ])
                    ->sendToDatabase($admin);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim notifikasi pendaftaran pelanggan baru: ' . $e->getMessage());
        }

        return redirect()->route('daftar.berhasil')->with('success_id', $pendaftaran->id);
    }

    public function success()
    {
        return view('pendaftaran.success');
    }
}
