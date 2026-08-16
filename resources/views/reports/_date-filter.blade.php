<div class="flex items-center gap-2 mb-3">
    @foreach (['all' => 'All Time', 'daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month', 'yearly' => 'This Year'] as $key => $label)
        <a href="{{ route($routeName, array_filter(['period' => $key, 'search' => request('search'), 'status' => request('status'), 'payment_status' => request('payment_status')])) }}"
           class="px-3 py-1.5 rounded-lg text-sm {{ (request('period') ?? null) == $key && !request()->hasAny(['date', 'month', 'year']) ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="flex items-end gap-4 pt-3 border-t border-gray-100">
    <form action="{{ route($routeName) }}" method="GET" class="flex items-end gap-2">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Specific Date</label>
            <input type="date" name="date" value="{{ request('date') }}"
                   class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
        </div>
        <button type="submit" class="bg-gray-700 text-white px-3 py-1.5 rounded-lg text-sm">Go</button>
        @if (request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
        @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        @if (request('payment_status'))<input type="hidden" name="payment_status" value="{{ request('payment_status') }}">@endif
    </form>

    <form action="{{ route($routeName) }}" method="GET" class="flex items-end gap-2">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Specific Month</label>
            <input type="month" name="month" value="{{ request('month') }}"
                   class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
        </div>
        <button type="submit" class="bg-gray-700 text-white px-3 py-1.5 rounded-lg text-sm">Go</button>
        @if (request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
        @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        @if (request('payment_status'))<input type="hidden" name="payment_status" value="{{ request('payment_status') }}">@endif
    </form>

    <form action="{{ route($routeName) }}" method="GET" class="flex items-end gap-2">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Specific Year</label>
            <input type="number" name="year" min="2000" max="2100" placeholder="----" value="{{ request('year') }}"
                   class="w-24 border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
        </div>
        <button type="submit" class="bg-gray-700 text-white px-3 py-1.5 rounded-lg text-sm">Go</button>
        @if (request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
        @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        @if (request('payment_status'))<input type="hidden" name="payment_status" value="{{ request('payment_status') }}">@endif
    </form>
</div>