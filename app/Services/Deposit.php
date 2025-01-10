<?php

namespace App\Services;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class Deposit
{

    public string $address;
    public int $limit = 100;
    public int $from_lt = 0;
    public int $last_lt = 0;
    public mixed $tx_hash;
    public array $data = [];
    public string $link = 'https://toncenter.com/api/v2/getTransactions';

    public function __construct($address, $last_lt = 0, $tx_hash = null)
    {
        $this->from_lt = $last_lt;
        $this->tx_hash = $tx_hash;
        $this->address = $address;
//        $this->address = "EQDbUFPEDe9jl-BAfbrwIGID0TRTMRWGsLJrx4hXC1Z_AtnE";
    }

    public function dto($data)
    {
        $user_id = Arr::get($data, 'in_msg.message');

        return [
            'wallet' => Arr::get($data, 'address.account_address'),
            'amount' => Arr::get($data, 'in_msg.value') / 1000000000,
            'signature' => Arr::get($data, 'transaction_id.hash'),
            'memo' => $user_id,
            'message' => Arr::get($data, 'in_msg.message'),
            'lt' => Arr::get($data, 'transaction_id.lt'),
            'created_at' => Arr::get($data, 'utime'),
        ];
    }

    public function check()
    {

        do {
            $query = [
                'limit' => $this->limit,
                'address' => $this->address,
            ];

            if ($this->from_lt > 0) {
                $query['lt'] = $this->from_lt;
            }
            if (!empty($this->tx_hash)) {
                $query['hash'] = $this->tx_hash;
            }

            $call = Http::
            get($this->link, $query);
//            print_r($query);
            if ($call->status() !== 200) {
                break;
            };
            $data = $call->json('result', []);
            $filted_data = $this->filter($data);
            foreach ($filted_data as $transaction) {
                yield $this->dto($transaction);
            }
            if (count($data) > 0) {
                $this->from_lt = Arr::get(end($data), 'transaction_id.lt');
                $this->tx_hash = Arr::get(end($data), 'transaction_id.hash');
            }
            sleep(2);
        } while (count($filted_data));

    }

    public function filter($data)
    {
        if (!empty($this->tx_hash) && count($data) > 0) {
            $data = array_slice($data, 1);
        }
        return Arr::where($data, function ($v) {
            return $v["@type"] == "raw.transaction" && count($v['out_msgs']) == 0;
        });
    }

    public function all($call)
    {
        foreach ($this->check() as $transaction) {
            $call($transaction);
        }
    }


}
