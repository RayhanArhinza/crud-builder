@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-50">
        <div class="max-w-full mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Left side -->
                <div class="flex items-center">
                    <button id="sidebarToggle" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 lg:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center ml-4">
                        <div class="bg-blue-600 p-2 rounded-lg">
                            <i class="fas fa-cube text-white"></i>
                        </div>
                        <span class="ml-3 text-xl font-bold text-gray-800">CRUD Generator</span>
                    </div>
                </div>

                <!-- Right side -->
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-bell"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Profile -->
                    <div class="relative" id="profileDropdown">
                        <button class="flex items-center gap-3 hover:bg-gray-100 rounded-lg p-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                                <!-- Display user initials or avatar -->
                                <span class="text-white text-sm font-medium">{{ Auth::user()->name[0] }}</span>
                            </div>
                            <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>
                        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-200">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                            <hr class="my-2 border-gray-200">
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>


        <!-- Updated Sidebar -->
        <aside id="sidebar" class="fixed top-16 left-0 w-64 h-[calc(100vh-4rem)] bg-white border-r border-gray-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40">
            <div class="flex flex-col h-full">
                <!-- Menu Items -->
                <div class="flex-1 overflow-y-auto">
                    <div class="px-6 space-y-4 py-4">
                        <!-- Dashboard Link -->
                        <a href="{{ route('crud.index') }}"
                           class="flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-home text-gray-500 mr-3"></i>
                            <span>Dashboard</span>
                        </a>

                        <!-- Tables Section -->
                        <div class="pt-4">
                            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tables
                            </h3>

                            <!-- Table Links -->
                            @foreach(\App\Models\CrudTable::all() as $table)
                                <a href="{{ route('table.index', $table->name) }}"
                                   class="flex items-center p-3 mt-2 text-gray-700 rounded-lg hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-table text-gray-500 mr-3"></i>
                                    <span>{{ ucfirst($table->name) }}</span>
                                </a>
                            @endforeach
                        </div>

                        <!-- Settings Section -->
                        <div class="pt-4">
                            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Settings
                            </h3>
                            <a href="#" class="flex items-center p-3 mt-2 text-gray-700 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="fas fa-cog text-gray-500 mr-3"></i>
                                <span>General Settings</span>
                            </a>
                            <a href="{{ route('role.index') }}" class="flex items-center p-3 mt-2 text-gray-700 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="fas fa-user-cog text-gray-500 mr-3"></i>
                                <span>Role Management</span>
                            </a>
                            <a href="#" class="flex items-center p-3 mt-2 text-gray-700 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="fas fa-user text-gray-500 mr-3"></i>
                                <span>User Management</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

    <!-- Overlay for mobile sidebar -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 hidden lg:hidden"></div>

    <!-- Main Content -->
    <main class="lg:ml-64 pt-16 min-h-screen bg-gray-50">
        <div class="p-6">
            @yield('content')
        </div>
    </main>

    <script>
        // Profile Dropdown Toggle
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');

        profileDropdown.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });

        // Close profile dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        // Mobile Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Close sidebar on window resize if screen becomes larger
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });

        // Add hover effect to sidebar menu items
        const menuItems = document.querySelectorAll('.sidebar a');
        menuItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                if (!item.classList.contains('bg-blue-600')) {
                    item.classList.add('bg-gray-100');
                }
            });

            item.addEventListener('mouseleave', () => {
                if (!item.classList.contains('bg-blue-600')) {
                    item.classList.remove('bg-gray-100');
                }
            });
        });

        // Active menu item handling
        menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                menuItems.forEach(menuItem => {
                    menuItem.classList.remove('bg-blue-600', 'text-white');
                    menuItem.classList.add('text-gray-600');
                });
                item.classList.remove('text-gray-600');
                item.classList.add('bg-blue-600', 'text-white');
            });
        });
    </script>
</body>
</html>
