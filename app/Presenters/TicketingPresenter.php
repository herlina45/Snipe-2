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
                'title' => trans('Ticket Number'),
                'sortable' => true,
                'visible' => true,
                'searchable' => true,
            ],
            [
                'field' => 'requested_date',
                'title' => trans('Requested Date'),
                'sortable' => true,
                'visible' => true,
                'searchable' => true,
            ],
            [
                'field' => 'required_date',
                'title' => trans('Required Date'),
                'sortable' => true,
                'visible' => true,
                'searchable' => true,
            ],
            [
                'field' => 'requested_by',
                'title' => trans('Requested By'),
                'sortable' => true,
                'visible' => true,
                'searchable' => true,
            ],
            [
                'field' => 'request_for',
                'title' => trans('Request For'),
                'sortable' => true,
                'visible' => true,
                'searchable' => true,
            ],
            [
                'field' => 'department',
                'title' => trans('Department'),
                'sortable' => true,
                'visible' => true,
                'searchable' => true,
            ],
            [
                'field' => 'category',
                'title' => trans('Category'),
                'sortable' => true,
                'visible' => true,
                'searchable' => true,
            ],
            [
                'field' => 'notes',
                'title' => trans('Notes'),
                'sortable' => false,
                'visible' => true,
                'searchable' => true,
            ],
            [
                'field' => 'status',
                'title' => trans('Status'),
                'sortable' => true,
                'visible' => true,
                'searchable' => true,
            ],
            [
                'field' => 'actions',
                'title' => trans('Actions'),
                'sortable' => false,
                'visible' => true,
                'searchable' => true,
                'formatter' => 'actionFormatter',
            ]
        ]);
    }

}