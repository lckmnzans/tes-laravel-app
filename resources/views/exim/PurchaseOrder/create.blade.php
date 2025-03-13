<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Purchase Order (PO)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-xl font-bold mb-4">Buat Purchase Order (PO) untuk Delivery Request (DR) #{{ $deliveryRequest->no_dr }}</h1>

                    <!-- Form Pembuatan PO -->
                    <form method="POST" action="{{ route('exim.purchaseorder.index.store') }}">
                        @csrf
                        <input type="hidden" name="delivery_request_id" value="{{ $deliveryRequest->id }}">

                        <div class="mb-4">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Customer</label>
                            <input type="text" id="customer_name" name="customer_name" value="{{ $deliveryRequest->pelanggan->name_customer }}" class="mt-1 block w-full" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="product_name" class="block text-sm font-medium text-gray-700">Produk</label>
                            <input type="text" id="product_name" name="product_name" value="{{ $deliveryRequest->product->produk }}" class="mt-1 block w-full" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" id="jumlah" name="jumlah" value="{{ $deliveryRequest->jumlah }}" class="mt-1 block w-full" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="total_amount" class="block text-sm font-medium text-gray-700">Total Harga</label>
                            <input type="text" id="total_amount" name="total_amount" value="{{ $deliveryRequest->product->harga * $deliveryRequest->jumlah }}" class="mt-1 block w-full" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status PO</label>
                            <select id="status" name="status" class="mt-1 block w-full">
                                <option value="pending" selected>Pending</option>
                                <option value="approved">Approved</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary">Buat PO</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
