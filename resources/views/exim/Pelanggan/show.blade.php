<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pelanggan') }}
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Informasi pribadi dan detail pelanggan.</p>
        </h2>
    </x-slot>
    <!--<div class="py-10">-->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray overflow-hidden shadow-sm sm:rounded-lg p-1">
                
                    <div class="mt-6 border-t border-gray-100">
                      <dl class="divide-y divide-gray-100">
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                          <dt class="text-sm font-medium leading-6 text-gray-900">Nama Customer</dt>
                          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $pelanggan->nama_customer }}</dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                          <dt class="text-sm font-medium leading-6 text-gray-900">Alamat</dt>
                          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $pelanggan->alamat }}</dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                          <dt class="text-sm font-medium leading-6 text-gray-900">Nomor HP</dt>
                          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $pelanggan->no_hp }}</dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                          <dt class="text-sm font-medium leading-6 text-gray-900">Email</dt>
                          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <a href="mailto:{{ $pelanggan->email }}" class="text-blue-600 hover:underline">{{ $pelanggan->email }}</a>
                        </dd>
                        
                        </div>
                      </dl>
                    </div>                 
            </div>
        </div>
    </div>
</x-app-layout>
