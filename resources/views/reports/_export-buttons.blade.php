<div class="flex gap-2">
    <a href="{{ route($routeName . '.pdf', request()->query()) }}"
       class="bg-red-50 text-red-700 border border-red-200 px-3 py-1.5 rounded-lg text-sm hover:bg-red-100">
        Export PDF
    </a>
    <a href="{{ route($routeName . '.excel', request()->query()) }}"
       class="bg-green-50 text-green-700 border border-green-200 px-3 py-1.5 rounded-lg text-sm hover:bg-green-100">
        Export Excel
    </a>
</div>