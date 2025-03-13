@extends('layout.dashboard')

@section('content')
<div class="panel h-full xl:col-span-2" x-data="sales">
    <div class="mb-5 flex items-center dark:text-white-light">
        <h5 class="text-lg font-semibold">Purchase Order</h5>
        <div x-data="{ open: false }" @click.outside="open = false" class="dropdown ltr:ml-auto rtl:mr-auto">
            <a href="javascript:;" @click="open = !open">
                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                </svg>
            </a>
            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="ltr:right-0 rtl:left-0">
                <li><a href="javascript:;" @click="open = false">Weekly</a></li>
                <li><a href="javascript:;" @click="open = false">Monthly</a></li>
                <li><a href="javascript:;" @click="open = false">Yearly</a></li>
            </ul>
        </div>
    </div>
    <p class="text-lg dark:text-white-light/90"> <span class="ml-2 text-primary"></span></p>
    <div class="relative overflow-hidden">
        <div x-ref="revenueChart" class="rounded-lg bg-white dark:bg-black">
            <!-- loader -->
            <div class="grid min-h-[325px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                <span class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sales', () => ({
            revenueChart: null,

            init() {
                const isDark = this.$store.app.theme === 'dark' || this.$store.app.isDarkMode;
                const isRtl = this.$store.app.rtlClass === 'rtl';

                // Fetch data for DR and PO
                this.fetchChartData().then((chartData) => {
                    // Render revenue chart
                    this.revenueChart = new ApexCharts(this.$refs.revenueChart, this.revenueChartOptions(chartData, isDark, isRtl));
                    this.$refs.revenueChart.innerHTML = '';
                    this.revenueChart.render();
                });

                // Watch for theme or RTL changes
                this.$watch('$store.app.theme', (theme) => {
                    const isDark = theme === 'dark' || this.$store.app.isDarkMode;
                    this.revenueChart.updateOptions(this.revenueChartOptions(chartData, isDark, isRtl));
                });

                this.$watch('$store.app.rtlClass', (rtlClass) => {
                    const isRtl = rtlClass === 'rtl';
                    this.revenueChart.updateOptions(this.revenueChartOptions(chartData, isDark, isRtl));
                });
            },

            async fetchChartData() {
                const response = await fetch('/api/dr-po-data'); // Adjust the API endpoint
                const data = await response.json();
                return data.data; // Assuming the data is under "data" key
            },

            revenueChartOptions(chartData, isDark, isRtl) {
                return {
                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: {
                            show: false,
                        },
                        background: isDark ? '#1f2937' : '#ffffff',
                        foreColor: isDark ? '#f9fafb' : '#374151',
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800,
                        },
                        rtl: isRtl,
                    },
                    series: [
                        {
                            name: 'Request Order',
                            data: chartData.map((item) => item.total_dr), // DR data
                        },
                        {
                            name: 'Purchase Order',
                            data: chartData.map((item) => item.total_po), // PO data
                        },
                    ],
                    xaxis: {
                        categories: chartData.map((item) => item.tanggal), // Months
                        axisBorder: {
                            show: true,
                            color: isDark ? '#52525b' : '#d1d5db',
                        },
                    },
                    yaxis: {
                        labels: {
                            formatter: (value) => `${value}`, // Just display the number
                        },
                    },
                };
            },
        }));
    });

</script>

@endsection
