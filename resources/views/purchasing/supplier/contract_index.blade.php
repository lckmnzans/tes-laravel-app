<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl font-semibold text-gray-800">Manajemen Kontrak Supplier</h3>
            <a href="{{ route('purchasing.supplier.contract_create') }}" 
               class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium shadow hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">
                + Tambah Kontrak
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6 space-y-1">
        <!-- List Supplier -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="p-6 space-y-4">
                @forelse ($suppliers as $supplier)
                    <!-- Card Supplier -->
                    <div class="p-4 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow border">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">
                                    <a href="{{ route('purchasing.supplier.contract_detail', $supplier->id) }}" class="hover:underline">
                                        {{ $supplier->nama_perusahaan }}
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-600">
                                    Jumlah Kontrak: <span class="font-medium">{{ $supplier->contracts->count() }}</span>
                                </p>
                            </div>
                            <a href="{{ route('purchasing.supplier.contract_detail', $supplier->id) }}" 
                               class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium shadow hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-6">
                        Tidak ada supplier dengan kontrak yang tersedia.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
