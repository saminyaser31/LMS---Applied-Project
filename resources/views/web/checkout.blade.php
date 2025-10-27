@extends('web.include.master')
@section('content')

    <div class="rbt-breadcrumb-default ptb--100 ptb_md--50 ptb_sm--30 bg-gradient-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">Checkout</h2>
                        <ul class="page-list">
                            <li class="rbt-breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li>
                                <div class="icon-right"><i class="feather-chevron-right"></i></div>
                            </li>
                            <li class="rbt-breadcrumb-item active">Checkout</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="checkout_area bg-color-white rbt-section-gap">
        <div class="container">

            <form action="{{ route('checkout.store') }}" method="post">
                @csrf
                <div class="row g-5 checkout-form">
                    <div class="col-lg-7">
                        <div class="checkout-content-wrapper">

                            <!-- Billing Address -->
                            <div id="billing-form">
                                <div class="row">
                                    <div class="col-md-6 col-12 mb--20">
                                        <label>First Name*</label>
                                        <input type="text" placeholder="First Name" name="first_name" value="{{ old('first_name') }}">
                                        @if ($errors->has('first_name'))
                                            <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 col-12 mb--20">
                                        <label>Last Name*</label>
                                        <input type="text" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}">
                                        @if ($errors->has('last_name'))
                                            <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 col-12 mb--20">
                                        <label>Email Address*</label>
                                        <input type="email" placeholder="Email Address" name="email" value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 col-12 mb--20">
                                        <label>Phone no*</label>
                                        <input type="text" placeholder="Phone number" name="phone" value="{{ old('phone') }}">
                                        @if ($errors->has('phone'))
                                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>

                                    <!-- New QR Code Section -->
                                    <div class="col-12 mb--20">
                                        <label>QR Code</label>
                                        <div class="qr-code-placeholder" style="border: 1px dashed #ccc; display: flex; align-items: center; padding: 10px;">
                                            <img src="/storage/web/bKash-QR-code.jpeg" alt="QR Code" style="max-width: 200px; max-height: 200px; width: auto; height: auto; display: block; margin-right: 10px;" id="qrCodeImage">
                                            <div style="flex-grow: 1; font-size: 14px;">
                                                <span style="color: #aaa;">Scan the QR Code (Personal bkash)</span>
                                                <ul>
                                                    <li>Select the SEND MONEY Option</li>
                                                    <li>Transfer the Amount</li>
                                                    <li>Share the Last 4 Digit of your bkash number</li>
                                                    <li>Share the Transaction ID</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12 mb--20">
                                        <label>Last 4 Digit of your bkash number*</label>
                                        <input type="number" placeholder="Enter Last 4 Digit of your bkash number" name="bkash_no" value="{{ old('bkash_no') }}">
                                        @if ($errors->has('bkash_no'))
                                            <span class="text-danger">{{ $errors->first('bkash_no') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-12 col-12 mb--20">
                                        <label>Transaction ID*</label>
                                        <input type="text" placeholder="Enter Transaction ID" name="transaction_id" value="{{ old('transaction_id') }}">
                                        @if ($errors->has('transaction_id'))
                                            <span class="text-danger">{{ $errors->first('transaction_id') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="row pl--50 pl_md--0 pl_sm--0">
                            <div class="col-12 mb--60">
                                <div class="checkout-cart-total">
                                    <h4>Course <span>Total</span></h4>
                                    <ul>
                                        @foreach ($cartCourses as $data)
                                            <input type="hidden" name="course_id[]" value="{{ $data->course->id }}">
                                            <li>{{ $data->course->title }} <span>{{ $data->course->discounted_price ?? $data->course->price }} CREDIT</span></li>
                                        @endforeach
                                    </ul>

                                    <input type="hidden" name="total" value="{{ $totalPrice }}">
                                    <input type="hidden" name="grand_total" value="{{ $totalPrice }}">
                                    <p>Sub Total <span>{{ $totalPrice }} CREDIT</span></p>
                                    <h4 class="mt--30">Grand Total <span>{{ $totalPrice }} CREDIT</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb--60">
                        <div class="plceholder-button mt--50">
                            <button type="submit" class="rbt-btn btn-gradient hover-icon-reverse">
                                <span class="icon-reverse-wrapper">
                                    <span class="btn-text">Place order</span>
                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
