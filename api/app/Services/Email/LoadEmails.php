<?php


namespace App\Services\Email;


use App\Models\Email;

class LoadEmails
{
    private $take;
    private $search;
    private $recipient;
    private $query;
    private $orderBy;
    private $status;
    private $orderDirection;

    public function __construct($parameters)
    {
        $this->setLocalParameters($parameters);
        $this->query = Email::query();
    }

    public function load() {
        $this->applyAddedBy();
        $this->applyRecipientFilter();
        $this->applyStatusFilter();
        $this->applySearch();
        $this->applyOrder();

        return $this->query->with('recipients')->paginate( $this->take );
    }

    private function applyAddedBy()
    {
        $this->query->where('added_by', auth()->user()->id);
    }

    private function applyStatusFilter()
    {
        if( $this->status != '' ) {
            $this->query->where('status', $this->status);
        }
    }

    private function applyRecipientFilter()
    {
        if( $this->recipient != '' ) {
            $this->query->whereHas('recipients', function ($query) {
                $query->where('recipients.id', $this->recipient);
            });
        }
    }

    private function applySearch()
    {
        if( $this->search != '' ){
            $this->query->where(function( $query ) {
                $query->where('sender_name', 'LIKE', '%'.$this->search.'%');
                $query->orWhere('sender_email', 'LIKE', '%'.$this->search.'%');
                $query->orWhere('subject', 'LIKE', '%'.$this->search.'%');
                $query->orWhereHas('recipients', function($query) {
                    $query->where('name', 'LIKE', '%'.$this->search.'%');
                    $query->orWhere('email', 'LIKE', '%'.$this->search.'%');
                });
            });
        }
    }

    private function applyOrder()
    {
        $this->query->orderBy( $this->orderBy, $this->orderDirection );
    }

    protected function setLocalParameters( $parameters )
    {
        $this->take             = isset( $parameters['take'] ) ? $parameters['take'] : 10;
        $this->search           = isset( $parameters['search'] ) ? $parameters['search'] : '';
        $this->status           = isset( $parameters['status'] ) ? $parameters['status'] : '';
        $this->recipient        = isset( $parameters['recipient'] ) ? $parameters['recipient'] : '';
        $this->orderBy          = isset( $parameters['order_by'] ) ? $parameters['order_by'] : 'updated_at';
        $this->orderDirection   = isset( $parameters['order_direction'] ) ? $parameters['order_direction'] : 'DESC';
    }
}
