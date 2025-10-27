@extends('web.include.master')
@section('content')

    <!-- Start breadcrumb Area -->
    <div class="rbt-breadcrumb-default ptb--100 ptb_md--50 ptb_sm--30 bg-gradient-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">Faqs</h2>
                        <ul class="page-list">
                            <li class="rbt-breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li>
                                <div class="icon-right"><i class="feather-chevron-right"></i></div>
                            </li>
                            <li class="rbt-breadcrumb-item active">Faqs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb Area -->

    <!-- Start Accordion Area  -->
    <div class="rbt-accordion-area accordion-style-1 bg-color-white rbt-section-gap">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-12">
                    <div class="rbt-accordion-style accordion">
                        {{-- <div class="section-title text-start mb--60">
                            <h4 class="title"></h4>
                        </div> --}}
                        <div class="rbt-accordion-style rbt-accordion-04 accordion">
                            <div class="accordion" id="accordionExamplec3">
                                @foreach($faqs as $key => $faq)
                                    <div class="accordion-item card">
                                        <h2 class="accordion-header card-header" id="headingThree{{ $key }}">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree{{ $key }}" aria-expanded="true" aria-controls="collapseThree{{ $key }}">
                                                {{ $faq->title }}
                                            </button>
                                        </h2>
                                        <div id="collapseThree{{ $key }}" class="accordion-collapse collapse show" aria-labelledby="headingThree{{ $key }}" data-bs-parent="#accordionExamplec3">
                                            <div class="accordion-body card-body">
                                                {!! $faq->description !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Accordion Area  -->

    <div class="rbt-separator-mid">
        <div class="container">
            <hr class="rbt-separator m-0">
        </div>
    </div>

    @endsection
