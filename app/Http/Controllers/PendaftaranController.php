<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    public function create(Request $request)
    {
        $mitra = null;
        if ($request->has('ref')) {
            $mitra = User::where('kode_referral', $request->query('ref'))
                ->where('role', 'mitra')
                ->where('status_aktif', true)
                ->first();
        }

        return view('pendaftaran.create', compact('mitra'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'nama_usaha' => 'required|string|max:255',
            'no_hp' => 'required|regex:/^0[0-9]{9,14}$/',
            'jenis_usaha' => 'required|in:sekolah,ruko,hotel,kesehatan,kuliner,ekspedisi,pertambangan,energi,agrikultur,media,lainnya',
            'jenis_usaha_lainnya' => 'required_if:jenis_usaha,lainnya|nullable|string|max:255',
            'foto_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_izin_usaha' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_nib_npwp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_lokasi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'kode_referral' => 'required',
        ], [
            'nama_pemilik.required' => 'Nama pemilik wajib diisi.',
            'nama_pemilik.max' => 'Nama pemilik maksimal 255 karakter.',
            'nama_usaha.required' => 'Nama usaha wajib diisi.',
            'nama_usaha.max' => 'Nama usaha maksimal 255 karakter.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Nomor HP tidak valid (contoh: 081234567890).',
            'jenis_usaha.required' => 'Jenis usaha wajib dipilih.',
            'jenis_usaha.in' => 'Pilihan jenis usaha tidak valid.',
            'jenis_usaha_lainnya.required_if' => 'Jenis usaha lainnya wajib diisi jika Anda memilih Lainnya.',
            'jenis_usaha_lainnya.max' => 'Jenis usaha lainnya maksimal 255 karakter.',
            'foto_ktp.required' => 'Foto KTP wajib diunggah.',
            'foto_ktp.file' => 'Foto KTP harus berupa file.',
            'foto_ktp.mimes' => 'Format Foto KTP harus JPG, JPEG, PNG, atau PDF.',
            'foto_ktp.max' => 'Ukuran Foto KTP maksimal 2MB.',
            'foto_izin_usaha.required' => 'Foto Izin Usaha wajib diunggah.',
            'foto_izin_usaha.file' => 'Foto Izin Usaha harus berupa file.',
            'foto_izin_usaha.mimes' => 'Format Foto Izin Usaha harus JPG, JPEG, PNG, atau PDF.',
            'foto_izin_usaha.max' => 'Ukuran Foto Izin Usaha maksimal 2MB.',
            'foto_nib_npwp.required' => 'Foto NIB atau NPWP wajib diunggah.',
            'foto_nib_npwp.file' => 'Foto NIB atau NPWP harus berupa file.',
            'foto_nib_npwp.mimes' => 'Format Foto NIB atau NPWP harus JPG, JPEG, PNG, atau PDF.',
            'foto_nib_npwp.max' => 'Ukuran Foto NIB atau NPWP maksimal 2MB.',
            'foto_lokasi.required' => 'Foto Lokasi Usaha wajib diunggah.',
            'foto_lokasi.file' => 'Foto Lokasi Usaha harus berupa file.',
            'foto_lokasi.mimes' => 'Format Foto Lokasi Usaha harus JPG, JPEG, PNG, atau PDF.',
            'foto_lokasi.max' => 'Ukuran Foto Lokasi Usaha maksimal 2MB.',
            'latitude.required' => 'Titik lokasi belum ditentukan. Silakan klik tombol Ambil Lokasi Saat Ini atau ketuk peta.',
            'longitude.required' => 'Titik lokasi belum ditentukan. Silakan klik tombol Ambil Lokasi Saat Ini atau ketuk peta.',
            'latitude.numeric' => 'Format lokasi tidak valid.',
            'longitude.numeric' => 'Format lokasi tidak valid.',
            'kode_referral.required' => 'Kode referral wajib diisi.',
        ]);

        $mitra = User::where('kode_referral', $request->input('kode_referral'))
            ->where('role', 'mitra')
            ->where('status_aktif', true)
            ->first();

        if (!$mitra) {
            return back()->withInput()->withErrors(['kode_referral' => 'Kode referral tidak valid atau mitra tidak aktif.']);
        }

        $pendaftaran = new Pendaftaran();
        $pendaftaran->nama_pemilik = $request->input('nama_pemilik');
        $pendaftaran->nama_usaha = $request->input('nama_usaha');
        $pendaftaran->no_hp = $request->input('no_hp');
        $pendaftaran->jenis_usaha = $request->input('jenis_usaha');
        $pendaftaran->jenis_usaha_lainnya = $request->input('jenis_usaha') === 'lainnya' ? $request->input('jenis_usaha_lainnya') : null;
        $pendaftaran->latitude = $request->input('latitude');
        $pendaftaran->longitude = $request->input('longitude');
        $pendaftaran->mitra_id = $mitra->id;
        $pendaftaran->sumber_input = 'pelanggan';
        $pendaftaran->status = 'pending';

        // Upload files
        $pendaftaran->foto_ktp = $request->file('foto_ktp')->store('pendaftaran', 'public');
        $pendaftaran->foto_izin_usaha = $request->file('foto_izin_usaha')->store('pendaftaran', 'public');
        $pendaftaran->foto_nib_npwp = $request->file('foto_nib_npwp')->store('pendaftaran', 'public');
        $pendaftaran->foto_lokasi = $request->file('foto_lokasi')->store('pendaftaran', 'public');

        $pendaftaran->save();

        return redirect()->route('daftar.berhasil')->with('success_id', $pendaftaran->id);
    }

    public function success()
    {
        return view('pendaftaran.success');
    }
}
