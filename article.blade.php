@php
    $preview = $preview ?? false;
    $nav = $nav ?? false;
@endphp
<article class="article content {{ $preview ? 'preview' : 'single' }}">
    {{-- Heading --}}
    <header class="heading">
        <hgroup>
            <{{ $preview ? 'h2' : 'h1' }}>
            @if($preview)
                <a href="{{ $article->url }}">{{ $article->title }}</a>
            @else
                {{ $article->title }}
            @endif
            </{{ $preview ? 'h2' : 'h1' }}> 
            <div class="meta">
                <dl>
                    <dt>Published on</dt>
                    <dd>{{ format_date($article->published) }}</dd>
                    <dt>by</dt>
                    <dd>{{ $article->author }}</dd>
                    @if($article->updated && (clone $article->updated)->setTime(0,0) > (clone $article->published)->setTime(0,0))
                        <dt>and updated</dt>
                        <dd>{{ format_date($article->updated) }}</dd>
                    @endif
                </dl>
            </div>
        </hgroup>
    </header>

    {{-- Featured Image --}}
    @if($article->image)
        {!! $article->image->display(['class' => 'featured_image']) !!}
    @endif
    {{-- Body --}}
    @if($preview)
        <blockquote cite="{{ $article->url }}" class="excerpt">{!! markdown($article->excerpt) !!}</blockquote>
    @else
        <div class="article-body {{ $article->hasLead() ? 'lead' : '' }}">
            {!! $article->html !!}
        </div>
    @endif

    @if($article->tags)
        <footer class="tags d-print-none">
            @foreach($article->tags as $tag)
                <a class="tag" href="{{ app_url(ltrim(config('content.tag-route').urlencode($tag), '/')) }}">
                    {{ $tag }}
                </a>
            @endforeach
        </footer>
    @endif

    @unless($preview)
        <div class="sharing d-print-none">
            @include('sharing', ['url' => current_url(), 'text' => $article->title])
        </div>
    @endunless

    {{-- Nav + Author --}}
    @if($preview)
        <nav class="more">
            <a class="btn btn-light" href="{{ $article->url }}">Continue reading <i class="fas fa-chevron-right"></i></a>
        </nav>
    @else
        @php
            $author = $article->author ?? config('content.author.name');
            $email = $article->email ?? config('content.author.email');
            $bio = $article->bio ?? config('content.author.bio');
        @endphp

        <div class="signature">
            <div class="author">
                <p class="name d-none d-sm-block">{{ $author }}</p>
                <div class="bio">{!! markdown($bio) !!}</div>
            </div>
        </div>
        @if($nav)
            @php
                $pages = query_pages("/blog/")->sortByDesc('published');

                $pagination = (paginate($pages, 1))->find(function($paged) use ($article) {
                    return ($paged->items()->first()->id == $article->id) ? true : false;
                });
            @endphp
            <nav class="post-nav row d-print-none">
                <div class="col-6">
                    @if($pagination->hasPrevious())
                        @php
                            $previous = $pagination->getPrevious()->items()->first();
                        @endphp
                        <a class="post-next" href="{{ $previous->url }}">
                            <span class="label"><i class="fas fa-chevron-left"></i> Next post</span>
                            <span class="title">{{ $previous->title }}</span>
                        </a>
                    @endif
                </div>
                <div class="col-6">
                    @if($pagination->hasNext())
                        @php
                            $next = $pagination->getNext()->items()->first();
                        @endphp
                        <a class="post-previous" href="{{ $next->url }}">
                            <span class="label">Previous post <i class="fas fa-chevron-right"></i></span>
                            <span class="title">{{ $next->title }}</span>
                        </a>
                    @endif
                </div>
            </nav>
        @endif
    @endif
</article>
