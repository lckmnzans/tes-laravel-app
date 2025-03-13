
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="favicon.png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
        <link href="css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="screen" href="{{asset('style/assets/css/perfect-scrollbar.min.css')}}">
        <link rel="stylesheet" type="text/css" media="screen" href="{{asset('style/assets/css/style.css')}}">
        <link defer="" rel="stylesheet" type="text/css" media="screen" href="{{asset('style/assets/css/animate.css')}}">
        <script src="{{asset('style/assets/js/perfect-scrollbar.min.js')}}"></script>
        <script defer="" src="{{asset('style/assets/js/popper.min.js')}}"></script>
        <script defer="" src="{{asset('style/assets/js/tippy-bundle.umd.min.js')}}"></script>
        <script defer="" src="{{asset('style/assets/js/sweetalert.min.js')}}"></script>
    </head>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased" :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]">
        <div class="main-container min-h-screen text-black dark:text-white-dark" :class="[$store.app.navbar]">
            <!-- Sidebar -->
            @include('layout.sidebar')

        <div class="flex-1 flex flex-col">
            <!-- Header -->
            @include('layout.header')
            
            <!-- Content -->
            <main class="p-4">
                    {{ $slot }}
                </main>
            </div>

        <!-- Footer -->
        <footer class="p-4 text-center text-gray-600 dark:text-gray-400">
            © {{ now()->year }}. PT.Katolec Indonesia
        </footer>
    </div>
    </div>
    <script src="{{asset('style/assets/js/alpine-collaspe.min.js')}}"></script>
        <script src="{{asset('style/assets/js/alpine-persist.min.js')}}"></script>
        <script defer="" src="{{asset('style/assets/js/alpine-ui.min.js')}}"></script>
        <script defer="" src="{{asset('style/assets/js/alpine-focus.min.js')}}"></script>
        <script defer="" src="{{asset('style/assets/js/alpine.min.js')}}"></script>
        <script src="{{asset('style/assets/js/custom.js')}}"></script>
        <script defer="" src="{{asset('style/assets/js/apexcharts.js')}}"></script>

        <script>
            // main section
            document.addEventListener('alpine:init', () => {
                Alpine.data('scrollToTop', () => ({
                    showTopButton: false,
                    init() {
                        window.onscroll = () => {
                            this.scrollFunction();
                        };
                    },

                    scrollFunction() {
                        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                            this.showTopButton = true;
                        } else {
                            this.showTopButton = false;
                        }
                    },

                    goToTop() {
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                    },
                }));

                // theme customization
                Alpine.data('customizer', () => ({
                    showCustomizer: false,
                }));

                Alpine.data('sidebar', () => ({
    init() {
        // Ambil semua link di sidebar
        const links = document.querySelectorAll('.sidebar ul a');

        // Tambahkan event listener untuk semua link
        links.forEach((link) => {
            link.addEventListener('click', (e) => {
                // Hapus semua link dengan kelas active
                links.forEach((lnk) => lnk.classList.remove('active'));

                // Tandai link yang di-klik sebagai aktif
                link.classList.add('active');

                // Jika link memiliki parent menu, tambahkan kelas open
                const parentMenu = link.closest('.menu');
                if (parentMenu) {
                    parentMenu.classList.toggle('open');
                }
            });
        });

        // Set link aktif berdasarkan URL
        const activeLink = document.querySelector('.sidebar ul a[href="' + window.location.pathname + '"]');
        if (activeLink) {
            activeLink.classList.add('active');

            // Buka parent menu jika link aktif berada dalam sub-menu
            const parentMenu = activeLink.closest('.menu');
            if (parentMenu) {
                parentMenu.classList.add('open');
            }
        }
    },
}));



                // header section
                Alpine.data('header', () => ({
                    init() {
                        const selector = document.querySelector('ul.horizontal-menu a[href="' + window.location.pathname + '"]');
                        if (selector) {
                            selector.classList.add('active');
                            const ul = selector.closest('ul.sub-menu');
                            if (ul) {
                                let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                                if (ele) {
                                    ele = ele[0];
                                    setTimeout(() => {
                                        ele.classList.add('active');
                                    });
                                }
                            }
                        }
                    },

                    notifications: [
                        {
                            id: 1,
                            profile: 'user-profile.jpeg',
                            message: '<strong class="text-sm mr-1">StarCode Kh</strong>invite you to <strong>Prototyping</strong>',
                            time: '45 min ago',
                        },

                    ],

                    messages: [
                        {
                            id: 1,
                            image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-success-light dark:bg-success text-success dark:text-success-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></span>',
                            title: 'Congratulations!',
                            message: 'Your OS has been updated.',
                            time: '1hr',
                        },
                        
                    ],


                    removeNotification(value) {
                        this.notifications = this.notifications.filter((d) => d.id !== value);
                    },

                    removeMessage(value) {
                        this.messages = this.messages.filter((d) => d.id !== value);
                    },
                }));
            });
        </script>
        <!-- Bootstrap CSS -->

</body>
</html>
