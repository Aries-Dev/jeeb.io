<?php

namespace Aries\Jeeb\Models;

use Illuminate\Database\Eloquent\Model;

class JeebTransaction extends Model {
    protected $table = "jeeb_transactions";
    protected $fillable = [
        "orderNo",
        "refrenceNo",
        "stateId",
        "coin",
        "address",
        "transactionId",
        "baseValue",
        "value",
        "paidValue",
        "finalizedTime",
        "expirationTime",
        "token"
    ];

    public function wallets() {
        return $this->hasMany(JeebTransactionWallet::class, 'jeeb_transaction_id');
    }
}