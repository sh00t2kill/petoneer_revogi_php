<?php


class Petoneer {
    protected $access_token;
    protected $base_url = 'https://as.revogi.net/app/';
    protected $username = "<<EMAIL>>";
    protected $password = "<<PASSWORD>>";
    protected $cache_file = 'cache.json';

    public function __construct() {
        $this->access_token = $this->getLoginToken();
        //This doesnt seem to actually be required -- it might not actually be login!
        //$this->login();
    }

    protected function getLoginToken() {
        if (time() - filemtime($this->cache_file) < 604800) {
            $cache_data = json_decode(file_get_contents($this->cache_file));
            if (isset($cache_data->token)) {
                return $cache_data->token;
            }
        }

        $data = json_encode(array(
            'language' => 0,
            'password' => $this->password,
            'region' => array(
                'country' => "AU",
                'timezone' => "Australia/Sydney"
            ),
            'username' => $this->username,
        ));

        $request = $this->post($data, 'user/101');
        $save_data = json_encode(array('token' => $request['data']['accessToken']));
        file_put_contents($this->cache_file, $save_data);
        return $request['data']['accessToken'];
    }

    protected function login() {
        $data = array(
                'language' => 0,
                'protocol' => 3,
        );
        $request = $this->post(json_encode($data), 'user/506');
    }

    protected function post($data, $endpoint) {
        // build our curl object
        $ch = curl_init($this->base_url . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $headers = array(
                'Content-Type: application/json',
                'accessToken: ' . $this->access_token,
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = json_decode(curl_exec($ch), true);
        return $response;
    }

    public function getDevices() {
        $data = array(
            'dev' => 'all',
            'protocol' => 3,
        );
        $request = $this->post(json_encode($data), 'user/500');
        return $request;
    }

    public function getDevice($device) {
        $data = array(
                'protocol' => 3,
                'sn' => $device,
        );
        $request = $this->post(json_encode($data), 'pww/31101');
        return $request;
    }
}
