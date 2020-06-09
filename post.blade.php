@extends("base")

@section("content")
    <div class="container container-md">
        <div class="row">
            <div class="col-12">
                @include('article', [
                    'article' => $page,
                    'nav' => true,
                ])
            </div>
        </div>
    </div>
@endsection