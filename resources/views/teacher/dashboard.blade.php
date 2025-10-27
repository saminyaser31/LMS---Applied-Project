@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">

                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col">

                    <div class="h-100">
                        <div class="row mb-3 pb-1">
                            <div class="col-12">
                                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                    <div class="flex-grow-1">
                                        {{-- <h4 class="fs-16 mb-1">Hello, {{Auth::user()->name}}!</h4> --}}
                                        <!-- <p class="text-muted mb-0">Here's your employee list.</p> -->
                                    </div>
                                </div>
                            </div>

                            @if(Auth::user()->approved == App\Models\User::STATUS_PENDING)
                                <div class="col-12 mt-2">
                                    <!-- Alert -->
                                    <div class="alert alert-success alert-dismissible alert-additional shadow fade show" role="alert">
                                        <div class="alert-body">
                                            {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="ri-error-warning-line fs-16 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="alert-heading">Hello, {{Auth::user()->name}}!</h5>
                                                    <p class="mb-0"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert-content">
                                            <p class="mb-0">Your account is under review. Please wait for verification and approval based on the information you've provided. Once approved, you will have access to all available modules.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div> <!-- end .h-100-->

                </div> <!-- end col -->


            </div>

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
