@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.domain.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.domains.update', [$domain->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label class="required" for="domain">{{ trans('cruds.domain.fields.domain') }}</label>
                    <input class="form-control {{ $errors->has('domain') ? 'is-invalid' : '' }}" type="text"
                        name="domain" id="domain" value="{{ old('domain', $domain->domain) }}" required>
                    @if ($errors->has('domain'))
                        <span class="text-danger">{{ $errors->first('domain') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.domain_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="tld">{{ trans('cruds.domain.fields.tld') }}</label>
                    <input class="form-control {{ $errors->has('tld') ? 'is-invalid' : '' }}" type="text" name="tld"
                        id="tld" value="{{ old('tld', $domain->tld) }}" required>
                    @if ($errors->has('tld'))
                        <span class="text-danger">{{ $errors->first('tld') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.tld_helper') }}</span>
                </div>
                <div class="form-group">
                    <label>{{ trans('cruds.domain.fields.status') }}</label>
                    @foreach (App\Models\Domain::STATUS_RADIO as $key => $label)
                        <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status"
                                value="{{ $key }}"
                                {{ old('status', $domain->status) === (string) $key ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @if ($errors->has('status'))
                        <span class="text-danger">{{ $errors->first('status') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.status_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="registered_at">{{ trans('cruds.domain.fields.registered_at') }}</label>
                    <input class="form-control datetime {{ $errors->has('registered_at') ? 'is-invalid' : '' }}"
                        type="text" name="registered_at" id="registered_at"
                        value="{{ old('registered_at', $domain->registered_at) }}" required>
                    @if ($errors->has('registered_at'))
                        <span class="text-danger">{{ $errors->first('registered_at') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.registered_at_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="expiration_date">{{ trans('cruds.domain.fields.expiration_date') }}</label>
                    <input class="form-control datetime {{ $errors->has('expiration_date') ? 'is-invalid' : '' }}"
                        type="text" name="expiration_date" id="expiration_date"
                        value="{{ old('expiration_date', $domain->expiration_date) }}" required>
                    @if ($errors->has('expiration_date'))
                        <span class="text-danger">{{ $errors->first('expiration_date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.expiration_date_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="transfer_date">{{ trans('cruds.domain.fields.transfer_date') }}</label>
                    <input class="form-control datetime {{ $errors->has('transfer_date') ? 'is-invalid' : '' }}"
                        type="text" name="transfer_date" id="transfer_date"
                        value="{{ old('transfer_date', $domain->transfer_date) }}" required>
                    @if ($errors->has('transfer_date'))
                        <span class="text-danger">{{ $errors->first('transfer_date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.transfer_date_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="who_is_privacy">{{ trans('cruds.domain.fields.who_is_privacy') }}</label>
                    <input class="form-control {{ $errors->has('who_is_privacy') ? 'is-invalid' : '' }}" type="text"
                        name="who_is_privacy" id="who_is_privacy"
                        value="{{ old('who_is_privacy', $domain->who_is_privacy) }}">
                    @if ($errors->has('who_is_privacy'))
                        <span class="text-danger">{{ $errors->first('who_is_privacy') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.who_is_privacy_helper') }}</span>
                </div>
                <div class="form-group">
                    <label>{{ trans('cruds.domain.fields.auto_renew') }}</label>
                    @foreach (App\Models\Domain::AUTO_RENEW_RADIO as $key => $label)
                        <div class="form-check {{ $errors->has('auto_renew') ? 'is-invalid' : '' }}">
                            <input class="form-check-input" type="radio" id="auto_renew_{{ $key }}"
                                name="auto_renew" value="{{ $key }}"
                                {{ old('auto_renew', $domain->auto_renew) === (string) $key ? 'checked' : '' }}>
                            <label class="form-check-label"
                                for="auto_renew_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @if ($errors->has('auto_renew'))
                        <span class="text-danger">{{ $errors->first('auto_renew') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.auto_renew_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="auth_code">{{ trans('cruds.domain.fields.auth_code') }}</label>
                    <input class="form-control {{ $errors->has('auth_code') ? 'is-invalid' : '' }}" type="text"
                        name="auth_code" id="auth_code" value="{{ old('auth_code', $domain->auth_code) }}">
                    @if ($errors->has('auth_code'))
                        <span class="text-danger">{{ $errors->first('auth_code') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.auth_code_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="domain_pricing_id">{{ trans('cruds.domain.fields.domain_pricing') }}</label>
                    <select class="form-control select2 {{ $errors->has('domain_pricing') ? 'is-invalid' : '' }}"
                        name="domain_pricing_id" id="domain_pricing_id" required>
                        @foreach ($domain_pricings as $id => $entry)
                            <option value="{{ $id }}"
                                {{ (old('domain_pricing_id') ? old('domain_pricing_id') : $domain->domain_pricing->id ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('domain_pricing'))
                        <span class="text-danger">{{ $errors->first('domain_pricing') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.domain_pricing_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="user_id">{{ trans('cruds.domain.fields.user') }}</label>
                    <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id"
                        id="user_id" required>
                        @foreach ($users as $id => $entry)
                            <option value="{{ $id }}"
                                {{ (old('user_id') ? old('user_id') : $domain->user->id ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('user'))
                        <span class="text-danger">{{ $errors->first('user') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domain.fields.user_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
