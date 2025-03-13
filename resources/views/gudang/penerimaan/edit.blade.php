<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Form Edit Bahan Baku -->
                    <form action="{{ route('gudang.bahanbaku.update', $bahan_baku->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Input Nama Bahan Baku -->
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

                        <!-- Input Satuan -->
                        <div>
                            <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                            <input type="number" name="satuan" id="satuan"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Satuan"
                                   value="{{ $bahan_baku->satuan }}">
                            @error('satuan')
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

                        <!-- Input Jenis TPB -->
                        <div>
                            <label for="jenis_tpb" class="block text-sm font-medium text-gray-700 mb-1">Jenis TPB</label>
                            <input type="text" name="jenis_tpb" id="jenis_tpb"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Jenis TPB"
                                   value="{{ $bahan_baku->jenis_tpb }}">
                            @error('jenis_tpb')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tombol Update -->
                        <div class="flex justify-end">
                            <!-- Tombol Kembali -->
                            <a href="{{ route('gudang.bahanbaku.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-md transition">
                                Kembali
                            </a>
                            <button type="submit"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-6 py-2 rounded-md shadow-sm transition duration-150 ease-in-out">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
