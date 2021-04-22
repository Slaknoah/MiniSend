<?php

namespace App\Http\Resources;

use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EmailCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ( $request->recipient ) {
            $meta = [
                'total_sent' => Email::where([
                        'status' => Email::STATUS_SENT,
                        'added_by' => $request->user()->id
                    ])
                    ->whereHas('recipients', function($query) use ($request) {
                        $query->where('recipients.id', $request->recipient);
                    })
                    ->count(),

                'total_failed' => Email::where([
                        'status' => Email::STATUS_FAILED,
                        'added_by' => $request->user()->id
                    ])
                    ->whereHas('recipients', function($query) use ($request) {
                        $query->where('recipients.id', $request->recipient);
                    })
                    ->count(),
            ];
        } else {
            $meta = [
                'total_sent' => Email::where([
                        'status' => Email::STATUS_SENT,
                        'added_by' => $request->user()->id
                    ])
                    ->count(),

                'total_failed'  => Email::where([
                        'status' => Email::STATUS_FAILED,
                        'added_by' => $request->user()->id
                    ])
                    ->count()
            ];
        }

        return [
            'data' => $this->collection,
            'meta' => $meta
        ];
    }
}
