{{-- filepath: resources/views/vendor/pagination/default.blade.php --}}
@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link"><i class="fa fa-angle-left"></i></span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="fa fa-angle-left"></i></a></li>
            @endif

            {{-- Page X of Y --}}
            <li class="page-status">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</li>

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="fa fa-angle-right"></i></a></li>
            @else
                <li class="page-item disabled"><span class="page-link"><i class="fa fa-angle-right"></i></span></li>
            @endif
        </ul>
    </nav>
@endif