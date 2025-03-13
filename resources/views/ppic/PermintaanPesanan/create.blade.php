<x-app-layout>
    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div id="notification-success" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-md shadow-lg z-50 transition-opacity duration-300">
            {{ session('success') }}
        </div>
    @endif

    <!-- Notifikasi Error -->
    @if (session('error'))
        <div id="notification-error" class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded-md shadow-lg z-50 transition-opacity duration-300">
            {{ session('error') }}
        </div>
    @endif

    <!-- Notifikasi Validasi -->
    @if ($errors->any())
        <div id="notification-validation-error" class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded-md shadow-lg z-50 transition-opacity duration-300">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Buat Jadwal Produksi untuk Pesanan: ' . $purchaseOrder->kode_po) }}
    </h2>
    <div class="py-1">
        
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Keterangan dan Tabel Detail Pesanan -->
                        <div>
                            <h5 class="mb-3 text-lg font-semibold text-gray-700 dark:text-white-light">Detail Pesanan</h5>
                            <table class="table-auto w-full border border-gray-300">
                                <tr class="bg-gray-100">
                                    <td class="p-2 font-bold border border-gray-300">Nomor PO</td>
                                    <td class="p-2 font-bold border border-gray-300">{{ $purchaseOrder->kode_po }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-bold border border-gray-300">Kode Produksi</td>
                                    <td class="p-2 border border-gray-300">
                                        {{ 'PROD-' . now()->format('Ymd') . '-' . $purchaseOrder->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-bold border border-gray-300">Nama Customer</td>
                                    <td class="p-2 border border-gray-300">{{ $purchaseOrder->deliveryRequest->pelanggan->nama_customer }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-bold border border-gray-300">Produk</td>
                                    <td class="p-2 border border-gray-300">
                                        @foreach($purchaseOrder->deliveryRequest->product as $product)
                                        {{ $product->produk }} ({{ $product->pivot->quantity }}),
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-bold border border-gray-300">Jumlah Produk</td>
                                    <td class="p-2 border border-gray-300">{{ $purchaseOrder->deliveryRequest->product->sum('pivot.quantity') }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-bold border border-gray-300">Harga Total</td>
                                    <td class="p-2 border border-gray-300">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    
                        <!-- Keterangan dan Tabel Bahan Baku -->
                        <div>
                            <h5 class="mb-3 text-lg font-semibold text-gray-700 dark:text-white-light">Bahan Baku yang Diperlukan</h5>
                            <div class="overflow-y-auto max-h-96 border border-gray-300 rounded-lg">
                                <table class="table-auto w-full border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="p-2 border border-gray-300">Nama Bahan Baku</th>
                                            <th class="p-2 border border-gray-300">Jumlah yang Dibutuhkan</th>
                                            <th class="p-2 border border-gray-300">Stok Tersedia</th>
                                            <th class="p-2 border border-gray-300">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bahanBaku as $bahan)
                                        <tr>
                                            <td class="p-2 border border-gray-300">{{ $bahan->nama }}</td>
                                            <td class="p-2 border border-gray-300">{{ $bahan->jumlah_dibutuhkan }}</td>
                                            <td class="p-2 border border-gray-300">{{ $bahan->stok_tersedia }}</td>
                                            <td class="p-2 border border-gray-300">
                                                @if ($bahan->stok_tersedia >= $bahan->jumlah_dibutuhkan)
                                                <span class="text-green-500">Cukup tersedia</span>
                                                @else
                                                <span class="text-red-500">Stok tidak mencukupi</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                        <form action="{{ route('ppic.permintaanpesanan.index.store') }}" method="POST" id="production-schedule-form">
                            @csrf
                            <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
                            <input type="hidden" name="quantity_to_produce" value="{{ $purchaseOrder->deliveryRequest->product->sum('pivot.quantity') }}">
                            <input type="hidden" name="proses" value="1">
                            <input type="hidden" name="statusProduksi" value="belum">
                            
                            <div> 
                                <h4 class="text-lg font-bold mt-4">Target Penyelesaian Proses</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                    <div>
                                        <label for="expected_finish_date" class="block text-sm font-medium text-gray-700">Expected Finish Date</label>
                                        <input type="date" id="expected_finish_date" name="expected_finish_date"
                                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required
                                               min="{{ now()->format('Y-m-d') }}" 
                                               value="{{ old('expected_finish_date', $expectedFinishDate) }}">
                                    </div>
                            
                                    <div>
                                        <label for="target_prep_materials" class="block text-sm font-medium text-gray-700">Prep Materials</label>
                                        <input type="date" id="target_prep_materials" name="target_prep_materials" 
                                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required
                                               min="{{ now()->format('Y-m-d') }}" 
                                               value="{{ old('target_prep_materials', $targetPrepMaterials) }}">
                                    </div>
                                    <div>
                                        <label for="target_production" class="block text-sm font-medium text-gray-700">Production</label>
                                        <input type="date" id="target_production" name="target_production" 
                                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required
                                               min="{{ now()->format('Y-m-d') }}" 
                                               value="{{ old('target_production', $targetProduction) }}">
                                    </div>
                                    <div>
                                        <label for="target_packaging" class="block text-sm font-medium text-gray-700">Packaging</label>
                                        <input type="date" id="target_packaging" name="target_packaging" 
                                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required
                                               min="{{ now()->format('Y-m-d') }}" 
                                               value="{{ old('target_packaging', $targetPackaging) }}">
                                    </div>
                                    <div>
                                        <label for="target_quality_control" class="block text-sm font-medium text-gray-700">Quality Control</label>
                                        <input type="date" id="target_quality_control" name="target_quality_control" 
                                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required
                                               min="{{ now()->format('Y-m-d') }}" 
                                               value="{{ old('target_quality_control', $targetQualityControl) }}">
                                    </div>
                                    <div>
                                        <label for="target_shipping" class="block text-sm font-medium text-gray-700">Shipping</label>
                                        <input type="date" id="target_shipping" name="target_shipping" 
                                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required
                                               min="{{ now()->format('Y-m-d') }}" 
                                               value="{{ old('target_shipping', $targetShipping) }}">
                                    </div>
                                </div>
                            </div>                                                     
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                                <textarea name="description" id="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
                            </div>

                            <!--Proses Produksi-->
                            <div class="panel h-full">
                                <div class="mb-3 flex items-center dark:text-white-light">
                                    <h5 class="text-lg font-semibold">Proses produksi yang Berlangsung</h5>
                                </div>
                                <div class="space-y-5">
                                    @foreach($processData as $process)
                                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                        @php
                                            $bgColor = '';
                                            $gradient = '';
                                            $textColor = '';
                                            switch ($process['category']) {
                                                case 'success':
                                                    $bgColor = 'bg-success-light';
                                                    $textColor = 'text-success';
                                                    $gradient = 'from-[#3cba92] to-[#0ba360]';
                                                    break;
                                                case 'danger':
                                                    $bgColor = 'bg-danger/10';
                                                    $textColor = 'text-danger';
                                                    $gradient = 'from-[#a71d31] to-[#3f0d12]';
                                                    break;
                                                case 'warning':
                                                    $bgColor = 'bg-warning/10';
                                                    $textColor = 'text-warning';
                                                    $gradient = 'from-[#fe5f75] to-[#fc9842]';
                                                    break;
                                                case 'primary':
                                                default:
                                                    $bgColor = 'bg-primary/10';
                                                    $textColor = 'text-primary';
                                                    $gradient = 'from-[#009ffd] to-[#2a2a72]';
                                                    break;
                                            }
                                        @endphp
                                        <!-- Ikon kategori -->
                                        <div class="h-7 w-7 flex items-center justify-center rounded-lg {{ $bgColor }} {{ $textColor }} dark:bg-dark-light dark:text-white-light cursor-pointer"
                                            data-bs-toggle="modal" data-bs-target="#modal-{{ $process['process_key'] }}" role="button">
                                            <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="..." stroke="currentColor" stroke-width="1.5"></path>
                                            </svg>
                                        </div>
                                        <!-- Detail proses -->
                                        <div class="flex-1">
                                            <div class="flex justify-between text-sm font-semibold text-white-dark">
                                                <span>{{ $process['stage'] }}</span>
                                                <span>{{ $process['percentage'] }}% ({{ $process['count'] }} items)</span>
                                            </div>
                                            <div class="h-3 w-full overflow-hidden rounded-full bg-dark-light dark:bg-dark-light/10">
                                                <div class="h-full rounded-full bg-gradient-to-r {{ $gradient }}" style="width: {{ $process['percentage'] }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal untuk proses tertentu -->
                                    <div class="modal fade" id="modal-{{ $process['process_key'] }}" tabindex="-1" aria-labelledby="modalLabel-{{ $process['process_key'] }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <!-- Header modal dengan background warna kategori -->
                                                <div class="modal-header {{ $process['category'] === 'success' ? 'bg-success-light text-success' : '' }} 
                                                                          {{ $process['category'] === 'danger' ? 'bg-danger/10 text-danger' : '' }} 
                                                                          {{ $process['category'] === 'warning' ? 'bg-warning/10 text-warning' : '' }} 
                                                                          {{ $process['category'] === 'primary' ? 'bg-primary/10 text-primary' : '' }}">
                                                    <h5 class="modal-title" id="modalLabel-{{ $process['process_key'] }}">{{ $process['stage'] }} Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                    
                                                <!-- Body modal -->
                                                <div class="modal-body">
                                                    <ul class="space-y-4">
                                                        @foreach($filteredProcessData[$process['process_key']] ?? [] as $data)
                                                        <li>
                                                            <h6 class="font-bold text-gray-700">{{ $data['kode'] }}</h6>
                                                            @foreach($data['durations'] as $stage => $percentage)
                                                            <div class="flex items-center space-x-3">
                                                                <span class="font-medium text-gray-600">{{ $stage }}</span>
                                                                <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                                                                    <!-- Bar persentase dengan warna kategori -->
                                                                    <div class="h-full rounded-full {{ $process['category'] === 'success' ? 'bg-success' : '' }} 
                                                                                                   {{ $process['category'] === 'danger' ? 'bg-danger' : '' }} 
                                                                                                   {{ $process['category'] === 'warning' ? 'bg-warning' : '' }} 
                                                                                                   {{ $process['category'] === 'primary' ? 'bg-primary' : '' }}" 
                                                                        style="width: {{ $percentage }}%;">
                                                                    </div>
                                                                </div>
                                                                <span class="text-sm text-gray-500">{{ round($percentage, 2) }}%</span>
                                                            </div>
                                                            @endforeach
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @endforeach
                                </div>
                            </div>
                                                     
                            <div class="flex justify-between">
                                <!--<button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md" onclick="window.location='{{ route('ppic.permintaanpesanan.index') }}'">
                                    Kembali ke Riwayat Pesanan
                                </button>-->
    
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">
                                    Simpan Penjadwalan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
       
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
         
    <script>
        // Notifikasi akan hilang setelah 5 detik
        setTimeout(() => {
            const successNotification = document.getElementById('notification-success');
            const errorNotification = document.getElementById('notification-error');
            const validationErrorNotification = document.getElementById('notification-validation-error');

            if (successNotification) {
                successNotification.style.opacity = '0';
                setTimeout(() => successNotification.remove(), 300);
            }

            if (errorNotification) {
                errorNotification.style.opacity = '0';
                setTimeout(() => errorNotification.remove(), 300);
            }

            if (validationErrorNotification) {
                validationErrorNotification.style.opacity = '0';
                setTimeout(() => validationErrorNotification.remove(), 300);
            }
        }, 5000);
        
        //date
        document.addEventListener('DOMContentLoaded', function () {
    const expectedFinishDateField = document.getElementById('expected_finish_date');
    const prepMaterialsField = document.getElementById('target_prep_materials');
    const productionField = document.getElementById('target_production');
    const packagingField = document.getElementById('target_packaging');
    const qualityControlField = document.getElementById('target_quality_control');
    const shippingField = document.getElementById('target_shipping');

    expectedFinishDateField.addEventListener('change', function () {
        const finishDate = new Date(this.value);

        if (!isNaN(finishDate)) {
            // Calculate process dates
            const prepMaterialsDate = new Date(finishDate);
            prepMaterialsDate.setDate(finishDate.getDate() - 25);

            const productionDate = new Date(finishDate);
            productionDate.setDate(finishDate.getDate() - 8);

            const packagingDate = new Date(finishDate);
            packagingDate.setDate(finishDate.getDate() - 6);

            const qualityControlDate = new Date(finishDate);
            qualityControlDate.setDate(finishDate.getDate() - 4);

            // Set the values
            prepMaterialsField.value = prepMaterialsDate.toISOString().split('T')[0];
            productionField.value = productionDate.toISOString().split('T')[0];
            packagingField.value = packagingDate.toISOString().split('T')[0];
            qualityControlField.value = qualityControlDate.toISOString().split('T')[0];
            shippingField.value = finishDate.toISOString().split('T')[0];
        }
    });
});

        //bahan baku
        const form = document.getElementById('production-schedule-form');
            form.addEventListener('submit', function (event) {
                const bahanBakuRows = document.querySelectorAll('tbody tr');
                let stokCukup = true;

                bahanBakuRows.forEach(row => {
                    const keterangan = row.querySelector('td:last-child span');
                    if (keterangan && keterangan.classList.contains('text-red-500')) {
                        stokCukup = false;
                    }
                });

                if (!stokCukup) {
                    alert('Stok bahan baku tidak mencukupi. Harap lakukan pengadaan terlebih dahulu.');
                    event.preventDefault();
                }
            });
//calender

    
    </script>
<style>
    .modal-backdrop {
        display: none; /* Hilangkan latar belakang gelap */
    }
</style>

</x-app-layout>
