@extends('layouts.admin')
@section('content')
    @can('domain_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.domains.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.domain.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.domain.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Domain">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.domain') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.tld') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.status') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.registered_at') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.expiration_date') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.transfer_date') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.who_is_privacy') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.auto_renew') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.auth_code') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.domain_pricing') }}
                            </th>
                            <th>
                                {{ trans('cruds.domain.fields.user') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($domains as $key => $domain)
                            <tr data-entry-id="{{ $domain->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $domain->id ?? '' }}
                                </td>
                                <td>
                                    {{ $domain->domain ?? '' }}
                                </td>
                                <td>
                                    {{ $domain->tld ?? '' }}
                                </td>
                                <td>
                                    {{ App\Models\Domain::STATUS_RADIO[$domain->status] ?? '' }}
                                </td>
                                <td>
                                    {{ $domain->registered_at ?? '' }}
                                </td>
                                <td>
                                    {{ $domain->expiration_date ?? '' }}
                                </td>
                                <td>
                                    {{ $domain->transfer_date ?? '' }}
                                </td>
                                <td>
                                    {{ $domain->who_is_privacy ?? '' }}
                                </td>
                                <td>
                                    {{ App\Models\Domain::AUTO_RENEW_RADIO[$domain->auto_renew] ?? '' }}
                                </td>
                                <td>
                                    {{ $domain->auth_code ?? '' }}
                                </td>
                                <td>
                                    {{ $domain->domainPricing->tld ?? '' }}
                                </td>
                                <td>
                                    {{ $domain->user->name ?? '' }}
                                </td>
                                <td>
                                    @can('domain_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.domains.show', $domain->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('domain_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.domains.edit', $domain->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('domain_delete')
                                        <form action="{{ route('admin.domains.destroy', $domain->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger"
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
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-Domain:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
