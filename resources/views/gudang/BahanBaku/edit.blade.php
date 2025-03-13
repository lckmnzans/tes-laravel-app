<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Bahan Baku') }}
        </h2>
    </x-slot>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-4">
                    <!-- Form Edit Bahan Baku -->
                    <form action="{{ route('gudang.bahanbaku.update', $bahan_baku->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Input Nama Bahan Baku -->
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Input Kode Bahan -->
                            <div>
                                <label for="kodeBahan" class="block text-sm font-medium text-gray-700 mb-1">Kode Bahan</label>
                                <input type="text" name="kodeBahan" id="kodeBahan"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Kode Bahan"
                                       value="{{ $bahan_baku->kodeBahan }}">
                                @error('kodeBahan')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Input Nama Bahan -->
                            <div>
                                <label for="namaBahan" class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan Baku</label>
                                <input type="text" name="namaBahan" id="namaBahan"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Nama Bahan Baku"
                                       value="{{ $bahan_baku->namaBahan }}">
                                @error('namaBahan')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Input Satuan -->
                            <div>
                                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                <input type="text" name="satuan" id="satuan"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Satuan"
                                       value="{{ $bahan_baku->satuan }}">
                                @error('satuan')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Input Jenis TPB -->
                            <div>
                                <label for="jenis_tpb" class="block text-sm font-medium text-gray-700 mb-1">Pilih Jenis TPB</label>
                                <select name="jenis_tpb" id="jenis_tpb"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <!-- Placeholder text for empty selection -->
                                    <option value="" disabled {{ empty($bahan_baku->jenis_tpb) ? 'selected' : '' }}>Pilih Jenis TPB</option>
                                    <!-- Options for KB and GB -->
                                    <option value="KB" {{ $bahan_baku->jenis_tpb == 'KB' ? 'selected' : '' }}>KB</option>
                                    <option value="GB" {{ $bahan_baku->jenis_tpb == 'GB' ? 'selected' : '' }}>GB</option>
                                </select>
                                @error('jenis_tpb')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            

                            <!-- Input Stok Bahan -->
                            <div>
                                <label for="stokBahan" class="block text-sm font-medium text-gray-700 mb-1">Stok Bahan</label>
                                <input type="number" name="stokBahan" id="stokBahan"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Stok Bahan"
                                       value="{{ $bahan_baku->stokBahan }}">
                                @error('stokBahan')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Input Stok Minimum -->
                            <div>
                                <label for="stok_minimum" class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                                <input type="number" name="stok_minimum" id="stok_minimum"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Stok Minimum"
                                       value="{{ $bahan_baku->stok_minimum }}">
                                @error('stok_minimum')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Update -->
                        <div class="flex justify-end gap-2"> <!-- Menambahkan gap antar tombol -->
                            <!-- Tombol Kembali -->
                            <a href="{{ route('gudang.bahanbaku.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-md transition">
                                Kembali
                            </a>
                            <!-- Tombol Update -->
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-6 py-2 rounded-md shadow-sm transition duration-150 ease-in-out">
                                Update
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
