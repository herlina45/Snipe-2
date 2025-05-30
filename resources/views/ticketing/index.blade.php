@extends('layouts/default')

{{-- Page title --}}
@section('title')
  @if (Request::has('status'))
    {{ ucfirst(str_replace('_', ' ', Request::get('status'))) }}
  @else
    {{ trans('general.all') }}
  @endif
  {{ trans('general.ticketing') }}
@stop

{{-- Header actions --}}
@section('header_right')
  <a href="{{ route('ticketing.custom_report') }}" style="margin-right: 5px;" class="btn btn-default">
    {{ trans('general.custom_export') }}
  </a>
  @can('create', \App\Models\Ticketing::class)
    <a href="{{ route('ticketing.create') }}" class="btn btn-primary pull-right">
      {{ trans('general.create') }}
    </a>
  @endcan
@stop

{{-- Page content --}}
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            {{-- Bulk Actions Toolbar --}}
            @include('partials.ticketing-bulk-actions', ['status' => Request::get('status')])

            {{-- Dynamic Table --}}
            <!-- <table
              data-advanced-search="true"
              data-click-to-select="true"
              data-columns="{{ \App\Presenters\TicketingPresenter::dataTableLayout() }}"
              
              data-cookie-id-table="ticketingListingTable"
              data-pagination="true"
              data-id-table="ticketingListingTable"
              data-search="true"
              data-side-pagination="server"
              data-show-columns="true"
              data-show-export="true"
              data-show-footer="true"
              data-show-refresh="true"
              data-sort-order="asc"
              data-sort-name="ticket_number"
              data-show-fullscreen="true"
              data-toolbar="#ticketingBulkEditToolbar"
              data-bulk-button-id="#bulkTicketingEditButton"
              data-bulk-form-id="#ticketingBulkForm"
              id="ticketingListingTable"
              class="table table-striped snipe-table"
              data-url="{{ route('api.ticketing.index', [
                  'status' => e(Request::get('status')),
                  'department_id' => e(Request::get('department_id'))
              ]) }}"
              data-export-options='{
                "fileName": "export{{ (Request::has('status')) ? '-'.str_slug(Request::get('status')) : '' }}-ticketing-{{ date('Y-m-d') }}",
                "ignoreColumn": ["actions","checkbox"]
              }'>
            </table> -->


            <table
      data-toggle="table"
      data-url="{{ route('api.ticketing.index', [
          'status' => e(Request::get('status')),
          'department_id' => e(Request::get('department_id'))
      ]) }}"
      data-search="true"
      data-pagination="true"
      data-side-pagination="server"
      data-show-refresh="true"
      data-show-columns="true"
      data-toolbar="#ticketingBulkEditToolbar"
      class="table table-striped snipe-table"
      id="ticketingListingTable">

      <thead>
        <tr>
          <th data-field="ticket_number" data-sortable="true">Ticket Number</th>
          <th data-field="requested_date" data-sortable="true">Requested Date</th>
          <th data-field="required_date" data-sortable="true">Required Date</th>
          <th data-field="requester" data-sortable="true">Requested By</th>
          <th data-field="request_for" data-sortable="true">Request For</th>
          <th data-field="department" data-sortable="true">Department</th>
          <th data-field="category" data-sortable="true">Category</th>
          <th data-field="status" data-sortable="true">Status</th>
          <th data-field="notes">Notes</th>
          <th data-field="actions" data-formatter="actionFormatter" data-align="center">Actions</th>

        </tr>
      </thead>
    </table>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

{{-- Additional Scripts --}}
@section('moar_scripts')
  @include('partials.bootstrap-table')

  <script>
    function actionFormatter(value, row, index) {
      return `
        <a href="/ticketing/${row.id}/edit" class="btn btn-sm btn-warning">Edit</a>
        <form method="POST" action="/ticketing/${row.id}" style="display:inline;" onsubmit="return confirm('Are you sure?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>
      `;
    }
  </script>
@stop