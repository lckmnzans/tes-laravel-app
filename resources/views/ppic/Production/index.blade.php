<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Jadwal Produksi') }}
        </h2>
    </x-slot>
        @if (session('success'))
                <div id="notification-success" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-md shadow-lg z-50 transition-opacity duration-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="notification-error" class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded-md shadow-lg z-50 transition-opacity duration-300">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div id="notification-validation-error" class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded-md shadow-lg z-50 transition-opacity duration-300">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Kategori Status Produksi -->
                    <div class="flex space-x-4 mb-6 border-b pb-2">
                        @php
                            $statuses = [
                                'Semua' => null,
                                'Prep Materials' => '1',
                                'Production' => '2',
                                'Packaging' => '3',
                                'Quality Control' => '4',
                                'Shipping' => '5',
                                'Selesai' => '6',
                            ];
                        @endphp
                    
                        @foreach ($statuses as $label => $value)
                            <a href="{{ route('ppic.production.index', ['status' => $value]) }}"
                               class="px-4 py-2 rounded-md text-sm font-medium 
                                      {{ request('status') == $value || (request('status') == null && $value == null) ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                    
                    <!-- Tabel Jadwal Produksi -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm text-gray-700">
                            <thead class="bg-gray-100 text-gray-800 uppercase">
                                <tr>
                                    <th class="border px-4 py-3 text-center font-medium">Nomor PO</th>
                                    <th class="border px-4 py-3 text-center font-medium">Produk</th>
                                    <th class="border px-4 py-3 text-center font-medium">Jumlah</th>
                                    <th class="border px-4 py-3 text-center font-medium">Tanggal Mulai</th>
                                    <th class="border px-4 py-3 text-center font-medium">Target Selesai</th>
                                    <th class="border px-4 py-3 text-center font-medium">Proses</th>
                                    <th class="border px-4 py-3 text-center font-medium" style="width: 300px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productionSchedules as $schedule)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="border px-4 py-3 text-center font-semibold text-gray-800">
                                            {{ $schedule->kode }}
                                        </td>
                                        <td class="border px-4 py-3 text-center">
                                            <ul>
                                                @foreach($schedule->purchaseOrder->deliveryRequest->product as $product)
                                                    <li>{{ $product->produk }}</li>
                                                @endforeach
                                            </ul>
                                        </td>  
                                        <td class="border px-4 py-3 text-center">
                                            <ul>
                                                @if($schedule->purchaseOrder && $schedule->purchaseOrder->deliveryRequest && $schedule->purchaseOrder->deliveryRequest->product)
                                                    @foreach($schedule->purchaseOrder->deliveryRequest->product as $product)
                                                        <li>{{ $product->pivot->quantity }}</li>
                                                    @endforeach
                                                @else
                                                    <li>-</li>
                                                @endif
                                            </ul>
                                        </td>
                                        
                                        <td class="border px-4 py-3 text-center">{{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }}</td>
                                        <td class="border px-4 py-3 text-center">{{ \Carbon\Carbon::parse($schedule->expected_finish_date)->translatedFormat('d F Y') }}</td>
                                        <td class="border px-4 py-3 text-center align-middle">
                                            <div class="flex flex-col items-center justify-center">
                                                <!-- Nama Proses -->
                                                <span class="font-bold text-green-500 mb-2">
                                                    {{ \App\Models\ProductionSchedule::getProsesList()[$schedule->proses] ?? 'Unknown' }}
                                                </span>
                                        
                                                @php
                                                    $targetTime = null;
                                                    $now = now();
                                                    $reminderText = 'Tidak ada target';
                                                    $boxClass = 'bg-gray-100 border-gray-400 text-gray-500'; // Default warna jika tidak ada target
                                        
                                                    // Tentukan target waktu berdasarkan proses saat ini
                                                    switch ($schedule->proses) {
                                                        case '1':
                                                            $targetTime = $schedule->target_prep_materials;
                                                            break;
                                                        case '2':
                                                            $targetTime = $schedule->target_production;
                                                            break;
                                                        case '3':
                                                            $targetTime = $schedule->target_packaging;
                                                            break;
                                                        case '4':
                                                            $targetTime = $schedule->target_quality_control;
                                                            break;
                                                        case '5':
                                                            $targetTime = $schedule->target_shipping;
                                                            break;
                                                    }
                                        
                                                    // Hitung hari tersisa jika target waktu ada
                                                    if ($targetTime) {
                                                        $daysLeft = $now->diffInDays($targetTime, false); // Angka bulat
                                                        $daysLeft = intval($daysLeft); // Membulatkan angka menjadi bulat
                                        
                                                        // Menentukan status berdasarkan sisa hari
                                                        if ($daysLeft > 0) {
                                                            $boxClass = 'bg-green-100 border-green-300 text-green-500'; // Hijau jika lebih dari 0 hari
                                                            $reminderText = "{$daysLeft} hari tersisa";
                                                        } elseif ($daysLeft == 0) {
                                                            $boxClass = 'bg-green-100 border-green-300 text-green-500'; // Tetap hijau jika hari target adalah hari ini
                                                            $reminderText = "Hari ini (target)";
                                                        } else {
                                                            $boxClass = 'bg-red-100 border-red-300 text-red-500'; // Merah jika tenggat lewat
                                                            $reminderText = abs($daysLeft) . " hari terlambat"; // Angka bulat untuk keterlambatan
                                                        }
                                                    }
                                                @endphp
                                        
                                                <!-- Kotak Target Waktu -->
                                                <div class="w-35 text-center py-1 px-3 rounded-lg font-semibold border {{ $boxClass }} text-xs">
                                                    {{ $reminderText }}
                                                </div>
                                        
                                                <!-- Menampilkan Target Waktu -->
                                                <div class="mt-2 text-xs text-gray-700 text-center">
                                                    Target: {{ $targetTime ? \Carbon\Carbon::parse($targetTime)->translatedFormat('d F Y') : 'Tidak ada target' }}
                                                </div>
                                            </div>
                                        </td>
                                        
                                        
                                        
                                        
                                    
                                        <!-- Tombol Lihat Detail -->
                                        <td class="border px-4 py-3 text-center">           
                                            <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600" 
                                                    data-modal-target="detailModal-{{ $schedule->id }}" 
                                                    data-modal-toggle="detailModal-{{ $schedule->id }}">
                                                Lihat Detail
                                            </button>

                                            <!-- Tombol Selesai -->
                                        @if($selectedStatus !== 'Semua' && $schedule->proses !== '6')
                                        @if(auth()->user()->role === 'operator')
                                            @if($schedule->proses === '0') 
                                                <!-- Pesan untuk Proses Antrian -->
                                                <p class="text-sm text-blue-500 mt-1">
                                                    Sedang dalam <strong>Proses Antrian</strong>. Menunggu slot produksi tersedia.
                                                </p>
                                            @elseif(in_array($schedule->proses, ['1', '2', '3', '4', '5']))
                                                <!-- Tombol untuk Proses selain Antrian -->
                                                <button type="button" 
                                                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600"
                                                        data-modal-target="uploadDocumentModal-{{ $schedule->id }}" 
                                                        data-modal-toggle="uploadDocumentModal-{{ $schedule->id }}">
                                                    Selesaikan Proses
                                                </button>

                                                @if($schedule->proses === '1' && $todayProductionCount >= $dailyProductionLimit)
                                            <!-- Pesan jika slot produksi penuh -->
                                            <p class="text-sm text-yellow-500 mt-1">
                                                Proses ini akan masuk ke status <strong>Antrian</strong>, karena batas produksi harian telah tercapai.
                                            </p>
                                        @endif


                                            @endif
                                        @endif
                                        @endif

                                        <!-- Modal untuk Lihat Detail -->
                                        <div id="detailModal-{{ $schedule->id }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                                                <!-- Header Modal -->
                                                <div class="flex justify-between items-center bg-blue-500 text-white px-6 py-4 rounded-t-lg">
                                                    <h3 class="text-lg font-semibold">
                                                        Detail Jadwal Produksi
                                                    </h3>
                                                    <button class="text-white hover:text-gray-200" data-modal-hide="detailModal-{{ $schedule->id }}">
                                                        ✕
                                                    </button>
                                                </div>

                                                <!-- Body Modal -->
                                                <div class="p-6 space-y-4">
                                                    <div class="space-y-1">
                                                        <p><strong class="text-gray-600">Kode Produksi:</strong> <span class="text-gray-800">{{ $schedule->kode ?? '-' }}</span></p>
                                                        <p><strong class="text-gray-600">Nama Customer:</strong> <span class="text-gray-800">{{ $schedule->purchaseOrder->deliveryRequest->pelanggan->nama_customer }}</span></p>
                                                    </div>
                                                    <div>
                                                        <p><strong class="text-gray-600">Produk:</strong></p>
                                                        <ul class="list-disc pl-6 text-gray-800">
                                                            @foreach($schedule->purchaseOrder->deliveryRequest->product as $product)
                                                                <li>{{ $product->produk }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <p><strong class="text-gray-600">Bahan Baku:</strong></p>
                                                        <ul class="list-disc pl-6 text-gray-800">
                                                            @foreach($schedule->purchaseOrder->deliveryRequest->product as $product)
                                                                @foreach($product->bahanBaku as $bahan)
                                                                    <li>{{ $bahan->namaBahan }}: {{ $bahan->pivot->quantity * $product->pivot->quantity }}</li>
                                                                @endforeach
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <p><strong class="text-gray-600">Tanggal Tiap Proses:</strong></p>
                                                        <ul class="text-gray-800 space-y-2">
                                                            <li><strong>Prep Materials:</strong> <span class="ml-2">{{ \Carbon\Carbon::parse($schedule->prep_materials_completed_at)->format('Y-m-d') ?? '-' }}</span></li>
                                                            <li><strong>Production:</strong> <span class="ml-2">{{ \Carbon\Carbon::parse($schedule->production_completed_at)->format('Y-m-d') ?? '-' }}</span></li>
                                                            <li><strong>Packaging:</strong> <span class="ml-2">{{ \Carbon\Carbon::parse($schedule->packaging_completed_at)->format('Y-m-d') ?? '-' }}</span></li>
                                                            <li><strong>Quality Control:</strong> <span class="ml-2">{{ \Carbon\Carbon::parse($schedule->quality_control_completed_at)->format('Y-m-d') ?? '-' }}</span></li>
                                                            <li><strong>Shipping:</strong> <span class="ml-2">{{ \Carbon\Carbon::parse($schedule->shipping_completed_at)->format('Y-m-d') ?? '-' }}</span></li>
                                                        </ul>
                                                        
                                                    </div>
                                                    <!--<div>
                                                        <p><strong class="text-gray-600">Dokumen Bukti:</strong></p>
                                                        @if($schedule->document)
                                                            <a href="{{ Storage::url($schedule->document) }}" target="_blank" 
                                                               class="text-blue-500 underline hover:text-blue-700">
                                                                Lihat Dokumen Produksi
                                                            </a>
                                                        @else
                                                            <span class="text-red-500">Dokumen belum diunggah.</span>
                                                        @endif
                                                    </div>   -->                                  
                                                </div>
                                                <!-- Footer Modal -->
                                                <div class="flex justify-end bg-gray-100 px-6 py-4 rounded-b-lg">
                                                    @if($schedule->proses !== '1' && auth()->user()->role === 'operator')
                                                    <form action="{{ route('ppic.production.rollbackStatus', $schedule->id) }}" method="POST">
                                                        @csrf
                                                        <button 
                                                            class="px-4 py-2 text-red-500 font-medium border border-red-500 rounded-md hover:bg-red-500 hover:text-white transition duration-300"
                                                            onclick="handleButtonClick()">
                                                            Kembali ke Proses Sebelumnya
                                                        </button>
                                                    </form>
                                                    @endif
                                                    <button class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" 
                                                            data-modal-hide="detailModal-{{ $schedule->id }}">
                                                        Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    <!-- Modal untuk Mengunggah Dokumen -->
                                    <div id="uploadDocumentModal-{{ $schedule->id }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                                            <div class="flex justify-between items-center bg-blue-500 text-white px-6 py-4 rounded-t-lg">
                                                <h3 class="text-lg font-semibold">Unggah Dokumen Bukti</h3>
                                                <button type="button" 
                                                        class="text-white hover:text-gray-200" 
                                                        data-modal-hide="uploadDocumentModal-{{ $schedule->id }}">
                                                    ✕
                                                </button>
                                            </div>
                                            <div class="p-6">
                                                <form action="{{ route('ppic.production.updateStatus', $schedule->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label for="document-{{ $schedule->id }}" class="block text-sm font-medium text-gray-700">Dokumen Bukti (PDF/JPG/PNG)</label>
                                                        <input type="file" id="document-{{ $schedule->id }}" name="document" 
                                                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                                                    </div>
                                                    <!-- Input Tambahan: Dokumen SJM -->
                                                    @if($schedule->proses === '1')
                                                        <div class="mb-4">
                                                            <label for="sjm-document-{{ $schedule->id }}" class="block text-sm font-medium text-gray-700">Dokumen Bahan Baku (PDF/JPG/PNG)</label>
                                                            <input type="file" id="sjm-document-{{ $schedule->id }}" name="sjm_document" 
                                                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                                                        </div>
                                                    @endif
                                                
                                                    @if($schedule->proses === '4') <!--quality contril-->
                                                        <div class="mb-4">
                                                            <label for="produced_quantity" class="block text-sm font-medium text-gray-700">Jumlah Produk yang Dihasilkan</label>
                                                            <input type="number" id="produced_quantity" name="produced_quantity" 
                                                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" 
                                                                required min="0"
                                                                value="{{ old('produced_quantity', $schedule->purchaseOrder->quantity) }}"> <!-- Auto-filled with order quantity -->
                                                        </div>
                                                        <div class="mb-4">
                                                            <label for="waste_quantity" class="block text-sm font-medium text-gray-700">Jumlah Produk Cacat</label>
                                                            <input type="number" id="waste_quantity" name="waste_quantity" 
                                                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" 
                                                                required min="0">
                                                        </div>
                                                    @endif
                                                    @if($schedule->proses === '5')
                                                    <!-- Tombol Unduh Surat Pengiriman -->
                                                    <div class="mb-4">
                                                        <a href="{{ route('ppic.production.docshipping', $schedule->id) }}" 
                                                            class="px-4 py-2 bg-blue-300 text-white rounded-md hover:bg-blue-400"
                                                            target="_blank">
                                                            Unduh Surat Pengiriman (Template)
                                                        </a>
                                                    </div> 
                                                    <!-- Tanggal Pengiriman -->
                                                    <div class="mb-4">
                                                        <label for="shipping_date" class="block text-sm font-medium text-gray-700">Tanggal Terima</label>
                                                        <input type="date" id="shipping_date" name="shipping_date" 
                                                            value="{{ $schedule->shipping_completed_at ? \Carbon\Carbon::parse($schedule->shipping_completed_at)->format('Y-m-d') : now()->format('Y-m-d') }}" 
                                                            class="w-full px-4 py-2 border rounded-md" required>
                                                    </div>
                                                    
                                                        <!-- Nama Customer -->
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-gray-700">Nama Customer</label>
                                                            <input type="text" 
                                                                value="{{ $schedule->purchaseOrder->deliveryRequest->pelanggan->nama_customer ?? 'Tidak tersedia' }}" 
                                                                class="w-full px-4 py-2 border rounded-md bg-gray-100" readonly>
                                                        </div>
                                                        <!-- Alamat Pengiriman -->
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-gray-700">Alamat Pengiriman</label>
                                                            <textarea class="w-full px-4 py-2 border rounded-md bg-gray-100" rows="3" readonly>{{ $schedule->purchaseOrder->deliveryRequest->pelanggan->alamat ?? 'Tidak tersedia' }}</textarea>
                                                        </div>
                                                        <!-- Nomor Surat Jalan
                                                        <div class="mb-4">
                                                            <label for="delivery_note" class="block text-sm font-medium text-gray-700">Nomor Surat Jalan (SJ)</label>
                                                            <input type="text" id="delivery_note" name="delivery_note" 
                                                                value="{{ $schedule->pengeluaranBB->kode_sjm ?? 'Belum tersedia' }}" 
                                                                class="w-full px-4 py-2 border rounded-md bg-gray-100" readonly>
                                                        </div>-->
                                                    @endif
                                                    <div class="flex justify-end">
                                                        <button type="button" 
                                                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 mr-2"
                                                                data-modal-hide="uploadDocumentModal-{{ $schedule->id }}">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                                            Simpan dan Lanjutkan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada jadwal produksi tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <style>
        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 50;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
        }
    
        .modal.show {
            display: block;
        }
    
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
            display: none;
        }
    
        .modal-overlay.show {
            display: block;
        }
        
    </style>
    
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

        //dokumen
        document.querySelectorAll('[data-modal-toggle]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-target');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                }
            });
        });

        document.querySelectorAll('[data-modal-hide]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-hide');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                }
            });
        });


    document.querySelectorAll('.btn-selesai').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const form = document.getElementById('form-selesai');
            const modal = document.getElementById('modal-selesai');
            const overlay = document.getElementById('modal-overlay');

            form.action = form.action.replace(':id', id);
            modal.classList.add('show');
            overlay.classList.add('show');
        });
    });

    document.getElementById('cancel-button').addEventListener('click', () => {
        document.getElementById('modal-selesai').classList.remove('show');
        document.getElementById('modal-overlay').classList.remove('show');
    });

    document.getElementById('modal-overlay').addEventListener('click', () => {
        document.getElementById('modal-selesai').classList.remove('show');
        document.getElementById('modal-overlay').classList.remove('show');
    });



        document.querySelectorAll('[data-modal-toggle]').forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-target');
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
        });
    });

    document.querySelectorAll('[data-modal-hide]').forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-hide');
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
        });
    });
    </script>
    
</x-app-layout>
