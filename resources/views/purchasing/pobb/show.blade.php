<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesan Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6 bg-white border-b border-gray-200">

            <!-- Informasi POBB -->
            <div class="mb-8">
                <!-- Tabel Informasi Umum -->
                <table class="min-w-full table-auto border-collapse border border-gray-300 rounded-lg">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2 text-left text-sm font-semibold text-gray-700">Kode Pesan Bahan Baku</th>
                            <th class="border px-4 py-2 text-left text-sm font-semibold text-gray-700">{{ $pobb->kode }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2 font-semibold text-gray-700">Supplier</td>
                            <td class="border px-4 py-2">{{ $pobb->supplier->nama_perusahaan ?? 'Tidak Ada Supplier' }}</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 font-semibold text-gray-700">Tanggal PO</td>
                            <td class="border px-4 py-2">{{ $pobb->tanggal_po }}</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 font-semibold text-gray-700">Status</td>
                            <td class="border px-4 py-2">
                                <span class="px-3 py-1 rounded-full text-white text-sm 
                                    {{ $pobb->status_order == 1 ? 'bg-yellow-500' : ($pobb->status_order == 2 ? 'bg-blue-500' : 'bg-green-500') }}">
                                    {{ $pobb->status_order == 1 ? 'Pending' : ($pobb->status_order == 2 ? 'Dikirim' : 'Selesai') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 font-semibold text-gray-700">Total Amount</td>
                            <td class="border px-4 py-2">
                                {{ $pobb->total_amount ? 'Rp ' . number_format($pobb->total_amount, 0, ',', '.') : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 font-semibold text-gray-700">Tanggal Pengiriman</td>
                            <td class="border px-4 py-2">{{ $pobb->tanggal_pengiriman ?? 'Belum Diatur' }}</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 font-semibold text-gray-700">No Surat Jalan</td>
                            <td class="border px-4 py-2">{{ $pobb->surat_jalan ?? 'Belum Diisi' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            

            <!-- Daftar Items -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-700 border-b-2 border-gray-200 pb-2 mb-4">
                    Daftar Items
                </h2>
                <table class="table-auto w-full border-collapse border border-gray-300 rounded-lg">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Nama Bahan</th>
                            <th class="border border-gray-300 px-4 py-2">Kode HS</th>
                            <th class="border border-gray-300 px-4 py-2">Jenis Kemasan</th>
                            <th class="border border-gray-300 px-4 py-2">Jumlah Kemasan</th>
                            <th class="border border-gray-300 px-4 py-2">Satuan</th>
                            <th class="border border-gray-300 px-4 py-2">Deskripsi</th>
                            <th class="border border-gray-300 px-4 py-2">Harga per Unit</th>
                            <th class="border border-gray-300 px-4 py-2">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pobb->items as $item)
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-4 py-2">{{ $item->purchaseRequest->bahanBaku->namaBahan ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $item->kode_hs ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $item->jenis_kemasan ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $item->jumlah_kemasan ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $item->satuan ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $item->deskripsi ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">Rp {{ number_format($item->harga_per_unit, 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $pobb->total_amount ? 'Rp ' . number_format($pobb->total_amount, 0, ',', '.') : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Dokumen Invoice, SJM, and No Pembayaran -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-700 border-b-2 border-gray-200 pb-2 mb-4">
                    Dokumen & Pembayaran
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Dokumen Invoice -->
                    <div class="space-y-2">
                        <p class="font-semibold">Dokumen Invoice</p>
                        @if ($pobb->dokumen_invoice)
                            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="openModal('invoice')">Lihat Dokumen</button>
                        @else
                            <p class="text-gray-500">Dokumen belum diupload.</p>
                        @endif
                    </div>
                    <!-- Dokumen SJM -->
                    <div class="space-y-2">
                        <p class="font-semibold">Bukti Pembayaran</p>
                        @if ($pobb->dokument_sjm)
                            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="openModal('sjm')">Lihat Dokumen</button>
                        @else
                            <p class="text-gray-500">Dokumen belum diupload.</p>
                        @endif
                    </div>
                    <!-- No Pembayaran -->
                    <div class="space-y-2">
                        <p class="font-semibold">Dokumen Pengiriman</p>
                        @if ($pobb->no_pembayaran)
                            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="openModal('pembayaran')">Lihat Dokumen</button>
                        @else
                            <p class="text-gray-500">Dokumen belum diupload.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tombol Kembali
            <div class="flex justify-end">
                <a href="{{ route('purchasing.pobb.index') }}" 
                   class="bg-indigo-600 text-white px-6 py-2 rounded-lg shadow hover:bg-indigo-700 transition duration-200">
                    Kembali
                </a>
            </div>-->
        </div>
    </div>

    <!-- Modal for Viewing Documents -->
<div id="documentModal" class="fixed z-10 inset-0 hidden overflow-y-auto bg-black bg-opacity-50" style="top: 50px;">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle"></h3>
                <div id="modalContent" class="mt-4">
                    <!-- Dynamic Content will be loaded here -->
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm mr-2" onclick="closeModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>


    <script>
        function openModal(docType) {
            const modal = document.getElementById('documentModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');

            let docPath = '';
            let docTitle = '';

            if (docType === 'invoice') {
                docPath = "{{ asset('storage/' . $pobb->dokumen_invoice) }}";
                docTitle = "Dokumen Invoice";
            } else if (docType === 'sjm') {
                docPath = "{{ asset('storage/' . $pobb->dokument_sjm) }}";
                docTitle = "Bukti Pembayaran";
            } else if (docType === 'pembayaran') {
                docPath = "{{ asset('storage/' . $pobb->no_pembayaran) }}";
                docTitle = "Dokumen Pengiriman";
            }

            modalTitle.textContent = docTitle;

            if (docPath.endsWith('.pdf')) {
                modalContent.innerHTML = `<iframe src="${docPath}" class="w-full h-96 border border-gray-300 rounded-lg shadow"></iframe>`;
            } else {
                modalContent.innerHTML = `<img src="${docPath}" alt="${docTitle}" class="w-full h-auto border border-gray-300 rounded-lg shadow">`;
            }

            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('documentModal');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>
