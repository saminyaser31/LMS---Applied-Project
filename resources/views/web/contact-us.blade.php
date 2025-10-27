@extends('web.include.master')
@section('content')

    <div class="rbt-conatct-area bg-gradient-11 rbt-section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title text-center mb--60">
                        <span class="subtitle bg-secondary-opacity" style="background: #f68c1e !important; color: #ffffff !important;">Contact Us</span>
                        <h2 class="title">{{ $contactUs->title ?? '' }}</h2>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12 sal-animate" data-sal="slide-up" data-sal-delay="150" data-sal-duration="800">
                    <div class="rbt-address">
                        <div class="icon">
                            <i class="feather-headphones"></i>
                        </div>
                        <div class="inner">
                            <h4 class="title">Contact Phone Number</h4>
                            <p><a href="tel:{{ $contactUs->phone_no_1 ?? '' }}">{{ $contactUs->phone_no_1 ?? '' }}</a></p>
                            <p><a href="tel:{{ $contactUs->phone_no_2 ?? '' }}">{{ $contactUs->phone_no_2 ?? '' }}</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12 sal-animate" data-sal="slide-up" data-sal-delay="200" data-sal-duration="800">
                    <div class="rbt-address">
                        <div class="icon">
                            <i class="feather-mail"></i>
                        </div>
                        <div class="inner">
                            <h4 class="title">Our Email Address</h4>
                            <p><a href="mailto:{{ $contactUs->email_1 ?? '' }}">{{ $contactUs->email_1 ?? '' }}</a></p>
                            <p><a href="mailto:{{ $contactUs->email_2 ?? '' }}">{{ $contactUs->email_2 ?? '' }}</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12 sal-animate" data-sal="slide-up" data-sal-delay="250" data-sal-duration="800">
                    <div class="rbt-address">
                        <div class="icon">
                            <i class="feather-map-pin"></i>
                        </div>
                        <div class="inner">
                            <h4 class="title">Our Location</h4>
                            <p>{{ $contactUs->location_1 ?? '' }}</p>
                            <p>{{ $contactUs->location_2 ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rbt-contact-address">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="thumbnail">
                        <img class="w-100 radius-6" src="{{ $contactUs->form_image ?? '#' }}" alt="Contact Images">
                    </div>
                </div>

                <div class="col-lg-6">
                    @include('layouts.common.session-message')

                    <div class="rbt-contact-form contact-form-style-1 max-width-auto">
                        <div class="section-title text-start">
                            <span class="subtitle bg-coral-opacity">{{ $contactUs->form_title ?? '' }}</span>
                        </div>
                    <h3 class="title">{{ $contactUs->form_subtitle ?? '' }}</h3>
                        <form id="contact-form" method="POST" action="{{ route('contact-message.store') }}" class="max-width-auto">
                            @csrf
                            <div class="form-group">
                                <input class="{{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" id="contact-name" type="text">
                                <label>Name</label>
                                @if($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <input class="{{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" type="email">
                                <label>Email</label>
                                @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <input class="{{ $errors->has('phone_no') ? 'is-invalid' : '' }}" name="phone_no" type="text">
                                <label>Phone No</label>
                                @if($errors->has('phone_no'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('phone_no') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <input class="{{ $errors->has('subject') ? 'is-invalid' : '' }}" type="text" id="subject" name="subject">
                                <label>Your Subject</label>
                                @if($errors->has('subject'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('subject') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <textarea class="{{ $errors->has('message') ? 'is-invalid' : '' }}" name="message" id="message"></textarea>
                                <label>Message</label>
                                @if($errors->has('message'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('message') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-submit-group">
                                <button name="submit" type="submit" class="rbt-btn btn-md btn-gradient hover-icon-reverse w-100">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">SEND</span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
