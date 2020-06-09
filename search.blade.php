@php
    $terms = urldecode(request_vars('q', ''));

    if ($terms) {
        $pages = query_pages('/blog/');
        $search = search($terms, $pages);
        $pagination = paginate($search, 1);

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
    'title' => $terms ? 'Search: "'.$terms.'"' : 'Search',
    'canonical' => $canonical ?? null,
    'prev' => $prev ?? null,
    'next' => $next ?? null,
])

@section('content')
    <div class="page-header">
        <div class="container container-md">
            <div class="row">
                <div class="col-12 text-center">         
                    <form id="search-form" class="form-inline my-2 my-lg-0" action="{{ $page->url }}">
                        <input id="search-input" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="q" value="{{ $terms }}">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>
                    <script type="text/javascript">
                        // Intercept form submit and go to the search results page directly, avoiding a redirect
                        document.getElementById('search-form').addEventListener('submit', function (e) {
                            var search_terms = document.getElementById('search-input').value;
                            location.href = "{{ $page->url }}?q=" + encodeURIComponent(search_terms);
                            e.preventDefault();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    @if($terms)
        <div class="container container-md">
            <div class="row">
                <div class="col-12">
                    <h1>Search results</h1>
                    @if($count = $search->results()->count())
                        <p class="text-center"><em>{{ $count }} article(s) found for "{{ $terms }}".</em></p>
                        <section id="search-results" class="content">
                            @foreach($pagination->items() as $result)
                                @php
                                    $resultTitle = $search->highlightTerms($result->title);
                                    $resultSummary = $search->getSummary($result, 75);
                                    $resultSummary = $search->highlightTerms($resultSummary);
                                @endphp

                                <a href="{{ $result->url }}" class="search-item article">
                                    <div class="row">
                                        @if($result->hasImage())
                                            <div class="col-12 col-sm-3">
                                                {!! $result->image->display(['class' => 'thumbnail', 'sizes' => '(max-width: 576px) 576px, 200px']) !!}
                                            </div>
                                        @endif
                                        <div class="{{ $result->hasImage() ? 'col-12 col-sm-9' : 'col-12' }}">
                                            <h2>{!! $resultTitle !!}</h2>
                                            <div class="meta">
                                                {{ $result->author }}, {{ format_date($result->published) }}
                                                @if($result->updated && (clone $result->updated)->setTime(0,0) > (clone $result->published)->setTime(0,0))
                                                    (updated: {{ format_date($result->updated) }})
                                                @endif
                                            </div>
                                            <p class="summary">{!! $resultSummary !!}</p>
                                            @if($result->tags)
                                                <div class="tags">
                                                    @foreach($result->tags as $tag)
                                                        <span class="tag">{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div> 
                                    </div>
                                </a>
                            @endforeach
                        </section>

                        {{-- Pagination --}}
                        @include('pagination', ['pagination' => $pagination, 'labelPrevious' => 'Back', 'labelNext' => 'More'])
                    @else
                        <p class="text-center"><em>No results found for "{{ $terms }}".</em></p>
                    @endif                        
                </div>
            </div>
        </div>
    @endif
@endsection