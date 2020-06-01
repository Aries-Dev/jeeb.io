<?php

namespace Aries\Jeeb\Utils;

use Aries\Jeeb\Facades\Convert;
use Aries\Jeeb\Facades\Issue;
use Aries\Jeeb\Models\JeebTransaction;
use Aries\Jeeb\Models\JeebTransactionWallet;

class Pay {
    private $order, $from, $amount, $with, $webhook, $callback, $language;

    private $model;

    public function __construct()
    {
        $this->language = 'auto';
    }

    public function order($order) {
        $this->order = $order;
        return $this;
    }

    public function from($from) {
        $this->from = $from;
        return $this;
    }

    public function amount($amount) {
        $this->amount = $amount;
        return $this;
    }

    public function with($coins) {
        $this->with = $coins;
        return $this;
    }

    public function webhook($webhook) {
        $this->webhook = $webhook;
        return $this;
    }

    public function callback($callback) {
        $this->callback = $callback;
        return $this;
    }

    public function language($language) {
        $this->language = $language;
        return $this;
    }

    public function process() {
        $value = Convert::from($this->from)->to('btc')->amount($this->amount)->convert();

        $issue = Issue::orderNumber($this->order)
        ->btcValue($value)
        ->with($this->with)
        ->webhookUrl($this->webhook)
        ->callbackUrl($this->callback)
        ->language($this->language)
        ->init();
        
        $transaction = JeebTransaction::create([
            'orderNo'           => $this->order,
            'stateId'           => 2,
            'baseValue'         => $value,
            'refrenceNo'        => $issue['refrenceNo'],
            'token'             => $issue['token'],
            'expirationTime'    => $issue['expirationTime']
        ]);

        foreach($issue['addresses'] as $address) {
            $wallet = JeebTransactionWallet::create([
                'coin'      =>  $address['coin'],
                'address'   =>  $address['address'],
                'value'     =>  $address['strValue']
            ]);

            $transaction->wallets()->save($wallet);
        }

        $this->model = $transaction->load('wallets');

        return $this;
    }

    public function show() {
        return $this->model;
    }

    public function redirect() {
        $url = config('jeeb.base_url') . '/payments/invoice?token=' . $this->model->token;
        return redirect($url);
    }
}