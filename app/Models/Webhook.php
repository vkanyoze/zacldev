<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Webhook extends Model implements Auditable
{
    use HasFactory, Uuids, AuditableTrait;

    protected $fillable = [
        'user_id',
        'reference_id',
        'transaction_id',
        'save_card',
        'service_type',
        'invoice_reference',
        'card_id',
        'currency'
    ];

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
