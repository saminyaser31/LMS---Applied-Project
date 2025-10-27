@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Order {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            @can('create_order')
                <div class="row">
                    <div class="col-12">
                        <div style="margin-bottom: 10px;" class="row">
                            <div class="col-lg-12 text-end">
                                <a class="btn btn-success" href="{{ route('admin.orders.create') }}">
                                    {{ trans('global.add') }} Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Total Order- {{ $totalOrders }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('admin.orders.index') }}" method="GET">
                                        <div class="row d-flex flex-row align-items-center card-body">

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="required" for="student_id">Student</label>
                                                    <select class="form-control search select2 {{ $errors->has('student_id') ? 'is-invalid' : '' }}" name="student_id" id="student_id">
                                                        <option value="">Select</option>
                                                        @if (isset($students))
                                                            @foreach ($students as $key => $data)
                                                                <option value="{{ $data->id }}" {{ request('student_id', '') == $data->id ? 'selected' : '' }}>{{ $data->name . ' (' . $data->email . ')' }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @if($errors->has('student_id'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('student_id') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="created_time_from">Created Time (From)</label>
                                                    <input class="form-control datetimepicker" value="{{ request('created_time_from') }}" type="text" name="created_time_from" id="created_time_from">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="created_time_from">Created Time (To)</label>
                                                    <input class="form-control datetimepicker" value="{{ request('created_time_to') }}" type="text" name="created_time_to" id="created_time_to">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="" for="order_type">Order Type</label>
                                                    <select class="form-control {{ $errors->has('order_type') ? 'is-invalid' : '' }}" name="order_type" id="order_type">
                                                        <option value="">Select</option>
                                                        @foreach ($orderType as $key => $label)
                                                            <option {{ request('order_type', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="" for="order_status">Order status</label>
                                                    <select class="form-control {{ $errors->has('order_status') ? 'is-invalid' : '' }}" name="order_status" id="order_status">
                                                        <option value="">Select</option>
                                                        @foreach ($orderStatus as $key => $label)
                                                            <option {{ request('order_status', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-3">
                                                <button type="submit" class="btn btn-success btn-md mr-3" style="padding: 6px 19px;"><i class="mdi mdi-file-search-outline"></i> Search</button>
                                                <a href="{{ route('admin.orders.index') }}" class="btn btn-warning btn-md">Clear</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Order List</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                @if (!($orders->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Email</th>
                                            <th>Price</th>
                                            <th>Transaction ID</th>
                                            <th>Order type</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($orders as $key => $order)
                                        <tr>
                                            {{-- <td>{{ $orders->firstItem() + $key }}</td> --}}
                                            <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                            <td>{{ $order->student ? $order->student->email : '' }}</td>
                                            <td>{{ $order->total }}</td>
                                            <td>{{ $order->transaction_id }}</td>
                                            <td>{{ $order->order_type }}</td>
                                            <td>
                                                @if ($order->status == App\Models\Order::STATUS_PENDING)
                                                    <span class="badge bg-warning">{{ App\Models\Order::STATUS_SELECT[$order->status] }}</span>
                                                @elseif ($order->status == App\Models\Order::STATUS_ENABLE)
                                                    <span class="badge bg-success">{{ App\Models\Order::STATUS_SELECT[$order->status] }}</span>
                                                @elseif ($order->status == App\Models\Order::STATUS_DISABLE)
                                                    <span class="badge bg-danger">{{ App\Models\Order::STATUS_SELECT[$order->status] }}</span>
                                                    {{ App\Models\Order::STATUS_SELECT[$order->status] }}
                                                @endif
                                            </td>
                                            <td>{{ ($order->createdBy) ? $order->createdBy->email : '' }}</td>
                                            <td>
                                                @can('edit_order')
                                                    <a class="btn btn-sm btn-primary mt-2" href="{{ route('admin.orders.edit', $order->id) }}">
                                                        {{ trans('global.edit') }}
                                                    </a>
                                                @endcan
                                                @can('delete_order')
                                                    <a class="btn btn-sm btn-danger mt-2" onclick="return confirm('{{ trans('global.areYouSure') }}');"
                                                        href="{{ route('admin.orders.delete', $order->id) }}">
                                                        {{ trans('global.delete') }}
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td rowspan="5">
                                            <h5 class="text-center">Not available</h5>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        @parent
        <script>
        </script>
    @endsection
