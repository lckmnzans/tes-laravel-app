<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Permintaan Bahan Baku') }}
        </h2>
    </x-slot>
    @if (session()->has('status'))
        <div class="mb-4 text-green-500 bg-green-100 p-4 rounded-md">
            {{ session('status') }}
        </div>
    @endif
            <div class="bg-white shadow-md rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Form Purchase Request -->
                    <form action="{{ route('gudang.bahanbaku.stok_minimum.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="overflow-auto bg-gray-50 border border-gray-300 rounded-md">
                            <table class="w-full bg-white border-collapse border border-gray-200 text-sm text-center">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2">Kode Bahan</th>
                                        <th class="border border-gray-300 px-4 py-2">Nama Bahan</th>
                                        <th class="border border-gray-300 px-4 py-2">Stok Saat Ini</th>
                                        <th class="border border-gray-300 px-4 py-2">Jumlah Permintaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lowStockMaterials as $material)
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2">{{ $material->kodeBahan }}</td>
                                            <td class="border border-gray-300 px-4 py-2">{{ $material->namaBahan }}</td>
                                            <td class="border border-gray-300 px-4 py-2">{{ $material->stokBahan }}</td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input 
                                                    type="hidden" 
                                                    name="bahan_baku_id[]" 
                                                    value="{{ $material->id }}">
                                                <input 
                                                    type="number" 
                                                    name="jumlah[{{ $material->id }}]" 
                                                    placeholder="Jumlah yang dibutuhkan" 
                                                    class="block w-full max-w-xs mx-auto rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                                                    required 
                                                    min="1">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="border border-gray-300 px-4 py-2 text-gray-500">
                                                Semua bahan baku sudah diajukan PR atau stok mencukupi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Tombol Aksi -->
                        @if($lowStockMaterials->isNotEmpty())
                            <div class="flex justify-end space-x-4 mt-6">
                                <a href="{{ route('gudang.bahanbaku.stokminimum') }}" class="px-4 py-2 bg-gray-200 text-gray-700 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring focus:ring-gray-300">
                                    Batal
                                </a>
                                <button type="submit" class="px-4 py-2 bg-gray-500 text-white border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                                    Kirim Permintaan Bahan Baku
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
