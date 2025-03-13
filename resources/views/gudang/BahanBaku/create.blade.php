<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Bahan Baku') }}
        </h2>
    </x-slot>
    <!-- Notifikasi Error -->
    @if (session()->has('error'))
        <div class="mb-4 text-red-500 bg-red-100 p-4 rounded-md">
            {{ session('error') }}
        </div>
    @endif
    <div class="py-1">
        <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Form Tambah Bahan Baku -->
                <form action="{{ route('gudang.bahanbaku.index.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Kode Bahan -->
                        <div>
                            <label for="kodeBahan" class="block text-sm font-medium text-gray-700">Kode Bahan</label>
                            <input 
                                type="text" 
                                name="kodeBahan" 
                                id="kodeBahan" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Masukkan kode bahan" 
                                required>
                            @error('kodeBahan')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Nama Bahan -->
                        <div>
                            <label for="namaBahan" class="block text-sm font-medium text-gray-700">Nama Bahan</label>
                            <input 
                                type="text" 
                                name="namaBahan" 
                                id="namaBahan" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Masukkan nama bahan" 
                                required>
                            @error('namaBahan')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Satuan -->
                        <div>
                            <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan</label>
                            <input 
                                type="text" 
                                name="satuan" 
                                id="satuan" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Masukkan satuan" 
                                required>
                            @error('satuan')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Jenis TPB (Dropdown) -->
                        <div>
                            <label for="jenis_tpb" class="block text-sm font-medium text-gray-700">Jenis TPB</label>
                            <select name="jenis_tpb" id="jenis_tpb" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-400">
                                <!-- Placeholder yang konsisten dengan input lainnya -->
                                <option value="" disabled selected class="text-gray-400">Pilih Jenis TPB</option>
                                <option value="KB" class="text-gray-700">KB</option>
                                <option value="GB" class="text-gray-700">GB</option>
                            </select>
                            @error('jenis_tpb')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        

                        <!-- Stok Bahan -->
                        <div>
                            <label for="stokBahan" class="block text-sm font-medium text-gray-700">Stok Bahan</label>
                            <input 
                                type="number" 
                                name="stokBahan" 
                                id="stokBahan" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Masukkan stok bahan" 
                                required>
                            @error('stokBahan')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Stok Minimum -->
                        <div>
                            <label for="stok_minimum" class="block text-sm font-medium text-gray-700">Stok Minimum</label>
                            <input 
                                type="number" 
                                name="stok_minimum" 
                                id="stok_minimum" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Masukkan stok minimum" 
                                required>
                            @error('stok_minimum')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('gudang.bahanbaku.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 focus:outline-none focus:border-blue-600 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
