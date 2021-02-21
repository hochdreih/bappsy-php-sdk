<?php
namespace Bappsy;



use Exception;
use HTTP_Request2;
use stdClass;

interface IBappsy{
    function get($endpoint, $q, $prePart, $postPart);
    function getDetail($endpoint, $id, $q, $prePart, $postPart);
    function copy( $endpoint, $id);
    function delete( $type, $id);
    function update($endpoint, $id, $data, $q, $prePart, $postPart);
    function create($endpoint, $data, $prePart, $postPart);
}

/**
 * Class Bappsy
 * @package Bappsy
 */
class Bappsy implements IBappsy
{
    private $apiKey;
    private $apiVersion = 'v1';
    private $_apiHost = 'bappsy.com';
    private $_apiUrl;

    function __construct($apiKey)
    {
        if (!$apiKey) {
            throw new Exception('API Key is Missing. Create one at https://app.bappsy.com/#/config/api');
        }

        $this->_apiUrl = 'https://api-' . $this->apiVersion .'.'. $this->_apiHost;
        $this->apiKey = $apiKey;
    }

    /**
     * @param $endpoint
     * @param $q
     * @param $prePart
     * @param $postPart
     * @return mixed
     */
    public function get($endpoint, $q = null, $prePart= null, $postPart= null)
    {
        $result = $this->doRequest($this->buildRequest('GET', $endpoint, null, $prePart, $postPart, $q));
        return json_decode($result);
    }

    /**
     * @param $endpoint
     * @param $id
     * @param $q
     * @param $prePart
     * @param $postPart
     * @return mixed
     */
    function getDetail($endpoint, $id, $q, $prePart, $postPart)
    {
        $result = $this->doRequest($this->buildRequest('GET', $endpoint, $id, $prePart, $postPart, $q));
        return json_decode($result);
    }

    /**
     * @param $endpoint
     * @param $id
     * @return mixed
     */
    function copy($endpoint, $id)
    {
        $result = $this->doRequest($this->buildRequest('GET', $endpoint, $id, 'copy'));
        return json_decode($result);
    }

    /**
     * @param $type
     * @param $id
     * @return mixed
     */
    function delete($type, $id)
    {
        $result = $this->doRequest($this->buildRequest('POST', 'delete', $id, null, null, null, array('type' => $type, 'id' => $id)));
        return json_decode($result);
    }

    /**
     * @param $endpoint
     * @param $id
     * @param $data
     * @param $q
     * @param $prePart
     * @param $postPart
     * @return mixed
     */
    function update($endpoint, $id, $data, $q, $prePart, $postPart)
    {
        $dataToSend = $data;
        $dataToSend['_id'] = $id;
        $result = $this->doRequest($this->buildRequest('PUT', $endpoint, null, $prePart, $postPart, $q, $dataToSend));
        return json_decode($result);
    }

    /**
     * @param $endpoint
     * @param $data
     * @param $prePart
     * @param $postPart
     * @return mixed
     */
    function create($endpoint, $data, $prePart = null, $postPart = null)
    {

        $dataToSend = $data;
        unset($dataToSend->_id);
        $result = $this->doRequest($this->buildRequest('POST', $endpoint, null, $prePart, $postPart, null, $dataToSend));
        return json_decode($result);

    }

    private function buildRequest($method, $endpoint, $id = null , $prePart = null, $postPart = null, $q = null, $data =null  ){
        $url = $this->_apiUrl . '/api/' . $endpoint . ($prePart !== null ? '/'. $prePart : '') . ($id  !== null ? '/'. $id : '') . ($postPart  !== null ? '/' . $postPart : '') . '?';
        $url = $url . 'access_token=' . $this->apiKey;
        $result = new stdClass();
        $result->url = $url;
        $result->method = $method;
        $result->data = $data;
        return $result;
    }


    private function doRequest($req) {
        try {
        $request = new HTTP_Request2();
        $request->setUrl($req->url);
        switch ($req->method) {
            case 'DELETE':
            case 'POST':
                $request->setMethod(\HTTP_Request2::METHOD_POST);
                break;
            case 'PUT':
                $request->setMethod(\HTTP_Request2::METHOD_PUT);
                break;
            default:
                $request->setMethod(\HTTP_Request2::METHOD_GET);
                break;
        }

            $dataToSend = json_encode($req->data);
            var_dump($req->url);
            var_dump($dataToSend);

            $request->setHeader('Content-Type', 'application/json');

            if ($req->method !== 'GET') {
                $request->setBody($dataToSend);
            }

            $response = $request->send();
            if ($response->getStatus() == 200) {
                var_dump($response->getBody());
                return $response->getBody();
            }
            else {
                echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                    $response->getReasonPhrase();
            }
        }
        catch(HTTP_Request2_Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * @return string
     */
    public function getApiHost()
    {
        return $this->_apiHost;
    }

    /**
     * @param string $apiHost
     */
    public function setApiHost($apiHost)
    {
        $this->_apiHost = $apiHost;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $this->_apiUrl = $apiUrl;
    }


}


