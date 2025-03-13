<nav x-data="sidebar" class="sidebar fixed top-0 bottom-0 z-50 h-full min-h-screen w-[260px] shadow-lg transition-all duration-300" :class="{'dark text-white-dark' : $store.app.semidark}">
    <div class="h-full bg-white dark:bg-[#0e1726]">
        <div class="flex items-center justify-between px-4 py-3">
            <span class="text-2xl font-semibold dark:text-white-light">KATOLEC</span>
            <button class="collapse-icon" @click="$store.app.toggleSidebar()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path opacity="0.5" d="M17 19L11 12L17 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </button>
        </div>
        <ul class="perfect-scrollbar relative h-[calc(100vh-80px)] p-4 space-y-1 overflow-y-auto">
            <li>
                <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <svg class="w-5 h-5" fill="none">
                        <path opacity="0.5" d="M2 12C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" fill="currentColor"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
