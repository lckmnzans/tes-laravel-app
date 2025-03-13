<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-4 text-lg font-semibold">Edit Data Supplier</h1>
                    <hr class="mb-6" />

                    @if (session()->has('status'))
                        <div class="mb-4 text-green-500">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('purchasing.supplier.update', $supplier->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Perusahaan -->
                        <div class="mb-4">
                            <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-input w-full mt-1" value="{{ old('nama_perusahaan', $supplier->nama_perusahaan) }}" required>
                            @error('nama_perusahaan')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-input w-full mt-1" value="{{ old('alamat', $supplier->alamat) }}" required>
                            @error('alamat')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Contact Person -->
                        <div class="mb-4">
                            <label for="contact_person" class="block text-sm font-medium text-gray-700">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-input w-full mt-1" value="{{ old('contact_person', $supplier->contact_person) }}" required>
                            @error('contact_person')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- No CP -->
                        <div class="mb-4">
                            <label for="no_cp" class="block text-sm font-medium text-gray-700">No CP</label>
                            <input type="text" name="no_cp" id="no_cp" class="form-input w-full mt-1" value="{{ old('no_cp', $supplier->no_cp) }}" required>
                            @error('no_cp')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- No Telpon -->
                        <div class="mb-4">
                            <label for="no_tlp" class="block text-sm font-medium text-gray-700">No Telpon</label>
                            <input type="text" name="no_tlp" id="no_tlp" class="form-input w-full mt-1" value="{{ old('no_tlp', $supplier->no_tlp) }}" required>
                            @error('no_tlp')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" class="form-input w-full mt-1" value="{{ old('email', $supplier->email) }}" required>
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('purchasing.supplier.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring focus:ring-gray-300">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-gray-500 text-white border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                                Update Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
