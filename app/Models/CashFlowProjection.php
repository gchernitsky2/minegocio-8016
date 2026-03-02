<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlowProjection extends Model
{
    protected $fillable = [
        'title',
        'date',
        'expected_income',
        'expected_expense',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'expected_income' => 'decimal:2',
        'expected_expense' => 'decimal:2',
    ];
}
