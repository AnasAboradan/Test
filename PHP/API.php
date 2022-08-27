<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
require 'Data.php';
$URL='https://www.forsakringskassan.se/fk_apps/MEKAREST/public/v1/iv-planerad/IVplaneradvardland.json';

class API{

    private $Url;
    private $Source;
    function __construct($url)
    {
      $this->Url=$url;
      $this->init_Source($url);
    }

    private function init_Source($url){
        $this->Source=new Data($url);
        if(!$this->Source->get_isDataAvailable())
        die($this->error('can not connect the server'));
    }

    // listen to request send from the applications
    public function response_to_request()
    { 
       if(isset($_GET['year']))
        {
          $year=$_GET['year'];
          if(ctype_digit($year)) $this->create_list_of_counteris_code($year);
          else die($this->error('Invalid year')); 
        }
        else if($_GET['list_of_year']) {
            $this->create_list_of_year();
        }
        else die($this->error('Invalid variable name'));
    }
     
    private function create_list_of_counteris_code($year){
        $list= $this->Source->get_list_of_contriesCode_males_and_females($year);
        $this->success('',$list);
    }
   
    function create_list_of_year()
    {   
        $list=$this->Source->get_list_of_years();
        $this->success('',$list);
    }
      // send error in case the request failed or the source did not respond
    private function error($msg){
        $response=array('success' =>false , "message"=> $msg,'data'=>null);
        echo json_encode($response);
    }
    // send the data in case request has been successful
    private function success($msg,$data)
    {
        $response=array('success' =>true , "message"=> $msg,'data'=>$data);
        echo json_encode($response);
    }

}


  $Api= new API($URL); 
  $Api->response_to_request();
  










