<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Permintaan Pesanan') }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <form action="{{ route('exim.deliveryrequest.index.store') }}" method="POST">
                @csrf
                <!-- Pilih Pelanggan -->
                <div class="mb-4">
                    <label for="pelanggan_id" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                    <select name="pelanggan_id" id="pelanggan_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 select2" 
                            style="width: 100%;" required>
                        <option value="" disabled selected>Pilih Pelanggan</option>
                        @foreach($pelanggans as $pelanggan)
                            <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama_customer }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Produk Section -->
                <div id="products-section">
                    <div class="product-row mb-4 border-b pb-4">
                        <label for="product_id" class="block text-sm font-medium text-gray-700">Produk</label>
                        <select name="products[0][product_id]" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 select2" 
                                style="width: 100%;" required>
                            <option value="" disabled selected>Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->produk }}</option>
                            @endforeach
                        </select>

                        <label for="quantity" class="block text-sm font-medium text-gray-700 mt-2">Jumlah</label>
                        <input type="number" name="products[0][quantity]" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            required min="1" />
                    </div>
                </div>

                <!-- Tombol Tambah Produk -->
                <button type="button" id="add-product" 
                        class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">
                    <i class="fa fa-plus mr-2"></i>Tambah Produk
                </button>

                <!-- Tombol Simpan -->
                <button type="submit" 
                        class="inline-flex items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                    <i class="fa fa-save mr-2"></i>Simpan Permintaan
                </button>
            </form>
        </div>
    </div>

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi select2 pada elemen select yang memiliki class "select2"
            $('.select2').select2({
                placeholder: "Pilih",
                allowClear: true
            });
        });
    
        document.getElementById('add-product').addEventListener('click', function () {
            const productsSection = document.getElementById('products-section');
            const productRows = document.querySelectorAll('.product-row');
            const index = productRows.length; // Menentukan index berikutnya
    
            // Template untuk produk baru
            const newRow = document.createElement('div');
            newRow.classList.add('product-row', 'mb-4', 'border-b', 'pb-4');
            newRow.innerHTML = `
                <label class="block text-sm font-medium text-gray-700">Produk</label>
                <select name="products[${index}][product_id]" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 select2" 
                        required>
                    <option value="" disabled selected>Pilih</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->produk }}</option>
                    @endforeach
                </select>
    
                <label for="quantity" class="block text-sm font-medium text-gray-700 mt-2">Jumlah</label>
                <input type="number" name="products[${index}][quantity]" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                       required min="1" />
    
                <!-- Tombol Hapus Produk -->
                <button type="button" class="remove-product mt-2 text-red-600 hover:text-red-800">
                    <i class="fa fa-trash mr-2"></i>Hapus Produk
                </button>
            `;
    
            // Menambahkan event listener untuk tombol hapus
            newRow.querySelector('.remove-product').addEventListener('click', function () {
                newRow.remove(); // Menghapus row produk
            });
    
            productsSection.appendChild(newRow); // Menambahkan row ke dalam section
    
            // Inisialisasi select2 pada produk baru yang ditambahkan
            $(newRow.querySelector('.select2')).select2({
                placeholder: "Pilih",
                allowClear: true
            });
        });
    </script>
</x-app-layout>
