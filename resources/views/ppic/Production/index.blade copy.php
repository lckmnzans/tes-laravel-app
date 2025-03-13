<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Jadwal Produksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Header -->
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Jadwal Produksi</h1>

                    <!-- Table Responsive -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm text-gray-700">
                            <thead class="bg-gray-100 text-gray-800 uppercase">
                                <tr>
                                    <th class="border px-4 py-3 text-center font-medium">Nomor PO</th>
                                    <th class="border px-4 py-3 text-center font-medium">Nama Customer</th>
                                    <th class="border px-4 py-3 text-center font-medium">Produk</th>
                                    <th class="border px-4 py-3 text-center font-medium">Batch Number</th>
                                    <th class="border px-4 py-3 text-center font-medium">Tanggal Mulai</th>
                                    <th class="border px-4 py-3 text-center font-medium">Tanggal Selesai</th>
                                    <th class="border px-4 py-3 text-center font-medium">Status</th>
                                    <th class="border px-4 py-3 text-center font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productionSchedules as $schedule)
                                    <tr class="hover:bg-gray-50 transition">
                                        <!-- Nomor PO -->
                                        <td class="border px-4 py-3 text-center font-semibold text-gray-800">
                                            {{ $schedule->purchaseOrder->kode_po }}
                                        </td>

                                        <!-- Nama Customer -->
                                        <td class="border px-4 py-3 text-center">
                                            {{ $schedule->purchaseOrder->deliveryRequest->pelanggan->nama_customer }}
                                        </td>

                                        <!-- Produk -->
                                        <td class="border px-4 py-3 text-center">
                                            @foreach($schedule->purchaseOrder->deliveryRequest->product as $product)
                                                <div>{{ $product->produk }} ({{ $product->pivot->quantity }})</div>
                                            @endforeach
                                        </td>

                                        <!-- Batch Number -->
                                        <td class="border px-4 py-3 text-center">{{ $schedule->batch_number }}</td>

                                        <!-- Tanggal Mulai dan Selesai -->
                                        <td class="border px-4 py-3 text-center">{{ $schedule->schedule_date }}</td>
                                        <td class="border px-4 py-3 text-center">{{ $schedule->expected_finish_date }}</td>

                                        <!-- Status -->
                                        <td class="border px-4 py-3 text-center">
                                            <span class="{{ $schedule->proses === 'prep materials' ? 'text-blue-500' : 'text-green-500' }}">
                                                {{ ucfirst($schedule->proses) }}
                                            </span>
                                        </td>

                                        <!-- Aksi -->
                                        <td class="border px-4 py-3 text-center">
                                            <a href="apps-invoice-list.html"
                                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md shadow">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada jadwal produksi yang tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
