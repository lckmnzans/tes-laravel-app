<x-app-layout> 
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h3 class="text-2x1 font-semibold text-gray-800">Daftar Permintaan Pesanan</h3>
            <div class="flex space-x-4">
                <a href="{{ route('exim.deliveryrequest.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-xm text-white font-medium px-4 py-2 rounded-md shadow-md transition">

                    <i class="fa fa-plus mr-1"></i> Tambah Permintaan
                </a>
            </div>
        </div>
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
        <!-- Tabel Delivery Request -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-md text-sm text-gray-700">
                <thead class="bg-gray-100 text-gray-800 uppercase">
                    <tr>
                        <th class="border px-4 py-2 text-center">No Permintaan</th>
                        <th class="border px-4 py-2 text-center">Tanggal</th>
                        <th class="border px-4 py-2 text-center">Customer</th>
                        <th class="border px-4 py-2 text-center">Produk</th>
                        <th class="border px-4 py-2 text-center">Jumlah</th>
                        <th class="border px-4 py-2 text-center">Total Harga</th>
                        <th class="border px-4 py-2 text-center">Status DR</th>
                        <th class="border px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryRequests as $deliveryRequest)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2 text-center">{{ $deliveryRequest->no_dr }}</td>
                            <td class="border px-4 py-2 text-xs text-center">
                                {{ $deliveryRequest->created_at->format('d F Y') }}
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
                            <td class="border px-4 py-2 text-center">Rp {{ number_format($deliveryRequest->total_harga, 2, ',', '.') }}</td>
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
                                @if($deliveryRequest->status_dr === 'tertunda')
                                    <span class="bg-yellow-100 text-xs text-yellow-800 px-2 py-1 rounded-md">Menunggu persetujuan</span>
                                @elseif($deliveryRequest->status_dr === 'disetujui')
                                    @if($deliveryRequest->purchaseOrder)
                                        <a href="{{ route('exim.deliveryrequest.show', $deliveryRequest->purchaseOrder->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md shadow-md">
                                            Lihat Invoice
                                        </a>
                                    @else
                                    <button 
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md shadow-md"
                                    onclick="openModal(this)" 
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
                                    Buat Pesanan
                                </button>
                                
                                    @endif
                                    @endif
                            </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">Data tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $deliveryRequests->links() }}
        </div>
    </div>

<!-- Modal Approve DR -->
<div id="approveModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
    <div class="modal-content">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Buat Pesanan</h3>
            <div id="modalContent" class="text-gray-700"></div>
            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md mr-2">
                    Tutup
                </button>
                <button type="button" id="approveBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Buat Pesanan
                </button>
            </div>
        </div>
    </div>
</div>


<style>
        /* Gaya untuk modal */
        #approveModal {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            inset: 0; /* Set modal memenuhi layar */
            background-color: rgba(0, 0, 0, 0.5); /* Latar belakang transparan */
            z-index: 50;
        }
    
        /* Konten modal */
        #approveModal .modal-content {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            padding: 20px;
            animation: fadeIn 0.3s ease-in-out;
        }
    
        /* Animasi modal */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
    
    <!-- JavaScript Modal -->
    <script>
        
    // Fungsi untuk membuka modal
// Fungsi untuk membuka modal
function openModal(button) {
    const modal = document.getElementById('approveModal');
    const noDr = button.getAttribute('data-no_dr');
    const customer = button.getAttribute('data-customer');
    const totalHarga = button.getAttribute('data-total_harga');
    const products = JSON.parse(button.getAttribute('data-products')); // Perbaikan: gunakan `button` untuk mengambil atribut
    const deliveryRequestId = button.getAttribute('data-id');

    setTimeout(() => {
        ['notification-success', 'notification-error', 'notification-validation-error'].forEach(notificationId => {
            const notification = document.getElementById(notificationId);
            if (notification) {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }
        });
    }, 5000);
    
    // Isi konten modal dengan data dari tombol
    let productsTable = products
        .map(
            (product) =>
                `<tr>
                    <td class="border px-4 py-2">${product.name}</td>
                    <td class="border px-4 py-2 text-center">${product.quantity}</td>
                </tr>`
        )
        .join("");

    document.getElementById('modalContent').innerHTML = `
        <table class="min-w-full bg-white border border-gray-300 rounded-md text-sm text-gray-700">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">Detail</th>
                    <th class="border px-4 py-2">Informasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-4 py-2"><strong>No DR:</strong></td>
                    <td class="border px-4 py-2">${noDr}</td>
                </tr>
                <tr>
                    <td class="border px-4 py-2"><strong>Customer:</strong></td>
                    <td class="border px-4 py-2">${customer}</td>
                </tr>
                <tr>
                    <td class="border px-4 py-2"><strong>Total Harga:</strong></td>
                    <td class="border px-4 py-2">Rp ${new Intl.NumberFormat().format(totalHarga)}</td>
                </tr>
            </tbody>
        </table>
        <h3 class="font-semibold text-lg mb-2">Daftar Produk</h3>
        <table class="min-w-full bg-white border border-gray-300 rounded-md text-sm text-gray-700">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">Nama Produk</th>
                    <th class="border px-4 py-2 text-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                ${productsTable}
            </tbody>
        </table>
    `;

    // Simpan ID ke tombol "Buat PO"
    document.getElementById('approveBtn').setAttribute('data-id', deliveryRequestId);

    // Tampilkan modal
    modal.classList.remove('hidden');
    modal.style.display = 'flex'; // Pastikan modal muncul di tengah
}

// Fungsi untuk menutup modal
function closeModal() {
    const modal = document.getElementById('approveModal');
    modal.classList.add('hidden');
    modal.style.display = 'none'; // Sembunyikan modal
}

// Fungsi untuk submit permintaan "Buat PO"
document.getElementById('approveBtn').addEventListener('click', function () {
    const deliveryRequestId = this.getAttribute('data-id');

    // Kirim permintaan POST untuk membuat PO
    fetch(`/delivery-request/${deliveryRequestId}/create-po`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal membuat PO dan Invoice');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                // Perbarui kolom aksi di tabel
                const row = document.querySelector(`[data-id="${deliveryRequestId}"]`).closest('tr');
                const actionCell = row.querySelector('td:last-child');

                actionCell.innerHTML = `
                    <a href="${data.data.invoice_url}"
                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md shadow-md">
                       Lihat Invoice
                    </a>
                `;

                // Tutup modal
                closeModal();

                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membuat PO dan Invoice.');
        });
});

// Pastikan modal tidak muncul langsung saat halaman dimuat
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('approveModal');
    if (modal) {
        modal.classList.add('hidden'); // Sembunyikan modal jika belum digunakan
        modal.style.display = 'none';
    }
});

</script>    </x-app-layout>
