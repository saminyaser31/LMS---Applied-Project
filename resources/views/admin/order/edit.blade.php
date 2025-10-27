@extends('layouts.common.master')
@section('css')
    @parent
    <style>
        /* #order-content-section .ck-editor__editable {
            min-height: 100px !important;
        } */
    </style>
@endsection

@section('content')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ trans('global.edit') }} Order</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <form method="POST" action="{{ route("admin.orders.update", $order->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-12" id="order-basic-section">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="required" for="student_id">Student</label>
                                <select class="form-control search js-example-basic-single {{ $errors->has('student_id') ? 'is-invalid' : '' }}" id="student_id" disabled>
                                    <option value="">Select</option>
                                    @if (isset($students))
                                        @foreach ($students as $key => $data)
                                            <option value="{{ $data->id }}" {{ old('student_id', '') == $data->id || $order->student_id == $data->id ? 'selected' : '' }}>{{ $data->name . ' (' . $data->email . ')' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('student_id'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('student_id') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="course_id">Course</label>
                                <select class="form-control js-example-basic-multiple {{ $errors->has('course_id') ? 'is-invalid' : '' }}" id="course_id" multiple disabled>
                                    <option value="">Select</option>
                                    @if (isset($courses))
                                        @foreach ($courses as $key => $data)
                                            <option value="{{ $data->id }}" {{ old('course_id', '') == $data->id || in_array($data->id, $enrollments) ? 'selected' : '' }}>{{ $data->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('course_id'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('course_id') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="order_type">Order Type</label>
                                <select class="form-control js-example-basic-multiple {{ $errors->has('order_type') ? 'is-invalid' : '' }}" id="order_type" disabled>
                                    <option value="">Select</option>
                                    @foreach ($orderType as $key => $label)
                                        <option {{ old('order_type', '') == $key || $order->order_type == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('order_type'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('order_type') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3" style="display: {{ $order->order_type == \App\Models\Order::NEW_PAID_ORDER || $order->order_type == \App\Models\Order::TEST_ORDER ? 'block' : 'none' }};">
                                <label class="required" for="transaction_id">Transaction ID</label>
                                <input class="form-control {{ $errors->has('transaction_id') ? 'is-invalid' : '' }}" id="transaction_id" value="{{ old('transaction_id') ?? $order->transaction_id }}" disabled>
                                @if($errors->has('transaction_id'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('transaction_id') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="bkash_no">Bkash No</label>
                                <input class="form-control {{ $errors->has('bkash_no') ? 'is-invalid' : '' }}" id="bkash_no" value="{{ old('bkash_no') ?? $order->bkash_no }}" disabled>
                                @if($errors->has('bkash_no'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('bkash_no') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="total">Total</label>
                                <input class="form-control {{ $errors->has('total') ? 'is-invalid' : '' }}" id="total" value="{{ old('total') ?? $order->total }}" disabled>
                                @if($errors->has('total'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('total') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="" for="remarks">Remarks</label>
                                <textarea class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks') ?? $order->remarks }}</textarea>

                                @if($errors->has('remarks'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('remarks') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="status">Status</label>
                                <select class="form-control js-example-basic-multiple {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                                    @foreach ($orderStatus as $key => $label)
                                        <option {{ old('status', '') == $key || $order->status == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('status'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('status') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 text-start">
                    <button class="btn btn-primary" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    @parent

    @include('layouts.common.ckeditor5.43_3_0')
    {{-- @include('layouts.common.ckeditor5.super_build_41_2_1') --}}

    <script>
        $(document).ready(function () {
            const NEW_PAID_ORDER = {!! App\Models\Order::NEW_PAID_ORDER !!};
            const FREE_ORDER = {!! App\Models\Order::FREE_ORDER !!};
            const TEST_ORDER = {!! App\Models\Order::TEST_ORDER !!};

            // Function to toggle Transaction ID field
            function toggleTransactionId(selectedOrderType) {
                if (selectedOrderType == NEW_PAID_ORDER) {
                    $('#transaction_id').closest('.mb-3').show();
                } else if (selectedOrderType == TEST_ORDER) {
                    $('#transaction_id').closest('.mb-3').show();
                } else {
                    $('#transaction_id').closest('.mb-3').hide();
                }
            }

            // Bind change event to order_type select
            $('#order_type').on('change', function () {
                const selectedOrderType = $(this).val();
                toggleTransactionId(selectedOrderType);
            });

            var orderType = $('#order_type').val();
            toggleTransactionId(orderType);

            function updateTotalCost(courseIds) {
                $.ajax({
                    url: "{{ route('admin.orders.total-cost') }}", // Your route to calculate total cost
                    method: "POST",
                    data: {
                        course_ids: courseIds,
                        _token: "{{ csrf_token() }}" // CSRF token for security
                    },
                    success: function (response) {
                        if (response.status === 200) {
                            $('#total').val(response.totalCost); // Update the total field
                        } else {
                            alert(response.message || 'An error occurred while calculating total cost.');
                        }
                    },
                    error: function (xhr) {
                        alert('Failed to calculate total cost. Please try again.');
                    }
                });
            }

            // Event listener for course selection
            $('#course_id').on('change', function () {
                const selectedCourses = $(this).val(); // Get selected course IDs
                if (selectedCourses.length > 0) {
                    updateTotalCost(selectedCourses); // Call the function to update total
                } else {
                    $('#total').val(''); // Clear total if no courses are selected
                }
            });
        });
    </script>
@endsection

