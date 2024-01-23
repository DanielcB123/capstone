<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meet Me Halfway</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">


    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    @vite(['resources/js/welcome.js', 'resources/css/app.css', 'resources/css/welcome.css'])
</head>
<body class="antialiased bg-gray-100">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo and Links -->

                    <div class="flex justify-center items-center pr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40">
                            <path fill="#0369a1" d="M320 64A64 64 0 1 0 192 64a64 64 0 1 0 128 0zm-96 96c-35.3 0-64 28.7-64 64v48c0 17.7 14.3 32 32 32h1.8l11.1 99.5c1.8 16.2 15.5 28.5 31.8 28.5h38.7c16.3 0 30-12.3 31.8-28.5L318.2 304H320c17.7 0 32-14.3 32-32V224c0-35.3-28.7-64-64-64H224zM132.3 394.2c13-2.4 21.7-14.9 19.3-27.9s-14.9-21.7-27.9-19.3c-32.4 5.9-60.9 14.2-82 24.8c-10.5 5.3-20.3 11.7-27.8 19.6C6.4 399.5 0 410.5 0 424c0 21.4 15.5 36.1 29.1 45c14.7 9.6 34.3 17.3 56.4 23.4C130.2 504.7 190.4 512 256 512s125.8-7.3 170.4-19.6c22.1-6.1 41.8-13.8 56.4-23.4c13.7-8.9 29.1-23.6 29.1-45c0-13.5-6.4-24.5-14-32.6c-7.5-7.9-17.3-14.3-27.8-19.6c-21-10.6-49.5-18.9-82-24.8c-13-2.4-25.5 6.3-27.9 19.3s6.3 25.5 19.3 27.9c30.2 5.5 53.7 12.8 69 20.5c3.2 1.6 5.8 3.1 7.9 4.5c3.6 2.4 3.6 7.2 0 9.6c-8.8 5.7-23.1 11.8-43 17.3C374.3 457 318.5 464 256 464s-118.3-7-157.7-17.9c-19.9-5.5-34.2-11.6-43-17.3c-3.6-2.4-3.6-7.2 0-9.6c2.1-1.4 4.8-2.9 7.9-4.5c15.3-7.7 38.8-14.9 69-20.5z"/>
                        </svg>
                    </div>


                    <div class="flex-shrink-0 flex items-center -ml-3 sm:-ml-0">
                        <a href="{{ url('/') }}">{{ config('app.name', 'Meet Me Halfway') }}</a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <!-- Navigation Links -->
                        <!-- More navigation items -->
                    </div>
                </div>
                <div class="sm:ml-6 flex items-center">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="rounded px-2 py-2 bg-sky-600 hover:bg-sky-500 text-gray-100 hover:text-white">Log in</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded px-2 py-2 bg-emerald-600 hover:bg-emerald-500 ml-4 text-gray-100 hover:text-white">Sign Up</a>
                        @endif
                    @endguest

                    @if(Auth::user())
                    <div class="flex">
                        <a href="/home" class="bg-green-400 hover:bg-green-300 p-2 rounded mx-1 text-white">Home</a>
                        <a class="bg-sky-400 hover:bg-sky-300 p-2 rounded mx-1 text-white" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>

                    @endif
                    
                </div>
            </div>
        </div>
    </nav>



                    
    
    
    <!-- Body Section -->
    <div class="py-10">
        <main>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <!-- Replace with your content -->
                <div class="px-4 py-8 sm:px-0">
                    <div class="border-4 border-dashed border-gray-200 rounded-lg h-auto">
                        <!-- Hero Section -->
                        <div class="bg-white overflow-hidden">
                            <div class="relative max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 lg:py-24">
                                <div class="text-center">
                                    
                                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                                        <span class="slide-in-left">Meet M</span><span class="slide-in-right">e Halfway</span>
                                    </h2>
                                    

                                    <p class="fade-in mt-4 text-lg leading-6 text-gray-500">
                                        Finding the perfect meeting point has never been easier. 
                                    </p>
                                    
                                </div>
                            </div>
                        </div>

                        <!-- Features Section -->
                        <div class="py-12 bg-white">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                <div class="lg:text-center">
                                    <h2 class="fade-in text-base text-indigo-600 font-semibold tracking-wide uppercase" style="animation-delay: 1s;">
                                        Features
                                    </h2>                                    
                                    <p class="fade-in mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl" style="animation-delay: 2s;">
                                        Everything you need, all in one place
                                    </p>
                                </div>

                                <div class="mt-10">
                                    <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                                        <!-- Feature 1 -->
                                        <div class="fade-in relative" style="animation-delay: 3s;">
                                            <dt>
                                                <p class="text-lg leading-6 font-medium text-gray-900">Nearby Amenities</p>
                                            </dt>
                                            <dd class="mt-2 text-base text-gray-500">
                                                Discover restaurants, cafes, and other amenities close to your meeting point.
                                            </dd>
                                        </div>

                                        <!-- Feature 2 -->
                                        <div class="fade-in relative" style="animation-delay: 3.5s;">
                                            <dt>
                                                <p class="text-lg leading-6 font-medium text-gray-900">Safety First</p>
                                            </dt>
                                            <dd class="mt-2 text-base text-gray-500">
                                                Choose to meet near police stations or public areas for added safety.
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <footer class="bg-gray-800 text-gray-300">
                            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
                                <div class="xl:grid xl:grid-cols-4 xl:gap-8">
                                    <div class="xl:col-span-1">
                                        <p class="mt-0 text-sm">
                                            UNT Capstone 4901 project
                                        </p>
                                    </div>

                                    <div class="mt-8 grid grid-cols-0 gap-0 xl:mt-0 xl:col-span-2">
                                        <div class="md:grid md:grid-cols-3 md:gap-8">
                                            <div>
                                                <h4 class="text-sm font-semibold uppercase tracking-wider">Project Links</h4>
                                                <ul class="mt-4 space-y-4">
                                                    <li><a href="https://github.com/your-github-repo" target="_blank" class="hover:underline">GitHub Repository</a></li>
                                                    <li><a href="/documentation" class="hover:underline">Documentation</a></li>
                                                    <li><a href="/about" class="hover:underline">About the Project</a></li>
                                                </ul>
                                            </div>
                                            <div class="mt-8 md:mt-0">
                                                <h4 class="text-sm font-semibold uppercase tracking-wider">Team Members</h4>
                                                <ul class="mt-4 space-y-4">
                                                    <li><a href="/team/john-doe" class="hover:underline">Daniel Burgess</a></li>
                                                    <li><a href="/team/jane-doe" class="hover:underline">Abel Hernandez</a></li>
                                                    <li><a href="/team/jane-doe" class="hover:underline">Naomi Unuane</a></li>
                                                    <li><a href="/team/more-members" class="hover:underline">Andrew Bradford</a></li>

                                                </ul>
                                            </div>

                                            <div class="mt-8 md:mt-5">
                                                <ul class="mt-4 space-y-4">
                                                    <li><a href="/team/more-members" class="hover:underline">Sabrina Salazar</a></li>
                                                    <li><a href="/team/more-members" class="hover:underline">Hasti Rathod</a></li>
                                                    
                                                </ul>
                                            </div>

                                        </div>
                                    </div>

                                    
                                </div>

                                <div class="mt-12 border-t border-gray-700 pt-8 md:flex md:items-center md:justify-between">
                                    <p class="mt-8 text-base md:mt-0">
                                        {{ date('Y') }} Meet Me Halfway. Capstone UNT CSCE.
                                    </p>
                                </div>
                            </div>
                        </footer>


                    </div>
                </div>
                <!-- /End replace -->
            </div>
        </main>
    </div>
</body>
</html>
