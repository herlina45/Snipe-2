        @extends('layouts.edit-form')

        @section('content')

        @php
            $formAction = isset($item->id) ? route('ticketing.update', $item->id) : route('ticketing.store');
            $formMethod = isset($item->id) ? 'PUT' : 'POST';
        @endphp

        <h1>{{ isset($item->id) ? __('Update Ticket') : __('Create Ticket') }}</h1>
        <p class="text-muted">{{ __('Fill the ticket form carefully') }}</p>

        <form action="{{ $formAction }}" method="POST">
            @csrf
            @if($formMethod === 'PUT')
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="ticket_number">Ticket Number</label>
                <input type="text" name="ticket_number" id="ticket_number" class="form-control" 
                    value="{{ old('ticket_number', $item->ticket_number ?? '') }}" disabled>
            </div>

            @include('partials.forms.edit.user-select', [
                'translated_name' => 'Requested By',
                'fieldname' => 'requested_by',
                'required' => 'true',
                'compact' => true,
            ])

            <div class="form-group">
                <label for="department_id">Departemen</label>
                <select name="department_id" class="form-control" required>
                    <option value="">Select Department</option>
                    @foreach (\App\Models\Department::all() as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            
            @include('partials.forms.edit.user-select', [
                'translated_name' => 'Request For (Optional)',
                'fieldname' => 'request_for',
                'compact' => true,
            ])

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" 
                            {{ old('category_id', $item->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

    @include ('partials.forms.edit.datepicker', [
        'translated_name' => 'Tanggal Pengajuan',
        'fieldname' => 'requested_date',
        'required' => 'true',
        'value' => old('requested_date', isset($item->requested_date) ? \Carbon\Carbon::parse($item->requested_date)->format('Y-m-d') : '')
    ])
    @include ('partials.forms.edit.datepicker', [
        'translated_name' => 'Tanggal Dibutuhkan (Opsional)',
        'fieldname' => 'required_date',
        'required' => 'false',
        'value' => old('required_date', isset($item->required_date) ? \Carbon\Carbon::parse($item->required_date)->format('Y-m-d') : '')
    ])

            <div class="form-group">
                <label for="notes">Notes (Optional)</label>
                <textarea name="notes" id="notes" class="form-control" rows="4">{{ old('notes', $item->notes ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" disabled>
                    @php
                        $statuses = [
                            'Waiting for approval' => 'Waiting for approval',
                            'Approved' => 'Approved',
                            'Rejected' => 'Rejected'
                        ];
                    @endphp
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ old('status', $item->status ?? 'waiting_for_approval') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                {{ isset($item->id) ? 'Update Ticket' : 'Create Ticket' }}
            </button>

            <a href="{{ route('ticketing.index') }}" class="btn btn-secondary">Back to ticket list</a>
        </form>

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
        });
    </script>
@endsection
