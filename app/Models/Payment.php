<?php
  
namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
  
class Payment extends Model  implements Auditable
{
    use HasFactory, Uuids, AuditableTrait;
  
    public $table = "payments";
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'invoice_reference',
        'description',
        'service_type',
        'amount_spend',
        'payment_method',
        'transaction_reference',
        'user_id',
        'card_id',
        'status',
        'reconciliaton_reference',
        'payment',
        'currency',
        'name',
        'surname'
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

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
