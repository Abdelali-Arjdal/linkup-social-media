<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>LinkUp - Connect with the World</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-primary-50 via-white to-accent-50 min-h-screen">
        <!-- Hero Section -->
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
            <div class="max-w-3xl mx-auto text-center space-y-8">
                <!-- Logo -->
                <div class="flex flex-col items-center mb-12">
                    <img 
                        src="{{ asset('images/linkup-logo.png') }}" 
                        alt="LinkUp Logo" 
                        class="h-32 md:h-40 w-auto mb-6"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <!-- Fallback if image not found -->
                    <div style="display: none;" class="flex flex-col items-center">
                        <x-linkup-logo class="w-32 h-32 md:w-40 md:h-40 mb-6" />
                        <p class="text-xs text-gray-500">Please place linkup-logo.png in public/images/</p>
                    </div>
                </div>

                <!-- Slogan -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    Connect, Share, and Engage with People Around the World
                </h1>

                <!-- Description -->
                <p class="text-lg md:text-xl text-gray-600 leading-relaxed max-w-2xl mx-auto mb-12">
                    Welcome to <strong class="text-primary font-semibold">LinkUp</strong>, your modern social networking platform. 
                    Share your thoughts, connect with friends, discover new content, and build meaningful relationships 
                    in a safe and engaging environment.
                </p>
                    
                <!-- Feature Cards -->
                <div class="grid md:grid-cols-3 gap-8 mb-12">
                    <div class="card text-center hover:shadow-lg transition-shadow duration-300">
                        <div class="w-20 h-20 rounded-full bg-primary flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Connect</h3>
                        <p class="text-gray-600 leading-relaxed">Build your network and stay connected with friends and family</p>
                    </div>

                    <div class="card text-center hover:shadow-lg transition-shadow duration-300">
                        <div class="w-20 h-20 rounded-full bg-primary flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Share</h3>
                        <p class="text-gray-600 leading-relaxed">Express yourself and share your moments with the community</p>
                    </div>

                    <div class="card text-center hover:shadow-lg transition-shadow duration-300">
                        <div class="w-20 h-20 rounded-full bg-primary flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Discover</h3>
                        <p class="text-gray-600 leading-relaxed">Explore new content and discover what's happening around you</p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a 
                        href="{{ route('register') }}" 
                        class="btn-primary text-lg px-10 py-4 w-full sm:w-auto font-bold shadow-lg hover:shadow-xl transition-all duration-300"
                    >
                        Get Started
                    </a>
                    <a 
                        href="{{ route('login') }}" 
                        class="btn-outline text-lg px-10 py-4 w-full sm:w-auto font-bold hover:shadow-md transition-all duration-300"
                    >
                        Sign In
                    </a>
                </div>

                <!-- Footer -->
                <div class="mt-16 pt-8 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} LinkUp. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
