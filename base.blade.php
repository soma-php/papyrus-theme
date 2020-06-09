<!DOCTYPE html>
<html lang="{{ config('content.language', 'en-US') }}" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @php
        $title = $title ?? $page->title;
        $description = strip_bare($page->description ?? $page->excerpt ?? config('content.description', ''));
        $author = $page->author ?: config('content.author.name', '');
        $robots = $page->robots ?: config('content.robots', 'index, follow');
        $keywords = implode(', ', $page->keywords ?: []);

        if ($page->isDraft()) {
            $robots = 'no-index, no-follow';
        }
    @endphp

    <title>{{ ($title ? $title.' | ' : '').config('content.title') }}</title>

    {{-- Set canonical --}}
    <link rel="canonical" href="{{ $canonical ?? $page->url }}">

    @if(isset($prev) && ! is_null($prev))
        <link rel="prev" href="{{ $prev }}">
    @endif
    @if(isset($next) && ! is_null($next))
        <link rel="next" href="{{ $next }}">
    @endif

    {{-- Meta and social --}}
    <meta name="description" content="{{ $description }}">
    <meta name="author" content="{{ $author }}">
    <meta name="robots" content="{{ $robots }}">
    <meta name="keywords" content="{{ $keywords }}"> 

    <meta name="og:type" content="{{ request_uri() == '/' ? 'website' : 'article' }}">
    <meta name="og:title" content="{{ $title }}">
    <meta name="og:description" content="{{ $description }}">
    <meta name="og:url" content="{{ $canonical ?? $page->url }}">
    <meta name="og:site_name" content="{{ config('content.title') }}">

    @if($page->image)
        <meta name="og:image" content="{{ $page->image->src }}">
    @endif

    @include('dist.styles')

    @yield('head')
</head>
<body>
    <div id="menu" class="sticky-top d-print-none">
        <div class="container container-md">
            <nav class="navbar navbar-expand-lg navbar-light bg-outline px-0">
                <a class="navbar-brand" href="{{ app_url() }}">{{ config('content.title') }}</a>

                <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        @foreach(Content::menu('main') as $item)
                            @if(isset($item['route']))
                                @php
                                    $uri = (ends_with($item['route'], 'index')) ? substr($item['route'], 0, -5) : $item['route'];
                                    $active = (request_uri() == $uri) ? 'active' : '';
                                @endphp
                            @endif
                            
                            <li class="nav-item {{ $active ?? '' }}">
                                <a class="nav-link" href="{{ $item['url'] }}">{{ $item['label'] ?? 'Link' }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    {{-- Container --}}
	<main id="content">
        @yield('content')
    </main>

    {{-- Scroll to top --}}
    <a id="btn-to-top" href="#" class="btn btn-outline btn-lg btn-to-top d-print-none" role="button">
        <i class="fas fa-chevron-up"></i>
    </a>

    <footer id="footer">
        <p class="copyright">
            Copyright 2020{{ (date("Y") != '2020') ? '-'.date("Y") : '' }} &copy; {{ config('content.author.name') }}
            @if($contact = get_page('/contact.md'))
               <span class="d-print-none">- <a href="{{ $contact->url }}">Contact</a></span>
            @endif
        </p>
    </footer>
    
    @include('dist.scripts')
</body>
</html>
