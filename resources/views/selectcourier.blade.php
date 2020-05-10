@extends('layouts.argon', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    {{--<div class="card-header bg-transparent pb-5">--}}
                    {{--<div class="text-muted text-center mt-2 mb-3"><small>{{ __('Sign in with') }}</small></div>--}}
                    {{--<div class="btn-wrapper text-center">--}}
                    {{--<a href="#" class="btn btn-neutral btn-icon">--}}
                    {{--<span class="btn-inner--icon"><img src="{{ asset('argon') }}/img/icons/common/github.svg"></span>--}}
                    {{--<span class="btn-inner--text">{{ __('Github') }}</span>--}}
                    {{--</a>--}}
                    {{--<a href="#" class="btn btn-neutral btn-icon">--}}
                    {{--<span class="btn-inner--icon"><img src="{{ asset('argon') }}/img/icons/common/google.svg"></span>--}}
                    {{--<span class="btn-inner--text">{{ __('Google') }}</span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="card" style="width: 18rem;">
                            <img class="card-img-top" src="../../assets/img/theme/team-1-800x800.jpg" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem;">
                            <img class="card-img-top" src="../../assets/img/theme/team-1-800x800.jpg" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
