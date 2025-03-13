<header class="z-40 bg-white shadow-md dark:bg-[#0e1726]">
    <div class="flex items-center justify-between px-5 py-2">
        <button @click="$store.app.toggleSidebar()" class="lg:hidden">
            <svg width="24" height="24" fill="none">
                <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        <div class="flex items-center space-x-4">
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded-md">Logout</button>
            </form>
        </div>
    </div>
</header>
