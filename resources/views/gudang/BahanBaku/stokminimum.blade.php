<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Stok Minimum') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Table Responsive -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm text-gray-700">
                            <thead class="bg-gray-100 text-gray-800 uppercase">
                                <tr>
                                    <th class="border px-4 py-3 text-center font-medium">No</th>
                                    <th class="border px-4 py-3 text-center font-medium">Kode Bahan</th>
                                    <th class="border px-4 py-3 text-center font-medium">Nama Bahan</th>
                                    <th class="border px-4 py-3 text-center font-medium">Stok Bahan</th>
                                    <th class="border px-4 py-3 text-center font-medium">Safety Stock</th>
                                    <!--<th class="border px-4 py-3 text-center font-medium">Harga Bahan</th>-->
                                    <th class="border px-4 py-3 text-center font-medium">Status</th>
                                    <th class="border px-4 py-3 text-center font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockMaterials as $key => $material)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="border px-4 py-3 text-center">{{ $key + 1 }}</td>
                                    <td class="border px-4 py-3 text-center">{{ $material->kodeBahan }}</td>
                                    <td class="border px-4 py-3 text-center">{{ $material->namaBahan }}</td>
                                    <td class="border px-4 py-3 text-center" style="color: red;">{{ $material->stokBahan }}</td>
                                    <td class="border px-4 py-3 text-center">{{ $material->stok_minimum }}</td>
                                    <!--<td class="border px-4 py-3 text-center">Rp {{ number_format($material->hargaBahan, 0, ',', '.') }}</td>-->
                                    <td class="border px-4 py-3 text-center">
                                        @if($material->status_pr == 'Sudah Diajukan')
                                            <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded">Sudah Diajukan</span>
                                        @elseif($material->status_pr == 'Belum Diajukan')
                                            <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded">Belum Diajukan</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-3 text-center">
                                        @if($material->status_pr == 'Belum Diajukan')
                                            <form action="{{ route('gudang.pr.createpr') }}" method="GET">
                                                @csrf
                                                <input type="hidden" name="material_id[]" value="{{ $material->id }}">
                                                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-500 rounded-md hover:bg-blue-600">
                                                    Permintaan Bahan Baku
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">Tidak Ada Aksi</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- Pesan jika Tidak Ada Data -->
                    @if($lowStockMaterials->isEmpty())
                        <p class="mt-6 text-center text-gray-500 text-lg">
                            Purchase Request Sudah Diajukan.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
