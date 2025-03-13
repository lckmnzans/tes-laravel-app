<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Penerimaan Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-0">Tambah Penerimaan Bahan Baku</h1>
                    <hr />

                    @if (session()->has('error'))
                        <div class="text-red-500">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p><a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-primary">Kembali</a></p>

                    <form action="{{ route('gudang.penerimaan.index.store') }}" method="POST">
                        @csrf

                        <!-- Pilih Bahan Baku -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="bahan_baku_id">Bahan Baku</label>
                                <select name="bahan_baku_id" class="form-control" required>
                                    <option value="">Pilih Bahan Baku</option>
                                    @foreach ($bahanBakuList as $bahan)
                                        <option value="{{ $bahan->id }}">{{ $bahan->namaBahan }}</option>
                                    @endforeach
                                </select>
                                @error('bahan_baku_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Jenis Bahan Baku -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="jenisBahan">Jenis Bahan Baku</label>
                                <input type="text" name="jenisBahan" class="form-control" placeholder="Jenis Bahan Baku" required>
                                @error('jenisBahan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Jumlah Order -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="jumlah_order">Jumlah Order</label>
                                <input type="number" name="jumlah_order" class="form-control" placeholder="Jumlah Order" required>
                                @error('jumlah_order')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Supplier -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="supplier">Supplier</label>
                                <input type="text" name="supplier" class="form-control" placeholder="Nama Supplier" required>
                                @error('supplier')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
