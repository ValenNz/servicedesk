<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ServiceDesk')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('notifications', {
                items: [],
                add(type, message, title = '', duration = 4000) {
                    const id = Date.now() + Math.random();
                    this.items.push({ id, type, message, title, duration });
                    if (duration > 0) {
                        setTimeout(() => this.remove(id), duration);
                    }
                },
                remove(id) {
                    this.items = this.items.filter(i => i.id !== id);
                }
            });

            window.notify = {
                success: (message, title = 'Success!') => Alpine.store('notifications').add('success', message, title),
                error: (message, title = 'Error') => Alpine.store('notifications').add('error', message, title),
                warning: (message, title = 'Warning') => Alpine.store('notifications').add('warning', message, title),
                info: (message, title = 'Information') => Alpine.store('notifications').add('info', message, title),
            };
        });
    </script>
</head>

<body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen bg-[#f0f2f5]">
        
        @include('partials.sidebar')

        <div class="lg:pl-64 transition-all duration-300">
            @include('partials.header')

            <main class="p-4 lg:p-6">
                @yield('content')
            </main>
        </div>

        <div x-data="{}" 
             x-init="
                @if(session('success')) window.notify.success('{{ session('success') }}', 'Success!'); @endif
                @if(session('error')) window.notify.error('{{ session('error') }}', 'Error'); @endif
                @if(session('warning')) window.notify.warning('{{ session('warning') }}', 'Warning'); @endif
                @if(session('info')) window.notify.info('{{ session('info') }}', 'Information'); @endif
             " 
             class="fixed top-4 right-4 z-50 space-y-3 max-w-md w-full pointer-events-none">
            
            <template x-for="notif in $store.notifications.items" :key="notif.id">
                <div x-show="true"
                     x-transition:enter="transition duration-300 ease-out"
                     x-transition:enter-start="transform translate-x-full opacity-0"
                     x-transition:enter-end="transform translate-x-0 opacity-100"
                     x-transition:leave="transition duration-200 ease-in"
                     x-transition:leave-start="transform translate-x-0 opacity-100"
                     x-transition:leave-end="transform translate-x-full opacity-0"
                     class="pointer-events-auto bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 flex items-start gap-3 overflow-hidden relative"
                     :class="{
                        'border-l-4 border-l-green-500': notif.type === 'success',
                        'border-l-4 border-l-red-500': notif.type === 'error',
                        'border-l-4 border-l-amber-500': notif.type === 'warning',
                        'border-l-4 border-l-blue-500': notif.type === 'info'
                     }">
                    
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                             :class="{
                                'bg-green-100 text-green-600': notif.type === 'success',
                                'bg-red-100 text-red-600': notif.type === 'error',
                                'bg-amber-100 text-amber-600': notif.type === 'warning',
                                'bg-blue-100 text-blue-600': notif.type === 'info'
                             }">
                            <svg x-show="notif.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <svg x-show="notif.type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <svg x-show="notif.type === 'warning'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <svg x-show="notif.type === 'info'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-800" x-text="notif.title || 'Notification'"></h4>
                        <p class="text-sm text-gray-600 mt-0.5 leading-relaxed" x-text="notif.message"></p>
                    </div>

                    <button @click="$store.notifications.remove(notif.id)" class="flex-shrink-0 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>

                    <div class="absolute bottom-0 left-0 h-1 opacity-20"
                         :class="{
                            'bg-green-500': notif.type === 'success',
                            'bg-red-500': notif.type === 'error',
                            'bg-amber-500': notif.type === 'warning',
                            'bg-blue-500': notif.type === 'info'
                         }"
                         :style="`width: 100%; animation: shrink ${notif.duration}ms linear forwards`">
                    </div>
                </div>
            </template>
        </div>
    </div>
</body>
</html>