<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Penerimaan Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="py-1">    
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Tabel -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-md">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-sm font-semibold text-gray-700">No</th>
                                    <th class="border px-4 py-2 text-sm font-semibold text-gray-700">Kode POBB</th>
                                    <th class="border px-4 py-2 text-sm font-semibold text-gray-700">No Surat Jalan</th>
                                    <th class="border px-4 py-2 text-sm font-semibold text-gray-700">Bahan Baku</th>
                                    <th class="border px-4 py-2 text-sm font-semibold text-gray-700">Jumlah Order</th>
                                    <th class="border px-4 py-2 text-sm font-semibold text-gray-700">Status</th>
                                    <th class="border px-4 py-2 text-center text-sm font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penerimaanbbs as $key => $penerimaan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-4 py-2">{{ $key + 1 }}</td>
                                        <td class="border px-4 py-2">{{ $penerimaan->purchaseOrderBB->kode ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">{{ $penerimaan->purchaseOrderBB->surat_jalan ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">{{ $penerimaan->bahanBaku->namaBahan ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">{{ $penerimaan->jumlah_order }}</td>
                                        <td class="border px-4 py-2">
                                            @if($penerimaan->status === '1')
                                                <span class="text-yellow-500 font-semibold">Dikirim</span>
                                            @elseif($penerimaan->status === '2')
                                                <span class="text-green-500 font-semibold">Diterima</span>
                                            @else
                                                <span class="text-gray-500 font-semibold">Selesai</span>
                                            @endif
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            @if($penerimaan->status === '1')
                                                <button onclick="openModal('verifikasiModal-{{ $penerimaan->id }}')" 
                                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">
                                                    Verifikasi
                                                </button>
                                            @elseif($penerimaan->status === '2')
                                                <a href="{{ route('gudang.penerimaan.detail', $penerimaan->id) }}"
                                                   class=" text-green-600 px-3 py-1 rounded hover:text-green-800 transition">
                                                    Lihat Detail
                                                </a>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-500 py-4">Data tidak tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal Verifikasi -->
                    @foreach ($penerimaanbbs as $penerimaan)
                        <div class="fixed z-50 inset-0 hidden bg-gray-800 bg-opacity-75" id="verifikasiModal-{{ $penerimaan->id }}">
                            <div class="flex items-center justify-center min-h-screen">
                                <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                                    <div class="flex justify-between items-center p-4 border-b">
                                        <h3 class="text-lg font-semibold">Verifikasi Penerimaan Bahan Baku</h3>
                                        <button onclick="closeModal('verifikasiModal-{{ $penerimaan->id }}')" class="text-gray-600">&times;</button>
                                    </div>
                                    <form action="{{ route('gudang.penerimaan.storeVerification', $penerimaan->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="p-4 space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Tanggal Terima</label>
                                                <input type="date" name="tanggal_terima" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Lokasi Stok</label>
                                                <select name="lokasi_stok" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                    <option value="KB">KB </option>
                                                    <option value="GB">GB </option>
                                                </select>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Jumlah Terima</label>
                                                    <input type="number" name="jumlah_terima" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Jumlah Tidak Layak</label>
                                                    <input type="number" name="reject" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Status Barang</label>
                                                <select name="status_barang" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                    <option value="Diterima Sebagian">Diterima Sebagian</option>
                                                    <option value="Diterima Lengkap">Diterima Lengkap</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                                                <textarea name="catatan" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Unggah Bukti</label>
                                                <input type="file" name="bukti" accept="image/*,application/pdf" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                            </div>
                                        </div>
                                        <div class="flex justify-end px-4 py-3 bg-gray-50 border-t">
                                            <button type="button" onclick="closeModal('verifikasiModal-{{ $penerimaan->id }}')" 
                                                class="bg-gray-500 text-white px-3 py-1 rounded mr-2 hover:bg-gray-600">Batal</button>
                                            <button type="submit" 
                                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Simpan</button>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Modal -->
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
</x-app-layout>
