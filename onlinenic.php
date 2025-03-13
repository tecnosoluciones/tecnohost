<?php


class API_Onlinenic{

    const API_TEST_ONLINENIC = 'https://ote.onlinenic.com';
    const API_LIVE_ONLINENIC = 'https://api.onlinenic.com';

    private $user_online_nic;
    private $password_online_nic;
    private $apikey_online_nic;
    private $api_url;

    public function __construct($user_online_nic, $password_online_nic, $apikey_online_nic, $sandbox = true){

            if($sandbox){
                $this->user_online_nic        = '10578';
                $this->password_online_nic    = '654123';
                $this->apikey_online_nic      = 'v}k5s(`ipc$G~koH';
                $this->api_url = self::API_TEST_ONLINENIC;
            }else{
                $this->user_online_nic        = $user_online_nic;
                $this->password_online_nic    = $password_online_nic;
                $this->apikey_online_nic      = $apikey_online_nic;
                $this->api_url = self::API_LIVE_ONLINENIC;
            }

    }

    public function checkDomain(string $domain, $op){
            return $this->request('domain', 'checkDomain', array('domain'=>$domain, 'op'=> $op));
    }

    public function request(string $resource, string $command, array $params = []){

        $timestamp = time();
        $token = md5($this->user_online_nic.md5($this->password_online_nic).$timestamp.$command);

        $data = [
            'user'      => $this->user_online_nic,
            'timestamp' => $timestamp,
            'apikey'    => $this->apikey_online_nic,
            'token'     => $token,
        ];

      /*  $data = $data + $params;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->api_url);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, array('form_params'=>$data) );


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);


        curl_close ($ch);*/

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://ote.onlinenic.com/api4/domain/index.php?command=checkDomain");
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_HEADER, false); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $vars = [
            'user'      => $this->user_online_nic,
            'timestamp' => $timestamp,
            'apikey'    => $this->apikey_online_nic,
            'token'     => $token,
            'domain' => 'tecnosoluciones.com',
            'ext' => 'net'
           // 'password' => $this->password_online_nic,
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($vars));

        $server_output = curl_exec($ch);

        curl_close ($ch);

        
        return $server_output;
    }

}

$online_nic = new API_Onlinenic(null, null, null, true);

var_dump($online_nic->checkDomain('tecnosoluciones', 'com'));