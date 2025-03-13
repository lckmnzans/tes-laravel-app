<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informasi Data -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-gray-100 border border-gray-200 rounded-lg text-center">
                    <h3 class="text-lg font-semibold text-gray-600">Customer</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $total_customers ?? 0 }}</p>
                </div>
                <div class="p-4 bg-blue-100 border border-gray-200 rounded-lg text-center">
                    <h3 class="text-lg font-semibold text-gray-600">Supplier</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $total_suppliers ?? 0 }}</p>
                </div>
                <div class="p-4 bg-gray-200 border border-gray-200 rounded-lg text-center">
                    <h3 class="text-lg font-semibold text-gray-800">Produk</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $total_products ?? 0 }}</p>
                </div>
                <div class="p-4 bg-blue-200 border border-gray-200 rounded-lg text-center">
                    <h3 class="text-lg font-semibold text-gray-800">Bahan Baku</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $total_materials ?? 0 }}</p>
                </div>
            </div>
            
            <!-- Konten Khusus Role -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
