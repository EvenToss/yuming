<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Models\Juzi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use QL\QueryList;
use GuzzleHttp\Psr7\Response;

class JuziPut implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $domain;

    /**
     * Create a new job instance.
     *
     * @param Domain $domain
     */
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $yuming = $this->domain->url;
        $this->index($yuming);
    }

    public function index($yuming)
    {
        $yuming = $this->redicrtUrl($yuming);
        QueryList::multiGet($yuming)->concurrency(10)->withHeaders([
            'Cookie' => 'juz_Session=kc72eembn4k3ucueo2gvopaeq0; Hm_lvt_8b7ca1767a027c0ac4628fae1cf8339e=1578651884,1578709344,1578983203; Hm_lvt_f87ce311d1eb4334ea957f57640e9d15=1578651883,1578983203; juzsnapshot=Y; juz_user_login=ckaN%2FxaWO8T%2FV8Yk2wxe02jycbrjLzlR9SnDxd30FM8qrFAAFRgy9kIB1aabfyBWWDaGT5iR%2BQd%2FW98%2FTUVhMGhpbuPOnyOWjypzHMdYLMWyT9lA3XketZ3KV9a5lPFHxNwm4r3HpRhniB6ySsW9bg%3D%3D; Hm_lpvt_8b7ca1767a027c0ac4628fae1cf8339e=1578986968; Hm_lpvt_f87ce311d1eb4334ea957f57640e9d15=1578986968',
        ])->success(function (QueryList $ql, Response $response, $index) {
            $data = $ql->find('tbody>tr')->map(function ($row) {
                $condition[] = $row->find('td:eq(1)')->texts()->all();
                $condition[] = $row->find('td>a')->texts()->all();

                return $condition;
            });

            foreach ($data as $k => $cont) {
                if (!empty($cont[1])) {
                    Juzi::create([
                        'domain_id' => $this->domain->id,
                        'year'      => $cont[0][0],
                        'title'     => $cont[1][0],
                    ]);
                }
                \Log::info($this->domain->url . '查询完成');
            }
        })->send();
    }

    public function redicrtUrl($yuming)
    {
        $ql = QueryList::post('https://tool.lizseo.com/snapshot/save/', [
            'post_hash'  => 'b72775b559810cc3c85a4e0f977d1e57',
            'domains'    => $yuming,
            'ajax'       => '1',
            'mark_title' => '',
            '_post_type' => 'ajax'
        ], [
            'headers' => [
                'Content-Type'     => 'application/x-www-form-urlencoded',
                'X-Requested-With' => 'xmlhttprequest',
                'User-Agent'       => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
                'Cookie'           => 'juz_Session=kc72eembn4k3ucueo2gvopaeq0; Hm_lvt_8b7ca1767a027c0ac4628fae1cf8339e=1578651884,1578709344,1578983203; Hm_lvt_f87ce311d1eb4334ea957f57640e9d15=1578651883,1578983203; juzsnapshot=Y; juz_user_login=ckaN%2FxaWO8T%2FV8Yk2wxe02jycbrjLzlR9SnDxd30FM8qrFAAFRgy9kIB1aabfyBWWDaGT5iR%2BQd%2FW98%2FTUVhMGhpbuPOnyOWjypzHMdYLMWyT9lA3XketZ3KV9a5lPFHxNwm4r3HpRhniB6ySsW9bg%3D%3D; Hm_lpvt_8b7ca1767a027c0ac4628fae1cf8339e=1578986968; Hm_lpvt_f87ce311d1eb4334ea957f57640e9d15=1578986968',
            ]
        ]);

        $data   = \GuzzleHttp\json_decode($ql->getHtml());
        $urls[] = $data->rsm->url;

        return $urls;
    }
}
