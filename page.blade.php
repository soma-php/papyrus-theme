@extends("base")

@section("content")
    <div class="container container-md">
        <div class="row">
            <div class="col-12 content article-body {{ $page->hasLead() ? 'lead' : '' }}">
                {!! $page->html !!}
            </div>
        </div>
    </div>
@endsection