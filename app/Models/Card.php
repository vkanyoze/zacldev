<?php
  
namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
  
class card extends Model implements Auditable
{
    use HasFactory, Uuids, AuditableTrait;
  
    public $table = "cards";
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'name',
        'surname',
        'card_number',
        'expiry_date',
        'type_of_card',
        'address',
        'city',
        'country',
        'postal_code',
        'email_address',
        'phone_number',
        'user_id',
        'state'
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