<?php

namespace Aries\Jeeb\Models;

use Illuminate\Database\Eloquent\Model;

class JeebTransactionWallet extends Model {
    protected $table = "jeeb_transaction_wallets";
    protected $fillable = [
        "coin",
        "value",
        "address"
    ];

    public function transaction() {
        return $this->belongsTo(JeebTransaction::class, 'jeeb_transaction_id');
    }
}