<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl font-semibold text-gray-800">Data Produk</h3>
            <div class="flex space-x-4">
                <a href="{{ route('gudang.product.create') }}" 
                   class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium shadow hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">
                    + Tambah Produk
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6">

        <!-- Product Table -->
        <div class="overflow-hidden border-0 p-0 mt-1.5">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm text-gray-700">
                    <thead class="bg-gray-100 text-gray-800 uppercase">
                        <tr>
                            <th class="border px-4 py-3 text-center font-medium align-middle">No</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Produk</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Jenis Produk</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Seri Produk</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Model PCB</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Part Number</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Spesifikasi</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Bahan Baku</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Harga</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $key => $product)
                            <tr class="hover:bg-gray-50 transition duration-300">
                                <td class="border px-4 py-3 text-center">{{ $key + 1 }}</td>
                                <td class="border px-4 py-3 text-center">{{ $product->produk }}</td>
                                <td class="border px-4 py-3 text-center">{{ $product->jenis_produk }}</td>
                                <td class="border px-4 py-3 text-center">{{ $product->seri_produk }}</td>
                                <td class="border px-4 py-3 text-center">{{ $product->model_pcb }}</td>
                                <td class="border px-4 py-3 text-center">{{ $product->part_number }}</td>
                                <td class="border px-4 py-3 text-center">{{ $product->spesifikasi ?? 'Tidak ada spesifikasi' }}</td>
                                <td class="border px-4 py-3 text-center">
                                    <ul>
                                        @foreach($product->bahanBaku as $bahan)
                                            <li>{{ $bahan->namaBahan }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="border px-4 py-3 text-center">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                <td class="border px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('gudang.product.edit', $product->id) }}" class="px-3 py-1 text-xs text-blue-800 bg-blue-50 border border-blue-500 rounded-md hover:bg-blue-300 transition duration-200">
                                            Edit
                                        </a>
                                        <!-- Delete Button -->
                                        <form action="{{ route('gudang.product.index.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-xs text-red-700 bg-red-50 border border-red-500 rounded-md hover:bg-red-300 hover:border-red-500 transition duration-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Message if No Products Exist -->
        @if($products->isEmpty())
            <p class="mt-6 text-center text-gray-500 text-lg">
                Tidak ada data produk yang tersedia.
            </p>
        @endif
        <div class="mt-4">
            {{ $products->links() }} <!-- Pagination -->
        </div>
    </div>
</x-app-layout>
