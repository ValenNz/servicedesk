<header class="sticky top-0 z-20 bg-white border-b border-gray-200 px-4 lg:px-6 py-3" x-data="{ notificationsOpen: false }">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4 flex-1">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="relative flex-1 max-w-md hidden sm:block">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Search ticket, category, or keyword..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" />
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative" @click.outside="notificationsOpen = false">
                <button @click="notificationsOpen = !notificationsOpen" 
                        class="relative p-2 rounded-xl hover:bg-gray-100 transition-colors"
                        :class="{ 'bg-gray-100': notificationsOpen }">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-red-600 text-white text-[10px] font-bold rounded-full ring-2 ring-white animate-pulse">
                        2
                    </span>
                </button>

                <div x-show="notificationsOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50 animate-fade-in"
                     style="display: none;">
                    
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">Notifications</h3>
                            <p class="text-xs text-gray-500 mt-0.5">You have 2 unread</p>
                        </div>
                        <button class="text-xs font-semibold text-red-600 hover:text-red-700 hover:underline transition-colors">
                            Mark all read
                        </button>
                    </div>

                    <div class="max-h-96 overflow-y-auto custom-scrollbar">
                        @php
                            $mockNotifications = [
                                ['type' => 'ticket', 'message' => 'New high priority ticket <b>TKT-008</b> has been created', 'time' => '2 minutes ago', 'read' => false, 'link' => '#'],
                                ['type' => 'assign', 'message' => 'Ticket <b>TKT-005</b> has been assigned to you', 'time' => '15 minutes ago', 'read' => false, 'link' => '#'],
                                ['type' => 'status', 'message' => 'Ticket <b>TKT-003</b> status changed to Resolved', 'time' => '3 hours ago', 'read' => true, 'link' => '#'],
                            ];
                            
                            $notifStyles = [
                                'ticket' => ['bg' => 'bg-red-100', 'color' => 'text-red-600', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                                'assign' => ['bg' => 'bg-blue-100', 'color' => 'text-blue-600', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                'status' => ['bg' => 'bg-yellow-100', 'color' => 'text-yellow-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ];
                        @endphp

                        @foreach ($mockNotifications as $notif)
                            @php $style = $notifStyles[$notif['type']] ?? $notifStyles['status']; @endphp
                            <a href="{{ $notif['link'] }}" class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 {{ !$notif['read'] ? 'bg-red-50/30' : '' }}">
                                <div class="flex gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $style['bg'] }}">
                                        <svg class="w-4 h-4 {{ $style['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $style['icon'] }}" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800 leading-snug">{!! $notif['message'] !!}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notif['time'] }}</p>
                                    </div>
                                    @if (!$notif['read'])
                                        <div class="w-2 h-2 bg-red-500 rounded-full flex-shrink-0 mt-2"></div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                        <a href="#" class="block w-full text-center text-sm font-semibold text-red-600 hover:text-red-700 hover:underline transition-colors">
                            View all notifications →
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 pl-3 border-l border-gray-200">
                <div class="w-9 h-9 bg-red-600 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>
    </div>
</header>