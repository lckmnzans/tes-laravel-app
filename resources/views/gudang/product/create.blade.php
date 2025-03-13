<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk') }}
        </h2>
    </x-slot>

    <div class="py-1">
                <div class="p-1 text-gray-900">
                    <!-- Notifikasi Error -->
                    @if (session()->has('error'))
                        <div class="mb-4 text-red-500 bg-red-100 p-4 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                    <!-- Form Tambah Produk -->
                    <form action="{{ route('gudang.product.index.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Nama dan Jenis Produk -->
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Nama Produk -->
                            <div>
                                <label for="produk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                                <input 
                                    type="text" 
                                    name="produk" 
                                    id="produk" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="Masukkan nama produk" 
                                    required>
                                @error('produk')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Jenis Produk -->
                            <div>
                                <label for="jenis_produk" class="block text-sm font-medium text-gray-700">Jenis Produk</label>
                                <input 
                                    type="text" 
                                    name="jenis_produk" 
                                    id="jenis_produk" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="Masukkan jenis produk" 
                                    required>
                                @error('jenis_produk')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Seri Produk dan Model PCB -->
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Seri Produk -->
                            <div>
                                <label for="seri_produk" class="block text-sm font-medium text-gray-700">Seri Produk</label>
                                <input 
                                    type="text" 
                                    name="seri_produk" 
                                    id="seri_produk" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="Masukkan seri produk" 
                                    required>
                                @error('seri_produk')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Model PCB -->
                            <div>
                                <label for="model_pcb" class="block text-sm font-medium text-gray-700">Model PCB</label>
                                <input 
                                    type="text" 
                                    name="model_pcb" 
                                    id="model_pcb" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="Masukkan model PCB" 
                                    required>
                                @error('model_pcb')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Part Number dan Harga -->
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Part Number -->
                            <div>
                                <label for="part_number" class="block text-sm font-medium text-gray-700">Part Number</label>
                                <input 
                                    type="text" 
                                    name="part_number" 
                                    id="part_number" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="Masukkan part number" 
                                    required>
                                @error('part_number')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Harga -->
                            <div>
                                <label for="harga" class="block text-sm font-medium text-gray-700">Harga Produk</label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="harga" 
                                    id="harga" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="Masukkan harga produk" 
                                    required>
                                @error('harga')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Spesifikasi -->
                        <div>
                            <label for="spesifikasi" class="block text-sm font-medium text-gray-700">Spesifikasi</label>
                            <textarea 
                                name="spesifikasi" 
                                id="spesifikasi" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="Masukkan spesifikasi produk">{{ old('spesifikasi') }}</textarea>
                            @error('spesifikasi')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Pilih Bahan Baku -->
                        <div>
                            <label for="bahan_baku" class="block text-lg font-semibold text-gray-700 mb-4">Pilih Bahan Baku</label>
                            <div class="overflow-auto bg-gray-50 border border-gray-300 rounded-md">
                                <table class="w-full bg-white border-collapse border border-gray-400 text-sm text-center">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="border border-gray-300 px-4 py-2">#</th>
                                            <th class="border border-gray-300 px-4 py-2">Nama Bahan Baku</th>
                                            <th class="border border-gray-300 px-4 py-2">Jumlah yang Dibutuhkan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bahanBakuList as $bahan_baku)
                                            <tr>
                                                <td class="border border-gray-300 px-4 py-2">
                                                    <input 
                                                        type="checkbox" 
                                                        name="bahan_baku[{{ $bahan_baku->id }}][id]" 
                                                        value="{{ $bahan_baku->id }}" 
                                                        id="bahan_baku_{{ $bahan_baku->id }}" 
                                                        class="rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:ring-opacity-50">
                                                </td>
                                                <td class="border border-gray-300 px-4 py-2">
                                                    <label for="bahan_baku_{{ $bahan_baku->id }}" class="text-gray-700">{{ $bahan_baku->namaBahan }}</label>
                                                </td>
                                                <td class="border border-gray-300 px-4 py-2">
                                                    <input 
                                                        type="number" 
                                                        name="bahan_baku[{{ $bahan_baku->id }}][quantity]" 
                                                        class="block w-full max-w-xs mx-auto rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                                                        placeholder="Qty" 
                                                        min="1" 
                                                        disabled>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        

                        <!-- Tombol Submit -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('gudang.product.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:outline-none focus:border-blue-600 focus:ring focus:ring-blue-300 transition">
                                Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                const quantityInput = this.closest('tr').querySelector('input[type="number"]');
                if (this.checked) {
                    quantityInput.disabled = false;
                    quantityInput.focus(); // Otomatis fokus ke input saat diaktifkan
                } else {
                    quantityInput.disabled = true;
                    quantityInput.value = ''; // Reset nilai jika checkbox tidak dipilih
                }
            });
        });
    </script>
    
    
</x-app-layout>
