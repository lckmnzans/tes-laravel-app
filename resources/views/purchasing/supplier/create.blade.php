<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Tambah Supplier') }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-4">Form Tambah Supplier</h1>
        <hr class="mb-4" />

        @if (session()->has('error'))
            <div class="bg-red-100 text-red-700 border border-red-400 rounded-lg p-4 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('purchasing.supplier.index.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kode Supplier -->
                <div>
                    <label for="kode_supplier" class="block text-gray-700 font-medium">Kode Supplier</label>
                    <input type="text" name="kode_supplier" id="kode_supplier" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Kode Supplier">
                    @error('kode_supplier')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nama Perusahaan -->
                <div>
                    <label for="nama_perusahaan" class="block text-gray-700 font-medium">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nama Perusahaan" required>
                    @error('nama_perusahaan')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-gray-700 font-medium">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-textarea mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Alamat Perusahaan" required></textarea>
                    @error('alamat')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Negara -->
                <div>
                    <label for="negara" class="block text-gray-700 font-medium">Negara</label>
                    <input type="text" name="negara" id="negara" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Negara">
                    @error('negara')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Person -->
                <div>
                    <label for="contact_person" class="block text-gray-700 font-medium">Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nama Contact Person" required>
                    @error('contact_person')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nomor Telepon Contact Person -->
                <div>
                    <label for="no_cp" class="block text-gray-700 font-medium">Nomor Telepon Contact Person</label>
                    <input type="text" name="no_cp" id="no_cp" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nomor Telepon Contact Person" required>
                    @error('no_cp')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nomor Telepon Perusahaan -->
                <div>
                    <label for="no_tlp" class="block text-gray-700 font-medium">Nomor Telepon Perusahaan</label>
                    <input type="text" name="no_tlp" id="no_tlp" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nomor Telepon Perusahaan" required>
                    @error('no_tlp')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- NPWP -->
                <div>
                    <label for="npwp" class="block text-gray-700 font-medium">NPWP</label>
                    <input type="text" name="npwp" id="npwp" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nomor NPWP">
                    @error('npwp')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" id="email" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Email Perusahaan" required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Catatan -->
                <div>
                    <label for="catatan" class="block text-gray-700 font-medium">Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-textarea mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Catatan Tambahan"></textarea>
                    @error('catatan')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
