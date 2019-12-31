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
            'Cookie' => 'juzsnapshot=Y; juz_Session=oat79l47lm4371pn0er3uv7st1; Hm_lvt_8b7ca1767a027c0ac4628fae1cf8339e=1576135826,1576564276,1577771900; Hm_lvt_f87ce311d1eb4334ea957f57640e9d15=1576135826,1576564276,1577771900; juz_user_login=YtT0WTOP7OKa3GwWEQvOp7wcdCbyASOe5xrTaJi7z8hArc%2FdzSby9ojZIAsfuY2whbsBVf8UMf%2FhQqeI4SDr77Si%2FNxPKGGyMniKMqi5UUz0ETn7DIHI4gQ5JrPeckfw; Hm_lpvt_8b7ca1767a027c0ac4628fae1cf8339e=1577785857; Hm_lpvt_f87ce311d1eb4334ea957f57640e9d15=1577785857',
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
            'post_hash'  => '8405484ac34935413db24f4f8a48dc42',
            'domains'    => $yuming,
            'ajax'       => '1',
            'mark_title' => '',
            '_post_type' => 'ajax'
        ], [
            'headers' => [
                'Content-Type'     => 'application/x-www-form-urlencoded',
                'X-Requested-With' => 'xmlhttprequest',
                'User-Agent'       => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
                'Cookie'           => 'juzsnapshot=Y; juz_Session=oat79l47lm4371pn0er3uv7st1; Hm_lvt_8b7ca1767a027c0ac4628fae1cf8339e=1576135826,1576564276,1577771900; Hm_lvt_f87ce311d1eb4334ea957f57640e9d15=1576135826,1576564276,1577771900; juz_user_login=YtT0WTOP7OKa3GwWEQvOp7wcdCbyASOe5xrTaJi7z8hArc%2FdzSby9ojZIAsfuY2whbsBVf8UMf%2FhQqeI4SDr77Si%2FNxPKGGyMniKMqi5UUz0ETn7DIHI4gQ5JrPeckfw; Hm_lpvt_8b7ca1767a027c0ac4628fae1cf8339e=1577785857; Hm_lpvt_f87ce311d1eb4334ea957f57640e9d15=1577785857',
            ]
        ]);

        $data   = \GuzzleHttp\json_decode($ql->getHtml());
        $urls[] = $data->rsm->url;

        return $urls;
    }
}
