<?php

//get social count
if(!function_exists('tn_count_all_share')){
    function tn_count_all_share($url)
    {
        $url_snip = tn_name_to_id(substr($url, 0, 40));
        $tn_url_shares_transient = 'tn_share_' . $url_snip;
        $cache_result = get_transient($tn_url_shares_transient);

        if ($cache_result !== false) {
            return $cache_result;
        } else {

            //twitter
            $json_string = file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url=' . $url);
            $json = json_decode($json_string, true);
            $count['twitter'] = isset($json['count']) ? intval($json['count']) : 0;

            //linkedin
            $json_string = file_get_contents("http://www.linkedin.com/countserv/count/share?url=$url&format=json");
            $json = json_decode($json_string, true);
            $count['linkedin'] = isset($json['count']) ? intval($json['count']) : 0;

            //facebook
            $json_string = file_get_contents('http://graph.facebook.com/?ids=' . $url);
            $json = json_decode($json_string, true);
            $count['facebook'] = isset($json[$url]['shares']) ? intval(($json[$url]['shares'])) : 0;

            //Pinterest
            $return_data = file_get_contents('http://api.pinterest.com/v1/urls/count.json?url=' . $url);
            $json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
            $json = json_decode($json_string, true);
            $count['pinterest'] = isset($json['count']) ? intval($json['count']) : 0;

            //google plus
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . rawurldecode($url) . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
            $curl_results = curl_exec($curl);
            curl_close($curl);
            $json = json_decode($curl_results, true);
            $count['plus_one'] = isset($json[0]['result']['metadata']['globalCounts']['count']) ? intval($json[0]['result']['metadata']['globalCounts']['count']) : 0;

            $count['all'] = $count['twitter'] + $count['pinterest'] + $count['plus_one'] + $count['facebook'] + $count['linkedin'];

            set_transient($tn_url_shares_transient, $count, 60 * 60 * 4);

            return $count;
        }
    }
}

if(!function_exists('tn_name_to_id')){
    function tn_name_to_id($name)
    {
        $id = str_replace(array(' ', ',', '.', '"', "'", '/', "\\", '+', '=', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '~', '`', '<', '>', '?', '[', ']', '{', '}', '|', ':',), '', $name);
        return $id;
    }
}


