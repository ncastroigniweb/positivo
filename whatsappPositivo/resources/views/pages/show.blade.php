@extends('layouts.front', ['class' => ''])
<style>
    .masthead{
        /* background-image: none !important; */
        /* height: 500 !important;
        background-position: bottom !important; */
    }
    .display-2, .page-title{
        padding-top: 250px;
    }
</style>
@section('content')
    <header class="masthead" style="{{ 'background-image: url('.config('global.restorant_details_cover_image').')' }}">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 text-center">
                    {{-- <h1 class="display-2 page-title">{{ $page->title }}</h1> --}}
                </div>
            </div>
        </div>
    </header>
    <section class="section">
        <div class="container container-pages">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title white">
                        {!! $page->content !!} 
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
