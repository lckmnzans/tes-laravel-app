
<body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased" :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]">
    <!-- sidebar menu overlay -->
    <div x-cloak="" class="fixed inset-0 z-50 bg-[black]/60 lg:hidden" :class="{'hidden' : !$store.app.sidebar}" @click="$store.app.toggleSidebar()"></div>

<!-- screen loader
<div class="screen_loader animate__animated fixed inset-0 z-[60] grid place-content-center bg-[#fafafa] dark:bg-[#060818]">
    <svg width="64" height="64" viewbox="0 0 135 135" xmlns="http://www.w3.org/2000/svg" fill="#4361ee">
        <path d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
            <animatetransform attributename="transform" type="rotate" from="0 67 67" to="-360 67 67" dur="2.5s" repeatcount="indefinite"></animatetransform>
        </path>
        <path d="M28.19 40.31c6.627 0 12-5.374 12-12 0-6.628-5.373-12-12-12-6.628 0-12 5.372-12 12 0 6.626 5.372 12 12 12zm30.72-19.825c4.686 4.687 12.284 4.687 16.97 0 4.686-4.686 4.686-12.284 0-16.97-4.686-4.687-12.284-4.687-16.97 0-4.687 4.686-4.687 12.284 0 16.97zm35.74 7.705c0 6.627 5.37 12 12 12 6.626 0 12-5.373 12-12 0-6.628-5.374-12-12-12-6.63 0-12 5.372-12 12zm19.822 30.72c-4.686 4.686-4.686 12.284 0 16.97 4.687 4.686 12.285 4.686 16.97 0 4.687-4.686 4.687-12.284 0-16.97-4.685-4.687-12.283-4.687-16.97 0zm-7.704 35.74c-6.627 0-12 5.37-12 12 0 6.626 5.373 12 12 12s12-5.374 12-12c0-6.63-5.373-12-12-12zm-30.72 19.822c-4.686-4.686-12.284-4.686-16.97 0-4.686 4.687-4.686 12.285 0 16.97 4.686 4.687 12.284 4.687 16.97 0 4.687-4.685 4.687-12.283 0-16.97zm-35.74-7.704c0-6.627-5.372-12-12-12-6.626 0-12 5.373-12 12s5.374 12 12 12c6.628 0 12-5.373 12-12zm-19.823-30.72c4.687-4.686 4.687-12.284 0-16.97-4.686-4.686-12.284-4.686-16.97 0-4.687 4.686-4.687 12.284 0 16.97 4.686 4.687 12.284 4.687 16.97 0z">
            <animatetransform attributename="transform" type="rotate" from="0 67 67" to="360 67 67" dur="8s" repeatcount="indefinite"></animatetransform>
        </path>
    </svg>
</div>
<!-- scroll to top button -->
<div class="fixed bottom-6 z-50 ltr:right-6 rtl:left-6" x-data="scrollToTop">
    <template x-if="showTopButton">
        <button type="button" class="btn btn-outline-primary animate-pulse rounded-full bg-[#fafafa] p-2 dark:bg-[#060818] dark:hover:bg-primary" @click="goToTop">
            <svg width="24" height="24" class="h-4 w-4" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M12 20.75C12.4142 20.75 12.75 20.4142 12.75 20L12.75 10.75L11.25 10.75L11.25 20C11.25 20.4142 11.5858 20.75 12 20.75Z" fill="currentColor"></path>
                <path d="M6.00002 10.75C5.69667 10.75 5.4232 10.5673 5.30711 10.287C5.19103 10.0068 5.25519 9.68417 5.46969 9.46967L11.4697 3.46967C11.6103 3.32902 11.8011 3.25 12 3.25C12.1989 3.25 12.3897 3.32902 12.5304 3.46967L18.5304 9.46967C18.7449 9.68417 18.809 10.0068 18.6929 10.287C18.5768 10.5673 18.3034 10.75 18 10.75L6.00002 10.75Z" fill="currentColor"></path>
            </svg>
        </button>
    </template>
</div>
<div class="main-container min-h-screen text-black dark:text-white-dark" :class="[$store.app.navbar]">
    <!-- start sidebar section -->
    <div :class="{'dark text-white-dark' : $store.app.semidark}">
        <nav x-data="sidebar" class="sidebar fixed top-0 bottom-0 z-50 h-full min-h-screen w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
            <div class="h-full bg-white dark:bg-[#0e1726]">
                <div class="flex items-center justify-between px-4 py-3">
                    <a href="index.html" class="main-logo flex shrink-0 items-center">
                        <!--<img class="ml-[5px] w-8 flex-none" src="{{asset('style/assets/images/logo.png')}}" alt="image">-->
                        <span class="align-middle text-2xl font-semibold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline">KATOLEC</span>
                    </a>
                    <!--<a href="javascript:;" class="collapse-icon flex h-8 w-8 items-center rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10" @click="$store.app.toggleSidebar()">
                        <svg class="m-auto h-5 w-5" width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>-->
                </div>
                <ul class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold" x-data="{ activeDropdown: 'dashboard' }">
                    @if($role === 'manager')
                    <li class="menu nav-item">
                        <a href="{{ route('manager.dashboard') }}" class="nav-link group">
                            <div class="flex items-center">
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <ul>
                            <li class="nav-item">
                                <a href="{{ route('manager.persetujuandr') }}" class="group">
                                    <div class="flex items-center">
                                        
                                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Persetujuan Pesanan</span>
                                    </div>
                                </a>
                            </li>
                    <li class="nav-item">
                        <a href="{{ route('auth.register') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Manajemen User</span>
                            </div>
                        </a>
                    </li>
                    <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold text-gray-400 uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Manajemen Produksi</span>
                    </h2>
                    <li class="nav-item">
                        <a href="{{ route('gudang.product.index') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Data Produk</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ppic.production.index') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Proses Produksi</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ppic.production.report') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Laporan Produksi</span>
                            </div>
                        </a>
                    </li>
                    <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold text-gray-400 uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Manajemen Inventori</span>
                    </h2>
                    <li class="nav-item">
                        <ul>
                            <li class="nav-item">
                                <a href="{{ route('gudang.bahanbaku.index') }}" class="group">
                                    <div class="flex items-center">
                                       
                                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Data Bahan Baku</span>
                                    </div>
                                </a>
                            </li>
                    <li class="nav-item">
                        <a href="{{ route('purchasing.pobb.index') }}" class="group">
                            <div class="flex items-center">
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Pesanan Bahan Baku</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('gudang.report') }}" class="group">
                            <div class="flex items-center">
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Laporan Inventori</span>
                            </div>
                        </a>
                    </li>

                    @elseif($role === 'exim')
                    <li class="menu nav-item">
                        <a href="{{ route('exim.dashboard') }}" class="nav-link group">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" fill="currentColor"></path>
                                    <path d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z" fill="currentColor"></path>
                                </svg>
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">DASHBOARD</span>
                            </div>
                        </a>
                    </li>
                    <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-2 px-6 font-bold text-gray-400 uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.0" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Data</span>
                    </h2>
                    <li class="nav-item">
                        <a href="{{ route('exim.pelanggan.index') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Data Pelanggan</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu nav-item">
                        <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'invoice'}" @click="activeDropdown === 'invoice' ? activeDropdown = null : activeDropdown = 'invoice'">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Manajemen Pesanan</span>
                            </div>
                            <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'invoice'}">
                                <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak="" x-show="activeDropdown === 'invoice'" x-collapse="" class="sub-menu text-gray-500">
                            <li>
                                <a href="{{ route('exim.deliveryrequest.index') }}">Permintaan Pesanan</a>
                            </li>
                            <li>
                                <a href="{{ route('exim.purchaseorder.index') }}">Daftar Pesanan</a>
                            </li>
                        </ul>
                    </li>
                    <!--<h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>LAPORAN</span>
                    </h2>
                    <li class="menu nav-item">
                        <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'pages'}" @click="activeDropdown === 'pages' ? activeDropdown = null : activeDropdown = 'pages'">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V10C2 6.22876 2 4.34315 3.17157 3.17157C4.34315 2 6.23869 2 10.0298 2C10.6358 2 11.1214 2 11.53 2.01666C11.5166 2.09659 11.5095 2.17813 11.5092 2.26057L11.5 5.09497C11.4999 6.19207 11.4998 7.16164 11.6049 7.94316C11.7188 8.79028 11.9803 9.63726 12.6716 10.3285C13.3628 11.0198 14.2098 11.2813 15.0569 11.3952C15.8385 11.5003 16.808 11.5002 17.9051 11.5001L18 11.5001H21.9574C22 12.0344 22 12.6901 22 13.5629V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22Z" fill="currentColor"></path>
                                    <path d="M6 13.75C5.58579 13.75 5.25 14.0858 5.25 14.5C5.25 14.9142 5.58579 15.25 6 15.25H14C14.4142 15.25 14.75 14.9142 14.75 14.5C14.75 14.0858 14.4142 13.75 14 13.75H6Z" fill="currentColor"></path>
                                    <path d="M6 17.25C5.58579 17.25 5.25 17.5858 5.25 18C5.25 18.4142 5.58579 18.75 6 18.75H11.5C11.9142 18.75 12.25 18.4142 12.25 18C12.25 17.5858 11.9142 17.25 11.5 17.25H6Z" fill="currentColor"></path>
                                    <path d="M11.5092 2.2601L11.5 5.0945C11.4999 6.1916 11.4998 7.16117 11.6049 7.94269C11.7188 8.78981 11.9803 9.6368 12.6716 10.3281C13.3629 11.0193 14.2098 11.2808 15.057 11.3947C15.8385 11.4998 16.808 11.4997 17.9051 11.4996L21.9574 11.4996C21.9698 11.6552 21.9786 11.821 21.9848 11.9995H22C22 11.732 22 11.5983 21.9901 11.4408C21.9335 10.5463 21.5617 9.52125 21.0315 8.79853C20.9382 8.6713 20.8743 8.59493 20.7467 8.44218C19.9542 7.49359 18.911 6.31193 18 5.49953C17.1892 4.77645 16.0787 3.98536 15.1101 3.3385C14.2781 2.78275 13.862 2.50487 13.2915 2.29834C13.1403 2.24359 12.9408 2.18311 12.7846 2.14466C12.4006 2.05013 12.0268 2.01725 11.5 2.00586L11.5092 2.2601Z" fill="currentColor"></path>
                                </svg>
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Laporan</span>
                            </div>
                            <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'pages'}">
                                <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak="" x-show="activeDropdown === 'Laporan'" x-collapse="" class="sub-menu text-gray-500">
                            <li>
                                <a href="pages-knowledge-base.html">Laporan Harian</a>
                            </li>
                            <li>
                                <a href="pages-faq.html">Laporan Bulanan</a>
                            </li>
                            <li>
                                <a href="pages-faq.html">Laporan Tahunan</a>
                            </li>
                        </ul>
                    </li>-->

                    @elseif($role === 'ppic')
                    <li class="menu nav-item">
                        <a href="{{ route('ppic.dashboard') }}" class="nav-link group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">DASHBOARD</span>
                            </div>
                        </a>
                    </li>
                    <!--<h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Data</span>-->
                    </h2>
                    <li class="nav-item">
                        <a href="{{ route('ppic.permintaanpesanan.index') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Daftar Pesanan</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ppic.production.index') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Manajemen Produksi</span>
                            </div>
                        </a>
                    </li> 
                    <li class="nav-item">
                        <a href="{{ route('ppic.production.report') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Laporan</span>
                            </div>
                        </a>
                    </li> 

                    @elseif($role === 'gudang')
                    <li class="menu nav-item">
                        <a href="{{ route('gudang.dashboard') }}" class="nav-link group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">DASHBOARD</span>
                            </div>
                        </a>
                    </li>
                    <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold text-gray-400 uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Data</span>
                    </h2>
                    <li class="nav-item">
                        <a href="{{ route('gudang.product.index') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Data Produk</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('gudang.bahanbaku.index') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Data Bahan Baku</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('gudang.penerimaan.index') }}" class="group">
                            <div class="flex items-center">
                               
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Penerimaan Bahan Baku</span>
                            </div>
                        </a>
                    </li>   
                    <li class="nav-item">
                        <a href="{{ route('gudang.pengeluaran.index') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Pengeluaran Bahan Baku</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('gudang.report') }}" class="group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Laporan</span>
                            </div>
                        </a>
                    </li>

                    @elseif($role === 'purchasing')
                    <li class="menu nav-item">
                        <a href="{{ route('purchasing.dashboard') }}" class="nav-link group">
                            <div class="flex items-center">
                                
                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">DASHBOARD</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold text-gray-400 uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                            <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Purchasing</span>
                        </h2>
                        <ul>
                            <li class="nav-item">
                                <a href="{{ route('purchasing.supplier.index') }}" class="group">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5" d="M3 10C3 6.22876 3 4.34315 4.17157 3.17157C5.34315 2 7.22876 2 11 2H13C16.7712 2 18.6569 2 19.8284 3.17157C21 4.34315 21 6.22876 21 10V14C21 17.7712 21 19.6569 19.8284 20.8284C18.6569 22 16.7712 22 13 22H11C7.22876 22 5.34315 22 4.17157 20.8284C3 19.6569 3 17.7712 3 14V10Z" fill="currentColor"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.25 12C7.25 11.5858 7.58579 11.25 8 11.25H16C16.4142 11.25 16.75 11.5858 16.75 12C16.75 12.4142 16.4142 12.75 16 12.75H8C7.58579 12.75 7.25 12.4142 7.25 12Z" fill="currentColor"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.25 8C7.25 7.58579 7.58579 7.25 8 7.25H16C16.4142 7.25 16.75 7.58579 16.75 8C16.75 8.41421 16.4142 8.75 16 8.75H8C7.58579 8.75 7.25 8.41421 7.25 8Z" fill="currentColor"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.25 16C7.25 15.5858 7.58579 15.25 8 15.25H13C13.4142 15.25 13.75 15.5858 13.75 16C13.75 16.4142 13.4142 16.75 13 16.75H8C7.58579 16.75 7.25 16.4142 7.25 16Z" fill="currentColor"></path>
                                        </svg>
                                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Data Supplier</span>
                                    </div>
                                </a>
                            </li>
                            <li class="menu nav-item">
                                <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'invoice'}" @click="activeDropdown === 'invoice' ? activeDropdown = null : activeDropdown = 'invoice'">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" fill="currentColor"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 5.25C12.4142 5.25 12.75 5.58579 12.75 6V6.31673C14.3804 6.60867 15.75 7.83361 15.75 9.5C15.75 9.91421 15.4142 10.25 15 10.25C14.5858 10.25 14.25 9.91421 14.25 9.5C14.25 8.82154 13.6859 8.10339 12.75 7.84748V11.3167C14.3804 11.6087 15.75 12.8336 15.75 14.5C15.75 16.1664 14.3804 17.3913 12.75 17.6833V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V17.6833C9.61957 17.3913 8.25 16.1664 8.25 14.5C8.25 14.0858 8.58579 13.75 9 13.75C9.41421 13.75 9.75 14.0858 9.75 14.5C9.75 15.1785 10.3141 15.8966 11.25 16.1525V12.6833C9.61957 12.3913 8.25 11.1664 8.25 9.5C8.25 7.83361 9.61957 6.60867 11.25 6.31673V6C11.25 5.58579 11.5858 5.25 12 5.25ZM11.25 7.84748C10.3141 8.10339 9.75 8.82154 9.75 9.5C9.75 10.1785 10.3141 10.8966 11.25 11.1525V7.84748ZM14.25 14.5C14.25 13.8215 13.6859 13.1034 12.75 12.8475V16.1525C13.6859 15.8966 14.25 15.1785 14.25 14.5Z" fill="currentColor"></path>
                                        </svg>
                                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Pesanan Bahan Baku</span>
                                    </div>
                                    <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'invoice'}">
                                        <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </div>
                                </button>
                                <ul x-cloak="" x-show="activeDropdown === 'invoice'" x-collapse="" class="sub-menu text-gray-500">
                                    <li>
                                        <a href="{{ route('gudang.pr.index') }}">Permintaan Bahan Baku</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('purchasing.pobb.index') }}">Pesan Bahan Baku</a>
                                    </li>
                                </ul>
                            </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>