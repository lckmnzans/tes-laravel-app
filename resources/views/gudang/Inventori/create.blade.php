<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-0">Tambah Bahan Baku</h1>
                    <hr />
                    @if (session()->has('error'))
                        <div class="text-red-500">
                            {{ session('error') }}
                        </div>
                    @endif
                    <p><a href="{{ route('gudang.bahanbaku.index') }}" class="btn btn-primary">Kembali</a></p>

                    <form action="{{ route('gudang.bahanbaku.index.store') }}" method="POST">
                        @csrf

                        <!-- Nama Bahan -->
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="nama_bahan" class="form-control" placeholder="Nama Bahan" required>
                                @error('nama_bahan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Jenis Bahan -->
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="jenis_bahan" class="form-control" placeholder="Jenis Bahan" required>
                                @error('jenis_bahan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Penerimaan -->
                        <div class="row mb-3">
                            <div class="col">
                                <input type="date" name="tanggal_penerimaan" class="form-control" placeholder="Tanggal Penerimaan">
                                @error('tanggal_penerimaan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Supplier -->
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="supplier" class="form-control" placeholder="Supplier">
                                @error('supplier')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div class="row mb-3">
                            <div class="col">
                                <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
                                @error('jumlah')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Stok Tersedia -->
                        <div class="row mb-3">
                            <div class="col">
                                <input type="number" name="stok_tersedia" class="form-control" placeholder="Stok Tersedia" required>
                                @error('stok_tersedia')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Safety Stock -->
                        <div class="row mb-3">
                            <div class="col">
                                <input type="number" name="safety_stock" class="form-control" placeholder="Safety Stock">
                                @error('safety_stock')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="number" step="0.01" name="harga" class="form-control" placeholder="Harga" required>
                                @error('harga')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>                        

                        <!-- Lokasi Stok -->
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="lokasi_stok" class="form-control" placeholder="Lokasi Stok">
                                @error('lokasi_stok')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- No Penerimaan -->
                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" name="no_penerimaan" class="form-control" placeholder="No Penerimaan">
                                @error('no_penerimaan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="row mb-3">
                            <div class="col">
                                <textarea name="catatan" class="form-control" placeholder="Catatan"></textarea>
                                @error('catatan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('gudang.bahanbaku.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
