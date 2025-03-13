<?php


class API_Onlinenic{

    const API_TEST_ONLINENIC = 'https://ote.onlinenic.com:5999/api4';
    const API_LIVE_ONLINENIC = 'https://api.onlinenic.com/api4';

    public $user_online_nic;
    private $password_online_nic;
    private $apikey_online_nic;
    private $api_url;

    public function __construct($user_online_nic, $password_online_nic, $apikey_online_nic, $sandbox = true){

        if($sandbox){
            $this->user_online_nic        = '10578';
            $this->password_online_nic    = '654123';
            $this->apikey_online_nic      = '{![Xic=GAUlWXEI_';
            $this->api_url = self::API_TEST_ONLINENIC;
        }else{
            $this->user_online_nic        = $user_online_nic;
            $this->password_online_nic    = $password_online_nic;
            $this->apikey_online_nic      = $apikey_online_nic;
            $this->api_url = self::API_LIVE_ONLINENIC;
        }

    }

    public function getAuthCode($data){
        return $this->request('domain', 'getAuthCode', $data);
    }
    public function CreateContact($data){
        return $this->request('domain', 'CreateContact', $data);
    }
    public function domainChangeContact($data){
            return $this->request('domain', 'domainChangeContact', $data);
    }
    public function infoContact($data){
                return $this->request('domain', 'infoContact', $data);
    }
    public function updateContact($data){
                return $this->request('domain', 'updateContact', $data);
    }

    public function registerDomain($data){
        return $this->request('domain', 'registerDomain', $data);
    }
    public function infoDomain($domain){

        return $this->request('domain', 'infoDomain',  array('domain'=>$domain));
    }

    public function updateDomainDns($domain, $dns){

        $parameters = array_merge(array('domain'=>$domain), $dns);
        return $this->request('domain', 'updateDomainDns', $parameters);
    }

    public function checkDomain($domain, $op = 1){
        return $this->request('domain', 'checkDomain', array('domain'=>$domain, 'op'=> $op));
    }

    public function authCode($domain){
        return $this->request('domain', 'getAuthCode', array('domain'=>$domain));
    }

    public function request($resource,  $command, array $params = []){


        $timestamp = time();
        $token = md5($this->user_online_nic.md5($this->password_online_nic).$timestamp.$command);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->api_url."/domain/index.php?command=".$command);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        $vars = [
            'user'      => $this->user_online_nic,
            'timestamp' => $timestamp,
            'apikey'    => $this->apikey_online_nic,
            'token'     => $token
        ];

        $vars_ = array_merge($vars, $params);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($vars_));

        $server_output = curl_exec($ch);
        if ($server_output === false) {
                echo curl_error($ch).curl_errno($ch);
            }
        curl_close ($ch);

        return $server_output;
    }

}


