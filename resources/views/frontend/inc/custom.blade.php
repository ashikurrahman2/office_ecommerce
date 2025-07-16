@if ($paginator->hasPages())
<!-- Inline CSS -->
<style>
    .no-border {
        border: none !important;
        background: none !important;
    }
    .no-border:hover {
        color: red !important;
    }
    .no-border:focus {
        color: red !important;
    }
</style>

    <nav>
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link no-border">Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link no-border" href="{{ $paginator->previousPageUrl() }}@if(request('imageUrl'))&imageUrl={{ urlencode(request('imageUrl')) }}@endif" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @elseif ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2)
                            {{-- Display a few pages around the current page --}}
                            <li class="page-item"><a class="page-link" href="{{ $url }}@if(request('imageUrl'))&imageUrl={{ urlencode(request('imageUrl')) }}@endif">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link no-border" href="{{ $paginator->nextPageUrl() }}@if(request('imageUrl'))&imageUrl={{ urlencode(request('imageUrl')) }}@endif" rel="next" aria-label="@lang('pagination.next')">Next</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link no-border">Next</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const paginationLinks = document.querySelectorAll('.pagination a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function () {
                    showGlobalLoader();
                });
            });
        
            // After page reload
            hideGlobalLoader();
        });

        
        
    </script>
    

