<?php

namespace App\Presenters;

class TicketingPresenter
{
    /**
     * Define the columns for the Bootstrap Table in the ticketing index.
     *
     * @return string JSON-encoded column configuration
     */
    public static function dataTableLayout()
    {
        return json_encode([
            [
                'field' => 'checkbox',
                'checkbox' => true,
                'title' => 'Select'
            ],
            [
                'field' => 'ticket_number',
                'title' => trans('general.ticket_number'),
                'sortable' => true
            ],
            [
                'field' => 'requested_date',
                'title' => trans('general.requested_date'),
                'sortable' => true,
                'formatter' => 'dateFormatter'
            ],
            [
                'field' => 'required_date',
                'title' => trans('general.required_date'),
                'sortable' => true,
                'formatter' => 'dateFormatter'
            ],
            [
                'field' => 'requester',
                'title' => trans('general.requested_by'),
                'sortable' => true,
                'formatter' => 'nameFormatter'
            ],
            [
                'field' => 'department',
                'title' => trans('general.department'),
                'sortable' => true,
                'formatter' => 'nameFormatter'
            ],
            [
                'field' => 'request_for',
                'title' => trans('general.request_for'),
                'sortable' => true,
                'formatter' => 'nameFormatter'
            ],
            [
                'field' => 'category',
                'title' => trans('general.category'),
                'sortable' => true,
                'formatter' => 'nameFormatter'
            ],
            [
                'field' => 'notes',
                'title' => trans('general.notes'),
                'sortable' => false
            ],
            [
                'field' => 'status',
                'title' => trans('general.status'),
                'sortable' => true,
                'formatter' => 'statusFormatter'
            ],
            [
                'field' => 'actions',
                'title' => trans('general.actions'),
                'sortable' => false,
                'formatter' => 'actionsFormatter'
            ]
        ]);
    }
}