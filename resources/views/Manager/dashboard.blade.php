@extends('layout.dashboard')

@section('content')
<div class="grid gap-5 lg:grid-cols-2 md:grid-cols-1">
    <!-- Revenue Chart -->
    <div class="panel h-full bg-white dark:bg-dark rounded-lg shadow-md p-5">
        <div class="mb-5 flex items-center dark:text-white-light">
            <h5 class="text-lg font-semibold">Pesanan</h5>
        </div>
        <div id="revenueChart" class="overflow-hidden">
            <div class="grid min-h-[360px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                <span class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
            </div>
        </div>
    </div>

    <!-- Production Process Summary -->
    <div class="panel h-full bg-white dark:bg-dark rounded-lg shadow-md p-5">
        <div class="mb-5 flex items-center dark:text-white-light">
            <h5 class="text-lg font-semibold">Proses Produksi</h5>
        </div>
        <div class="space-y-9">
            @foreach($processData as $process)
            <div class="flex items-center">
                <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                    <div class="grid h-9 w-9 place-content-center rounded-full bg-success-light text-success dark:bg-success dark:text-success-light">
                        <svg width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="..." stroke="currentColor" stroke-width="1.5"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="mb-2 flex font-semibold text-white-dark">
                        <h6>{{ $process['stage'] }}</h6>
                        <p class="ltr:ml-auto rtl:mr-auto">{{ $process['percentage'] }}% ({{ $process['count'] }} items)</p>
                    </div>
                    <div class="h-2 rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#3cba92] to-[#0ba360]" style="width: {{ $process['percentage'] }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Stok Bahan Baku -->
    <div class="panel h-full col-span-2 bg-white dark:bg-dark rounded-lg shadow-md p-5">
        <div class="mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 dark:border-[#1b2e4b] dark:text-white-light">
            <h5 class="text-lg font-semibold">Stok Bahan Baku</h5>
        </div>
        <div id="bahanBakuChart" class="overflow-hidden">
            <div class="grid min-h-[360px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                <span class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Fungsi untuk mengambil data bahan baku
        async function fetchBahanBakuData() {
            try {
                const response = await fetch('/bahan-baku/data');
                if (!response.ok) throw new Error('Gagal mengambil data');
                return response.json();
            } catch (error) {
                console.error('Error fetching bahan baku data:', error);
                return [];
            }
        }

        // Fungsi untuk membuat grafik bahan baku
        async function renderBahanBakuChart() {
            const data = await fetchBahanBakuData();
            if (!data.length) {
                document.querySelector("#bahanBakuChart").innerHTML = '<p class="text-center text-red-500">Data bahan baku tidak tersedia.</p>';
                return;
            }

            const categories = data.map(item => item.namaBahan);
            const stokSaatIni = data.map(item => item.stokBahan);
            const stokMinimum = data.map(item => item.stok_minimum);

            const options = {
                series: [
                    { name: 'Stok Saat Ini', data: stokSaatIni },
                    { name: 'Stok Minimum', data: stokMinimum },
                ],
                chart: { type: 'bar', height: 360 },
                xaxis: { categories },
                colors: ['#ffbb44', '#5c1ac3'],
                plotOptions: {
                    bar: {
                        columnWidth: '55%',
                        borderRadius: 10,
                    },
                },
                tooltip: { y: { formatter: val => `${val} unit` } },
                legend: { position: 'bottom', horizontalAlign: 'center' },
            };

            const chart = new ApexCharts(document.querySelector("#bahanBakuChart"), options);
            chart.render();
        }

        // Fungsi untuk membuat grafik revenue
        async function renderRevenueChart() {
    const response = await fetch('/api/dr-po-data');
    const chartData = await response.json();
    const options = {
        series: [
            { name: 'Permintaan Pesanan', data: chartData.data.map(item => item.total_dr) },
            { name: 'Pesanan', data: chartData.data.map(item => item.total_po) },
        ],
        chart: {
            type: 'line',
            height: 360,
            toolbar: {
                show: false,  // Menonaktifkan toolbar
            }
        },
        xaxis: { categories: chartData.data.map(item => item.tanggal) },
    };

    const chart = new ApexCharts(document.querySelector("#revenueChart"), options);
    chart.render();
}


        // Panggil semua fungsi render grafik
        renderBahanBakuChart();
        renderRevenueChart();
    });
</script>
@endsection
