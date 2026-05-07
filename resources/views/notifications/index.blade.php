@extends('layouts.app')

@section('page_title', 'Notifications')
@section('page_subtitle', 'Centre de notifications')

@section('content')
<style>
    .notif-dark-container {
        font-family: 'Inter', sans-serif;
        background-color: #0f172a; /* Slate 900 */
        color: #e2e8f0;
        border-radius: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        max-width: 500px;
        margin: 0 auto;
        height: 700px;
        overflow: hidden;
    }
    .notif-header {
        background-color: #1e293b; /* Slate 800 */
        padding: 1.5rem;
        border-bottom: 1px solid #334155;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .notif-card {
        background-color: #1e293b; /* Slate 800 */
        border: 1px solid #334155;
        margin: 0.5rem 1rem;
        border-radius: 1.25rem;
        padding: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .notif-card:hover {
        background-color: #334155;
    }
    .notif-card.unread {
        background-color: rgba(79, 70, 229, 0.1);
        border-color: rgba(79, 70, 229, 0.3);
    }
    .notif-badge {
        background-color: #4f46e5;
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
    }
    .tab-btn {
        padding: 0.375rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #94a3b8;
        transition: all 0.2s;
    }
    .tab-btn.active {
        background-color: #334155;
        color: white;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 10px;
    }
</style>

<div class="flex items-center justify-center min-h-[calc(100vh-120px)]">
    <div class="notif-dark-container w-full">
        <!-- Header -->
        <div class="notif-header">
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-bold text-white">Notifications</h2>
                <span class="notif-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
            </div>
            @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.mark-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-medium text-slate-400 hover:text-indigo-400 transition-colors">
                    Mark all as read
                </button>
            </form>
            @endif
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 flex gap-2 overflow-x-auto no-scrollbar bg-[#1e293b]/50">
            <button class="tab-btn active whitespace-nowrap">
                Inbox <span class="opacity-50 text-xs ml-1">({{ auth()->user()->unreadNotifications->count() }})</span>
            </button>
            <button class="tab-btn whitespace-nowrap">
                General <span class="opacity-50 text-xs ml-1">({{ auth()->user()->notifications->count() }})</span>
            </button>
            <button class="tab-btn whitespace-nowrap">Archived</button>
        </div>

        <!-- Notification List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar py-2">
            @php
                $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->take(20)->get();
            @endphp

            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $data = $notification->data;
                        $title = $data['title'] ?? 'Notification';
                        $message = $data['message'] ?? '';
                        $icon = $data['icon'] ?? 'fa-bell';
                        $type = $data['type'] ?? 'info';
                        $time = $notification->created_at->diffForHumans();
                    @endphp

                    <div class="notif-card {{ $isUnread ? 'unread' : '' }} group relative">
                        <div class="flex gap-4">
                            <div class="relative">
                                <div class="w-12 h-12 rounded-full bg-indigo-600/20 flex items-center justify-center text-indigo-400 ring-2 ring-slate-800">
                                    <i class="fas {{ $icon }}"></i>
                                </div>
                                @if($isUnread)
                                <div class="absolute bottom-0 right-0 w-3 h-3 bg-indigo-500 border-2 border-slate-800 rounded-full"></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-[15px] text-slate-200 leading-snug">
                                    <span class="font-bold text-white">{{ $title }}</span> 
                                    <span class="text-slate-400">{{ $message }}</span>
                                </p>
                                <p class="text-xs text-slate-500 mt-1 font-medium">{{ $time }} • {{ config('app.name') }}</p>

                                @if($type === 'invitation' || str_contains($message, 'invit'))
                                    <div class="flex gap-2 mt-4">
                                        <button class="flex-1 py-2 bg-slate-700 hover:bg-slate-600 text-white text-xs font-bold rounded-xl transition-all">
                                            Decline
                                        </button>
                                        <button class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all">
                                            Accept
                                        </button>
                                    </div>
                                @endif

                                @if($type === 'file' || str_contains($message, 'fichier') || str_contains($message, 'document'))
                                    <div class="mt-3 p-3 bg-slate-900/50 border border-slate-700 rounded-xl flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-600/10 flex items-center justify-center text-indigo-400">
                                            <i class="fa-solid fa-file-pdf text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-200 truncate">Document_Medical.pdf</p>
                                            <p class="text-[10px] text-slate-500 font-bold uppercase">Consulter</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($isUnread)
                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            <button type="submit" title="Mark as read" class="text-slate-500 hover:text-white">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center h-full text-center px-10">
                    <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bell-slash text-3xl text-slate-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">No notifications</h3>
                    <p class="text-slate-500">We'll let you know when something new arrives.</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-slate-700/50 bg-slate-800/50">
            <button class="w-full py-3 text-sm font-bold text-slate-400 hover:text-white transition-colors bg-slate-700/30 rounded-2xl hover:bg-slate-700/50">
                See all history
            </button>
        </div>
    </div>
</div>

<script>
    // Notification UI component only
</script>
@endsection
