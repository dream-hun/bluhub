@extends('layouts.admin')
@section('content')
    @can('domain_pricing_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.domain-pricings.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.domainPricing.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.domainPricing.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-DomainPricing">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.domainPricing.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.domainPricing.fields.tld') }}
                            </th>
                            <th>
                                {{ trans('cruds.domainPricing.fields.registration_price') }}
                            </th>
                            <th>
                                {{ trans('cruds.domainPricing.fields.renewal_price') }}
                            </th>
                            <th>
                                {{ trans('cruds.domainPricing.fields.transfer_price') }}
                            </th>
                            <th>
                                {{ trans('cruds.domainPricing.fields.whois_privacy_price') }}
                            </th>
                            <th>
                                {{ trans('cruds.domainPricing.fields.min_registration_years') }}
                            </th>
                            <th>
                                {{ trans('cruds.domainPricing.fields.max_registration_years') }}
                            </th>
                            <th>
                                {{ trans('cruds.domainPricing.fields.is_active') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($domainPricings as $key => $domainPricing)
                            <tr data-entry-id="{{ $domainPricing->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $domainPricing->id ?? '' }}
                                </td>
                                <td>
                                    {{ $domainPricing->tld ?? '' }}
                                </td>
                                <td>
                                    {{ $domainPricing->formattedRegistrationPrice() ?? '' }}
                                </td>
                                <td>
                                    {{ $domainPricing->formattedRenewalPrice() ?? '' }}
                                </td>
                                <td>
                                    {{ $domainPricing->formattedTransferPrice() ?? '' }}
                                </td>
                                <td>
                                    {{ $domainPricing->whois_privacy_price ?? '' }}
                                </td>
                                <td>
                                    {{ $domainPricing->min_registration_years ?? '' }}
                                </td>
                                <td>
                                    {{ $domainPricing->max_registration_years ?? '' }}
                                </td>
                                <td>
                                    <span style="display:none">{{ $domainPricing->is_active ?? '' }}</span>
                                    <input type="checkbox" disabled="disabled"
                                        {{ $domainPricing->is_active ? 'checked' : '' }}>
                                </td>
                                <td>
                                    @can('domain_pricing_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.domain-pricings.show', $domainPricing->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('domain_pricing_edit')
                                        <a class="btn btn-xs btn-info"
                                            href="{{ route('admin.domain-pricings.edit', $domainPricing->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('domain_pricing_delete')
                                        <form action="{{ route('admin.domain-pricings.destroy', $domainPricing->id) }}"
                                            method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
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
            let table = $('.datatable-DomainPricing:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
