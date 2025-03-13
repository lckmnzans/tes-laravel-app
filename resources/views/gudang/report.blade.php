<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Inventori') }}
        </h2>
    </x-slot>

    <!-- Filter Laporan -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('gudang.report') }}" class="space-y-6">
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
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="Semua" {{ request('category') == 'Semua' ? 'selected' : '' }}>Semua</option>
                        <option value="penerimaan" {{ request('category') == 'penerimaan' ? 'selected' : '' }}>Penerimaan</option>
                        <option value="pengeluaran" {{ request('category') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
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

    <!-- Tabel Laporan -->
    @if (request('start_date') && request('end_date') && request('category'))
        @if ($penerimaan->isNotEmpty() || $pengeluaran->isNotEmpty())
            <div class="bg-gray-50 shadow rounded-lg p-4 mb-6">
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
                        <span class="text-sm font-semibold text-gray-900">{{ request('category') }}</span>
                    </div>
                </div>
                <div class="flex justify-end mt-4 space-x-4">
                    <a href="{{ route('gudang.report.print', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'category' => request('category')]) }}" target="_blank" class="px-4 py-2 bg-green-500 text-white rounded-md font-semibold hover:bg-green-600">
                        Cetak
                    </a>
                </div>
                <!-- Tabel Penerimaan -->
            @if (request('category') == 'penerimaan' || request('category') == 'Semua')
                <div class="card mb-4">
                    <div class="card-header">Penerimaan Bahan Baku</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bahan Baku</th>
                                    <th>Tanggal Penerimaan</th>
                                    <th>Jumlah</th>
                                    <th>Lokasi Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penerimaan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ optional($item->bahanBaku)->namaBahan ?? 'Data tidak tersedia' }}</td>
                                        <td>{{ $item->tanggal_terima }}</td>
                                        <td>{{ $item->jumlah_terima }}</td>
                                        <td>{{ $item->lokasi_stok ?? 'Lokasi tidak tersedia' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Tabel Pengeluaran -->
            @if ($pengeluaran->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header">Pengeluaran Bahan Baku</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bahan Baku</th>
                                <th>Tanggal Pengeluaran</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengeluaran as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($item->productionSchedule->purchaseOrder->deliveryRequest->product as $product)
                                                @foreach ($product->bahanBaku as $bahan)
                                                    <li>{{ $bahan->namaBahan }}: {{ $bahan->pivot->quantity }}</li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $item->tanggal_pengeluaran }}</td>
                                    <td>{{ $item->productionSchedule->quantity_to_produce }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
@endif

            </div>

            
        @else
            <div class="bg-white p-6 rounded-lg shadow text-gray-500 text-center">
                Tidak ada data yang ditemukan untuk filter yang dipilih.
            </div>
        @endif
    @endif
</x-app-layout>
