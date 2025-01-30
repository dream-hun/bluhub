@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.setting.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="site_name">{{ trans('cruds.setting.fields.site_name') }}</label>
                    <input class="form-control {{ $errors->has('site_name') ? 'is-invalid' : '' }}" type="text"
                        name="site_name" id="site_name" value="{{ old('site_name', '') }}" required>
                    @if ($errors->has('site_name'))
                        <span class="text-danger">{{ $errors->first('site_name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.site_name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="logo">{{ trans('cruds.setting.fields.logo') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('logo') ? 'is-invalid' : '' }}" id="logo-dropzone">
                    </div>
                    @if ($errors->has('logo'))
                        <span class="text-danger">{{ $errors->first('logo') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.logo_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="email">{{ trans('cruds.setting.fields.email') }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                        name="email" id="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.email_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="phone">{{ trans('cruds.setting.fields.phone') }}</label>
                    <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text"
                        name="phone" id="phone" value="{{ old('phone', '') }}" required>
                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.phone_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="address">{{ trans('cruds.setting.fields.address') }}</label>
                    <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text"
                        name="address" id="address" value="{{ old('address', '') }}">
                    @if ($errors->has('address'))
                        <span class="text-danger">{{ $errors->first('address') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.address_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="facebook">{{ trans('cruds.setting.fields.facebook') }}</label>
                    <input class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" type="text"
                        name="facebook" id="facebook" value="{{ old('facebook', '') }}">
                    @if ($errors->has('facebook'))
                        <span class="text-danger">{{ $errors->first('facebook') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.facebook_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="twitter">{{ trans('cruds.setting.fields.twitter') }}</label>
                    <input class="form-control {{ $errors->has('twitter') ? 'is-invalid' : '' }}" type="text"
                        name="twitter" id="twitter" value="{{ old('twitter', '') }}">
                    @if ($errors->has('twitter'))
                        <span class="text-danger">{{ $errors->first('twitter') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.twitter_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="instagram">{{ trans('cruds.setting.fields.instagram') }}</label>
                    <input class="form-control {{ $errors->has('instagram') ? 'is-invalid' : '' }}" type="text"
                        name="instagram" id="instagram" value="{{ old('instagram', '') }}">
                    @if ($errors->has('instagram'))
                        <span class="text-danger">{{ $errors->first('instagram') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.instagram_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="linkedin">{{ trans('cruds.setting.fields.linkedin') }}</label>
                    <input class="form-control {{ $errors->has('linkedin') ? 'is-invalid' : '' }}" type="text"
                        name="linkedin" id="linkedin" value="{{ old('linkedin', '') }}">
                    @if ($errors->has('linkedin'))
                        <span class="text-danger">{{ $errors->first('linkedin') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.linkedin_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="youtube">{{ trans('cruds.setting.fields.youtube') }}</label>
                    <input class="form-control {{ $errors->has('youtube') ? 'is-invalid' : '' }}" type="text"
                        name="youtube" id="youtube" value="{{ old('youtube', '') }}">
                    @if ($errors->has('youtube'))
                        <span class="text-danger">{{ $errors->first('youtube') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.youtube_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="whatsapp">{{ trans('cruds.setting.fields.whatsapp') }}</label>
                    <input class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}" type="text"
                        name="whatsapp" id="whatsapp" value="{{ old('whatsapp', '') }}">
                    @if ($errors->has('whatsapp'))
                        <span class="text-danger">{{ $errors->first('whatsapp') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.whatsapp_helper') }}</span>
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

@section('scripts')
    <script>
        Dropzone.options.logoDropzone = {
            url: '{{ route('admin.settings.storeMedia') }}',
            maxFilesize: 2, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2,
                width: 4096,
                height: 4096
            },
            success: function(file, response) {
                $('form').find('input[name="logo"]').remove()
                $('form').append('<input type="hidden" name="logo" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="logo"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($setting) && $setting->logo)
                    var file = {!! json_encode($setting->logo) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="logo" value="' + file.file_name + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
@endsection
