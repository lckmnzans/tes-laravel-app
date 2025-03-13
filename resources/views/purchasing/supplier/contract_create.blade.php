<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Kontrak Supplier
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('purchasing.supplier.contract_index.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Pilih Supplier -->
                <div class="mb-4">
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700">Pilih Supplier</label>
                    <select id="supplier_id" name="supplier_id" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="" disabled selected>Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- List Bahan Baku Dinamis -->
                <div id="bahan-baku-list">
                    <div class="bahan-baku-item mb-4 border-b pb-4">
                        <label class="block text-sm font-medium text-gray-700">Bahan Baku</label>
                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <select name="bahan_baku[0][id]" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                    <option value="" disabled selected>Pilih Bahan Baku</option>
                                    @foreach ($bahanBakus as $bahanBaku)
                                        <option value="{{ $bahanBaku->id }}">{{ $bahanBaku->namaBahan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input type="number" name="bahan_baku[0][harga_per_unit]" placeholder="Harga per Unit" required step="0.01" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <input type="number" name="bahan_baku[0][cif]" placeholder="CIF" required step="0.01" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <input type="number" name="bahan_baku[0][min_order]" placeholder="Minimal Order" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Tambah Bahan Baku -->
                <button type="button" id="add-bahan-baku" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Tambah Bahan Baku
                </button>

                <div class="grid grid-cols-3 gap-4">
                    <!-- Metode Pembayaran -->
                    <div>
                        <label for="method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <select id="method" name="method" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="" disabled selected>Pilih Metode</option>
                            <option value="cash">Cash</option>
                            <option value="credit">Credit</option>
                            <option value="installment">Transfer Bank</option>
                        </select>
                    </div>
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                        <select id="currency" name="currency" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="" disabled selected>Pilih Currency</option>
                            <option value="IDR">IDR - Indonesian Rupiah</option>
                            <option value="USD">USD - US Dollar</option>
                            <!-- Tambahkan opsi lainnya sesuai kebutuhan -->
                        </select>
                    </div>
                    <!-- Dokumen -->
                    <div>
                        <label for="dokument" class="block text-sm font-medium text-gray-700">Unggah Dokumen</label>
                        <input type="file" id="dokument" name="dokument" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Tanggal Mulai -->
                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="mb-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" id="end_date" name="end_date" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="mt-4">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let bahanBakuIndex = 1;

            // Tambah Bahan Baku
            document.getElementById('add-bahan-baku').addEventListener('click', function () {
                const list = document.getElementById('bahan-baku-list');

                // Template baris baru Bahan Baku
                const newBahanBaku = document.createElement('div');
                newBahanBaku.classList.add('bahan-baku-item', 'mb-4', 'border-b', 'pb-4');
                newBahanBaku.innerHTML = `
                    <label class="block text-sm font-medium text-gray-700">Bahan Baku</label>
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <select name="bahan_baku[${bahanBakuIndex}][id]" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="" disabled selected>Pilih Bahan Baku</option>
                                @foreach ($bahanBakus as $bahanBaku)
                                    <option value="{{ $bahanBaku->id }}">{{ $bahanBaku->namaBahan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="number" name="bahan_baku[${bahanBakuIndex}][harga_per_unit]" placeholder="Harga per Unit" required step="0.01" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <input type="number" name="bahan_baku[${bahanBakuIndex}][cif]" placeholder="CIF" required step="0.01" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <input type="number" name="bahan_baku[${bahanBakuIndex}][min_order]" placeholder="Minimal Order" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>
                    <button type="button" class="text-red-500 font-bold py-1 px-3 rounded mt-2" onclick="removeBahanBaku(this)">
                        Tutup
                    </button>
                `;

                // Tambahkan elemen ke list
                list.appendChild(newBahanBaku);
                bahanBakuIndex++;
            });
        });

        // Fungsi untuk menghapus bahan baku
        function removeBahanBaku(button) {
            button.closest('.bahan-baku-item').remove();
        }
    </script>
</x-app-layout>
