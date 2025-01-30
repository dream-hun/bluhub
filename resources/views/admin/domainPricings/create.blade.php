@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.domainPricing.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.domain-pricings.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="tld">{{ trans('cruds.domainPricing.fields.tld') }}</label>
                    <input class="form-control {{ $errors->has('tld') ? 'is-invalid' : '' }}" type="text" name="tld"
                        id="tld" value="{{ old('tld', '') }}" required>
                    @if ($errors->has('tld'))
                        <span class="text-danger">{{ $errors->first('tld') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domainPricing.fields.tld_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="registration_price">{{ trans('cruds.domainPricing.fields.registration_price') }}</label>
                    <input class="form-control {{ $errors->has('registration_price') ? 'is-invalid' : '' }}" type="number"
                        name="registration_price" id="registration_price" value="{{ old('registration_price', '') }}"
                        step="1" required>
                    @if ($errors->has('registration_price'))
                        <span class="text-danger">{{ $errors->first('registration_price') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domainPricing.fields.registration_price_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="renewal_price">{{ trans('cruds.domainPricing.fields.renewal_price') }}</label>
                    <input class="form-control {{ $errors->has('renewal_price') ? 'is-invalid' : '' }}" type="number"
                        name="renewal_price" id="renewal_price" value="{{ old('renewal_price', '') }}" step="1">
                    @if ($errors->has('renewal_price'))
                        <span class="text-danger">{{ $errors->first('renewal_price') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domainPricing.fields.renewal_price_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="transfer_price">{{ trans('cruds.domainPricing.fields.transfer_price') }}</label>
                    <input class="form-control {{ $errors->has('transfer_price') ? 'is-invalid' : '' }}" type="number"
                        name="transfer_price" id="transfer_price" value="{{ old('transfer_price', '') }}" step="1">
                    @if ($errors->has('transfer_price'))
                        <span class="text-danger">{{ $errors->first('transfer_price') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domainPricing.fields.transfer_price_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="whois_privacy_price">{{ trans('cruds.domainPricing.fields.whois_privacy_price') }}</label>
                    <input class="form-control {{ $errors->has('whois_privacy_price') ? 'is-invalid' : '' }}"
                        type="number" name="whois_privacy_price" id="whois_privacy_price"
                        value="{{ old('whois_privacy_price', '') }}" step="1">
                    @if ($errors->has('whois_privacy_price'))
                        <span class="text-danger">{{ $errors->first('whois_privacy_price') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domainPricing.fields.whois_privacy_price_helper') }}</span>
                </div>
                <div class="form-group">
                    <label
                        for="min_registration_years">{{ trans('cruds.domainPricing.fields.min_registration_years') }}</label>
                    <input class="form-control {{ $errors->has('min_registration_years') ? 'is-invalid' : '' }}"
                        type="number" name="min_registration_years" id="min_registration_years"
                        value="{{ old('min_registration_years', '1') }}" step="1">
                    @if ($errors->has('min_registration_years'))
                        <span class="text-danger">{{ $errors->first('min_registration_years') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domainPricing.fields.min_registration_years_helper') }}</span>
                </div>
                <div class="form-group">
                    <label
                        for="max_registration_years">{{ trans('cruds.domainPricing.fields.max_registration_years') }}</label>
                    <input class="form-control {{ $errors->has('max_registration_years') ? 'is-invalid' : '' }}"
                        type="number" name="max_registration_years" id="max_registration_years"
                        value="{{ old('max_registration_years', '') }}" step="1">
                    @if ($errors->has('max_registration_years'))
                        <span class="text-danger">{{ $errors->first('max_registration_years') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domainPricing.fields.max_registration_years_helper') }}</span>
                </div>
                <div class="form-group">
                    <div class="form-check {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', 0) == 1 || old('is_active') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                            for="is_active">{{ trans('cruds.domainPricing.fields.is_active') }}</label>
                    </div>
                    @if ($errors->has('is_active'))
                        <span class="text-danger">{{ $errors->first('is_active') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.domainPricing.fields.is_active_helper') }}</span>
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
