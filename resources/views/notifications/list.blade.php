{{-- resources/views/notifications/list.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifications</h5>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                Mark All as Read
                            </button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <a href="{{ route('notifications.show', $notification->id) }}"
                                   class="list-group-item list-group-item-action {{ is_null($notification->read_at) ? 'unread-notification' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $notification->data['body'] ?? '' }}</p>
                                    @if(isset($notification->data['extra']))
                                        <small class="text-muted">
                                            @foreach($notification->data['extra'] as $key => $value)
                                                {{ $key }}: {{ $value }}@if(!$loop->last), @endif
                                            @endforeach
                                        </small>
                                    @endif
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <p>No notifications found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.unread-notification {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
}
</style>
