<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Detail Penerimaan Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="max-w-7x0.5 mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-8 space-y-6">

            <!-- Grid Layout with Left Column (Information) and Right Column (Image) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Left Column: Information -->
                <div class="space-y-6">
                    <dl class="space-y-4">
                        <!-- Kode Bahan -->
                        <div class="flex items-center space-x-4 py-4 bg-gray-50 rounded-lg shadow-sm">
                            <dt class="text-sm font-medium text-gray-600">Kode Bahan</dt>
                            <dd class="text-lg text-gray-900 font-semibold">{{ $penerimaanbb->bahanBaku->kodeBahan ?? 'N/A' }}</dd>
                        </div>

                        <!-- Nama Bahan -->
                        <div class="flex items-center space-x-4 py-4 bg-gray-50 rounded-lg shadow-sm">
                            <dt class="text-sm font-medium text-gray-600">Nama Bahan</dt>
                            <dd class="text-lg text-gray-900">{{ $penerimaanbb->bahanBaku->namaBahan ?? 'N/A' }}</dd>
                        </div>

                        <!-- Jumlah Order -->
                        <div class="flex items-center space-x-4 py-4 bg-gray-50 rounded-lg shadow-sm">
                            <dt class="text-sm font-medium text-gray-600">Jumlah Order</dt>
                            <dd class="text-lg text-gray-900">{{ $penerimaanbb->jumlah_order }}</dd>
                        </div>

                        <!-- Jumlah Terima -->
                        <div class="flex items-center space-x-4 py-4 bg-gray-50 rounded-lg shadow-sm">
                            <dt class="text-sm font-medium text-gray-600">Jumlah Terima</dt>
                            <dd class="text-lg text-gray-900">{{ $penerimaanbb->jumlah_terima }}</dd>
                        </div>

                        <!-- Lokasi Stok -->
                        <div class="flex items-center space-x-4 py-4 bg-gray-50 rounded-lg shadow-sm">
                            <dt class="text-sm font-medium text-gray-600">Lokasi Stok</dt>
                            <dd class="text-lg text-gray-900">{{ $penerimaanbb->lokasi_stok ?? 'Tidak Ditetapkan' }}</dd>
                        </div>

                        <!-- Supplier -->
                        <div class="flex items-center space-x-4 py-4 bg-gray-50 rounded-lg shadow-sm">
                            <dt class="text-sm font-medium text-gray-600">Supplier</dt>
                            <dd class="text-lg text-gray-900">{{ $pobb->supplier->nama_perusahaan ?? 'N/A' }}</dd>
                        </div>

                        <!-- Status -->
                        <div class="flex items-center space-x-4 py-4 bg-gray-50 rounded-lg shadow-sm">
                            <dt class="text-sm font-medium text-gray-600">Status</dt>
                            <dd class="flex-grow">
                                @if ($penerimaanbb->status === '1')
                                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-lg bg-yellow-100 text-yellow-800">
                                        Dikirim
                                    </span>
                                @elseif ($penerimaanbb->status === '2')
                                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-lg bg-green-100 text-green-800">
                                        Diterima
                                    </span>
                                @else
                                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-lg bg-gray-100 text-gray-800">
                                        Selesai
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <!-- Tanggal Terima -->
                        <div class="flex items-center space-x-4 py-4 bg-gray-50 rounded-lg shadow-sm">
                            <dt class="text-sm font-medium text-gray-600">Tanggal Terima</dt>
                            <dd class="text-lg text-gray-900">{{ $penerimaanbb->tanggal_terima ?? 'Belum Ditetapkan' }}</dd>
                        </div>

                        <!-- Catatan -->
                        <div class="flex items-center space-x-4 py-4 bg-gray-50 rounded-lg shadow-sm">
                            <dt class="text-sm font-medium text-gray-600">Catatan</dt>
                            <dd class="text-lg text-gray-900">{{ $penerimaanbb->catatan ?? 'Tidak Ada Catatan' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Right Column: Bukti Penerimaan -->
                <div class="flex justify-center items-center">
                    @if ($penerimaanbb->bukti)
                        <div class="flex justify-center">
                            <p>Path Gambar: {{ asset('storage/' . $penerimaanbb->bukti) }}</p>
                            <img src="{{ asset('storage/' . $penerimaanbb->bukti) }}" 
                                 alt="Bukti Penerimaan" class="w-full max-w-md rounded-lg shadow-xl object-cover">
                        </div>
                    @endif
                </div>
                
                
            </div>

        </div>
    </div>
</x-app-layout>
