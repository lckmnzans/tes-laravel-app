<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Inventori') }}
        </h2>
    </x-slot>
    
    <div class="p-6">
        <!-- LowStokMaterials -->
        <div class="flex flex-wrap items-center justify-between gap-6 mb-1">
            <div class="alert mb-6 p-4 rounded-md"
            :class="{'bg-yellow-200 text-yellow-800': $lowStockMaterials->isNotEmpty(), 'bg-green-200 text-green-800': $lowStockMaterials->isEmpty()}">
                <strong>
                    @if($lowStockMaterials->isNotEmpty())
                        Perhatian:
                    @else
                        Semua aman:
                    @endif
                </strong>
                @if($lowStockMaterials->isNotEmpty())
                    Beberapa bahan baku mendekati stok minimum.
                    <a href="{{ route('gudang.bahanbaku.stokminimum') }}" class="text-red-600 underline">Klik di sini untuk melihat detail.</a>
                @else
                    Tidak ada bahan baku yang mendekati stok minimum. 
                    <span class="text-green-600">Stok dalam kondisi aman.</span>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                <a href="{{ route('gudang.bahanbaku.create') }}" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">
                    <svg width="24" height="24" viewbox="0 0 24 24" fill="none" class="h-5 w-5 mr-2">
                        <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5"></circle>
                        <path opacity="0.5" d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z" stroke="currentColor" stroke-width="1.5"></path>
                        <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                    Tambah Bahan Baku
                </a>
                <!-- Pencarian -->
                <form method="GET" action="{{ route('gudang.bahanbaku.index') }}" class="relative w-full sm:w-1/2">
                    <input type="text" name="searchUser" value="{{ request()->get('searchUser') }}" placeholder="Cari Bahan Baku" 
                        class="form-input py-2 px-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                    <div class="absolute top-1/2 -translate-y-1/2 right-3">
                        <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5"></circle>
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table for Inventory -->
        <div class="overflow-hidden border-0 p-0 mt-0.5">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm text-gray-700">
                    <thead class="bg-gray-100 text-gray-800 uppercase">
                        <tr>
                            <th class="border px-4 py-3 text-center font-medium align-middle">No</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Kode Bahan</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Nama Bahan</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Jenis TPB</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Satuan</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Stok Bahan</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Stok Minimum</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Tanggal Update</th>
                            <th class="border px-4 py-3 text-center font-medium align-middle">Opsi</th> <!-- Tambahkan align-middle dan text-center disini -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bahan_bakus as $key => $bahanBaku)
                        <tr class="hover:bg-gray-50 transition duration-300">
                            <td class="border px-4 py-3 text-center">{{ $key + 1 }}</td>
                            <td class="border px-4 py-3 text-center">{{ $bahanBaku->kodeBahan }}</td>
                            <td class="border px-4 py-3 text-center">{{ $bahanBaku->namaBahan }}</td>
                            <td class="border px-4 py-3 text-center">{{ $bahanBaku->jenis_tpb }}</td>
                            <td class="border px-4 py-3 text-center">{{ $bahanBaku->satuan }}</td>
                            <td class="border px-4 py-3 text-center">{{ $bahanBaku->stokBahan }}</td>
                            <td class="border px-4 py-3 text-center">{{ $bahanBaku->stok_minimum }}</td>
                            <td class="border px-4 py-3 text-center">
                                {{ \Carbon\Carbon::parse($bahanBaku->tanggal_update)->format('Y-m-d') }}
                            </td>
                            <td class="border px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('gudang.bahanbaku.edit', $bahanBaku->id) }}" class="px-3 py-1 text-xs text-blue-800 bg-blue-50 border border-blue-500 rounded-md hover:bg-blue-300 transition duration-200">
                                        Edit
                                    </a>
                                    
                                    <!-- Tombol Delete -->
                                    <form action="{{ route('gudang.bahanbaku.index.destroy', $bahanBaku->id) }}" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin menghapus bahan baku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-xs text-red-700 bg-red-50 border border-red-500 rounded-md hover:bg-red-300 hover:border-red-500 transition duration-200">
                                            Delete
                                        </button>
                                        
                                    </form>
                                </div>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pesan jika Tidak Ada Data -->
        @if($bahan_bakus->isEmpty())
            <p class="mt-6 text-center text-gray-500 text-lg">
                Tidak ada data bahan baku yang tersedia.
            </p>
        @endif
        <div class="mt-4">
            {{ $bahan_bakus->links() }} <!-- Menampilkan tombol pagination -->
        </div>
    </div>
</x-app-layout>
