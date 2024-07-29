<?php

namespace Drupal\dhl_api\services;

use GuzzleHttp\ClientInterface;
use Drupal\Component\Render\FormattableMarkup;
//use Symfony\Component\Yaml\Yaml;
use Drupal\Component\Serialization\Yaml;

/**
 * Class ApiService.
 */
class api_service {

    /**
     * GuzzleHttp\ClientInterface definition.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;
  
    /**
     * Constructs a new ApiService object.
     */
    public function __construct(ClientInterface $http_client) {
      $this->httpClient = $http_client;
    }
  
    /**
     * Implements connectApi.
     */
    public function getlocation($form_state) {
      $countrycode = $form_state->getValue('country');
      $addressLocality = $form_state->getValue('city');
      $postalCode = $form_state->getValue('postalcode');
      $endpoint = "https://api.dhl.com/location-finder/v1/find-by-address?countryCode=$countrycode&addressLocality= $addressLocality&postalCode=$postalCode";
        
      try {
        $response = $this->httpClient->get($endpoint,
        ['headers' => ['Accept' => 'application/json',
         'DHL-API-Key'=> '4GZoFzxZOgcpnYDlbRUoxkFyEAkNMipq']]);
        $data = $response->getBody()->getContents();
        $data_array = json_decode($data, TRUE);

        //here consider as what all are open time with 00:00:00 itis holiday
        foreach($data_array['locations'] as $elementKey => $element) {
            if($element['openingHours'][0]['opens'] === '00:00:00'){

                unset($data_array['locations'][$elementKey]);
            }
            
        }  
       //here i have use two diffrent kind of data so i have used two expload option
        foreach($data_array['locations'] as $elementKey => $element) {
            if($element['place']['address']['streetAddress'] != ''){
                $num =  explode(".", $element['place']['address']['streetAddress']);
                $numwithspace =  explode(" ", $element['place']['address']['streetAddress']);
               
                if( (json_decode(($num['1'])) % 2 != 0) or (json_decode(($numwithspace['1'])) % 2 != 0) ){
                        unset($data_array['locations'][$elementKey]);
                }
                
            }
            
        }  
        
        //here is yaml out put
        $breakpointEncoded = Yaml::encode($data_array['locations']);
        echo"<pre>";
         print_r($breakpointEncoded);
        echo"</pre>";       
        exit;
      }
      catch (RequestException $e) {
        return FALSE;
      }
    }
  
  }