<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pelanggan') }}
        </h2>
    </x-slot>

    <!--<div class="py-12">-->
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Notifikasi Error -->
                    @if (session()->has('error'))
                        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Tombol Kembali
                    <a href="{{ route('exim.pelanggan.index') }}" class="text-blue-500 hover:underline mb-6 inline-block">
                        &larr; Kembali ke Daftar Pelanggan
                    </a>-->

                    <!-- Form Input -->
                    <form action="{{ route('exim.pelanggan.index.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Customer -->
                            <div>
                                <label for="nama_customer" class="block text-sm font-medium text-gray-700 mb-1">Nama Customer</label>
                                <input type="text" name="nama_customer" id="nama_customer" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Masukkan Nama Customer">
                                @error('nama_customer')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div>
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <input type="text" name="alamat" id="alamat" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Masukkan Alamat">
                                @error('alamat')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Nomor HP -->
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                                <input type="text" name="no_hp" id="no_hp" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Masukkan No HP">
                                @error('no_hp')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Masukkan Email">
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-6 py-2 rounded-md shadow-md transition duration-300">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
