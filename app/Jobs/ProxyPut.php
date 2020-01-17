<?php

namespace App\Jobs;

use App\Models\Proxy;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class ProxyPut implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {

    }

    public function handle()
    {
        try {
            $this->IpQuery();
            $proxy  = Proxy::count();
            if ($proxy < 6) {
                dispatch(new ProxyPut());
            }
        } catch (\Exception $e) {
            \Log::info('代理ip获取失败', ['error' => $e->getMessage()]);
        }

    }

    public function IpQuery()
    {
        $client = new \GuzzleHttp\Client();
        $res    = $client->request('GET',
            'http://webapi.http.zhimacangku.com/getip?num=1&type=2&pro=&city=0&yys=0&port=1&pack=40233&ts=0&ys=0&cs=0&lb=1&sb=0&pb=4&mr=1&regions=');
        $html   = (string)$res->getBody();
        $html   = json_decode($html, true);
        foreach ($html['data'] as $val) {
            $ip = $val['ip'] . ':' . $val['port'];
            Proxy::create([
                'ip' => $ip
            ]);
        }
    }
}
