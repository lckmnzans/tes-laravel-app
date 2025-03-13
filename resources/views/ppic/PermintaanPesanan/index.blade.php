<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pesanan untuk Dijadwalkan ') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Table Responsive -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm text-gray-700">
                            <thead class="bg-gray-100 text-gray-800 uppercase">
                                <tr>
                                    <th class="border px-4 py-3 text-center font-medium">Nomor PO</th>
                                    <th class="border px-4 py-3 text-center font-medium">Tanggal PO</th>
                                    <th class="border px-4 py-3 text-center font-medium">Nama Customer</th>
                                    <th class="border px-4 py-3 text-center font-medium">Produk</th>
                                    <th class="border px-4 py-3 text-center font-medium">Jumlah Produk</th>
                                    <th class="border px-4 py-3 text-center font-medium">Harga Total</th>
                                    <th class="border px-4 py-3 text-center font-medium">Status Produksi</th>
                                    <th class="border px-4 py-3 text-center font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchaseOrders as $purchaseOrder)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="border px-4 py-3 text-center font-semibold text-gray-800">{{ $purchaseOrder->kode_po }}</td>
                                        <td class="border px-4 py-3 text-center">{{ $purchaseOrder->created_at->format('d-m-Y') }}</td>
                                        <td class="border px-4 py-3 text-center">
                                            {{ optional($purchaseOrder->deliveryRequest->pelanggan)->nama_customer ?? '-' }}
                                        </td>
                                        <td class="border px-4 py-3 text-left">
                                            <ul>
                                                @if($purchaseOrder->deliveryRequest && $purchaseOrder->deliveryRequest->product)
                                                    @foreach($purchaseOrder->deliveryRequest->product as $product)
                                                        <li>{{ $product->produk }}</li>
                                                    @endforeach
                                                @else
                                                    <li>-</li>
                                                @endif
                                            </ul>
                                        </td>
                                        <td class="border px-4 py-3 text-center">
                                            <ul>
                                                @if($purchaseOrder->deliveryRequest && $purchaseOrder->deliveryRequest->product)
                                                    @foreach($purchaseOrder->deliveryRequest->product as $product)
                                                        <li>{{ $product->pivot->quantity }}</li>
                                                    @endforeach
                                                @else
                                                    <li>-</li>
                                                @endif
                                            </ul>
                                        </td>
                                        <td class="border px-4 py-3 text-center font-semibold text-green-600">
                                            {{ $purchaseOrder->total_amount ? 'Rp ' . number_format($purchaseOrder->total_amount, 2, ',', '.') : '-' }}
                                        </td>
                                        <!-- Status Produksi -->
            <td class="border px-4 py-3 text-center">
                <span class="{{ $purchaseOrder->status === 'sudah dibuat' ? 'text-green-500' : 'text-red-500' }}">
                    {{ $purchaseOrder->status === 'sudah dibuat' ? 'Sudah Dijadwalkan' : 'Belum Dijadwalkan' }}
                </span>
            </td>
                                        <!-- Aksi -->
                                        <td class="border px-4 py-3 text-center">
                                            @if ($purchaseOrder->status === 'tertunda')
                                                <a href="{{ route('ppic.permintaanpesanan.create', $purchaseOrder->id) }}" 
                                                   class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                                    Jadwalkan
                                                </a>
                                            @elseif ($purchaseOrder->productionSchedule)
                                                <a href="{{ route('ppic.permintaanpesanan.show', $purchaseOrder->productionSchedule->id) }}" 
                                                   class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                                    Detail
                                                </a>
                                            @else
                                                <span class="text-gray-500">Aksi Tidak Tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada PO yang tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            
                        </table>
                    </div>

                    <!-- Pesan jika Tidak Ada Data -->
                    @if($purchaseOrders->isEmpty())
                        <p class="mt-6 text-center text-gray-500 text-lg">
                            Tidak ada PO yang siap untuk diproses menjadi jadwal produksi.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
