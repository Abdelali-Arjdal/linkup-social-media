<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LinkUp') }} - {{ $title ?? 'Social Network' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('scripts')
    </head>
    <body class="font-sans antialiased bg-gray-50" x-data="{ mobileSidebarOpen: false }">
        <div class="min-h-screen flex">
            <!-- Sidebar - Desktop -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-white border-r border-gray-200">
                <!-- Logo -->
                <div class="flex items-center justify-center px-6 py-5 border-b border-gray-200">
                    <span class="text-2xl font-bold">
                        <span class="text-dark">Link</span><span class="text-primary">Up</span>
                    </span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('feed') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('feed') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="font-medium">Feed</span>
                    </a>
                    
                    <a href="{{ route('search') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('search') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="font-medium">Search</span>
                    </a>
                    
                    <a href="{{ route('notifications.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('notifications.*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="font-medium">Notifications</span>
                        @php
                            $unreadCount = auth()->user()->unreadNotifications()->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-2 right-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-secondary rounded-full">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('messages.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('messages.*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="font-medium">Messages</span>
                        @php
                            $unreadMessagesCount = auth()->user()->conversations()
                                ->join('messages', function ($join) {
                                    $join->on('conversations.id', '=', 'messages.conversation_id')
                                        ->where('messages.sender_id', '!=', auth()->id())
                                        ->where('messages.is_read', false);
                                })
                                ->distinct()
                                ->count('messages.id');
                        @endphp
                        @if($unreadMessagesCount > 0)
                            <span class="absolute top-2 right-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-secondary rounded-full">{{ $unreadMessagesCount }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('profile.show', auth()->id()) }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('profile.show') && request()->route('user') && request()->route('user')->id == auth()->id() ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">Profile</span>
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('profile.edit') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">Settings</span>
                    </a>
                </nav>

                <!-- User Section -->
                <div class="px-4 py-4 border-t border-gray-200">
                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 w-full px-4 py-3 rounded-xl hover:bg-gray-100 transition-all duration-200">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 text-left">
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </a>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 lg:pl-64">
                <!-- Mobile Sidebar Toggle -->
                <div class="lg:hidden bg-white border-b border-gray-200 sticky top-0 z-50">
                    <div class="flex items-center justify-between px-4 py-3">
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="p-2 text-gray-600 hover:text-primary transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <a href="{{ route('feed') }}" class="flex items-center">
                            <img src="{{ asset('images/linkup-logo.png') }}" alt="LinkUp Logo" class="w-8 h-8" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <x-linkup-logo class="w-8 h-8" style="display: none;" />
                        </a>
                        <x-notifications-dropdown />
                    </div>
                </div>

                <!-- Mobile Sidebar -->
                <div class="lg:hidden">
                    <!-- Overlay -->
                    <div x-show="mobileSidebarOpen" 
                         @click="mobileSidebarOpen = false"
                         x-transition:enter="transition-opacity ease-linear duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity ease-linear duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40"></div>

                    <!-- Sidebar -->
                    <aside x-show="mobileSidebarOpen"
                           x-cloak
                           x-transition:enter="transition ease-in-out duration-300 transform"
                           x-transition:enter-start="-translate-x-full"
                           x-transition:enter-end="translate-x-0"
                           x-transition:leave="transition ease-in-out duration-300 transform"
                           x-transition:leave-start="translate-x-0"
                           x-transition:leave-end="-translate-x-full"
                           class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-50 overflow-y-auto">
                        <!-- Logo -->
                        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">
                            <div class="flex items-center justify-center">
                                <span class="text-2xl font-bold">
                                    <span class="text-dark">Link</span><span class="text-primary">Up</span>
                                </span>
                            </div>
                            <button @click="mobileSidebarOpen = false" class="p-2 text-gray-600 hover:text-primary transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Navigation -->
                        <nav class="flex-1 px-4 py-6 space-y-2">
                            <a href="{{ route('feed') }}" @click="mobileSidebarOpen = false" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('feed') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span class="font-medium">Feed</span>
                            </a>
                            
                            <a href="{{ route('search') }}" @click="mobileSidebarOpen = false" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('search') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span class="font-medium">Search</span>
                            </a>
                            
                            <a href="{{ route('notifications.index') }}" @click="mobileSidebarOpen = false" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('notifications.*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200 relative">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="font-medium">Notifications</span>
                            </a>
                            
                            <a href="{{ route('messages.index') }}" @click="mobileSidebarOpen = false" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('messages.*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200 relative">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span class="font-medium">Messages</span>
                                @php
                                    $unreadMessagesCount = auth()->user()->conversations()
                                        ->join('messages', function ($join) {
                                            $join->on('conversations.id', '=', 'messages.conversation_id')
                                                ->where('messages.sender_id', '!=', auth()->id())
                                                ->where('messages.is_read', false);
                                        })
                                        ->distinct()
                                        ->count('messages.id');
                                @endphp
                                @if($unreadMessagesCount > 0)
                                    <span class="absolute top-2 right-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-secondary rounded-full">{{ $unreadMessagesCount }}</span>
                                @endif
                            </a>
                                    $unreadCount = auth()->user()->unreadNotifications()->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute top-2 right-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-secondary rounded-full">{{ $unreadCount }}</span>
                                @endif
                            </a>
                            
                            <a href="{{ route('profile.show', auth()->id()) }}" @click="mobileSidebarOpen = false" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('profile.show') && request()->route('user') && request()->route('user')->id == auth()->id() ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium">Profile</span>
                            </a>
                            
                            <a href="{{ route('profile.edit') }}" @click="mobileSidebarOpen = false" class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('profile.edit') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-medium">Settings</span>
                            </a>
                        </nav>

                        <!-- User Section -->
                        <div class="px-4 py-4 border-t border-gray-200">
                            <a href="{{ route('profile.edit') }}" @click="mobileSidebarOpen = false" class="flex items-center space-x-3 w-full px-4 py-3 rounded-xl hover:bg-gray-100 transition-all duration-200">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="flex-1 text-left">
                                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                            </a>
                        </div>
                    </aside>
                </div>

                <!-- Page Content -->
                <main class="py-6 px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="mb-6 bg-accent-100 border border-accent-300 text-accent-800 px-4 py-3 rounded-xl flex items-center space-x-2" role="alert">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-secondary-100 border border-secondary-300 text-secondary-800 px-4 py-3 rounded-xl flex items-center space-x-2" role="alert">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
