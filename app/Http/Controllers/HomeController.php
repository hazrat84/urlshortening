<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Input;
use App\Url;

class HomeController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index(Request $request){
            $site_url = $request->fullUrl();
            $InvalidUrl = '';
            $urlempty = '';
            $shorturl_length = 0;
            $short_url = '';

            if ($request->input('submit')){
            echo 'Is set';
        }

            $url = $request->get('url');
            if($url != ''){
                if (!$this->checkUrl($url)) {
                    $InvalidUrl = 'Invalid Url';
                    $url = '';
                }else{ // url is valid
                    
                    $url_shortcode = $this->short_url_calculation($url);

                    $db_url = Url::where('shortenurl', '=', $url_shortcode)->first();

                    $url_length = strlen($url);
                    $short_url = $site_url.'/'.$url_shortcode;
                    $shorturl_length = strlen($short_url);

                    if(count($db_url) == 0){
                        // if not in db then insert
                        Url::Create([
                            'shortenurl' => $url_shortcode,
                            'url' => $url  
                        ]);
                    }
                }
                
                $urlempty = '';
            }else{ // url is empty check

                $url = '';
                $url_length = 0;
            }

            return view('home')->with(['url' => $url])
                           ->with(['url_length' => $url_length])
                           ->with(['urlempty' => $urlempty])
                           ->with(['InvalidUrl' => $InvalidUrl])
                           ->with(['shorturl_length' => $shorturl_length])
                           ->with(['short_url' => $short_url]);
        
        
    }

    public function short_url_calculation($url){
        $url_md5 = md5($url);
        $url_binary = $this->strToBin3($url_md5);

        $_43bitstring = mb_substr($url_binary, 0, 43);

        $url_shortcode = $this->base_convert_alt($_43bitstring, 2, 62);

        return $url_shortcode;
    }

    public function checkUrl($url){
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
                    return false;
        }else{
            return true;
        }
    }

    public function strToBin3($input)
    {
        if (!is_string($input))
            return false;
        $input = unpack('H*', $input);
        $chunks = str_split($input[1], 2);
        $ret = '';
        foreach ($chunks as $chunk)
        {
            $temp = base_convert($chunk, 16, 2);
            $ret .= str_repeat("0", 8 - strlen($temp)) . $temp;
        }
        return $ret;
    }

    public function base_convert_alt($val,$from_base,$to_base){
        static $gmp;
        static $bc;
        static $gmp62;
        static $ratio;
        if ($from_base<37) $val=strtoupper($val);
        if ($gmp===null) $gmp=function_exists('gmp_init');
        if ($gmp62===null) $gmp62=version_compare(PHP_VERSION,'5.3.2')>=0;
        if ($gmp && ($gmp62 or ($from_base<37 && $to_base<37)))
        return gmp_strval(gmp_init($val,$from_base),$to_base);
        if ($bc===null) $bc=function_exists('bcscale');
        $range='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        if ($from_base==10)
        $base_10=$val;
        else
        {
        $n=strlen(($val="$val"))-++$ratio;
        if ($bc) for($i=$n;$i>-1;($ratio=bcmul($ratio,$from_base)) && $i--)
        $base_10=bcadd($base_10,bcmul(strpos($range,$val[$i]),$ratio));
        else for($i=$n;$i>-1;($ratio*=$from_base) && $i--)
        $base_10+=strpos($range,$val[$i])*$ratio;
        }
        if ($bc)
        do $result.=$range[bcmod($base_10,$to_base)];
        while(($base_10=bcdiv($base_10,$to_base))>=1);
        else
        do $result.=$range[$base_10%$to_base];
        while(($base_10/=$to_base)>=1);
        return strrev($to_base<37?strtolower($result):$result);
    }
}