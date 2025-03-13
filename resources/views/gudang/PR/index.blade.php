<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Permintaan Bahan Baku') }}
        </h2>
    </x-slot>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="animate__animated p-1" :class="[$store.app.animation]">
        <!-- Pilihan Supplier -->
        <div class="flex w-full flex-col gap-4 sm:w-auto sm:flex-row sm:items-center sm:gap-3">
            <div class="flex gap-3">
                <div class="mb-4">
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Pilih Supplier:</label>
                    <select id="supplier" name="supplier" class="form-control block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" onchange="filterBySupplier()">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama_perusahaan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    

        <!-- Form Pembuatan POBB -->
        <form id="createPobbForm" method="GET" action="{{ route('purchasing.pobb.create') }}">
            <input type="hidden" name="supplier" value="{{ request('supplier') }}">
            <input type="hidden" id="selectedRequests" name="selected_requests" value="">

            <div class="panel mt-5 overflow-hidden border-0 p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" onclick="toggleCheckboxes(this)" {{ request('supplier') ? '' : 'disabled' }}>
                                </th>
                                <th>No</th>
                                <th>Kode Bahan</th>
                                <th>Nama Bahan</th>
                                <th>Jumlah Permintaan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseRequests as $key => $pr)
                            <tr>
                                <td>
                                    @php
                                        $hasValidContract = $pr->bahanBaku->contracts->contains('supplier_id', request('supplier'));
                                    @endphp
                                    @if ($pr->status != 'selesai' && $hasValidContract)
                                        <input type="checkbox" name="selected_requests[]" value="{{ $pr->id }}" class="checkbox-item" data-supplier="{{ request('supplier') }}">
                                    @endif
                                </td>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $pr->bahanBaku->kodeBahan }}</td>
                                <td>
                                    {{ $pr->bahanBaku->namaBahan }}
                                    @if ($pr->bahanBaku->contracts->isNotEmpty())
                                        <br>
                                        <small class="text-muted">
                                           
                                            <ul>
                                                @foreach($pr->bahanBaku->contracts as $contract)
                                                    <li>
                                                        Supplier {{ $contract->supplier->nama_perusahaan }} - Harga: {{ number_format($contract->pivot->harga_per_unit, 2) }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                            
                                        </small>
                                    @else
                                        <br>
                                        <small class="text-danger">(Tidak ada kontrak)</small>
                                    @endif
                                </td>
                                
                                <td>{{ $pr->jumlah }}</td>
                                <td>
                                    @if ($pr->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif ($pr->status == 'sudah')
                                        <span class="badge bg-primary">Pending</span>
                                    @else
                                        <span class="badge bg-warning">Belum Diajukan</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tombol Buat POBB -->
                <div class="mt-3">
                    @if(request('supplier'))  
                        <button type="submit" class="btn btn-primary" id="createPobbBtn" disabled>
                            Input Pesanan Bahan Baku
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        function filterBySupplier() {
            let supplierId = document.getElementById('supplier').value;
            let url = new URL(window.location.href);
            url.searchParams.set('supplier', supplierId);
            window.location.href = url.toString();
        }

        function toggleCheckboxes(source) {
            let checkboxes = document.querySelectorAll('.checkbox-item');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
            updateSelectedRequests();
        }

        function updateSelectedRequests() {
            let checkedBoxes = document.querySelectorAll('.checkbox-item:checked');
            let selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
            document.getElementById('selectedRequests').value = selectedIds.join(',');

            toggleSubmitButton();
        }

        function toggleSubmitButton() {
            let checkedBoxes = document.querySelectorAll('.checkbox-item:checked');
            let selectedSupplier = document.querySelector('input[name="supplier"]').value;

            console.log("Checked PRs:", checkedBoxes.length); // Debugging
            console.log("Selected Supplier:", selectedSupplier); // Debugging

            document.getElementById('createPobbBtn').disabled = checkedBoxes.length === 0;
        }

        document.querySelectorAll('.checkbox-item').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedRequests);
        });
    </script>
    <style>
        #supplier {
            width: 100%;  /* Menyesuaikan lebar elemen select */
            padding-right: 2.5rem; /* Memberikan ruang tambahan untuk simbol dropdown */
        }
    </style>
    

</x-app-layout>
