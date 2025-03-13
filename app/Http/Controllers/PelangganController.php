<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    public function index()
    {
        // Mengambil data pelanggan
        $pelanggans = Pelanggan::paginate(10);
        return view('exim.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        // Menampilkan form tambah pelanggan
        return view('exim.pelanggan.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:pelanggans,email',
        ]);
        
        // Menyimpan data pelanggan baru
        Pelanggan::create([
            'nama_customer' => $request->input('nama_customer'),
            'alamat' => $request->input('alamat'),
            'no_hp' => $request->input('no_hp'),
            'email' => $request->input('email'),
        ]);
        
        return redirect()->route('exim.pelanggan.index')->with('status', 'Pelanggan berhasil ditambahkan');
    }

    public function show($id)
    {
        // Menampilkan detail pelanggan
        $pelanggan = Pelanggan::findOrFail($id);
        return view('exim.pelanggan.show', compact('pelanggan'));
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('exim.pelanggan.edit', compact('pelanggan'));
    }


    public function update(Request $request, $id)
    {
        // Mencari data pelanggan berdasarkan ID
        $pelanggan = Pelanggan::findOrFail($id);
        
        // Validasi input
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:pelanggans,email,' . $pelanggan->id,
        ]);

        // Memperbarui data pelanggan
        $pelanggan->update([
            'nama_customer' => $request->input('nama_customer'),
            'alamat' => $request->input('alamat'),
            'no_hp' => $request->input('no_hp'),
            'email' => $request->input('email'),
        ]);

        return redirect()->route('exim.pelanggan.index')->with('status', 'Pelanggan berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Menghapus data pelanggan berdasarkan ID
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('exim.pelanggan.index')->with('status', 'Pelanggan berhasil dihapus');
    }
}
