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
            'Cookie' => 'juzsnapshot=Y; juz_Session=oat79l47lm4371pn0er3uv7st1; Hm_lvt_8b7ca1767a027c0ac4628fae1cf8339e=1576135826,1576564276,1577771900; Hm_lvt_f87ce311d1eb4334ea957f57640e9d15=1576135826,1576564276,1577771900; juz_user_login=Sq1Wbe8ucv%2F1OKlimdYl4WQ5dJRPq61yECPKjc5HNcikzgxXZCzgFXKqisssVzE9h99Z6hy1%2FIwOeWbIDxM6J7mCIfRhe5t8vlMLO3z3uXixBge5Mfh1AAEYDpo0S0nG; Hm_lpvt_f87ce311d1eb4334ea957f57640e9d15=1577773624; Hm_lpvt_8b7ca1767a027c0ac4628fae1cf8339e=1577773624',
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
            'post_hash'  => '5340593a2176f2af82b5b301e8fc489b',
            'domains'    => $yuming,
            'ajax'       => '1',
            'mark_title' => '',
            '_post_type' => 'ajax'
        ], [
            'headers' => [
                'Content-Type'     => 'application/x-www-form-urlencoded',
                'X-Requested-With' => 'xmlhttprequest',
                'User-Agent'       => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
                'Cookie'           => 'juzsnapshot=Y; juz_Session=oat79l47lm4371pn0er3uv7st1; Hm_lvt_8b7ca1767a027c0ac4628fae1cf8339e=1576135826,1576564276,1577771900; Hm_lvt_f87ce311d1eb4334ea957f57640e9d15=1576135826,1576564276,1577771900; juz_user_login=Sq1Wbe8ucv%2F1OKlimdYl4WQ5dJRPq61yECPKjc5HNcikzgxXZCzgFXKqisssVzE9h99Z6hy1%2FIwOeWbIDxM6J7mCIfRhe5t8vlMLO3z3uXixBge5Mfh1AAEYDpo0S0nG; Hm_lpvt_f87ce311d1eb4334ea957f57640e9d15=1577773624; Hm_lpvt_8b7ca1767a027c0ac4628fae1cf8339e=1577773624',
            ]
        ]);

        $data   = \GuzzleHttp\json_decode($ql->getHtml());
        $urls[] = $data->rsm->url;

        return $urls;
    }
}
