@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<h1 class="text-2xl font-bold mb-6">Audit Log</h1>

<form action="{{ route('audit-logs.index') }}" method="GET" class="flex gap-3 mb-4">
    <select name="model_type" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">All record types</option>
        @foreach ($modelOptions as $value => $label)
            <option value="{{ $value }}" {{ request('model_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>

    <select name="action" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">All actions</option>
        @foreach (['created' => 'Created', 'updated' => 'Updated', 'deleted' => 'Deleted'] as $value => $label)
            <option value="{{ $value }}" {{ request('action') == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Record Type</th>
                <!--<th class="px-4 py-3">Record ID</th>-->
                <th class="px-4 py-3">Action</th>
                <th class="px-4 py-3">User</th>
                <th class="px-4 py-3">Changes</th>
                <th class="px-4 py-3">When</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($logs as $log)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $log->model_name }}</td>
                    <!--<td class="px-4 py-3 text-gray-500">#{{ $log->auditable_id }}</td>-->
                    <td class="px-4 py-3">
                        @php
                            $actionColors = [
                                'created' => 'bg-green-100 text-green-800',
                                'updated' => 'bg-blue-100 text-blue-800',
                                'deleted' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full {{ $actionColors[$log->action] }} capitalize">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $log->user->name ?? 'Rai' }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs max-w-xs">
                        @if (in_array($log->action, ['created', 'deleted']))
                            {{ $log->display_summary ?? '—' }}
                        @else
                            @forelse ($log->formatted_changes as $label => $diff)
                                <div><strong>{{ $label }}:</strong> {{ $diff['old'] }} → {{ $diff['new'] }}</div>
                            @empty
                                <span class="text-gray-400">No visible changes</span>
                            @endforelse
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-400">{{ $log->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No audit entries yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $logs->links() }}</div>
@endsection