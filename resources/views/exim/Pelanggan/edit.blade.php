<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pelanggan') }}
        </h2>
    </x-slot>

    <!--<div class="py-12">-->
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Header
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Pelanggan</h1>
                    <hr class="mb-6">-->

                    <!-- Form Edit -->
                    <form action="{{ route('exim.pelanggan.update', $pelanggan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Pelanggan -->
                            <div>
                                <label for="nama_customer" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                                <input type="text" name="nama_customer" id="nama_customer" value="{{ $pelanggan->nama_customer }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 placeholder-gray-400"
                                       placeholder="Masukkan Nama Customer">
                                @error('nama_customer')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div>
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <input type="text" name="alamat" id="alamat" value="{{ $pelanggan->alamat }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 placeholder-gray-400"
                                       placeholder="Masukkan Alamat">
                                @error('alamat')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Nomor HP -->
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                                <input type="text" name="no_hp" id="no_hp" value="{{ $pelanggan->no_hp }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 placeholder-gray-400"
                                       placeholder="Masukkan No HP">
                                @error('no_hp')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email" value="{{ $pelanggan->email }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 placeholder-gray-400"
                                       placeholder="Masukkan Email">
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Update -->
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('exim.pelanggan.index') }}"
                               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md shadow-md hover:bg-gray-400 transition mr-3">
                                Batal
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 text-white font-medium px-6 py-2 rounded-md shadow-md hover:bg-yellow-600 transition">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
