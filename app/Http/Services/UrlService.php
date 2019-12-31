<?php
/**
 * Created by : PhpStorm
 * User: Even
 * Date: 2019/12/30
 * Time: 15:46
 */
namespace App\Services;

use App\Models\Domain;

class UrlService
{

    public function getUrl($id)
    {

        $url = Domain::find($id);

        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2019-07-01/2019-12-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2019-01-01/2019-06-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2018-07-01/2018-12-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2018-01-01/2018-06-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2017-07-01/2017-12-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2017-01-01/2017-06-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2016-07-01/2016-12-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2016-01-01/2016-06-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2015-07-01/2015-12-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2015-01-01/2015-06-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2014-07-01/2014-12-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2014-01-01/2014-06-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2013-07-01/2013-12-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2013-01-01/2013-06-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2012-07-01/2012-12-30/';
        $urls[] = 'https://lishi.aizhan.com/' . $url->url . '/randabr/2012-01-01/2012-06-30/';

        return $urls;
    }

}
