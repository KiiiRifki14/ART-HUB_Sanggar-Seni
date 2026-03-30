<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostumeRental extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['rental_date' => 'date', 'due_date' => 'date', 'returned_date' => 'date'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function vendor()
    {
        return $this->belongsTo(CostumeVendor::class, 'vendor_id');
    }
}
