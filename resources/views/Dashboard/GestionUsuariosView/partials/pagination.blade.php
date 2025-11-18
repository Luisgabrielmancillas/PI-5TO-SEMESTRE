@if ($items instanceof \Illuminate\Contracts\Pagination\Paginator)
    <div class="flex justify-end">
        {{ $items->onEachSide(1)->links('vendor.pagination.hydrobox') }}
    </div>
@endif
