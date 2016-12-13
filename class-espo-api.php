<?php

/**
 * Make api calls to EspoCRM
 *
 * @author DonavynElliott
 */



class EspoApi
{
    
    // properties
    protected $endpoint, $authorization, $headers, $curl;
    
    public function __construct($domain, $username, $password)
    {
        $this->endpoint      = $domain . '/api/v1/';
        $this->authorization = $this->authorize($username, $password);
    }
    
    /**
     * creates a curl session and stores it in the curl property. sets curls headers.
     *
     * @param string $query the domain, entity type, and search parameters combined into url
     * @return curl $curl A curl resource ready to be executed
     */
    private function setHeaders($query)
    {
        //create new curl if not set;
        $curl = (isset($this->curl)) ? $this->curl : curl_init();
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $header[0] = $this->authorization;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $query . urlencode($email));
        return $curl;
    }
    
    /**
     * creates a encoded string containing the username & password ready to be used in the headers
     *
     * @param string $username username for authorization token
     * @param string $password password for authorization token
     * @return string string with encoded user + pass for authorization token
     */
    private function authorize($username, $password)
    {
        $hash = "Espo-Authorization: " . base64_encode($this->username . ':' . $this->password);
        unset($username, $password);
        return;
    }
    
    /**
     * Builds Query URL with parameters encoded. Prepare headers for curl session. Return decoded json from curl query.
     *
     * @param string $entity the entity scope you want to search
     * @param array | string $params the search parameters | url encoded parameters
     * @return object the json outpout from espo crm
     */
    public function query($entity, $params)
    {
        $params     = (gettype($params) == 'array') ? http_build_query($params) : $params;
        $this->curl = $this->setHeaders($this->endpoint . $entity . '?' . $params);
        return json_decode(curl_exec($this->curl));
        
    }
    
    /**
     * method for retreiving curl session for debugging purposes, disabled for security purposes.
     * 
     * @return curl resource
     */
    // debug method disabled for security
    // public function debugCurl() {
    // 	return $this->curl;
    // }
}

$the_query = new EspoApi('installation_url.extension', 'username', 'password');

$query_args = array(
    'maxSize' => 20,
    'offset' => 0,
    'sortBy' => 'name',
    'asc' => true,
    //search text
    'where' => array(
        array(
            'type' => 'textFilter',
            'value' => 'johnson'
        )
    )
);

//this way works too
// $query_args = 'maxSize=20&offset=0&sortBy=name&asc=true&where%5B0%5D%5Btype%5D=textFilter&where%5B0%5D%5Bvalue%5D=johnson';


$result = $the_query->query('Account', $query_args);

var_dump($result);
