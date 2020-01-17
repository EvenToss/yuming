<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\History;
use App\Models\Juzi;
use Illuminate\Http\Request;

use QL\QueryList;
use GuzzleHttp\Psr7\Response;

class JuziController extends Controller
{
    public function index()
    {
        $yuming = $this->redicrtUrl();
        QueryList::multiGet($yuming)->concurrency(10)->withHeaders([
            'Cookie' => 'juz_Session=kc72eembn4k3ucueo2gvopaeq0; Hm_lvt_8b7ca1767a027c0ac4628fae1cf8339e=1578651884,1578709344,1578983203; Hm_lvt_f87ce311d1eb4334ea957f57640e9d15=1578651883,1578983203; juz_user_login=0d%2BxqBgSvqJ4aw%2Bm%2BnvoYRnraTiykdnxW3lLaiSfflhL2qXkbYXHjfRltGWE8hYAByIyKwagXDkUZtE%2FOy7000BUsdER%2BPD1lu7Cf%2BjAcSa%2BcRuqaRehV54ML5EclwMd; juzsnapshot=Y; Hm_lpvt_8b7ca1767a027c0ac4628fae1cf8339e=1578983350; Hm_lpvt_f87ce311d1eb4334ea957f57640e9d15=1578983350',
        ])->success(function (QueryList $ql, Response $response, $index) {
            $data = $ql->find('tbody>tr')->map(function ($row) {
                $condition[] = $row->find('td:eq(1)')->texts()->all();
                $condition[] = $row->find('td>a')->texts()->all();
                return $condition;
            });

            foreach ($data as $k => $cont) {
                if (!empty($cont[1])) {
                    Juzi::create([
                        'domain_id' => 1,
                        'year' => $cont[0][0],
                        'title' => $cont[1][0],
                    ]);
                }
            }
            echo 'success';
        })->send();
    }

    public function redicrtUrl()
    {
        $yuming = 'yescq.com';
        $ql     = QueryList::post('https://tool.lizseo.com/snapshot/save/', [
            'post_hash'  => 'ab7759765951684bf43d69d79f51d3ed',
            'domains'    => $yuming,
            'ajax'       => '1',
            'mark_title' => '',
            '_post_type' => 'ajax'
        ], [
            'headers' => [
                'Content-Type'     => 'application/x-www-form-urlencoded',
                'X-Requested-With' => 'xmlhttprequest',
                'User-Agent'       => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
                'Cookie'           => 'juz_Session=kc72eembn4k3ucueo2gvopaeq0; Hm_lvt_8b7ca1767a027c0ac4628fae1cf8339e=1578651884,1578709344,1578983203; Hm_lvt_f87ce311d1eb4334ea957f57640e9d15=1578651883,1578983203; juz_user_login=0d%2BxqBgSvqJ4aw%2Bm%2BnvoYRnraTiykdnxW3lLaiSfflhL2qXkbYXHjfRltGWE8hYAByIyKwagXDkUZtE%2FOy7000BUsdER%2BPD1lu7Cf%2BjAcSa%2BcRuqaRehV54ML5EclwMd; juzsnapshot=Y; Hm_lpvt_8b7ca1767a027c0ac4628fae1cf8339e=1578983350; Hm_lpvt_f87ce311d1eb4334ea957f57640e9d15=1578983350',
            ]
        ]);

        $data   = \GuzzleHttp\json_decode($ql->getHtml());
        $urls[] = $data->rsm->url;

        return $urls;
    }
}
