<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan') }}
        </h2>
    </x-slot>
        
                <!-- Komponen Halaman -->
                <div class="mb-6">
                    <table class="w-full border-collapse border border-gray-200">
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left">Nomor Pesanan</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">{{ $purchaseOrder->kode_po ?? '-' }}</th>
                        </tr>
                        
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">Nama Pelanggan</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $purchaseOrder->deliveryRequest->pelanggan->nama_customer ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">Produk</td>
                            <td class="border border-gray-300 px-4 py-2">
                                @foreach($purchaseOrder->deliveryRequest->product as $product)
                                {{ $product->produk }} ({{ $product->pivot->quantity }}),
                            @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">Jumlah Produk</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $purchaseOrder->deliveryRequest->product->sum('pivot.quantity') }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">Total Harga Barang</td>
                            <td class="border border-gray-300 px-4 py-2">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">Pajak (11%)</td>
                            <td class="border border-gray-300 px-4 py-2">Rp {{ number_format($taxAmount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">Total dengan Pajak</td>
                            <td class="border border-gray-300 px-4 py-2">Rp {{ number_format($totalWithTax, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">Sisa Tagihan</td>
                            <td class="border border-gray-300 px-4 py-2">
                                @if($invoice->status === 'lunas')
                                    Lunas
                                @elseif($invoice->payments->isEmpty())
                                    Rp {{ number_format($totalWithTax, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($totalWithTax - $invoice->payments->sum('amount'), 0, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">Tanggal Terbit Invoice</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $invoice->created_at->format('d-m-Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">Status Pembayaran</td>
                            <td class="border border-gray-300 px-4 py-2">
                                @if($invoice)
                                    {{ ucwords($invoice->status) ?? 'Belum Dibuat' }}
                                @else
                                    <span class="text-uppercase">Belum Dibuat</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Tabel Riwayat Pembayaran -->
                <div class="mb-6">
                    <h2 class="font-semibold text-lg mb-2">Tabel Riwayat Pembayaran:</h2>
                    <table class="w-full border-collapse border border-gray-200">
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2">Tanggal Pembayaran</th>
                            <th class="border border-gray-300 px-4 py-2">Jumlah Dibayar</th>
                            <th class="border border-gray-300 px-4 py-2">Metode Pembayaran</th>
                            <th class="border border-gray-300 px-4 py-2">Keterangan</th>
                        </tr>
                        @foreach($invoice->payments ?? [] as $payment)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $payment->created_at->format('d-m-Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $payment->method }}</td>
                            <td class="border border-gray-300 px-4 py-2 relative">
                                <span>{{ $payment->description }}</span>
                                <button class="absolute right-2 top-2 text-gray-500 hover:text-gray-700" onclick="toggleDropdown('dropdown-{{ $payment->id }}')">
                                    ⋮
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown-{{ $payment->id }}" class="dropdown-menu hidden absolute bg-white border rounded shadow-md right-0">
                                    <button class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-200" 
                                            onclick="openEditModal({{ $payment->id }}, '{{ $payment->amount }}', '{{ $payment->method }}', '{{ $payment->description }}')">
                                        Edit
                                    </button>
                                    <button class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-200" 
                                            onclick="openDetailModal('{{ asset('storage/' . $payment->dokumen) }}')">
                                        Bukti Pembayaran
                                    </button>
                                    <a href="{{ route('payments.downloadReceipt', $payment->id) }}" 
                                       class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-200 text-blue-500">
                                        Unduh Kwitansi
                                    </a>
                                    <form method="POST" action="{{ route('payments.delete', $payment->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-200 text-red-500">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                            
                        </tr>
                        @endforeach
                    </table>
                </div>

                <!-- Tombol -->
                <div class="flex justify-between mt-6">
                    <!-- Tombol Perbarui Status Pembayaran -->
                    <button onclick="openModal()" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Perbarui Status Pembayaran
                    </button>

                    <!-- Tombol Cetak Invoice -->
                    @if($invoice->status !== 'lunas')
                    <a href="{{ route('deliveryrequest.invoice', $invoice->id ?? 0) }}" 
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Cetak Invoice
                    </a>
                    @endif

                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pop-Up -->
    <div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">Perbarui Riwayat Pembayaran</h2>
    
            <form method="POST" action="{{ route('invoices.payInvoice', $invoice->id ?? 0) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">Tanggal Pembayaran</label>
                    <input type="date" name="payment_date" class="w-full border border-gray-300 rounded px-2 py-1" required>
                </div>
    
                <div class="mb-4">
                    <label class="block text-gray-700">Jumlah Dibayar</label>
                    <input type="number" name="amount" class="w-full border border-gray-300 rounded px-2 py-1" required>
                </div>
    
                <div class="mb-4">
                    <label class="block text-gray-700">Metode Pembayaran</label>
                    <select name="method" class="w-full border border-gray-300 rounded px-2 py-1" required>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Cash">Cash</option>
                        <option value="Kartu Kredit">Kartu Kredit</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="dokumen" class="block text-gray-700 font-semibold mb-2">Unggah Bukti Pembayaran</label>
                    <input type="file" name="dokumen" id="dokumen" class="w-full border border-gray-300 rounded px-3 py-2" accept=".jpeg,.png,.pdf" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Batal
                    </button>
                    <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Edit Pembayaran</h2>
        <form method="POST" action="{{ route('payments.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="payment_id" id="editPaymentId">
            <div class="mb-4">
                <label class="block text-gray-700">Jumlah Dibayar</label>
                <input type="number" name="amount" id="editAmount" class="w-full border border-gray-300 rounded px-2 py-1" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Metode Pembayaran</label>
                <select name="method" id="editMethod" class="w-full border border-gray-300 rounded px-2 py-1" required>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="Cash">Cash</option>
                    <option value="Kartu Kredit">Kartu Kredit</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Keterangan</label>
                <input type="text" name="description" id="editDescription" class="w-full border border-gray-300 rounded px-2 py-1" required>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Batal</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Detail Bukti Bayar</h2>
        <iframe id="detailFrame" class="w-full h-64 border rounded"></iframe>
        <div class="flex justify-end mt-4">
            <button type="button" onclick="closeDetailModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Tutup</button>
        </div>
    </div>
</div>

    
<script>
    // Modal Perbarui Pembayaran
    function openModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    document.querySelector('input[name="amount"]').addEventListener('input', function () {
        const remainingAmount = {{ session('remainingAmount', $totalWithTax) }};
        if (this.value > remainingAmount) {
            alert('Jumlah pembayaran tidak boleh melebihi sisa tagihan!');
            this.value = remainingAmount;
        }
    });

    function closeModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    // Dropdown dan Modal Tambahan untuk Edit dan Detail Bukti Bayar
    // Global variable to track the currently open dropdown
let currentDropdown = null;

// Function to toggle the dropdown menu
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);

    // If another dropdown is open, close it first
    if (currentDropdown && currentDropdown !== dropdown) {
        currentDropdown.classList.add('hidden');
    }

    // Toggle the current dropdown
    dropdown.classList.toggle('hidden');

    // Update the current dropdown reference
    currentDropdown = dropdown.classList.contains('hidden') ? null : dropdown;
}

// Event listener to close the dropdown when clicking outside
document.addEventListener('click', function (event) {
    // Check if the click is inside the currently open dropdown or its button
    if (currentDropdown && !event.target.closest('.relative')) {
        currentDropdown.classList.add('hidden');
        currentDropdown = null;
    }
});

// Prevent the dropdown from closing when clicking on the button itself
document.querySelectorAll('.relative button').forEach(button => {
    button.addEventListener('click', function (event) {
        event.stopPropagation(); // Prevent the event from propagating to the document
    });
});

// Modal Edit
function openEditModal(id, amount, method, description) {
    document.getElementById('editPaymentId').value = id;
    document.getElementById('editAmount').value = amount;
    document.getElementById('editMethod').value = method;
    document.getElementById('editDescription').value = description;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Modal Detail
function openDetailModal(url) {
    document.getElementById('detailFrame').src = url;
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}
 
</script>
<style>
    /* Dropdown menu styling */
    .dropdown-menu {
    display: none;
    position: absolute;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 5px;
    z-index: 1000;
    right: 0;
    top: 100%;
    width: 200px;
}

.dropdown-menu.hidden {
    display: none;
}

.dropdown-menu:not(.hidden) {
    display: block;
}

</style>

</x-app-layout>
