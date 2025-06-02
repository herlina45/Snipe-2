<!-- <div id="ticketingBulkEditToolbar">
    <form id="ticketingBulkForm" action="{{ route('ticketing.bulkUpdateStatus') }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="btn-group" role="group">
            <button type="submit" name="status" value="approved" id="bulkTicketingEditButton" class="btn btn-success">
                {{ trans('general.approve') }}
            </button>
            <button type="submit" name="status" value="rejected" id="bulkTicketingEditButton" class="btn btn-danger">
                {{ trans('general.reject') }}
            </button>
        </div>
    </form>
</div> -->

<div id="{{ (isset($id_divname)) ? $id_divname : 'ticketingBulkEditToolbar' }}" style="min-width:400px">
    <form
    method="POST"
    action="{{ route('ticketing.bulkedit') }}"
    accept-charset="UTF-8"
    class="form-inline"  
    id="{{ (isset($id_formname)) ? $id_formname : 'ticketingBulkForm' }}"
>
    @csrf

    {{-- The sort and order will only be used if the cookie is actually empty (like on first-use) --}}
    <input name="sort" type="hidden" value="ticketing.id">
    <input name="order" type="hidden" value="asc">
    <label for="bulk_actions">
        <span class="sr-only">
            {{ trans('button.bulk_actions') }}
        </span>
    </label>
    <select name="bulk_actions" class="form-control select2" aria-label="bulk_actions" style="min-width: 350px;">
        @if((isset($status)) && ($status == 'Deleted'))
            @can('delete', \App\Models\Ticketing::class)
                <option value="restore">{{trans('button.restore')}}</option>
            @endcan
        @else
            @can('update', \App\Models\Ticketing::class)
                <option value="edit">{{ trans('button.edit') }}</option>
            @endcan
            @can('checkout', \App\Models\Ticketing::class)
                <option value="checkout">{{ trans('general.bulk_checkout') }}</option>
            @endcan
            @can('delete', \App\Models\Ticketing::class)
                <option value="delete">{{ trans('button.delete') }}</option>
            @endcan
            <!-- <option value="labels" {{$snipeSettings->shortcuts_enabled == 1 ? "accesskey=l" : ''}}>{{ trans_choice('button.generate_labels', 2) }}</option> -->
        @endif
    </select>

    <button class="btn btn-primary" id="{{ (isset($id_button)) ? $id_button : 'bulkTicketingEditButton' }}" disabled>{{ trans('button.go') }}</button>
    </form>
</div>
