<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <!-- Heading -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen User</h2>
            <button onclick="openModal()" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring focus:ring-indigo-300 focus:outline-none">
                Tambah User
            </button>
        </div>

        <!-- Success/Error Messages -->
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

        <!-- Users Table Section -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-black-900 mb-4">Daftar User</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 border-b">#</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 border-b">Nama</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 border-b">Email</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 border-b">Role</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 border-b">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700 border-b">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 border-b">{{ $user->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 border-b">{{ $user->email }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 border-b capitalize">{{ $user->role }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 border-b">{{ $user->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="userModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah User Baru</h3>

            <form method="POST" action="{{ route('auth.register.store') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                    <input id="name" name="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input id="email" name="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block font-medium text-sm text-gray-700">Role</label>
                    <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="manager">Manager</option>
                        <option value="exim">EXIM</option>
                        <option value="ppic">PPIC</option>
                        <option value="gudang">Gudang</option>
                        <option value="purchasing">Purchasing</option>
                        <option value="operator">Operator</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <input id="password" name="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 mr-2">Batal</button>
                    <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Notifikasi akan hilang setelah 5 detik
        setTimeout(() => {
            const successNotification = document.getElementById('notification-success');
            const errorNotification = document.getElementById('notification-error');
            const validationErrorNotification = document.getElementById('notification-validation-error');

            if (successNotification) {
                successNotification.style.opacity = '0';
                setTimeout(() => successNotification.remove(), 300);
            }

            if (errorNotification) {
                errorNotification.style.opacity = '0';
                setTimeout(() => errorNotification.remove(), 300);
            }

            if (validationErrorNotification) {
                validationErrorNotification.style.opacity = '0';
                setTimeout(() => validationErrorNotification.remove(), 300);
            }
        }, 5000);
        function openModal() {
            document.getElementById('userModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
