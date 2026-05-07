@extends('layouts.app')

@section('title', 'Mes notifications')
@section('page-title', 'Notifications')

@section('styles')
<style>
    .notification-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        margin-bottom: 12px;
    }
    .notification-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .notification-card.unread {
        background-color: #e8f4f8;
        border-left-color: #1a5f7a;
    }
    .notification-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .notification-icon i {
        font-size: 20px;
    }
    .notification-time {
        font-size: 11px;
    }
    .btn-mark-read {
        transition: all 0.2s;
    }
    .btn-mark-read:hover {
        transform: scale(1.05);
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-bell text-primary me-2"></i>
                    Mes notifications
                    @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="badge bg-danger rounded-pill ms-2">{{ $unreadCount }} non lue(s)</span>
                    @endif
                </h5>
                @if($unreadCount > 0)
                    <form action="{{ route('secretaire.notifications.mark-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-check-double me-1"></i> Tout marquer comme lu
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        @php $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(15); @endphp
        
        @if($notifications->count() > 0)
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    @foreach($notifications as $notification)
                        @php
                            $isUnread = is_null($notification->read_at);
                            $icon = $notification->data['icon'] ?? 'fa-bell';
                            $title = $notification->data['title'] ?? 'Notification';
                            $message = $notification->data['message'] ?? '';
                            $url = $notification->data['url'] ?? '#';
                            $time = $notification->created_at->diffForHumans();
                        @endphp
                        
                        <div class="notification-card p-3 {{ $isUnread ? 'unread' : '' }} border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex">
                                    <div class="notification-icon bg-primary bg-opacity-10 me-3">
                                        <i class="fas {{ $icon }} text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <strong class="me-2">{{ $title }}</strong>
                                            @if($isUnread)
                                                <span class="badge bg-primary rounded-pill" style="font-size: 10px;">Nouveau</span>
                                            @endif
                                        </div>
                                        <p class="mb-1 small">{{ $message }}</p>
                                        <small class="text-muted notification-time">
                                            <i class="far fa-clock me-1"></i> {{ $time }}
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    @if($isUnread)
                                        <form action="{{ route('secretaire.notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary btn-mark-read" title="Marquer comme lu">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ $url }}" class="btn btn-sm btn-primary btn-mark-read" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fas fa-bell-slash"></i>
                        <h5 class="text-muted">Aucune notification</h5>
                        <p class="text-muted small">Vous serez notifié lors des nouvelles activités</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection