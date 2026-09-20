@extends('layouts.guest')

@section('content')
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="text-center space-y-2 mb-6">
        <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-red-600/30">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Service Desk Portal</h1>
        <p class="text-sm text-gray-500">Sign in to manage tickets, users & system</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ showPassword: false }">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="your.email@servicedesk.com"
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
                />
            </div>
            @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <input 
                    id="password" 
                    :type="showPassword ? 'text' : 'password'"
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
                />
                <button 
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                >
                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    name="remember"
                    class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500" 
                />
                <span class="text-sm text-gray-600">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-red-600 hover:text-red-700">
                    Forgot password?
                </a>
            @endif
        </div>

        <button 
            type="submit" 
            class="w-full py-3 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            Sign In
        </button>
    </form>

    <div class="pt-4 border-t border-gray-100">
        <p class="text-xs text-center text-gray-400 mb-2">Demo Credentials:</p>
        <div class="grid grid-cols-3 gap-2 text-xs">
            <button 
                type="button"
                onclick="fillDemo('admin')"
                class="px-3 py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors text-gray-600 font-medium border border-gray-200"
            >
                Admin
            </button>
            <button 
                type="button"
                onclick="fillDemo('employee')"
                class="px-3 py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors text-gray-600 font-medium border border-gray-200"
            >
                Employee
            </button>
            <button 
                type="button"
                onclick="fillDemo('user')"
                class="px-3 py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors text-gray-600 font-medium border border-gray-200"
            >
                User
            </button>
        </div>
    </div>

    <script>
        function fillDemo(type) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            
            if (type === 'admin') {
                emailInput.value = 'admin@servicedesk.com';
                passwordInput.value = 'password';
            } else if (type === 'employee') {
                emailInput.value = 'employee@servicedesk.com';
                passwordInput.value = 'password';
            } else if (type === 'user') {
                emailInput.value = 'user@servicedesk.com';
                passwordInput.value = 'password';
            }
            
            emailInput.classList.remove('border-red-300', 'bg-red-50');
            passwordInput.classList.remove('border-red-300', 'bg-red-50');
        }
    </script>
@endsection