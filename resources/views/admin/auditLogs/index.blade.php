@extends('layouts.common.master')
@section('content')
    <style>
        .audit-modal-wrapper {
            max-width: 100%;
            overflow-x: auto;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            {{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }} :
                            Total-{{ $auditLogs->toArray()['total'] }}
                        </div>

                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('admin.audit-logs.index') }}" method="GET">
                                        <div class="row d-flex flex-row align-items-center card-body">

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label
                                                        for="description">{{ trans('cruds.auditLog.fields.description') }}</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ request('description') }}" name="description">
                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label
                                                        for="subject_id">{{ trans('cruds.auditLog.fields.subject_id') }}</label>
                                                    <input class="form-control" value="{{ request('subject_id') }}"
                                                        type="text" name="subject_id" id="subject_id">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label
                                                        for="subject_type">{{ trans('cruds.auditLog.fields.subject_type') }}</label>
                                                    <input class="form-control" value="{{ request('subject_type') }}"
                                                        type="text" name="subject_type" id="subject_type">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label
                                                        for="subject_type">{{ trans('cruds.auditLog.fields.user_id') }}</label>
                                                    <input class="form-control" value="{{ request('user_id') }}"
                                                        type="text" name="user_id" id="user_id">
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-3">
                                                <button type="submit" class="btn btn-success btn-md mr-3" style="padding: 6px 19px;"><i class="mdi mdi-file-search-outline"></i> Search</button>
                                                <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-danger btn-md">Clear</a>
                                            </div>
                                        </div>
                                    </form>

                                </div>



                            </div>
                        </div>

                        <div class="table-responsive">

                            <table class="table table-striped" id="clients-table">
                                @if ($auditLogs->count())
                                    <thead>

                                        <tr>
                                            <th>
                                                {{ trans('cruds.auditLog.fields.id') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.auditLog.fields.description') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.auditLog.fields.subject_id') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.auditLog.fields.subject_type') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.auditLog.fields.user_id') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.auditLog.fields.created_at') }}
                                            </th>
                                        </tr>

                                    </thead>
                                    <tbody>

                                        <?php $i = $auditLogs->toArray()['from']; ?>
                                        @foreach ($auditLogs as $auditLog)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $auditLog->description }}</td>
                                                <td>{{ $auditLog->subject_id }}</td>
                                                <td> {{ $auditLog->subject_type ?? '' }}</td>
                                                <td> {{ $auditLog->user_id ?? '' }}</td>
                                                <td> {{ $auditLog->created_at ? globalDateTimeConverter($auditLog->created_at) : '' }}
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                        class="btn btn-sm btn-info show-audit-log" data-toggle="modal"
                                                        data-audit-log-id="{{ $auditLog->id }}"
                                                        data-modal-id="auditLogModal_{{ $auditLog->id }}"
                                                        data-modal-title="Audit Log Details (ID: {{ $auditLog->id }})">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>

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
                        {{ $auditLogs->withQueryString()->links('pagination::bootstrap-4') }}
                        </section>

                    </div>
                </div>

            </div>
        </div>

@endsection
@section('scripts')
    @parent
    <script>
        $('.show-audit-log').click(function() {
            var auditLogId = $(this).data('audit-log-id');
            var modalId = $(this).data('modal-id');
            var modalTitle = $(this).data('modal-title');
            var url = "{{ route('admin.audit-logs.show', ':auditLogId') }}".replace(':auditLogId', auditLogId);

            // Create a dynamic modal
            var dynamicModal = $(
                '<div class="modal fade" id="' + modalId + '" tabindex="-1" role="dialog" aria-labelledby="' +
                modalId + 'Label" aria-hidden="true">' +
                '    <div class="modal-dialog modal-lg" role="document">' +
                '        <div class="modal-content">' +
                '            <div class="modal-header">' +
                '                <h5 class="modal-title" id="' + modalId + 'Label">' + modalTitle + '</h5>' +
                '                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>' +
                '            </div>' +
                '            <div class="modal-body">' +
                '                <div id="' + modalId + 'Details" class="audit-modal-wrapper"></div>' +
                '            </div>' +
                '            <div class="modal-footer">' +
                '                <button type="button" class="btn btn-light btn-secondary" data-bs-dismiss="modal">Close</button>' +
                '            </div>' +
                '        </div>' +
                '    </div>' +
                '</div>'
            );

            // Append the modal to the body
            $('body').append(dynamicModal);

            // Make an AJAX request
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var auditLogDetails = $("#" + modalId + "Details");
                    auditLogDetails.empty();

                    // Create a table and append it to the modal body
                    var table = $('<table class="table table-bordered table-striped"></table>');
                    var tbody = $('<tbody></tbody>');

                    // Add rows and data to the table
                    tbody.append('<tr><th>Audit Log ID</th><td>' + data.id + '</td></tr>');
                    tbody.append('<tr><th>Description</th><td>' + data.description + '</td></tr>');
                    tbody.append('<tr><th>Subject ID</th><td>' + data.subject_id + '</td></tr>');
                    tbody.append('<tr><th>Subject Type</th><td>' + data.subject_type + '</td></tr>');
                    tbody.append('<tr><th>User ID</th><td>' + data.user_id + '</td></tr>');
                    tbody.append('<tr><th>Host</th><td>' + data.host + '</td></tr>');
                    tbody.append('<tr><th>Created at</th><td>' + data.created_at + '</td></tr>');

                    // Create a nested table for 'data.properties'
                    var nestedTable = $('<tr><th>Properties</th></tr>');
                    var nestedTbody = $('<td></td>');

                    // Add rows for the properties and values of 'data.properties' in the nested table
                    if (Object.keys(data.properties).length === 1) {
                        nestedTbody.append(data.properties);
                    } else {
                        for (var key in data.properties) {
                            if (data.properties.hasOwnProperty(key)) {
                                var value = data.properties[key];
                                // Check if the value is an object
                                if (typeof value === 'object') {
                                    // If it's an object, stringify it
                                    value = JSON.stringify(value);
                                }
                                // Append a table row for each property
                                nestedTbody.append('<tr><th>' + key + '</th><td>' + value +
                                    '</td></tr>');
                            }
                        }
                    }

                    nestedTable.append(nestedTbody);
                    tbody.append(nestedTable);

                    // Append the table to the modal body
                    table.append(tbody);
                    auditLogDetails.append(table);
                },
                error: function(xhr, status, error) {
                    // Handle errors if necessary
                    console.error(error);
                }
            });

            // Show the dynamic modal
            $('#' + modalId).modal('show');
        });
    </script>
@endsection
