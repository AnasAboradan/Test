<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");


class Data {
   
    private $data;
    private $source;
    private $isDataAvailable;
  
    function __construct($url) 
    {
      $this->source= $url;
      $this->init_data();
    }
    
   // // get data from source
   private function init_data()
    {
      $response=file_get_contents($this->source); 
      if($response!=false)  //success
      {
         $this->data=json_decode($response,true); 
         $this->isDataAvailable=true;
      }
      else     //faild
      { $this->data=null;
        $this->isDataAvailable=false;
      }
    }
 
    //return list of years the data contains
    public function get_list_of_years()
    {
      $length= count($this->data);
      $years=array();

      for ($i = 0; $i <= $length; $i++) 
      {
        if(isset($this->data[$i]['dimensions']['ar']))
        if(!in_array( $this->data[$i]['dimensions']['ar'],$years))
        array_push($years,$this->data[$i]['dimensions']['ar']);
      }
      return $years;
    }

    public function get_list_of_contriesCode_males_and_females($select_year)
    {
      
      $length= count($this->data);
      $contriesCode_males_and_females=array();

      for ($i = 0; $i <=  $length; $i++) 
      {
        
        if(isset($this->data[$i]['dimensions']['ar']))
        {
          
          $year=$this->data[$i]['dimensions']['ar'];
          $sex=$this->data[$i]['dimensions']['kon_kod'];
          $country_code=$this->data[$i]['dimensions']['vardland_kod'];
          $number_of_persons=$this->data[$i]['observations']['antal']['value'];
         
          if($year==$select_year && $sex!='ALL')
          {     //  create dictionary key(country code) value(numbers males/ females)
                // if the key already found , just update the value
            if(array_key_exists($country_code, $contriesCode_males_and_females))  
            {
               
                if($sex=='M')
                {
                  $contriesCode_males_and_females[$country_code]['males']=$number_of_persons;
                  
                }
                else
                {
                  $contriesCode_males_and_females[$country_code]['females']=$number_of_persons;
                }

            }
            else  // key not found, Add new key
            { 
                if($sex=='M')
                {

                  $contriesCode_males_and_females[$country_code]=array('males' => $number_of_persons,'females'=>null);                                       
                    
                }
                else 
                {
                  $contriesCode_males_and_females[$country_code]=array('males' => null,'females'=>$number_of_persons); 
                }  
            }
         }
       }
     }
         return $contriesCode_males_and_females;
   }
    
   // return true if data avilable
   public function get_isDataAvailable() 
    {
      return $this->isDataAvailable;
    }

}
