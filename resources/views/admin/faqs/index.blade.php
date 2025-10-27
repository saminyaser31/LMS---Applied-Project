@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">FAQs {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-12">
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12 text-end">
                            <a class="btn btn-success" href="{{ route('admin.faqs.create') }}">
                                {{ trans('global.add') }} FAQs
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Total FAQs- {{ $totalFaqs }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('admin.faqs.index') }}" method="GET">
                                        <div class="row d-flex flex-row align-items-center card-body">

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="title">Title</label>
                                                    <input class="form-control" value="{{ request('title') }}" type="text" name="title" id="title">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="" for="faqs_status">FAQs status</label>
                                                    <select class="form-control {{ $errors->has('faqs_status') ? 'is-invalid' : '' }}" name="faqs_status" id="faqs_status">
                                                        <option value="">Select</option>
                                                        @foreach ($faqStatus as $key => $label)
                                                            <option {{ request('faqs_status', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-3">
                                                <button type="submit" class="btn btn-success btn-md mr-3" style="padding: 6px 19px;"><i class="mdi mdi-file-search-outline"></i> Search</button>
                                                <a href="{{ route('admin.faqs.index') }}" class="btn btn-warning btn-md">Clear</a>
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
                            <h4>FAQs List</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                @if (!($faqs->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Title</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($faqs as $key => $faq)
                                        <tr>
                                            {{-- <td>{{ $faqs->firstItem() + $key }}</td> --}}
                                            <td>{{ ($faqs->currentPage() - 1) * $faqs->perPage() + $loop->iteration }}</td>
                                            <td>{{ $faq->title }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary mt-2"
                                                    href="{{ route('admin.faqs.show', $faq->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                                <a class="btn btn-sm btn-info mt-2"
                                                    href="{{ route('admin.faqs.edit', $faq->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>

                                                <form action="{{ route('admin.faqs.delete', $faq->id) }}" method="POST"
                                                    onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                    style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger mt-2" value="{{ trans('global.delete') }}">
                                                </form>
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
                        {{ $faqs->withQueryString()->links('pagination::bootstrap-5') }}
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
