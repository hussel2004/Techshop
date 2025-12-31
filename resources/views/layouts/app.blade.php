<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TechShop E-Commerce')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }

        /* Dropdown hover effect with delay */
        .dropdown-wrapper {
            position: relative;
        }

        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-25px);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 1s;
            pointer-events: none;
        }

        .dropdown-wrapper:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
            transition-delay: 0.3s;
        }

        /* Keep dropdown visible when hovering over it */
        .dropdown-menu:hover {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full flex flex-col">
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-2xl font-bold text-gray-900">TechShop</span>
                    </a>

                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-900 hover:text-primary-600 px-3 py-2 text-sm font-medium">Home</a>
                        <a href="{{ route('products.index') }}" class="text-gray-900 hover:text-primary-600 px-3 py-2 text-sm font-medium">Products</a>
                        <a href="{{ route('promotions.index') }}" class="text-gray-900 hover:text-primary-600 px-3 py-2 text-sm font-medium {{ request()->routeIs('promotions.*') ? 'text-primary-600 font-semibold' : '' }}">Super Deals</a>

                        <div class="dropdown-wrapper">
                            <button class="text-gray-900 hover:text-primary-600 px-3 py-2 text-sm font-medium inline-flex items-center">
                                Categories
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                                <div class="py-1">
                                    <a href="{{ route('products.index', ['category' => 'mobile-phones']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Mobile Phones</a>
                                    <a href="{{ route('products.index', ['category' => 'computers']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Computers</a>
                                    <a href="{{ route('products.index', ['category' => 'telephones']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Telephones</a>
                                    <a href="{{ route('products.index', ['category' => 'accessories']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Accessories</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <form action="{{ route('products.index') }}" method="GET" class="hidden md:flex items-center">
                        <input type="search" name="search" placeholder="Search products..." class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary-500 w-64">
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-r-md hover:bg-primary-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>

                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-700 hover:text-primary-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </a>

                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium">Register</a>
                    @else
                        <div class="dropdown-wrapper">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-primary-600">
                                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                                <div class="py-1">
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">My Orders</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Profile</a>
                                    @if(Auth::user()->is_admin)
                                        <div class="border-t border-gray-100"></div>
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Admin Dashboard</a>
                                    @endif
                                    <div class="border-t border-gray-100"></div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">About TechShop</h3>
                    <p class="text-gray-400">Your one-stop shop for the latest tech products, from mobile phones to accessories.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-white">Products</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white">Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white">Shipping Info</a></li>
                        <li><a href="#" class="hover:text-white">Returns</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                    <p class="text-gray-400 mb-4">Subscribe to get special offers and updates.</p>
                    <form class="flex">
                        <input type="email" placeholder="Your email" class="px-4 py-2 rounded-l-md text-gray-900 flex-grow">
                        <button class="bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-r-md">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 TechShop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Update cart count on page load
        fetch('{{ route("cart.count") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cart-count').textContent = data.count;
            });
    </script>

    @stack('scripts')
</body>
</html>
