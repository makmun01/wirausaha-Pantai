<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    protected $fillable = [
        'partner_name',
        'partnership_type',
        'contact_person',
        'phone_number',
        'start_date',
        'end_date',
        'status',
        'document_url',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
