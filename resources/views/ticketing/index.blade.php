@extends('layouts/default')

{{-- Page title --}}
@section('title')
  @if (Request::has('status'))
    {{ ucfirst(str_replace('_', ' ', Request::get('status'))) }}
  @else
    {{ trans('general.all') }}
  @endif
  {{ trans('Ticketing') }}
@stop

{{-- Header actions --}}
@section('header_right')
  <a href="{{ route('ticketing.export') }}" style="margin-right: 5px;" class="btn btn-default">
    {{ trans('Custom Export') }}
  </a>
  @can('create', \App\Models\Ticketing::class)
    <a href="{{ route('ticketing.create') }}" class="btn btn-primary pull-right">
      {{ trans('general.create') }}
    </a>
  @endcan
@stop
{{-- Page content Ticketing--}}
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            {{-- Bulk Actions Toolbar --}}
            @include('partials.ticketing-bulk-actions', ['status' => Request::get('status')])

            <table
              data-toggle="table"
              data-url="{{ route('api.ticketing.index', [
                  'status' => e(Request::get('status')),
                  'department_id' => e(Request::get('department_id'))
              ]) }}"
              data-click-to-select="true"
              data-columns="{{ \App\Presenters\TicketingPresenter::dataTableLayout() }}"
              data-show-export="true"
              data-show-fullscreen="true"
              data-search="true"
              data-pagination="true"
              data-side-pagination="server"
              data-show-refresh="true"
              data-show-columns="true"
              data-toolbar="#ticketingBulkEditToolbar"
              class="table table-striped snipe-table"
              id="ticketingListingTable">

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