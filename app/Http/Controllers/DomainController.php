<?php

namespace App\Http\Controllers;

use App\Jobs\JuziPut;
use App\Jobs\YumingPut;
use App\Models\Domain;
use App\Models\Juzi;
use QL\QueryList;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class DomainController extends Controller
{

    public function index()
    {
//        $client = new \GuzzleHttp\Client();
//        $res    = $client->request('POST', 'http://www.juming.com/ykj/', [
//            'ymbhfs'   => 0,
//            'ymbh'     => '',
//            'api_sou'  => 1,
//            'tb'       => 1,
//            'tao'      => 0,
//            'zt'       => 0,
//            'baocun2'  => '',
//            'ympc'     => '',
//            'ymlx'     => 2,
//            'ymhz'     => '',
//            'sfba'     => '',
//            'jgpx'     => 9,
//            'zcsj'     => 0,
//            'dqsj'     => 0,
//            'qian1'    => 100,
//            'qian2'    => 500,
//            'changdu1' => '',
//            'changdu2' => '',
//            'pr1'      => '',
//            'pr2'      => '',
//        ], [
//            'headers' => [
//                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
//                'Cookie'          => 'UM_distinctid=16e587b91c74cf-0f4b0ae3056c16-36664c08-1fa400-16e587b91c98c5; pgv_pvi=349203456; _qddaz=QD.9krexk.qdas9k.k2tv1xwd; tencentSig=3800264704; ASPSESSIONIDQSSDSRCD=MPACJPJALINLFNMEILPHFBJN; CNZZDATA3432862=cnzz_eid%3D1174670591-1573438370-%26ntime%3D1577697713; Hm_lvt_512ed551fae9428abd7d743009588c7a=1576135877,1576564264,1577699114; Hm_lvt_f94e107103e3c39e0665d52b6d4a93e7=1576135877,1576564264,1577699114; IESESSION=alive; pgv_si=s4203037696; _qdda=3-1.1; _qddab=3-sqfrr7.k4s9bg6o; _qddamta_4009972996=3-0; ASPSESSIONIDQSQAQTCC=HPKMHGNABEFCGBGGCLPJMIGD; Hm_lpvt_512ed551fae9428abd7d743009588c7a=1577699168; Hm_lpvt_f94e107103e3c39e0665d52b6d4a93e7=1577699168; Juming%2Ecom=islogincode=4220518ed3ff10f5c2&login%5Fuid=244174&sc%5Fcsrf=fdfcc6260b5d1013c9&new%5Fbanban%5Fzhu=1; ASPSESSIONIDQSQBTRDC=INHGIEOAIPEOLCGBLAPPBKFI',
//                'Accept-Language' => 'zh-CN,zh;q=0.9'
//            ]
//        ]);
//        $html   = (string)$res->getBody();
//        $data   = QueryList::html($html)->find('.domainsc')->texts();
//        dd($data);


        //http://www.juming.com/ykj/?api_sou=1&ymlx=2&qian1=100&qian2=500&jgpx=9&meiye=500


        $urls = [
            'http://www.juming.com/ykj/?api_sou=1&ymlx=2&qian1=100&qian2=200&jgpx=9&meiye=10'
        ];

        QueryList::multiGet($urls)->success(function (QueryList $ql, Response $response, $index) {
            $data  = $ql->find('.domainsc')->texts();
            $datas = $data->all();
            foreach ($datas as $data) {
                $domain = Domain::create([
                    'url' => $data
                ]);
                dispatch(new YumingPut($domain));
                dispatch(new JuziPut($domain));
            }
            echo '完成';
        })->send();


    }
}
