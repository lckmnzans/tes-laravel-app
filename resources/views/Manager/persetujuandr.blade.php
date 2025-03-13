<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Persetujuan Permintaan Pesanan') }}
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

            <div class="p-6">
                <!-- Tab Navigation -->
                <ul class="flex border-b tab-navigation">
                    <li class="mr-1">
                        <a href="#pending" class="bg-white inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold" data-tab="pending">Permintaan</a>
                    </li>
                    <li class="mr-1">
                        <a href="#approved" class="bg-white inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold" data-tab="approved">Riwayat</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div id="pending" class="tab-content">
                    <table class="min-w-full bg-white border border-gray-300 rounded-md text-sm text-gray-700">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2 text-center">No Permintaan</th>
                                <th class="border px-4 py-2 text-center">Tanggal</th>
                                <th class="border px-4 py-2 text-center">Customer</th>
                                <th class="border px-4 py-2 text-center">Produk</th>
                                <th class="border px-4 py-2 text-center">Jumlah</th>
                                <th class="border px-4 py-2 text-center">Total Harga</th>
                                <th class="border px-4 py-2 text-center">Status Permintaan</th>
                                <th class="border px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deliveryRequests as $deliveryRequest)
                                <tr class="hover:bg-gray-50">
                                    <td class="border px-4 py-2 text-center">{{ $deliveryRequest->no_dr }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        {{ $deliveryRequest->created_at->format('d-m-Y') }}
                                    </td>
                                    <td class="border px-4 py-2 text-center">{{ $deliveryRequest->pelanggan->nama_customer }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        @foreach($deliveryRequest->product as $product)
                                            {{ $product->produk }}<br>
                                        @endforeach
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        @foreach($deliveryRequest->product as $product)
                                            {{ $product->pivot->quantity }}<br>
                                        @endforeach
                                    </td>
                                    <td class="border px-4 py-2 text-center">Rp {{ number_format($deliveryRequest->total_harga, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        @if($deliveryRequest->status_dr === 'tertunda')
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-md">Tertunda</span>
                                        @elseif($deliveryRequest->status_dr === 'disetujui')
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-md">Disetujui</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-md">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        @if(auth()->user()->role === 'manager' && $deliveryRequest->status_dr === 'tertunda')
                                            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md"
                                                    data-modal-toggle="approveModal"
                                                    data-id="{{ $deliveryRequest->id }}"
                                                    data-no_dr="{{ $deliveryRequest->no_dr }}"
                                                    data-customer="{{ $deliveryRequest->pelanggan->nama_customer }}"
                                                    data-total_harga="{{ $deliveryRequest->total_harga }}"
                                                    data-products='@json($deliveryRequest->product->map(function ($product) {
                                                        return [
                                                            "name" => $product->produk,
                                                            "quantity" => $product->pivot->quantity,
                                                        ];
                                                    }))'>
                                                Setujui
                                            </button>
                                        @elseif($deliveryRequest->status_dr === 'disetujui')
                                            <span class="text-gray-500 italic">Sudah disetujui</span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="approved" class="tab-content hidden">
                    <table class="min-w-full bg-white border border-gray-300 rounded-md text-sm text-gray-700">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2 text-center">No Permintaan</th>
                                <th class="border px-4 py-2 text-center">Tanggal</th>
                                <th class="border px-4 py-2 text-center">Customer</th>
                                <th class="border px-4 py-2 text-center">Produk</th>
                                <th class="border px-4 py-2 text-center">Jumlah</th>
                                <th class="border px-4 py-2 text-center">Total Harga</th>
                                <th class="border px-4 py-2 text-center">Status Permintaan</th>
                                <th class="border px-4 py-2 text-center">Status Pesanan</th>
                                <th class="border px-4 py-2 text-center">Status Invoice</th>
                                <th class="border px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($approvedRequests as $deliveryRequest)
                                <tr>
                                    <td class="border px-4 py-2 text-center">{{ $deliveryRequest->no_dr }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        {{ $deliveryRequest->created_at->format('d-m-Y') }}
                                    </td>
                                    <td class="border px-4 py-2 text-center">{{ $deliveryRequest->pelanggan->nama_customer }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        @foreach($deliveryRequest->product as $product)
                                            {{ $product->produk }}<br>
                                        @endforeach
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        @foreach($deliveryRequest->product as $product)
                                            {{ $product->pivot->quantity }}<br>
                                        @endforeach
                                    </td>
                                    <td class="border px-4 py-2 text-center">Rp {{ number_format($deliveryRequest->total_harga, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-green-500">Disetujui</td>
                                    <td class="border px-4 py-2">
                                        @if($deliveryRequest->status_po === 'tertunda')
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-md">Tertunda</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-md">Sudah Dibuat</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if($deliveryRequest->status_invoice === 'tertunda')
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-md">Tertunda</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-md">Sudah Dibuat</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if($deliveryRequest->purchaseOrder)
                                        <a href="{{ route('exim.deliveryrequest.show', $deliveryRequest->purchaseOrder->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md shadow-md">
                                            Lihat Invoice
                                        </a>
                                        @else
                                            <span class="text-gray-500 italic">PO Belum Dibuat</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Tidak ada DR yang disetujui.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
    <!-- Modal Pop-up -->
    <div id="approveModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl">
            <div class="modal-header font-semibold text-xl mb-4">Konfirmasi Persetujuan</div>
            <div id="modalContent" class="modal-body text-gray-700 text-sm">
                <!-- Konten modal akan diisi oleh JavaScript -->
            </div>

            <!-- Proses Produksi yang Sedang Berlangsung -->
            <div class="flex space-x-6 mt-4">
                <!-- Proses Produksi -->
                <div class="flex-1">
                    <h3 class="font-semibold text-sm text-gray-600 mb-2">Proses Produksi</h3>
                    <table class="min-w-full table-auto bg-white border border-gray-200 rounded-md text-xs text-gray-700">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-3 py-2 text-center">Proses</th>
                                <th class="border px-3 py-2 text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalData = 0;
                            @endphp
                            @foreach(['1', '0', '2', '3', '4', '5'] as $proses)
                                @if ($proses != '6')
                                    @php
                                        $count = $productionData[$proses]->count ?? 0;
                                        $totalData += $count;
                                    @endphp
                                    <tr>
                                        <td class="border px-3 py-2 text-center">{{ $statusMap[$proses] ?? 'Unknown Process' }}</td>
                                        <td class="border px-3 py-2 text-center">{{ $count }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            
                <!-- Jumlah Pesanan dan Jumlah Proses Produksi -->
                <div class="flex-1 flex flex-col justify-between">
                    <!-- Jumlah Pesanan yang Belum Dijadwalkan -->
                    <div class="mb-4"></div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-700">Jumlah Pesanan yang Belum Dijadwalkan:</p>
                        <p class="text-lg font-semibold 
                            {{ $totalPurchaseOrders == $totalData ? 'text-red-500' : 'text-blue-500' }} 
                            cursor-pointer hover:text-blue-600">
                            <strong>{{ $totalPurchaseOrders }}</strong>
                        </p>
                        @if($totalPurchaseOrders == $totalData)
                        <p class="text-red-500 text-xs mt-1">
                            <strong class="font-bold">Warning:</strong> Pesanan yang Belum Dijadwalkan sama dengan Jumlah Proses Produksi!
                        </p>
                        @endif
                    </div>
                    <!-- Jumlah Proses Produksi yang Sedang Berlangsung -->
                    <div>
                        <p class="text-sm text-gray-700">Jumlah Proses Produksi yang Sedang Berlangsung:</p>
                        <p class="text-lg font-semibold
                        {{$totalData >= $dailyProductionLimit ? 'text-red-500' : 'text-blue-500' }} 
                        cursor-pointer hover:text-blue-600">
                            <strong>{{ $totalData }}</strong>
                        </p>
                        @if($totalData >= $dailyProductionLimit)
                            <p class="text-red-500 text-xs mt-1">
                                <strong class="font-bold">Warning:</strong> Slot produksi telah penuh
                            </p>
                        @endif
                    </div>
                    <div class="mb-4"></div>
                </div>
            </div>
            
            
            <!-- Modal Footer -->
            <div class="modal-footer flex justify-end mt-4">
                <button type="button" onclick="closeModal()" class="btn btn-secondary bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md mr-2">
                    Tutup
                </button>
                <!--<button type="button" 
                        id="rejectBtn" 
                        class="btn btn-danger bg-red-400 hover:bg-red-500 text-white px-4 py-2 rounded-md mr-2">
                    Tolak
                </button>-->
                <button type="button" 
                        id="approveBtn" 
                        class="btn btn-primary 
                            {{ ($totalPurchaseOrders == $totalData || $totalData >= $dailyProductionLimit) ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-500 hover:bg-blue-600' }} 
                            text-white px-4 py-2 rounded-md">
                    {{ ($totalPurchaseOrders == $totalData || $totalData >= $dailyProductionLimit) ? 'Tetap Lanjutkan' : 'Setujui' }}
                </button>
            </div>
        </div>
    </div>
    
    <style>
        #approveModal {
            display: none; /* Awalnya tersembunyi */
            position: fixed;
            inset: 0;
            z-index: 50;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
    
        #approveModal.active {
            display: flex; /* Tampilkan modal */
            visibility: visible;
            opacity: 1;
        }
    
        .modal-content {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            padding: 1.5rem;
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }
    
        #approveModal.active .modal-content {
            transform: scale(1);
        }
    
        .modal-header {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #333;
        }
    
        .modal-body {
            margin-bottom: 1.5rem;
            color: #555;
        }
    
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
    
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-weight: 500;
            text-transform: capitalize;
            transition: background-color 0.3s ease;
        }
    
        .btn-primary {
            background-color: #3490dc;
            color: #fff;
        }
    
        .btn-primary:hover {
            background-color: #2779bd;
        }
    
        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }
    
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        </style>
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('approveModal');
    const modalContent = document.getElementById('modalContent');
    const approveBtn = document.getElementById('approveBtn');

    // Fungsi untuk menghilangkan notifikasi setelah 5 detik
    setTimeout(() => {
        ['notification-success', 'notification-error', 'notification-validation-error'].forEach(notificationId => {
            const notification = document.getElementById(notificationId);
            if (notification) {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }
        });
    }, 5000);

    // Event untuk membuka modal pada Permintaan RO
    document.querySelectorAll('[data-modal-toggle="approveModal"]').forEach(button => {
        button.addEventListener('click', function () {
            const noDr = this.getAttribute('data-no_dr');
            const customer = this.getAttribute('data-customer');
            const totalHarga = this.getAttribute('data-total_harga');
            const products = JSON.parse(this.getAttribute('data-products'));
            const deliveryRequestId = this.getAttribute('data-id');
            // Buat baris tabel untuk setiap produk
            let productRows = '';
products.forEach(product => {
    productRows += `
        <tr class="hover:bg-gray-50 transition-all">
            <td class="border px-3 py-2 text-sm">${product.name}</td>
            <td class="border px-3 py-2 text-sm text-center">${product.quantity}</td>
        </tr>
    `;
});

// Isi konten modal dengan tabel
modalContent.innerHTML = `
    <div class="flex space-x-6">
        <!-- Detail Tabel -->
        <div class="flex-1">
            <table class="min-w-full bg-white border border-gray-300 rounded-md text-xs text-gray-700 mb-3">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Detail</th>
                        <th class="border px-4 py-2 text-left">Informasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">No DR:</td>
                        <td class="border px-4 py-2">${noDr}</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Customer:</td>
                        <td class="border px-4 py-2">${customer}</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Total Harga:</td>
                        <td class="border px-4 py-2">Rp ${new Intl.NumberFormat().format(totalHarga)}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Daftar Produk Tabel -->
            <h3 class="font-semibold text-sm mb-2">Daftar Produk</h3>
            <table class="min-w-full bg-white border border-gray-300 rounded-md text-xs text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Nama Produk</th>
                        <th class="border px-4 py-2 text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    ${productRows}
                </tbody>
            </table>
        </div>
    </div>
`;


            // Isi ID DR ke tombol persetujuan
            approveBtn.setAttribute('data-id', deliveryRequestId);

            // Tampilkan modal
            modal.classList.add('active');
        });
    });

    // Fungsi untuk menutup modal
    window.closeModal = function () {
        modal.classList.remove('active');
    };

    // Event untuk tombol Setujui
    approveBtn.addEventListener('click', function () {
        const deliveryRequestId = this.getAttribute('data-id');

        // Membuat form untuk persetujuan
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/delivery-request/${deliveryRequestId}/approve`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    });

    // Logic untuk navigasi antar tab
    const tabs = document.querySelectorAll('.tab-navigation a');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();

            // Reset semua tab dan konten
            tabs.forEach(t => t.classList.remove('bg-blue-500', 'text-white'));
            tabContents.forEach(content => content.classList.add('hidden'));

            // Aktifkan tab yang dipilih
            this.classList.add('bg-blue-500', 'text-black');
            const target = document.querySelector(this.getAttribute('href'));
            target.classList.remove('hidden');
        });
    });

    // Set tab default saat halaman pertama kali dimuat
    const defaultTab = window.location.hash ? document.querySelector(`[href="${window.location.hash}"]`) : tabs[0];
    if (defaultTab) defaultTab.click();
});
            </script>
            
</x-app-layout>
