<?php
class BaseController
{
    /**
     * __call magic method.
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    /**
     * Get URI elements.
     * 
     * @return array
     */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode('/', $uri);

        return $uri;
    }

    /**
     * Get querystring params.
     * 
     * @return array
     */
    protected function getQueryStringParams()
    {
        return parse_str($_SERVER['QUERY_STRING'], $query);
    }

    /**
     * Get body as array.
     * 
     * @return array
     */
    protected function getBody()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    /**
     * Validate data.
     * 
     * @return string
     */
    protected function checkBodyInvoiceC($data)
    {
        $response = [];
        if (!array_key_exists("cuit_owner", $data)) {
            array_push($response, "Cuit del propietario requerido");
        }
        if (!array_key_exists("cuit_client", $data)) {
            array_push($response, "Cuit del cliente requerido (0 para consumidor final)");
        }
        if (!array_key_exists("importe", $data)) {
            array_push($response, "Importe requerido");
        }

        return $response;
    }

    /**
     * Send API output.
     *
     * @param mixed  $data
     * @param string $httpHeader
     */
    protected function sendOutput($data, $httpHeaders = array())
    {
        header_remove();
        if (is_array($httpHeaders) && count($httpHeaders)) {           
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        echo $data;
        exit;
    }
}
?>
