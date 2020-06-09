@extends("base")

@section("content")
    <div class="container container-md">
        <div class="row">
            <div class="col-12 content">
                {!! $page->html !!}
                <div class="text-left contact-button">
                    <a href="{{ $page->form ?? config('content.contact_form', '') }}" class="btn btn-lg btn-outline-success" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Contact
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection