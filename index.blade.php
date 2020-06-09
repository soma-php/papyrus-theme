@php
    $pages = query_pages("/blog/")->sortByDesc('published');
    $pagination = paginate($pages);

    // Canonical
    $canonical = $pagination->current()->url(request_url());

    if ($pagination->hasPrevious()) {
        $prev = $pagination->getPrevious()->url(request_url());
    }
    if ($pagination->hasNext()) {
        $next = $pagination->getNext()->url(request_url());
    }
@endphp

@extends('base', [
    'canonical' => $canonical ?? null,
    'prev' => $prev ?? null,
    'next' => $next ?? null,
])

@section('content')
    <div class="container container-md">
        <div class="row">
            <div class="col-12">
                <h1 class="d-none">Blog archive</h1>
                
                @foreach($pagination->items() as $post)
                    @include('article', ['article' => $post, 'preview' => true])
                @endforeach

                {{-- Pagination --}}
                @include('pagination', ['pagination' => $pagination])
            </div>
        </div>
    </div>
@endsection