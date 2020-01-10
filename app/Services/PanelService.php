<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class PanelService
{
    /**
     * Check url
     *
     * @param $url
     * @param $checks
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkUrl($url, $checks){
        $client = new \GuzzleHttp\Client();
        try{
            $res = $client->request('GET', $url);
        }catch(\Exception $exception){
            return false;
        }
        $resBody = $res->getBody();
        
        if($checks['first_part']){
            $resBodyFirstPart = self::parse($resBody, $checks['first_part_start'], $checks['first_part_end']);
            if($resBodyFirstPart === false){
                return false;
            }
            $result = (bool)substr_count($resBodyFirstPart, $checks['first_matching']);
        }else{
            $result = (bool)substr_count($resBody, $checks['first_matching']);
        }
        $result = ($checks['first_if'] === 'without') ? !$result : $result;
        
        if(!$checks['add_status']){
            return $result;
        }
        
        if($checks['second_part']){
            $resBodySecondPart = self::parse($resBody, $checks['second_part_start'], $checks['second_part_end']);
            if($resBodySecondPart === false){
                return false;
            }
            $secondResult = (bool)substr_count($resBodySecondPart, $checks['second_matching']);
        }else{
            $secondResult = (bool)substr_count($resBody, $checks['second_matching']);
        }
        $secondResult = ($checks['second_if'] === 'without') ? !$secondResult : $secondResult;
        
        if($checks['add_cond'] === 'and'){
            $result = ($result && $secondResult);
        }else{
            // or
            $result = ($result || $secondResult);
        }
        
        return $result;
    }
    
    /**
     * Check urls
     *
     * @param $urls
     * @param $checks
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkUrls($urls, $checks){
        $validator = Validator::make(array_keys($urls), [
            '*' => 'url',
        ]);
        
        if($validator->fails()){
            return false;
        }
        
        foreach($urls as $url => $status){
            $urls[$url] = self::checkUrl($url, $checks);
        }
        
        return $urls;
    }
    
    /**
     * Cut string
     *
     * @param $string
     * @param $start
     * @param $end
     * @return bool|string
     */
    public static function parse($string, $start, $end){
        $startPos = strpos($string, $start);
        if($startPos === false){
            return false;
        }
        
        $string = substr($string, $startPos);
        
        $endPos = strpos($string, $end);
        if($endPos === false){
            return false;
        }
        
        return substr($string, 0, $endPos);
    }
    
    /**
     * Cut UTM
     *
     * @param $string
     * @return string|string[]|null
     */
    public static function cutUtm($string){
        $patternOne = [
            '/&utm_source=?[^&#]*/i',
            '/&utm_medium=?[^&#]*/i',
            '/&utm_campaign=?[^&#]*/i',
            '/&utm_content=?[^&#]*/i',
            '/&utm_term=?[^&#]*/i',
        ];
        $patternTwo = [
            '/\?utm_source=?.*&/iU',
            '/\?utm_medium=?.*&/iU',
            '/\?utm_campaign=?.*&/iU',
            '/\?utm_content=?.*&/iU',
            '/\?utm_term=?.*&/iU',
        ];
        $patternThree = [
            '/\?utm_source=?[^&#]*$/i',
            '/\?utm_medium=?[^&#]*$/i',
            '/\?utm_campaign=?[^&#]*$/i',
            '/\?utm_content=?[^&#]*$/i',
            '/\?utm_term=?[^&#]*$/i',
        ];
        
        $string = preg_replace($patternOne, '', $string);
        $string = preg_replace($patternTwo, '?', $string);
        $string = preg_replace($patternThree, '', $string);
        
        return $string;
    }
    
    /**
     * Get ads links
     *
     * @param $ads
     * @return array
     */
    public static function getAdsLinks($ads){
        $links = [];
        foreach($ads as $ad){
            $links[$ad->TextAd->Href] = '';
        }
        return $links;
    }
}
