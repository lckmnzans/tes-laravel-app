<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-semibold text-gray-800">Detail Kontrak Supplier</h3>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-2 lg:px-8 py-1 space-y-6" x-data="{ isModalOpen: false }">
        <!-- Detail Supplier -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h4 class="text-xl font-bold text-gray-800 mb-4">{{ $supplier->nama_perusahaan }}</h4>
            <p class="text-gray-600"><strong>Alamat:</strong> {{ $supplier->alamat }}</p>
            <p class="text-gray-600"><strong>Telepon:</strong> {{ $supplier->no_tlp }}</p>
        
            <!-- Tombol Lihat Detail -->
            <button
                class="mt-4 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-2 px-4 rounded-lg"
                @click="isModalOpen = true">
                Lihat Detail
            </button>
        </div>

        <!-- Modal Pop-Up -->
        <div
            x-show="isModalOpen"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50"
            x-cloak>
            <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                <!-- Header Modal -->
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-gray-800">Detail Supplier</h4>
                    <button @click="isModalOpen = false" class="text-gray-500 hover:text-gray-700">
                        ✖
                    </button>
                </div>

                <!-- Konten Modal -->
                <div class="space-y-3">
                    <p class="text-gray-600"><strong>Nama Perusahaan:</strong> {{ $supplier->nama_perusahaan }}</p>
                    <p class="text-gray-600"><strong>Alamat:</strong> {{ $supplier->alamat }}</p>
                    <p class="text-gray-600"><strong>Telepon:</strong> {{ $supplier->no_tlp }}</p>
                    <p class="text-gray-600"><strong>Email:</strong> {{ $supplier->email ?? 'Tidak tersedia' }}</p>
                    <p class="text-gray-600"><strong>Website:</strong> {{ $supplier->website ?? 'Tidak tersedia' }}</p>
                </div>

                <!-- Footer Modal -->
                <div class="mt-6 text-right">
                    <button
                        @click="isModalOpen = false"
                        class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded-lg">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Daftar Kontrak -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h4 class="text-xl font-bold text-gray-800 mb-4">Daftar Kontrak</h4>
            @forelse ($supplier->contracts as $contract)
                <div class="border-b border-gray-200 py-4">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-800">
                            <span class="
                                inline-block px-3 py-1 rounded-full text-sm font-semibold text-white
                                @if ($contract->status == '1') bg-green-500
                                @elseif ($contract->status == '2') bg-yellow-500
                                @else bg-red-500 @endif
                            ">
                                @if ($contract->status == '1')
                                    Active
                                @elseif ($contract->status == '2')
                                    Diperpanjang
                                @else
                                    Kadaluarsa
                                @endif
                            </span>
                        </p>
                        <p class="text-gray-600 text-sm">
                            <strong>Tanggal:</strong> 
                            {{ \Carbon\Carbon::parse($contract->start_date)->translatedFormat('d F Y') }} - 
                            {{ \Carbon\Carbon::parse($contract->end_date)->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    @if ($contract->bahanBakus->isNotEmpty())
                        <table class="w-full table-auto text-sm text-left border-collapse mt-4">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-4 py-2 border">Nama Bahan Baku</th>
                                    <th class="px-4 py-2 border">Kode Bahan</th>
                                    <th class="px-4 py-2 border">Harga per Unit</th>
                                    <th class="px-4 py-2 border">CIF</th>
                                    <th class="px-4 py-2 border">Minimum Order</th>
                                    <th class="px-4 py-2 border">Method</th>
                                    <th class="px-4 py-2 border">Status</th>
                                    <th class="px-4 py-2 border">Due Day</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contract->bahanBakus as $bahan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ $bahan->namaBahan }}</td>
                                        <td class="px-4 py-2">{{ $bahan->kodeBahan }}</td>
                                        <td class="px-4 py-2">{{ number_format($bahan->pivot->harga_per_unit, 2) }}</td>
                                        <td class="px-4 py-2">{{ number_format($bahan->pivot->cif, 2) }}</td>
                                        <td class="px-4 py-2">{{ $bahan->pivot->min_order }}</td>
                                        <td class="px-4 py-2">{{ $contract->method }}</td>
                                        <td class="px-4 py-2">
                                            @if ($contract->status == '1')
                                                Active
                                            @elseif ($contract->status == '2')
                                                Inactive
                                            @else
                                                Pending
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">{{ $contract->due_day }} days</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-gray-500 mt-2">Tidak ada bahan baku terkait kontrak ini.</p>
                    @endif
                </div>
                
            @empty
                <p class="text-center text-gray-500 py-4">Tidak ada kontrak yang tersedia.</p>
            @endforelse
        </div>
        <!-- Daftar Transaksi (Purchase Order) -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h4 class="text-xl font-bold text-gray-800 mb-4">Riwayat Transaksi</h4>
            @if ($purchaseOrderBBs->isEmpty())
                <p class="text-center text-gray-500 py-4">Tidak ada transaksi untuk supplier ini.</p>
            @else
                <table class="w-full table-auto text-sm text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">Nomor PO</th>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Total</th>
                            <th class="px-4 py-2 border">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrderBBs as $po)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $po->kode }}</td>
                                <td class="px-4 py-2">{{ $po->tanggal_po }}</td>
                                <td class="px-4 py-2">{{ number_format($po->total_amount, 2) }}</td>
                                <td class="px-4 py-2">
                                    @if ($po->status_order == 1)
                                        Pending
                                    @elseif ($po->status_order == 2)
                                        Dikirim
                                    @elseif ($po->status_order == 3)
                                        Selesai
                                    @else
                                        Tidak Diketahui
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    </div>
</x-app-layout>
