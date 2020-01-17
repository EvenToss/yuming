<?php

namespace App\Jobs;

use QL\QueryList;
use GuzzleHttp\Psr7\Response;
use App\Models\Domain;
use App\Models\History;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class YumingPut implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $domain;

    public    $timeout = 120;
    protected $tires   = 3;

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
        try {
            $yuming   = [];
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2019-07-01/2019-12-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2019-01-01/2019-06-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2018-07-01/2018-12-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2018-01-01/2018-06-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2017-07-01/2017-12-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2017-01-01/2017-06-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2016-07-01/2016-12-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2016-01-01/2016-06-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2015-07-01/2015-12-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2015-01-01/2015-06-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2014-07-01/2014-12-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2014-01-01/2014-06-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2013-07-01/2013-12-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2013-01-01/2013-06-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2012-07-01/2012-12-30/';
            $yuming[] = 'https://lishi.aizhan.com/' . $this->domain->url . '/randabr/2012-01-01/2012-06-30/';

            $this->lishi($yuming);
        } catch (\Exception $e) {
            \Log::info($this->domain->url . '---', ['error' => $e->getMessage()]);
        }

    }

    public function lishi($yuming)
    {
        QueryList::multiGet($yuming)->success(function (QueryList $ql, Response $response, $index) {
            $often = $ql->find('.cha-default')->texts()->all();
            if (!strcmp($often, '您的查询太频繁了,请稍后查询! 谢谢您的使用')) {

                $data = $ql->find('tr:gt(0)')->map(function ($row) {
                    return $row->find('td')->texts()->all();
                });

                $datas = $data->all();

                array_pop($datas);

                foreach ($datas as $val) {
                    $p_w = str_replace('-', 0, $val[1]);
                    $m_w = str_replace('-', 0, $val[2]);
                    $p_v = str_replace('-', 0, str_replace(',', '', $val[3]));
                    $m_v = str_replace('-', 0, str_replace(',', '', $val[4]));

                    $this->domain->histories()->create([
                        'time'          => $val[0],
                        'pc_weight'     => $p_w,
                        'm_weight'      => $m_w,
                        'pc_vocabulary' => $p_v,
                        'm_vocabulary'  => $m_v,
                        'domain_id'     => $this->domain->id
                    ]);
                    \Log::info($this->domain->url . '查询完成');
                }
            }
        })->send();
    }
}
