<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesan Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-md sm:rounded-lg p-6">
        <form action="{{ route('purchasing.pobb.index.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach ($purchaseRequests as $pr)
                <input type="hidden" name="selected_requests[]" value="{{ $pr->id }}">
            @endforeach


            <!-- Supplier -->
            <div class="mb-6">
                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier:</label>
                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                <input type="text" value="{{ $supplier->nama_perusahaan }}" 
                       class="w-full mt-1 p-2 rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
            </div>

            <!-- Daftar Purchase Requests -->
            <h3 class="text-lg font-semibold mb-3 border-b pb-2">Daftar Permintaan Bahan Baku</h3>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 rounded-md shadow-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">Kode Bahan</th>
                            <th class="border p-2">Nama Bahan</th>
                            <th class="border p-2">Jumlah</th>
                            <th class="border p-2">Harga per Unit</th>
                            <th class="border p-2">CIF</th>
                            <th class="border p-2">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($purchaseRequests as $pr)
                            @php
                                $contract = $pr->bahanBaku->contracts->where('supplier_id', $supplier->id)->first();
                                $hargaPerUnit = $contract ? $contract->pivot->harga_per_unit : 0;
                                $cif = $contract ? $contract->pivot->cif : 0;
                                $totalHarga = $pr->jumlah * $hargaPerUnit;
                            @endphp
                            <tr class="border-t">
                                <td class="border p-2">{{ $pr->bahanBaku->kodeBahan }}</td>
                                <td class="border p-2">{{ $pr->bahanBaku->namaBahan }}</td>
                                <td class="border p-2 text-center">{{ $pr->jumlah }}</td>
                                <td class="border p-2 text-right">{{ number_format($hargaPerUnit, 2) }}</td>
                                <td class="border p-2 text-right">{{ number_format($cif, 2) }}</td>
                                <td class="border p-2 text-right font-semibold">{{ number_format($totalHarga, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Informasi PO -->
            <h3 class="text-lg font-semibold mt-6 mb-3 border-b pb-2">Informasi Pesanan</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="no_aju" class="block text-sm font-medium text-gray-700">Nomor Aju:</label>
                    <input type="text" name="no_aju" id="no_aju" value="{{ $noAju }}" required 
                           class="w-full mt-1 p-2 rounded-md border-gray-300 shadow-sm">
                </div>
                
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700">Mata Uang:</label>
                    <input type="text" name="currency" id="currency" value="{{ $defaultCurrency }}" readonly
                           class="w-full mt-1 p-2 rounded-md border-gray-300 bg-gray-100 shadow-sm">
                </div>
                
                <div>
                    <label for="tanggal_daftar" class="block text-sm font-medium text-gray-700">Tanggal Daftar:</label>
                    <input type="date" name="tanggal_daftar" id="tanggal_daftar" value="{{ $defaultTanggalDaftar }}" required 
                           class="w-full mt-1 p-2 rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <!-- Detail POBB Item -->
            <h3 class="text-lg font-semibold mt-6 mb-3 border-b pb-2">Detail Item</h3>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label for="kode_hs" class="block text-sm font-medium text-gray-700">Kode HS:</label>
                    <input type="text" name="kode_hs" id="kode_hs" placeholder="Masukkan Kode HS" 
                           class="w-full mt-1 p-2 rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan:</label>
                    <input type="text" name="satuan" id="satuan" placeholder="Masukkan satuan (kg, pcs, dll)" 
                           class="w-full mt-1 p-2 rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="jumlah_kemasan" class="block text-sm font-medium text-gray-700">Jumlah Kemasan:</label>
                    <input type="number" name="jumlah_kemasan" id="jumlah_kemasan" min="1" placeholder="Masukkan jumlah kemasan" 
                           class="w-full mt-1 p-2 rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="jenis_kemasan" class="block text-sm font-medium text-gray-700">Jenis Kemasan:</label>
                    <input type="text" name="jenis_kemasan" id="jenis_kemasan" placeholder="Masukkan jenis kemasan" 
                           class="w-full mt-1 p-2 rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <!-- Invoice -->
            <h3 class="text-lg font-semibold mt-6 mb-3 border-b pb-2">Invoice</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="no_invoice" class="block text-sm font-medium text-gray-700">No Invoice:</label>
                    <input type="text" name="no_invoice" id="no_invoice" required 
                           class="w-full mt-1 p-2 rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="invoice_dokumen" class="block text-sm font-medium text-gray-700">Dokumen Invoice:</label>
                    <input type="file" name="invoice_dokumen" id="invoice_dokumen" accept=".pdf,.jpg,.png" 
                           class="w-full mt-1 p-2 rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <!-- Catatan -->
            <div class="mt-4">
                <label for="foot_note" class="block text-sm font-medium text-gray-700">Catatan:</label>
                <textarea name="foot_note" id="foot_note" rows="3" 
                          class="w-full mt-1 p-2 rounded-md border-gray-300 shadow-sm"></textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="mt-6 text-right">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition">
                    Buat POBB
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
