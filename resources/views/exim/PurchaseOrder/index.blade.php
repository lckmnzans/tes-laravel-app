<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <!-- Table Responsive -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm text-gray-700">
                <thead class="bg-gray-100 text-gray-800 uppercase">
                    <tr>
                        <th class="border px-4 py-3 text-center font-medium">No</th>
                        <th class="border px-4 py-3 text-center font-medium">Customer</th>
                        <th class="border px-4 py-3 text-center font-medium">Tanggal</th>
                        <th class="border px-4 py-3 text-center font-medium">Produk</th>
                        <th class="border px-4 py-3 text-center font-medium">Bahan Baku</th>
                        <th class="border px-4 py-3 text-center font-medium">Jumlah</th>
                        <th class="border px-4 py-3 text-center font-medium">Total Harga</th>
                        <th class="border px-4 py-3 text-center font-medium">Status</th>
                        <th class="border px-4 py-3 text-center font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $purchaseOrder)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border px-4 py-3 text-center">{{ $purchaseOrder->kode_po }}</td>
                            <td class="border px-4 py-2 text-center">
                                {{ optional($purchaseOrder->deliveryRequest->pelanggan)->nama_customer ?? 'Pelanggan Tidak Ditemukan' }}
                            </td>
                            <td class="border px-4 py-2 text-xs text-center">
                                {{ $purchaseOrder->created_at->format('d-m-Y') }}
                            </td>
                            <td class="border px-4 py-3 text-center">
                                @if($purchaseOrder->deliveryRequest && $purchaseOrder->deliveryRequest->product)
                                    @foreach($purchaseOrder->deliveryRequest->product as $product)
                                        {{ $product->produk ?? 'Produk Tidak Ditemukan' }}<br>
                                    @endforeach
                                @else
                                    <span class="text-gray-500">Tidak ada produk</span>
                                @endif
                            </td>
                            <td class="border px-4 py-3 text-center">
                                @if($purchaseOrder->deliveryRequest && $purchaseOrder->deliveryRequest->product)
                                    @foreach($purchaseOrder->deliveryRequest->product as $product)
                                        @foreach($product->bahanBaku as $bahan)
                                            {{ $bahan->namaBahan ?? 'Bahan Tidak Ditemukan' }}<br>
                                        @endforeach
                                    @endforeach
                                @else
                                    <span class="text-gray-500">Tidak ada bahan baku</span>
                                @endif
                            </td>
                            <td class="border px-4 py-3 text-center">
                                @if($purchaseOrder->deliveryRequest && $purchaseOrder->deliveryRequest->product)
                                    @foreach($purchaseOrder->deliveryRequest->product as $product)
                                        {{ $product->pivot->quantity ?? '0' }}<br>
                                    @endforeach
                                @else
                                    <span class="text-gray-500">0</span>
                                @endif
                            </td>
                            <td class="border px-4 py-3 text-center">
                                {{ number_format($purchaseOrder->total_amount, 2, ',', '.') }}
                            </td>
                            <td class="border px-4 py-3 text-center table-status">
                                @if($purchaseOrder->status === 'tertunda')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-md">Belum Dijadwalkan</span>
                                @elseif($purchaseOrder->status === 'sudah dibuat')
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-md">Sudah Dijadwalkan</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-md">{{ ucfirst($purchaseOrder->status) }}</span>
                                @endif
                            </td>
                            
                            <td class="border px-4 py-3 text-center">
                                <a href="{{ route('exim.deliveryrequest.show', $purchaseOrder->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md shadow">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-gray-500">
                                Tidak ada Purchase Orders yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $purchaseOrders->links() }}
        </div>
    </div>
    <style>
        /* Menambahkan nowrap untuk kolom status */
    .table-status {
        white-space: nowrap;
    }
</style>
</x-app-layout>
