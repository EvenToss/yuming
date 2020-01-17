<?php

namespace App\Http\Controllers;

use App\Exports\DomainExport;
use App\Jobs\JuziPut;
use App\Jobs\ProxyPut;
use App\Jobs\YumingPut;
use App\Models\Domain;
use App\Models\Proxy;
use GuzzleHttp\Psr7\Response;
use Maatwebsite\Excel\Facades\Excel;
use QL\QueryList;

class DomainController extends Controller
{

    public function index()
    {
        $ql = QueryList::get('https://lishi.aizhan.com');
        dd($ql);

        $urls = [
            'http://www.juming.com/ykj/?api_sou=1&tao=84099&ympc=120&ympc_kt=0&ympc_jw=0&ymlx=0&qian1=1000&qian2=1000&changdu2=8&jgpx=9&meiye=5&page=1'
        ];
        $proxy  = Proxy::count();
        if ($proxy < 6) {
            dispatch(new ProxyPut());
        }
        QueryList::multiGet($urls)->concurrency(5)->withHeaders([
            'Cookie' => 'ASPSESSIONIDQQQDRSDD=OIPIMBICHLLPHDGLMBKFPCOE; UM_distinctid=16fa2a31eb56c7-058caa1a8b1925-36664c08-1fa400-16fa2a31eb7980; Hm_lvt_512ed551fae9428abd7d743009588c7a=1578981597; Hm_lvt_f94e107103e3c39e0665d52b6d4a93e7=1578981597; IESESSION=alive; _qddaz=QD.lcnrmd.uwpjzd.k5dgvhqi; _qddab=3-4mbdw8.k5dgvhqk; pgv_pvi=8877628416; pgv_si=s5736689664; tencentSig=7987227648; Juming%2Ecom=islogincode=ed294e6c2b9d1a4774&login%5Fuid=244174&new%5Fbanban%5Fzhu=1; ASPSESSIONIDQQQCQTCC=FBEAPLKCMLBNANNOCODGFINI; CNZZDATA3432862=cnzz_eid%3D2023393431-1578976949-%26ntime%3D1578982350; skinName=null; Hm_lpvt_512ed551fae9428abd7d743009588c7a=1578985114; Hm_lpvt_f94e107103e3c39e0665d52b6d4a93e7=1578985114'
        ])->success(function (QueryList $ql){
            $data  = $ql->find('.domainsc')->texts();
            $datas = $data->all();
            foreach ($datas as $data) {
                $domain = Domain::create([
                    'url' => $data
                ]);
                dispatch(new YumingPut($domain));
//                dispatch(new JuziPut($domain));
            }
            echo '完成';
        })->send();
    }

    public function export()
    {
        return Excel::download(new DomainExport , 'Domain.xlsx');
    }
}
