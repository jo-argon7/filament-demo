<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id','expense_ref','expense_date','expense_amt',
    ];

    public function supplier(): belongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault();
    }
}
