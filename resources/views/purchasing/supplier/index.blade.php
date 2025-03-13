<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl font-semibold text-gray-800">Manajemen Supplier</h3>
            <div class="flex space-x-4">
                <a href="{{ route('purchasing.supplier.create') }}" 
                   class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium shadow hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">
                    + Tambah Supplier
                </a>
                <a href="{{ route('purchasing.supplier.contract_create') }}" 
                   class="px-6 py-2 bg-green-600 text-white rounded-lg font-medium shadow hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-300">
                    📜 Tambah Kontrak
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6 space-y-4">
        <!-- Pencarian -->
        <form method="GET" action="{{ route('purchasing.supplier.index') }}" class="mb-4">
            <div class="relative">
                <input type="text" name="search" placeholder="Cari Supplier..."
                       class="w-full px-4 py-2 border rounded-lg shadow-sm"
                       value="{{ request('search') }}">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                    🔍
                </button>
            </div>
        </form>

        <!-- List Supplier -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="p-6 space-y-4">
                @forelse ($suppliers as $supplier)
                    <div class="p-4 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow border">
                        <div class="flex justify-between items-center">
                            <!-- Bagian Kiri: Informasi Supplier -->
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">
                                    <a href="{{ route('purchasing.supplier.show', $supplier->id) }}" class="hover:underline">
                                        {{ $supplier->nama_perusahaan }}
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-600">
                                    Alamat: <span class="font-medium">{{ $supplier->alamat }}</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    Contact Person: <span class="font-medium">{{ $supplier->contact_person }}</span> 
                                    ({{ $supplier->no_cp }})
                                </p>
                            </div>
        
                            <!-- Bagian Kanan: Tombol Lihat Detail & Dropdown -->
                            <div class="flex items-center space-x-2">
                                <!-- Tombol Lihat Detail -->
                                <a href="{{ route('purchasing.supplier.contract_detail', $supplier->id) }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium shadow hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                                    Lihat Detail Kontrak
                                </a>
        
                                <!-- Tombol Titik Tiga dengan Dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="px-2 py-1 text-gray-600 hover:text-gray-900 focus:outline-none">
                                        ⋮
                                    </button>
        
                                    <div x-show="open" @click.away="open = false" 
                                         class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border z-10">
                                        <ul class="py-2 text-gray-700">
                                            <li>
                                                <a href="{{ route('purchasing.supplier.edit', $supplier->id) }}" 
                                                   class="block px-4 py-2 hover:bg-gray-100">
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('purchasing.supplier.show', $supplier->id) }}" 
                                                   class="block px-4 py-2 hover:bg-gray-100">
                                                    Detail
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('purchasing.supplier.index.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus supplier ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-6">
                        Tidak ada supplier yang tersedia.
                    </p>
                @endforelse
            </div>
        </div>
        
    </div>
</x-app-layout>
