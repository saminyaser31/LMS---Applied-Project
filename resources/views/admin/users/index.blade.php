@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @can('user_create')
                <div class="row">
                    <div class="col-12">
                        <div style="margin-bottom: 10px;" class="row">
                            <div class="col-lg-12">
                                <a class="btn btn-success" href="{{ route('admin.users.create') }}">
                                    {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example"
                                class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="10">

                                        </th>
                                        <th>
                                            {{ trans('cruds.user.fields.id') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.user.fields.name') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.user.fields.email') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.user.fields.email_verified_at') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.user.fields.approved') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.user.fields.roles') }}
                                        </th>
                                        <th>
                                            &nbsp;
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="filteredData">
                                    @foreach ($users as $key => $user)
                                        <tr data-entry-id="{{ $user->id }}">
                                            <td>

                                            </td>
                                            <td>
                                                {{ $user->id ?? '' }}
                                            </td>
                                            <td>
                                                {{ $user->name ?? '' }}
                                            </td>
                                            <td>
                                                {{ $user->email ?? '' }}
                                            </td>
                                            <td>
                                                {{ $user->email_verified_at ?? '' }}
                                            </td>
                                            <td>
                                                <span style="display:none">{{ $user->approved ?? '' }}</span>
                                                <input type="checkbox" disabled="disabled"
                                                    {{ $user->approved ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                @foreach ($user->roles as $key => $item)
                                                    <span class="badge bg-secondary">{{ $item->title }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @can('user_show')
                                                    <a class="btn btn-sm btn-primary mt-2"
                                                        href="{{ route('admin.users.show', $user->id) }}">
                                                        {{ trans('global.view') }}
                                                    </a>
                                                @endcan

                                                @can('user_edit')
                                                    <a class="btn btn-sm btn-info mt-2"
                                                        href="{{ route('admin.users.edit', $user->id) }}">
                                                        {{ trans('global.edit') }}
                                                    </a>
                                                @endcan

                                                @can('user_delete')
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="submit" class="btn btn-sm btn-danger mt-2"
                                                            value="{{ trans('global.delete') }}">
                                                    </form>
                                                @endcan

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        @parent
        <script>
            $(function() {
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                @can('user_delete')
                    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                    let deleteButton = {
                        text: deleteButtonTrans,
                        url: "{{ route('admin.users.massDestroy') }}",
                        className: 'btn-danger',
                        action: function(e, dt, node, config) {
                            var ids = $.map(dt.rows({
                                selected: true
                            }).nodes(), function(entry) {
                                return $(entry).data('entry-id')
                            });

                            if (ids.length === 0) {
                                alert('{{ trans('global.datatables.zero_selected') }}')

                                return
                            }

                            if (confirm('{{ trans('global.areYouSure') }}')) {
                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: config.url,
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function() {
                                        location.reload()
                                    })
                            }
                        }
                    }
                    dtButtons.push(deleteButton)
                @endcan

                $.extend(true, $.fn.dataTable.defaults, {
                    orderCellsTop: true,
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 100,
                });
                let table = $('.datatable-User:not(.ajaxTable)').DataTable({
                    buttons: dtButtons
                })
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });

            })
        </script>
    @endsection
