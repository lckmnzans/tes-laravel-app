<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Jadwal Produksi') }}
        </h2>
    </x-slot>

    <div class="py-4">
        
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Section Header -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">Informasi Jadwal Produksi</h3>
                        <p class="text-gray-600">Berikut detail jadwal produksi yang sudah dijadwalkan.</p>
                    </div>

                    <!-- Informasi Produksi -->
                    <table class="table-auto w-full mb-6 border border-gray-300">
                        <tr>
                            <td class="p-2 font-bold border border-gray-300">Kode Produksi</td>
                            <td class="p-2 border border-gray-300">{{ $jadwalProduksi->kode }}</td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold border border-gray-300">Nomor PO</td>
                            <td class="p-2 border border-gray-300">{{ $jadwalProduksi->purchaseOrder->kode_po }}</td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold border border-gray-300">Nama Customer</td>
                            <td class="p-2 border border-gray-300">{{ $jadwalProduksi->purchaseOrder->deliveryRequest->pelanggan->nama_customer }}</td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold border border-gray-300">Produk</td>
                            <td class="p-2 border border-gray-300">
                                <ul>
                                    @foreach($jadwalProduksi->purchaseOrder->deliveryRequest->product as $product)
                                        <li>{{ $product->produk }} ({{ $product->pivot->quantity }})</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold border border-gray-300">Jumlah Produk</td>
                            <td class="p-2 border border-gray-300">{{ $jadwalProduksi->quantity_to_produce }}</td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold border border-gray-300">Deskripsi</td>
                            <td class="p-2 border border-gray-300">{{ $jadwalProduksi->description ?? '-' }}</td>
                        </tr>
                    </table>

                    <!-- Tabel Jadwal Penyelesaian -->
                    <h3 class="text-lg font-semibold mb-4">Target Penyelesaian Proses</h3>
                    <table class="table-auto w-full border border-gray-300 mb-6">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 border border-gray-300">Proses</th>
                                <th class="p-2 border border-gray-300">Target Penyelesaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-2 border border-gray-300">Prep Materials</td>
                                <td class="p-2 border border-gray-300">{{ \Carbon\Carbon::parse($jadwalProduksi->target_prep_materials)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 border border-gray-300">Production</td>
                                <td class="p-2 border border-gray-300">{{ \Carbon\Carbon::parse($jadwalProduksi->target_production)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 border border-gray-300">Packaging</td>
                                <td class="p-2 border border-gray-300">{{ \Carbon\Carbon::parse($jadwalProduksi->target_packaging)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 border border-gray-300">Quality Control</td>
                                <td class="p-2 border border-gray-300">{{ \Carbon\Carbon::parse($jadwalProduksi->target_quality_control)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 border border-gray-300">Shipping</td>
                                <td class="p-2 border border-gray-300">{{ \Carbon\Carbon::parse($jadwalProduksi->target_shipping)->format('d-m-Y') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Button Cetak Jadwal -->
                    <div class="flex justify-end">
                        <a href="{{ route('ppic.permintaanpesanan.cetak', $jadwalProduksi->id) }}" 
                           class="px-6 py-3 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600">
                           Cetak Jadwal Produksi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
