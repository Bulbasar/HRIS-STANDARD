<?php
//namespace BiometricsData;
class Employee {
    private $url;
    private $password;

    public function __construct($password, $url) {
        $this->password = $password;
        $this->url = $url;
    }

    public function Create($name, $userPass, $id, $contactNum = null) {
        $api = $this->url . '/person/create';
        $personData = array(
            'id' => $id,
            'name' => $name,
            'facePermission' => '2',
            'fingerPermission' => '2',
            'passwordPermission' => '2',
            'phone' => $contactNum != null ? $contactNum : '',
            'password' => $userPass,
        );

        $jsonEncoded_personData = json_encode($personData);

        $params = array(
            'pass' => $this->password,
            'person' => $jsonEncoded_personData
        );

        $endpoint = $api . '?' . http_build_query($params);
        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        try {
            $data = curl_exec($curl);
            $res = json_decode($data);
            return isset($res->msg) ? $res->msg : 'Something went wrong, please try again';
        } catch (Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        } finally {
            curl_close($curl);
        }
    }

    public function GetAllRecords() {
        $api = $this->url . '/person/all';

        $curl = curl_init($api);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        try {
            $data = curl_exec($curl);
            $res = json_decode($data);
            return $res;
        } catch (Exception $e) {
            return null;
        } finally {
            curl_close($curl);
        }
    }

    public function IsEmployeeExist($empID) {
        $api = $this->url . "/person/find?pass=$this->password&id=$empID";

        $curl = curl_init($api);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        try {
            $data = curl_exec($curl);
            $res = json_decode($data);
        } catch (Exception $e) {
            return null;
        } finally {
            curl_close($curl);
        }

        if ($res == null) return null;
        return $res->success ? true : false;
    }



    // public function IsEmployeeExist($empID){
    //     //echo $this->password;
    //     $api = $this->url."/person/find?pass=$this->password&id=$empID";
    
    //     try {
    //         $curl = curl_init($api);
    //         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //         $data = curl_exec($curl);
    
    //         if ($data === false) {
    //             // Handle the cURL error
    //             throw new Exception("cURL error: " . curl_error($curl));
    //         }
    
    //         $res = json_decode($data);
    
    //         if ($res === null) {
    //             // Handle JSON decoding error
    //             throw new Exception("JSON decoding error: " . json_last_error_msg());
    //         }
    //     } catch (Exception $e) {
    //         // Handle the exception, log or return an error message
    //         return null;
    //     }
    
    //     return isset($res->success) ? $res->success : false;
    // }
}
