@extends('layouts.edit-form')

@section('content')
  @php
    $formAction = isset($item->id) ? route('ticketing.update', $item->id) : route('ticketing.store');
    $formMethod = isset($item->id) ? 'PUT' : 'POST';
  @endphp

  <div class="container mt-4">
    <h1 class="mb-3">{{ isset($item->id) ? __('Update Ticket') : __('Create Ticket') }}</h1>
    <p class="text-muted mb-4">{{ __('Fill the ticket form carefully') }}</p>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ $formAction }}" method="POST" class="needs-validation" novalidate>
      @csrf
      @if($formMethod === 'PUT')
        @method('PUT')
      @endif

      <div class="card mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="form-group {{ $errors->has('ticket_number') ? 'has-error' : '' }}">
                <label for="ticket_number" class="form-label">Ticket Number</label>
                @if ($item->id)
                  <input class="form-control" type="text" name="ticket_number" id="ticket_number" 
                         value="{{ old('ticket_number', $item->ticket_number) }}" readonly>
                @else
                  <input class="form-control" type="text" name="ticket_number" id="ticket_number" 
                         value="{{ old('ticket_number', \App\Models\Ticketing::autoincrement_asset()) }}" readonly>
                  <p class="help-block">Asset tag will be auto-generated</p>
                @endif
                @error('ticket_number')
                  <span class="alert-msg"><i class="fas fa-times"></i> {{ $message }}</span>
                @enderror
                @error('asset_tag')
                  <span class="alert-msg"><i class="fas fa-times"></i> {{ $message }}</span>
                @enderror
              </div>
            </div>

            <div class="col-md-6 mb-3">
              @include('partials.forms.edit.user-select', [
                'translated_name' => 'Requested By',
                'fieldname' => 'requested_by',
                'required' => 'true',
                'compact' => true,
                'value' => old('requested_by', $item->requested_by ?? '')
              ])
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="form-group {{ $errors->has('department_id') ? 'has-error' : '' }}">
                <label for="department_id" class="form-label">Departemen</label>
                <select name="department_id" id="department_id" class="form-control" required>
                  <option value="">{{ __('Select Department') }}</option>
                  @foreach (\App\Models\Department::all() as $department)
                    <option value="{{ $department->id }}" 
                            {{ old('department_id', $item->department_id ?? '') == $department->id ? 'selected' : '' }}>
                      {{ $department->name }}
                    </option>
                  @endforeach
                </select>
                @error('department_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6 mb-3">
              @include('partials.forms.edit.user-select', [
                'translated_name' => 'Request For (Optional)',
                'fieldname' => 'request_for',
                'compact' => true,
                'value' => old('request_for', $item->request_for ?? '')
              ])
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-control" required>
                  <option value="">{{ __('Select Category') }}</option>
                  @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" 
                            {{ old('category_id', $item->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                      {{ $cat->name }}
                    </option>
                  @endforeach
                </select>
                @error('category_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6 mb-3">
              @include('partials.forms.edit.datepicker', [
                'translated_name' => 'Tanggal Pengajuan',
                'fieldname' => 'requested_date',
                'required' => 'true',
                'value' => old('requested_date', isset($item->requested_date) ? \Carbon\Carbon::parse($item->requested_date)->format('Y-m-d') : '')
              ])
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              @include('partials.forms.edit.datepicker', [
                'translated_name' => 'Tanggal Dibutuhkan (Opsional)',
                'fieldname' => 'required_date',
                'required' => 'false',
                'value' => old('required_date', isset($item->required_date) ? \Carbon\Carbon::parse($item->required_date)->format('Y-m-d') : '')
              ])
            </div>

            <div class="col-md-6 mb-3">
              <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" {{ isset($item->id) ? : 'required' }}>
                  @php
                    $statuses = [
                      'Waiting for approval' => 'Waiting for approval',
                      'Approved' => 'Approved',
                      'Rejected' => 'Rejected'
                    ];
                  @endphp
                  @foreach($statuses as $value => $label)
                    <option value="{{ $value }}"
                            {{ old('status', $item->status ?? 'waiting_for_approval') == $value ? 'selected' : '' }}>
                      {{ $label }}
                    </option>
                  @endforeach
                </select>
                @error('status')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="form-group {{ $errors->has('notes') ? 'has-error' : '' }}">
              <label for="notes" class="form-label">Notes (Optional)</label>
              <textarea name="notes" id="notes" class="form-control" rows="4">{{ old('notes', $item->notes ?? '') }}</textarea>
              @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          {{ isset($item->id) ? __('Update Ticket') : __('Create Ticket') }}
        </button>
        <a href="{{ route('ticketing.index') }}" class="btn btn-secondary">Back to ticket list</a>
      </div>
    </form>
  </div>

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('moar_scripts')
  <script nonce="{{ csrf_token() }}">
    $(document).ready(function() {
      $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
      });

      // Bootstrap form validation
      (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
          form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      })();
    });
  </script>
@endsection