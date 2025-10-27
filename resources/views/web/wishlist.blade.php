@extends('web.include.master')
@section('content')

    <div class="rbt-breadcrumb-default ptb--100 ptb_md--50 ptb_sm--30 bg-gradient-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">Wishlist</h2>
                        <ul class="page-list">
                            <li class="rbt-breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li>
                                <div class="icon-right"><i class="feather-chevron-right"></i></div>
                            </li>
                            <li class="rbt-breadcrumb-item active">Wishlist</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wishlist_area bg-color-white rbt-section-gap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="#">
                        <div class="cart-table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="pro-title">Course</th>
                                        <th class="pro-price">Price</th>
                                        <th class="pro-remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courses as $data)
                                        <tr>
                                            <td class="pro-title"><a href="#">{{ $data->course->title }}</a></td>
                                            <td class="pro-price"><span>{{ $data->course->price }} CREDIT</span></td>
                                            <td class="pro-remove"><a href="{{ route('cart.delete', $data->id) }}"><i class="feather-x"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
