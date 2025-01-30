@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.domain.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.domains.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.id') }}
                            </th>
                            <td>
                                {{ $domain->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.domain') }}
                            </th>
                            <td>
                                {{ $domain->domain }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.tld') }}
                            </th>
                            <td>
                                {{ $domain->tld }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.status') }}
                            </th>
                            <td>
                                {{ App\Models\Domain::STATUS_RADIO[$domain->status] ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.registered_at') }}
                            </th>
                            <td>
                                {{ $domain->registered_at }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.expiration_date') }}
                            </th>
                            <td>
                                {{ $domain->expiration_date }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.transfer_date') }}
                            </th>
                            <td>
                                {{ $domain->transfer_date }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.who_is_privacy') }}
                            </th>
                            <td>
                                {{ $domain->who_is_privacy }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.auto_renew') }}
                            </th>
                            <td>
                                {{ App\Models\Domain::AUTO_RENEW_RADIO[$domain->auto_renew] ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.auth_code') }}
                            </th>
                            <td>
                                {{ $domain->auth_code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.domain_pricing') }}
                            </th>
                            <td>
                                {{ $domain->domain_pricing->tld ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.domain.fields.user') }}
                            </th>
                            <td>
                                {{ $domain->user->name ?? '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.domains.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
