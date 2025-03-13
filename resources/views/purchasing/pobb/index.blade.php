<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pesan Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <!-- Tabel POBB -->
            <table class="min-w-full border border-gray-200 rounded-md overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                        <th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-700">Supplier</th>
                        <th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                        <th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-700">Kode Bahan</th>
                        <th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-700">Nama Bahan</th>
                        <th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-700">Total Amount</th>
                        <th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal Pengiriman</th>
                        <th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($purchaseOrderBBs as $pobb)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $pobb->kode }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $pobb->supplier ? $pobb->supplier->nama_perusahaan : 'Tidak Ada Supplier' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ \Carbon\Carbon::parse($pobb->tanggal_po)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                @foreach ($pobb->items as $item)
                                    {{ optional($item->purchaseRequest->bahanBaku)->kodeBahan ?? '-' }}<br>
                                @endforeach
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                @foreach ($pobb->items as $item)
                                    {{ optional($item->purchaseRequest->bahanBaku)->namaBahan ?? '-' }}<br>
                                @endforeach
                            </td>            
                            <td class="px-4 py-2 text-sm">
                                <span class="px-2 py-1 rounded-md text-white {{ $pobb->status_order == 1 ? 'bg-yellow-500' : ($pobb->status_order == 2 ? 'bg-blue-500' : 'bg-green-500') }}">
                                    {{ $pobb->status_order == 1 ? 'Pending' : ($pobb->status_order == 2 ? 'Dikirim' : 'Selesai') }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                {{ $pobb->total_amount ? number_format($pobb->total_amount, 2) . ' ' . $pobb->currency : 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                @if ($pobb->status_order == '1')
                                    <span class="text-yellow-500 font-semibold">Menunggu Tanggal Pengiriman</span>
                                @elseif ($pobb->status_order == '2')
                                {{ \Carbon\Carbon::parse($pobb->tanggal_pengiriman)->format('d-m-Y') }}
                                @else
                                {{ \Carbon\Carbon::parse($pobb->tanggal_pengiriman)->format('d-m-Y') }}
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 justify-center items-center space-y-2" id="action-{{ $pobb->id }}">
                                @if ($pobb->status_order == '1')
                                    <!-- Cek apakah dokumen pembayaran sudah diinputkan -->
                                    @if ($pobb->dokumen_invoice)
                                        <button class="bg-blue-500 text-black px-3 py-1 rounded-md text-sm w-full" onclick="openModal('{{ $pobb->id }}')">Input Detail Pengiriman</button>
                                    @else
                                        <button class="bg-blue-500 text-black px-3 py-1 rounded-md text-sm cursor-not-allowed opacity-50 w-full" disabled>Input Detail Pengiriman</button>
                                    @endif
                                    <!-- Tombol Input Bukti Bayar (pindah ke atas) -->
                                    <button class="bg-yellow-500 text-white px-3 py-1 rounded-md text-sm w-full" onclick="openUploadModal('{{ $pobb->id }}')">
                                        Input Bukti Bayar
                                    </button>
                                @elseif ($pobb->status_order == '3')
                                    <span class="text-gray-500 font-semibold">Pesanan Selesai</span> <!-- Pesanan selesai -->
                                @elseif ($pobb->status_order == '2' && $pobb->dokumen_invoice)
                                    <a href="{{ route('purchasing.pobb.show', $pobb->id) }}" class="bg-green-500 text-white px-3 py-1 rounded-md text-sm w-full">Lihat Detail</a>
                                @endif
                            </td>
                            
                            
                            
                            <!--<td class="px-4 py-2 text-sm text-gray-800 justify-center items-center space-x-2" id="action-{{ $pobb->id }}">
                                @if ($pobb->status_order == '1')
                                    <button class="bg-blue-500 text-black px-3 py-1 rounded-md text-sm" onclick="openModal('{{ $pobb->id }}')">Input Detail Pengiriman</button>
                                @elseif ($pobb->status_order == '3')
                                    <span class="text-gray-500 font-semibold">Pesanan Selesai</span> 
                                @elseif ($pobb->status_order == '2' && $pobb->dokumen_invoice)
                                    <a href="{{ route('purchasing.pobb.show', $pobb->id) }}" class="bg-green-500 text-white px-3 py-1 rounded-md text-sm">Lihat Detail</a>
                                @endif
                            </td>-->
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $purchaseOrderBBs->links() }}
            </div>
        </div>
    </div>

    <!-- Modal for Input Bukti Bayar -->

    <div id="uploadModal" class="fixed z-10 inset-0 hidden overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg">
                <div class="px-6 py-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Unggah Bukti Pembayaran</h3>
                    <form id="uploadForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="pobbIdForUpload" name="pobb_id">
                        <div class="mt-4">
                            <input type="file" id="dokument_sjm" name="dokument_sjm"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm mr-2" onclick="closeUploadModal()">Batal</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Input Tanggal Pengiriman -->
    <div id="tanggalModal" class="fixed z-10 inset-0 hidden overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg">
                <div class="px-6 py-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Set Tanggal Pengiriman</h3>
                    <form id="tanggalForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="pobbId" name="pobb_id">
                        <div class="mt-4">
                            <label for="tanggal_pengiriman" class="block text-sm font-medium text-gray-700">Tanggal Pengiriman</label>
                            <input type="date" id="tanggal_pengiriman" name="tanggal_pengiriman" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   required>
                        </div>
                        <div class="mt-4">
                            <label for="no_pembayaran" class="block text-sm font-medium text-gray-700">Berkas Pengiriman</label>
                            <input type="file" id="no_pembayaran" name="no_pembayaran" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   required>
                        </div>
                        <div class="mt-4">
                            <label for="surat_jalan" class="block text-sm font-medium text-gray-700">Nomor Surat Jalan</label>
                            <input type="text" id="surat_jalan" name="surat_jalan" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   required>
                        </div>
                        <!--<div class="mt-4">
                            <label for="dokument_sjm" class="block text-sm font-medium text-gray-700">Unggah Dokumen</label>
                            <input type="file" id="dokument_sjm" name="dokument_sjm"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>-->
                        <div class="mt-4 flex justify-end">
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm mr-2" onclick="closeModal()">Batal</button>                
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm">Simpan</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="notification" class="fixed top-4 right-4 z-50 hidden bg-green-500 text-white px-4 py-2 rounded-md shadow-md">
        Tanggal pengiriman berhasil disimpan.
    </div>

    <!-- Script -->
    <script>
        function openModal(pobbId) {
            const modal = document.getElementById('tanggalModal');
            const pobbIdInput = document.getElementById('pobbId');
            const form = document.getElementById('tanggalForm');
            
            modal.classList.remove('hidden');
            pobbIdInput.value = pobbId;
            form.action = `/pobb/${pobbId}/update-date`; // Pastikan URL sudah benar
        }

        function closeModal() {
            const modal = document.getElementById('tanggalModal');
            modal.classList.add('hidden');
        }

        function showNotification(message, duration = 3000) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.remove('hidden');

            setTimeout(() => {
                notification.classList.add('hidden');
            }, duration);
        }

        const form = document.getElementById('tanggalForm');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                if (response.ok) {
                    showNotification('Tanggal pengiriman berhasil disimpan.');
                    closeModal(); 
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    const errorData = await response.json();
                    showNotification('Error: ' + (errorData.message || 'Terjadi kesalahan.'), 5000);
                }
            } catch (error) {
                showNotification('Terjadi kesalahan saat menyimpan tanggal pengiriman.', 5000);
                console.error(error);
            }
        });

        function openUploadModal(pobbId) {
    const modal = document.getElementById('uploadModal');
    const pobbIdInput = document.getElementById('pobbIdForUpload');
    const form = document.getElementById('uploadForm');

    // Tampilkan modal
    modal.classList.remove('hidden');
    pobbIdInput.value = pobbId;
    form.action = `/pobb/${pobbId}/buktibayar`; // Pastikan URL sudah benar
}

function closeUploadModal() {
    const modal = document.getElementById('uploadModal');
    modal.classList.add('hidden');
}

// Event listener untuk submit form dengan AJAX
const uploadForm = document.getElementById('uploadForm');
uploadForm.addEventListener('submit', async (e) => {
    e.preventDefault(); // Mencegah form untuk submit biasa (tanpa reload halaman)
    const formData = new FormData(uploadForm); // Mengambil data dari form

    try {
        // Mengirimkan data form ke server dengan AJAX
        const response = await fetch(uploadForm.action, {
            method: 'POST', // Gunakan metode POST
            body: formData, // Mengirim data form
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // CSRF token
            },
        });

        if (response.ok) {
            // Jika response sukses, tampilkan notifikasi dan tutup modal
            showNotification('Bukti Bayar berhasil diunggah.');
            closeUploadModal();
            setTimeout(() => {
                location.reload(); // Reload halaman setelah 3 detik (untuk melihat perubahan)
            }, 3000);
        } else {
            // Jika terjadi error pada response, tampilkan pesan error
            const errorData = await response.json();
            showNotification('Error: ' + (errorData.message || 'Terjadi kesalahan.'), 5000);
        }
    } catch (error) {
        // Menangani error jika AJAX gagal
        showNotification('Terjadi kesalahan saat mengunggah bukti bayar.', 5000);
        console.error(error); // Log error di console
    }
});

// Fungsi untuk menampilkan notifikasi
function showNotification(message, duration = 3000) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.classList.remove('hidden'); // Tampilkan notifikasi

    setTimeout(() => {
        notification.classList.add('hidden'); // Sembunyikan notifikasi setelah beberapa detik
    }, duration);
}

    </script>
</x-app-layout>
