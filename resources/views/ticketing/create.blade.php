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
                    value="{{ old('ticket_number', $item->ticket_number ?? '') }}" readonly>
            </div>

            <!-- <div class="form-group">
                <label for="requested_by">Requested By</label>
                <select name="requested_by" id="requested_by" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" 
                            {{ old('requested_by', $item->requested_by ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div> -->

@include('partials.forms.edit.user-select', [
    'translated_name' => 'Requested By',
    'fieldname' => 'requested_by',
    'required' => 'true',
    'compact' => true, // <== ini kuncinya
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
            <!-- <div class="form-group">
                <label for="department_id">Department</label>
                <select name="department_id" id="department_id" class="form-control" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" 
                            {{ old('department_id', $item->department_id ?? '') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div> -->

            <!-- <div class="form-group">
                <label for="request_for">Request For (Optional)</label>
                <select name="request_for" id="request_for" class="form-control">
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" 
                            {{ old('request_for', $item->request_for ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div> -->

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
                <select name="status" id="status" class="form-control" required>
                    <!-- @php
                        $statuses = ['Waiting for approval', 'Approved', 'In Progress', 'Completed', 'Rejected'];
                    @endphp -->

                    @php
                        $statuses = [
                            'waiting_for_approval' => 'Waiting for approval',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected'
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
