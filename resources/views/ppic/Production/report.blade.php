<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Hasil Produksi') }}
        </h2>
    </x-slot>

    <!-- Filter Laporan -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('ppic.production.report') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Mulai Tanggal</label>
                    <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ request('start_date') }}">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ request('end_date') }}">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua</option>
                        <option value="prep materials" {{ request('status') == 'prep materials' ? 'selected' : '' }}>Prep Materials</option>
                        <option value="production" {{ request('status') == 'production' ? 'selected' : '' }}>Production</option>
                        <option value="packaging" {{ request('status') == 'packaging' ? 'selected' : '' }}>Packaging</option>
                        <option value="quality control" {{ request('status') == 'quality control' ? 'selected' : '' }}>Quality Control</option>
                        <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Shipping</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow hover:bg-blue-600">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Informasi Tambahan -->
    @if ($productionSchedules->isNotEmpty())
        <div class="bg-gray-100 shadow rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex justify-between items-center border-b border-gray-300 py-2">
                    <span class="text-sm font-medium text-gray-700">Dari Tanggal:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ request('start_date') }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-300 py-2">
                    <span class="text-sm font-medium text-gray-700">Sampai Tanggal:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ request('end_date') }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-300 py-2">
                    <span class="text-sm font-medium text-gray-700">Kategori:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ request('status') }}</span>
                </div>
            </div>

            <!-- Tombol Cetak dan Print -->
            <div class="flex justify-end mt-4 space-x-4">
                <a href="{{ route('ppic.production.report.print', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'status' => request('status')]) }}" target="_blank" class="px-4 py-2 bg-green-500 text-white rounded-md font-semibold hover:bg-green-600">
                    Cetak
                </a>
                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md font-semibold hover:bg-gray-600">
                    Print
                </button>
            </div>
            <div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal PO
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Model PCB
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Order
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durasi Produksi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status Produksi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Progress (%)
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Catatan Penting
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($productionSchedules as $index => $schedule)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $schedule->purchaseOrder->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $schedule->purchaseOrder->deliveryRequest->pelanggan->nama_customer }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                @foreach ($schedule->purchaseOrder->deliveryRequest->product as $product)
                                    {{ $product->produk }}
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $schedule->quantity_to_produce }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d/m/Y') }} - 
                                {{ \Carbon\Carbon::parse($schedule->expected_finish_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $schedule->proses }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                @switch($schedule->proses)
                                    @case('prep materials')
                                        20%
                                        @break
                                    @case('production')
                                        50%
                                        @break
                                    @case('packaging')
                                        70%
                                        @break
                                    @case('quality control')
                                        80%
                                        @break
                                    @case('shipping')
                                        90%
                                        @break
                                    @case('selesai')
                                        100%
                                    @break
                                    @default
                                        0%
                                @endswitch
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $schedule->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
    @endif
   
</x-app-layout>
