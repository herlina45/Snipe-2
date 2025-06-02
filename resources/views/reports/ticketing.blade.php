@extends('layouts/default')

{{-- Page Title --}}
@section('title')
    {{ trans('general.custom_report') }}: Ticketing
@parent
@stop

@section('header_right')
    <a href="{{ route('ticketing.index') }}" class="btn btn-primary pull-right">
        {{ trans('general.back') }}
    </a>
@stop

{{-- Page content --}}
@section('content')
<div class="row">
    <div class="col-md-9">
        <form
            method="POST"
            action="{{ route('ticketing.export') }}"
            accept-charset="UTF-8"
            class="form-horizontal"
            id="custom-report-form"
        >
        {{ csrf_field() }}

        <div class="box box-default">
            <div class="box-header with-border">
                <h2 class="box-title" style="padding-top: 7px;">
                    {{ trans('general.customize_report') }}
                </h2>
            </div>

            <div class="box-body">
                <div class="col-md-4" id="included_fields_wrapper">
                    <label class="form-control">
                        <input type="checkbox" id="checkAll" checked="checked">
                        {{ trans('general.select_all') }}
                    </label>

                    <label class="form-control">
                        <input type="checkbox" name="ticket_number" value="1" checked />
                        {{ trans('Ticket Number') }}
                    </label>
                    <label class="form-control">
                        <input type="checkbox" name="requested_date" value="1" checked />
                        {{ trans('Requested Date') }}
                    </label>
                    <label class="form-control">
                        <input type="checkbox" name="required_date" value="1" checked />
                        {{ trans('Required Date') }}
                    </label>
                    <label class="form-control">
                        <input type="checkbox" name="requested_by" value="1" checked />
                        {{ trans('Requested By') }}
                    </label>
                    <label class="form-control">
                        <input type="checkbox" name="request_for" value="1" checked />
                        {{ trans('Request For') }}
                    </label>
                    <label class="form-control">
                        <input type="checkbox" name="department" value="1" checked />
                        {{ trans('Department') }}
                    </label>
                    <label class="form-control">
                        <input type="checkbox" name="category" value="1" checked />
                        {{ trans('Category') }}
                    </label>
                    <label class="form-control">
                        <input type="checkbox" name="status" value="1" checked />
                        {{ trans('Status') }}
                    </label>
                    <label class="form-control">
                        <input type="checkbox" name="notes" value="1" checked />
                        {{ trans('Notes') }}
                    </label>
                </div>

                <div class="col-md-8">
                    <p>
                        {!! trans('general.report_fields_info') !!}
                    </p>

                    @include ('partials.forms.edit.department-select', [
                        'translated_name' => trans('general.department'),
                        'fieldname' => 'by_dept_id[]',
                        'multiple' => 'true',
                        'hide_new' => 'true',
                    ])

                    <!-- Requested By -->
                    @include ('partials.forms.edit.user-select', [
                        'translated_name' => trans('Requested By'),
                        'fieldname' => 'by_requested_by_id[]',
                        'multiple' => 'true',
                        'hide_new' => 'true',
                    ])

                    <!-- Request For -->
                    @include ('partials.forms.edit.user-select', [
                        'translated_name' => trans('Request For'),
                        'fieldname' => 'by_request_for_id[]',
                        'multiple' => 'true',
                        'hide_new' => 'true',
                    ])

                    <!-- Requested Date -->
                    <div class="form-group requested-range{{ ($errors->has('requested_start') || $errors->has('requested_end')) ? ' has-error' : '' }}">
                        <label for="requested_start" class="col-md-3 control-label">{{ trans('Requested Date') }}</label>
                        <div class="input-daterange input-group col-md-7" id="datepicker">
                            <input type="text" class="form-control" name="requested_start" aria-label="requested_start">
                            <span class="input-group-addon">{{ strtolower(trans('general.to')) }}</span>
                            <input type="text" class="form-control" name="requested_end" aria-label="requested_end">
                        </div>
                        @if ($errors->has('requested_start') || $errors->has('requested_end'))
                            <div class="col-md-9 col-lg-offset-3">
                                {!! $errors->first('requested_start', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                                {!! $errors->first('requested_end', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                            </div>
                        @endif
                    </div>

                    <!-- Required Date -->
                    <div class="form-group required-range{{ ($errors->has('required_start') || $errors->has('required_end')) ? ' has-error' : '' }}">
                        <label for="required_start" class="col-md-3 control-label">{{ trans('Required Date') }}</label>
                        <div class="input-daterange input-group col-md-7" id="datepicker">
                            <input type="text" class="form-control" name="required_start" aria-label="required_start">
                            <span class="input-group-addon">{{ strtolower(trans('general.to')) }}</span>
                            <input type="text" class="form-control" name="required_end" aria-label="required_end">
                        </div>
                        @if ($errors->has('required_start') || $errors->has('required_end'))
                            <div class="col-md-9 col-lg-offset-3">
                                {!! $errors->first('required_start', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                                {!! $errors->first('required_end', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                            </div>
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="status" class="col-md-3 control-label">{{ trans('Status') }}</label>
                        <div class="col-md-7">
                            <select name="status" class="form-control">
                                <option value="Waiting for approval" {{ old('status', $ticket->status ?? '') == 'Waiting for approval' ? 'selected' : '' }}>Waiting for approval</option>
                                <option value="Approved" {{ old('status', $ticket->status ?? '') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Rejected" {{ old('status', $ticket->status ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <!-- Category -->
                    @include ('partials.forms.edit.category-select', [
                        'translated_name' => trans('Category'),
                        'fieldname' => 'by_category_id[]',
                        'multiple' => 'true',
                        'hide_new' => 'true',
                        'category_type' => 'asset',
                    ])

            <div class="box-footer text-right">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-download icon-white" aria-hidden="true"></i>
                    {{ trans('general.generate') }}
                </button>
            </div>
        </div>
        </form>
    </div>
</div>

@stop

@section('moar_scripts')
<script>
    $('.requested-range .input-daterange').datepicker({
        clearBtn: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $('.required-range .input-daterange').datepicker({
        clearBtn: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $("#checkAll").change(function () {
        $("#included_fields_wrapper input:checkbox").prop('checked', $(this).prop("checked"));
    });
</script>
@stop