@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Notifications</h1>
    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="text-sm text-indigo-600 hover:underline">Mark all as read</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Title</th>
                <th class="px-4 py-3">Message</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">When</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($notifications as $notification)
                <tr class="{{ !$notification->is_read ? 'bg-indigo-50' : '' }}">
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ $notification->link ?? '#' }}" class="hover:underline">{{ $notification->title }}</a>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $notification->message }}</td>
                    <td class="px-4 py-3">
                        @if ($notification->is_read)
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Read</span>
                        @else
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">Unread</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-400">{{ $notification->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No notifications yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $notifications->links() }}</div>
@endsection