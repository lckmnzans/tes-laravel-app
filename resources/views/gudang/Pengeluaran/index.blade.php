<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengeluaran Bahan Baku - Divisi Gudang') }}
        </h2>
    </x-slot>

    <div class="py-4">
        
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Tabs Navigasi -->
                    <div class="flex space-x-4 mb-6 border-b pb-2 tab-navigation">
                        <button class="px-4 py-2 text-sm font-medium rounded-md tab-button bg-blue-500 text-white" 
                                onclick="showTab('jadwal', this)">
                            Jadwal Produksi
                        </button>
                        <button class="px-4 py-2 text-sm font-medium rounded-md tab-button bg-gray-200 text-gray-700" 
                                onclick="showTab('riwayat', this)">
                            Riwayat Keluar
                        </button>
                    </div>

                    <!-- Tab Jadwal Produksi -->
                    <div id="jadwal" class="tab-content">
                        <h3 class="text-lg font-semibold mb-4">Daftar Jadwal Produksi (Prep Materials)</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm text-gray-700">
                                <thead class="bg-gray-100 text-gray-800 uppercase">
                                    <tr>
                                        <th class="border px-4 py-3 text-center font-medium">Kode Produksi</th>
                                        <th class="border px-4 py-3 text-center font-medium">Produk</th>
                                        <th class="border px-4 py-3 text-center font-medium">Komponen</th>
                                        <th class="border px-4 py-3 text-center font-medium">Jumlah</th>
                                        <th class="border px-4 py-3 text-center font-medium">Tanggal Mulai</th>
                                        <th class="border px-4 py-3 text-center font-medium">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jadwalProduksi as $jadwal)
                                        <tr>
                                            <td class="border px-4 py-3 text-center">{{ $jadwal->kode }}</td>
                                            <td class="border px-4 py-3 text-center">
                                                <ul>
                                                    @foreach($jadwal->purchaseOrder->deliveryRequest->product as $product)
                                                        <li>{{ $product->produk }} - Qty: {{ $product->pivot->quantity }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="border px-4 py-3 text-center">
                                                <ul>
                                                    @php
                                                        $bahanBakuList = $jadwal->purchaseOrder->deliveryRequest->product->map(function ($product) {
                                                            return $product->bahanBaku->map(function ($bahan) use ($product) {
                                                                $jumlah_dibutuhkan = $bahan->pivot->quantity * $product->pivot->quantity;
                                                                return (object) [
                                                                    'nama' => $bahan->namaBahan,
                                                                    'jumlah_dibutuhkan' => $jumlah_dibutuhkan,
                                                                ];
                                                            });
                                                        })->collapse();
                                                    @endphp
                                            
                                                    @foreach($bahanBakuList as $bahan)
                                                        <li>{{ $bahan->nama }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="border px-4 py-3 text-center">
                                                <ul>
                                                    @foreach($bahanBakuList as $bahan)
                                                        <li>{{ $bahan->jumlah_dibutuhkan }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="border px-4 py-3 text-center">{{ $jadwal->schedule_date }}</td>
                                            <td class="border px-4 py-3 text-center">
                                                @if($jadwal->pengeluaranBB)
                                                    <a href="{{ route('gudang.pengeluaran.cetak-sjm', $jadwal->id) }}" target="_blank"
                                                       class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                                        Cetak BB Keluar
                                                    </a>
                                                @else
                                                    <button 
                                                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600"
                                                        data-modal-target="verifikasiModal-{{ $jadwal->id }}" 
                                                        data-modal-toggle="verifikasiModal-{{ $jadwal->id }}">
                                                        Verifikasi
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Modal untuk Verifikasi -->
                                        <div id="verifikasiModal-{{ $jadwal->id }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                                                <div class="flex justify-between items-center bg-blue-500 text-white px-6 py-4 rounded-t-lg">
                                                    <h3 class="text-lg font-semibold">Verifikasi Pengeluaran Bahan Baku</h3>
                                                    <button class="text-white hover:text-gray-200" data-modal-hide="verifikasiModal-{{ $jadwal->id }}">✕</button>
                                                </div>
                                                <div class="p-6 space-y-4">
                                                    <form action="{{ route('gudang.pengeluaran.create-sjm', $jadwal->id) }}" method="POST">
                                                        @csrf
                                                        <div>
                                                            <label class="block text-gray-700 font-medium mb-2" for="kode_sjm">Kode</label>
                                                            <input type="text" id="kode_sjm" name="kode_sjm" value="{{ 'SJM-' . now()->format('YmdHis') }}" readonly 
                                                                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                        </div>
                                                        <div>
                                                            <label class="block text-gray-700 font-medium mb-2" for="tanggal_pengeluaran">Tanggal Pengeluaran</label>
                                                            <input type="date" id="tanggal_pengeluaran" name="tanggal_pengeluaran" 
                                                                   value="{{ old('tanggal_pengeluaran', now()->format('Y-m-d')) }}" readonly
                                                                   class="w-full px-4 py-2 border rounded-md bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                        </div>
                                                        <div>
                                                            <label class="block text-gray-700 font-medium mb-2" for="keterangan">Lokasi Tujuan</label>
                                                            <textarea id="keterangan" name="keterangan" rows="3" 
                                                                      class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                                                        </div>
                                                        <div class="flex justify-end bg-gray-100 px-6 py-4 rounded-b-lg">
                                                            <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 mr-2"
                                                                    data-modal-hide="verifikasiModal-{{ $jadwal->id }}">
                                                                Batal
                                                            </button>
                                                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                                                Cetak BB Keluar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data jadwal produksi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Riwayat SJM -->
                    <div id="riwayat" class="tab-content hidden">
                        <h3 class="text-lg font-semibold mb-4">Riwayat Bahan Baku Keluar</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm text-gray-700">
                                <thead class="bg-gray-100 text-gray-800 uppercase">
                                    <tr>
                                        <th class="border px-4 py-3 text-center font-medium">Kode</th>
                                        <th class="border px-4 py-3 text-center font-medium">Kode Produksi</th>
                                        <th class="border px-4 py-3 text-center font-medium">Produk</th>
                                        <th class="border px-4 py-3 text-center font-medium">Komponen</th>
                                        <th class="border px-4 py-3 text-center font-medium">Tanggal Pengeluaran</th>
                                        <th class="border px-4 py-3 text-center font-medium">Keterangan</th>
                                        <!--<th class="border px-4 py-3 text-center font-medium">Aksi</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayatSJM as $sjm)
                                        <tr>
                                            <td class="border px-4 py-3 text-center">{{ $sjm->kode_sjm }}</td>
                                            <td class="border px-4 py-3 text-center">{{ $sjm->productionSchedule->kode }}</td>
                                            <td class="border px-4 py-3 text-center">
                                                <ul>
                                                    @foreach($sjm->productionSchedule->purchaseOrder->deliveryRequest->product as $product)
                                                        <li>{{ $product->produk }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="border px-4 py-3 text-center">
                                                <ul>
                                                    @foreach($sjm->productionSchedule->purchaseOrder->deliveryRequest->product as $product)
                                                        @foreach($product->bahanBaku as $bahan)
                                                            <li>{{ $bahan->namaBahan }}: {{ $bahan->pivot->quantity }}</li>
                                                        @endforeach
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="border px-4 py-3 text-center">{{ $sjm->tanggal_pengeluaran }}</td>
                                            <td class="border px-4 py-3 text-center">{{ $sjm->keterangan ?? '-' }}</td>
                                            <!--<td class="border px-4 py-3 text-center">
                                                <a href="{{ route('gudang.pengeluaran.cetak-sjm', $sjm->id) }}" target="_blank"
                                                   class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                                    Cetak Ulang
                                                </a>
                                            </td>-->
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada riwayat.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Tab dan Modal -->
    <script>
        function showTab(tabId, button) {
            // Sembunyikan semua tab
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden'); // Tambahkan class hidden
            });

            // Nonaktifkan semua tombol tab
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });

            // Tampilkan tab yang dipilih
            const selectedTab = document.getElementById(tabId);
            if (selectedTab) {
                selectedTab.classList.remove('hidden'); // Hapus class hidden
            }

            // Aktifkan tombol yang dipilih
            button.classList.add('bg-blue-500', 'text-white');
            button.classList.remove('bg-gray-200', 'text-gray-700');
        }

        // Tampilkan tab pertama secara default
        document.addEventListener('DOMContentLoaded', function () {
            showTab('jadwal', document.querySelector('.tab-navigation button'));
        });
        
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
        }

        document.querySelectorAll('[data-modal-toggle]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-target');
                document.getElementById(modalId).classList.remove('hidden');
            });
        });

        document.querySelectorAll('[data-modal-hide]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-hide');
                document.getElementById(modalId).classList.add('hidden');
            });
        });

        document.addEventListener('DOMContentLoaded', () => showTab('jadwal'));
    </script>
</x-app-layout>
