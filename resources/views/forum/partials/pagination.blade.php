@if ($paginator->lastPage() > 1)
	<nav>
		<ul class="pagination pagination-sm">
			<li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
				<a href="{{ $paginator->url( $paginator->currentPage() - 1 ) }}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
			</li>

			@if ( $paginator->currentPage() > 6 )
				<li class="disabled"><a href="javascript: void(0);" aria-label="andLess">...</a></li>
			@endif

			@if ( $paginator->currentPage() > 1 )
				@for ($i = ( $paginator->currentPage() > 5 ? $paginator->currentPage() - 5 : 1 ); $i <= $paginator->currentPage() - 1; $i++)
					<li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
						<a href="{{ $paginator->url($i) }}">{{ $i }}</a>
					</li>
				@endfor
			@endif

            <li class="active"><a href="{{ $paginator->url($paginator->currentPage()) }}">{{ $paginator->currentPage() }}</a></li>

            @if ( $paginator->currentPage() < $paginator->lastPage() )
                @for ($i = $paginator->currentPage() + 1; $i <= ( $paginator->lastPage() - $paginator->currentPage() > 5 ? $paginator->currentPage() + 5 : $paginator->lastPage() ); $i++)
    				<li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
    					<a href="{{ $paginator->url($i) }}">{{ $i }}</a>
    				</li>
    			@endfor
            @endif

			@if ( $paginator->lastPage() - $paginator->currentPage() > 6 )
				<li class="disabled"><a href="javascript: void(0);" aria-label="andMore">...</a></li>
			@endif

			<li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
				<a href="{{ $paginator->url($paginator->currentPage() + 1) }}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
			</li>
		</ul>
	</nav>
@endif
