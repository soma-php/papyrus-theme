@if($pagination->count() > 1)    
    <nav> 
        {{-- Newer --}}
        @if($pagination->hasPrevious())
            <a class="btn btn-outline-danger float-left" href="{{ $pagination->getPrevious()->url(request_url()) }}">
                <i class="fas fa-chevron-left"></i> {{ $labelPrevious ?? 'Newer' }}
            </a>
        @endif
        {{-- Older --}}
        @if($pagination->hasNext())
            <a class="btn btn-outline-danger float-right" href="{{ $pagination->getNext()->url(request_url()) }}">
                {{ $labelNext ?? 'Older' }} <i class="fas fa-chevron-right"></i> 
            </a>
        @endif
    </nav>
@endif