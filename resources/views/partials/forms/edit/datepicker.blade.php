<!-- resources/views/partials/forms/edit/datepicker.blade.php -->
<div class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}">
    <label for="{{ $fieldname }}">{{ $translated_name }} {{ $required == 'true' ? '*' : '' }}</label>
    <div class="input-group">
        <input type="text" class="form-control datepicker" name="{{ $fieldname }}" id="{{ $fieldname }}"
               placeholder="{{ trans('general.select_date') }}" 
               value="{{ old($fieldname, $value ?? '') }}"
               {{ $required == 'true' ? 'required' : '' }}>
        <span class="input-group-addon"><x-icon type="calendar" /></span>
    </div>
    {!! $errors->first($fieldname, '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
</div>

@push('moar_scripts')
    <script nonce="{{ csrf_token() }}">
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                clearBtn: true,
                todayHighlight: true,
                language: 'id' // Lokal Indonesia
            });
        });
    </script>
@endpush