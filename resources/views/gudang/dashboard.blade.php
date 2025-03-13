@extends('layout.dashboard')

@section('content')
<div class="panel h-full p-0 lg:col-span-2">
    <div class="mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 dark:border-[#1b2e4b] dark:text-white-light">
        <h5 class="text-lg font-semibold">Stok Bahan Baku</h5>
        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
            <a href="javascript:;" @click="toggle">
                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                </svg>
            </a>
        </div>
    </div>

    <!-- Elemen untuk grafik -->
    <div id="bahanBakuChart" class="overflow-hidden">
        <div class="grid min-h-[360px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
            <span class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
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
                console.error(error);
                return [];
            }
        }

        // Fungsi untuk membuat grafik
        async function renderBahanBakuChart() {
            const data = await fetchBahanBakuData();

            // Parsing data untuk grafik
            const categories = data.map(item => item.namaBahan);
            const stokSaatIni = data.map(item => item.stokBahan);
            const stokMinimum = data.map(item => item.stok_minimum);

            const options = {
                series: [
                    {
                        name: 'Stok Saat Ini',
                        data: stokSaatIni,
                    },
                    {
                        name: 'Stok Minimum',
                        data: stokMinimum,
                    },
                ],
                chart: {
                    type: 'bar',
                    height: 360,
                },
                xaxis: {
                    categories: categories,
                },
                colors: ['#ffbb44','#5c1ac3' ],
                plotOptions: {
                    bar: {
                        columnWidth: '55%',
                        borderRadius: 10,
                    },
                },
                tooltip: {
                    y: {
                        formatter: val => `${val} unit`,
                    },
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                },
            };

            // Render grafik
            const chart = new ApexCharts(document.querySelector("#bahanBakuChart"), options);
            chart.render();
        }

        renderBahanBakuChart();
    });
</script>

@endsection
