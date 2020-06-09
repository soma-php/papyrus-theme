@php
    $tag = urldecode(request_vars('t', false));

    if ($tag) {
        // Load tag archive
        $pages = all_pages()->filter(function ($page) use ($tag) {
            return in_array($tag, $page->tags ?: []);
        })->sortByDesc('published');

        $pagination = paginate($pages);

        // Canonical
        $canonical = $pagination->current()->url(request_url());

        if ($pagination->hasPrevious()) {
            $prev = $pagination->getPrevious()->url(request_url());
        }
        if ($pagination->hasNext()) {
            $next = $pagination->getNext()->url(request_url());
        }
    }
@endphp

@extends('base', [
    'title' => (($tag) ? '#'.$tag.' | ' : '').'Tag Archive',
    'canonical' => $canonical ?? null,
    'prev' => $prev ?? null,
    'next' => $next ?? null,
])

@section('content')
    <div class="page-header">
        <div class="container container-md">
            <div class="row">
                <div class="col-12 text-center">
                    <h1>{{ $tag ? '#'.$tag : 'Popular tags' }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container container-md">
        <div class="row">
            <div class="col-12">
                @if($tag)                    
                    @if($pages->count())
                        @foreach($pagination->items() as $page)
                            @include('article', ['preview' => true, 'article' => $page])
                        @endforeach

                        @include('pagination', ['pagination' => $pagination])
                    @else
                        <p class="text-center"><em>No posts tagged "{{ $tag }}" found.</em></p>
                    @endif      
                @else
                    @php
                        $tags = [];

                        foreach (all_pages() as $page) {
                            $tags = array_merge($tags, $page->tags ?: []);
                        }

                        $popular = array_count_values($tags);
                        arsort($popular);
                        $top = array_slice($popular, 0, 30, true);
                    @endphp
                    <section class="content">
                        @foreach($top as $tag => $count)
                            <a class="tag" href="{{ app_url(ltrim(config('content.tag-route').urlencode($tag), '/')) }}">
                                {{ $tag }}
                            </a>
                        @endforeach
                    </section>
                @endif                     
            </div>
        </div>
    </div>
@endsection