@extends('web.include.master')
@section('content')

    <div class="rbt-breadcrumb-default ptb--100 ptb_md--50 ptb_sm--30 bg-gradient-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">Cart</h2>
                        <ul class="page-list">
                            <li class="rbt-breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li>
                                <div class="icon-right"><i class="feather-chevron-right"></i></div>
                            </li>
                            <li class="rbt-breadcrumb-item active">Cart</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rbt-cart-area bg-color-white rbt-section-gap">
        <div class="cart_area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-8 col-sm-12 col-12">
                        <form action="#">
                            <!-- Cart Table -->
                            <div class="cart-table table-responsive mb--60">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="pro-title">Course</th>
                                            <th class="pro-price">Price</th>
                                            <th class="pro-remove">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($courses && $courses->isNotEmpty())
                                            @foreach($courses as $data)
                                                <tr>
                                                    <td class="pro-title"><a href="#">{{ $data->course ? $data->course->title : '' }}</a></td>
                                                <td class="pro-price"><span>{{ ($data->course) ? ($data->course->discounted_price ??  $data->course->price) : '0' }} CREDIT</span></td>
                                                <td class="pro-remove"><a href="{{ route('cart.delete', $data->id) }}"><i class="feather-x"></i></a>
                                                </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>


                    <div class="col-lg-5 col-md-4 col-sm-12 col-12">
                        <div class="cart-summary">
                            <div class="cart-summary-wrap">
                                <div class="section-title text-start">
                                    <h4 class="title mb--30">Cart Summary</h4>
                                </div>
                                <p>Sub Total <span>{{ $totalPrice }} CREDIT</span></p>
                                <h2>Grand Total <span>{{ $totalPrice }} CREDIT</span></h2>
                            </div>

                            <div class="cart-submit-btn-group">
                                <div class="single-button w-50">
                                    <a class="rbt-btn btn-gradient rbt-switch-y icon-hover w-100 text-center" href="{{ route('checkout')}}">
                                        <span class="btn-text">Checkout</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </a>
                                </div>
                                <div class="single-button w-50">
                                    <a class="rbt-btn btn-border rbt-switch-y icon-hover w-100 text-center" href="{{ route('courses')}}">
                                        <span class="btn-text">Continue Purchasing</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
