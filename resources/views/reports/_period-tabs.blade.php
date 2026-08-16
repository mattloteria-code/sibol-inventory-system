<div class="flex items-center gap-2 mb-4">
    @foreach (['all' => 'All Time', 'daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month', 'yearly' => 'This Year'] as $key => $label)
        <a href="{{ route($routeName, ['period' => $key]) }}"
           class="px-3 py-1.5 rounded-lg text-sm {{ $period == $key ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-300 text-gray-600' }}">
            {{ $label }}
        </a>
    @endforeach
</div>