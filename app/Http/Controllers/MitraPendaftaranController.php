<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PendaftaranMitraBaru;

class MitraPendaftaranController extends Controller
{
    public function create()
    {
        return view('mitra-pendaftaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'nama_bank' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.string' => 'Nama lengkap harus berupa teks.',
            'nama.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.string' => 'Nomor HP harus berupa teks.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'alamat.string' => 'Alamat lengkap harus berupa teks.',
            'nama_bank.required' => 'Nama bank wajib diisi.',
            'nama_bank.string' => 'Nama bank harus berupa teks.',
            'nama_bank.max' => 'Nama bank maksimal 255 karakter.',
            'no_rekening.required' => 'Nomor rekening wajib diisi.',
            'no_rekening.string' => 'Nomor rekening harus berupa teks.',
            'no_rekening.max' => 'Nomor rekening maksimal 50 karakter.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        $mitra = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'nama_bank' => $request->nama_bank,
            'no_rekening' => $request->no_rekening,
            'password' => Hash::make($request->password),
            'role' => 'mitra',
            'status_aktif' => false,
            'status_pendaftaran' => 'pending',
        ]);

        try {
            Mail::to(config('mail.admin_email'))->send(new PendaftaranMitraBaru($mitra));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email notifikasi pendaftaran mitra: ' . $e->getMessage());
        }

        return redirect()->route('daftar-mitra.success');
    }

    public function success()
    {
        return view('mitra-pendaftaran.success');
    }
}
