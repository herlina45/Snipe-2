<div id="ticketingBulkEditToolbar">
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
</div>