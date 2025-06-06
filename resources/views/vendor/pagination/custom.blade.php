@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination" style="width: 100%; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; padding: 15px 0; border-top: 1px solid var(--grey);">
        <!-- Phần hiển thị trên mobile -->
        <div class="pagination-mobile" style="display: none; @media (max-width: 768px) { display: flex; gap: 10px; width: 100%; }">
            @if ($paginator->onFirstPage())
                <span class="pagination-btn pagination-btn--disabled" style="padding: 8px 12px; background-color: var(--grey); color: var(--dark); border-radius: 5px; cursor: not-allowed;">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" style="padding: 8px 12px; background-color: var(--blue); color: var(--light); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" style="padding: 8px 12px; background-color: var(--blue); color: var(--light); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="pagination-btn pagination-btn--disabled" style="padding: 8px 12px; background-color: var(--grey); color: var(--dark); border-radius: 5px; cursor: not-allowed;">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <!-- Phần hiển thị trên desktop -->
        <div class="pagination-desktop" style="display: flex; align-items: center; width: 100%; @media (max-width: 768px) { display: none; }">
            <div class="pagination-info" style="margin-right: 20px; color: var(--dark);">
                <p>
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="pagination-number" style="font-weight: 600;">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="pagination-number" style="font-weight: 600;">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="pagination-number" style="font-weight: 600;">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div class="pagination-links" style="display: flex; gap: 5px; align-items: center;">
                <!-- Previous Page Link -->
                @if ($paginator->onFirstPage())
                    <span class="pagination-btn pagination-btn--disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}" style="padding: 8px 12px; background-color: var(--grey); color: var(--dark); border-radius: 5px; cursor: not-allowed; display: inline-flex; align-items: center; gap: 5px;">
                        <svg class="pagination-icon" fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn" aria-label="{{ __('pagination.previous') }}" style="padding: 8px 12px; background-color: var(--blue); color: var(--light); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                        <svg class="pagination-icon" fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                <!-- Pagination Elements -->
                @foreach ($elements as $element)
                    <!-- "Three Dots" Separator -->
                    @if (is_string($element))
                        <span class="pagination-btn pagination-btn--disabled" aria-disabled="true" style="padding: 8px 12px; background-color: var(--grey); color: var(--dark); border-radius: 5px; cursor: not-allowed;">
                            {{ $element }}
                        </span>
                    @endif

                    <!-- Array Of Links -->
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-btn pagination-btn--active" aria-current="page" style="padding: 8px 12px; background-color: var(--blue); color: var(--light); border-radius: 5px; font-weight: 600;">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="pagination-btn" aria-label="{{ __('Go to page :page', ['page' => $page]) }}" style="padding: 8px 12px; background-color: var(--light); color: var(--dark); border-radius: 5px; text-decoration: none;">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                <!-- Next Page Link -->
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn" aria-label="{{ __('pagination.next') }}" style="padding: 8px 12px; background-color: var(--blue); color: var(--light); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                        <svg class="pagination-icon" fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span class="pagination-btn pagination-btn--disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}" style="padding: 8px 12px; background-color: var(--grey); color: var(--dark); border-radius: 5px; cursor: not-allowed; display: inline-flex; align-items: center; gap: 5px;">
                        <svg class="pagination-icon" fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif