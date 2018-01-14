<?php
namespace JiNexus\WorldpayUs;

class WorldpayUsRequest
{
    protected $gatewayUrl;
    protected $origin;
    protected $sandbox = true;
    protected $secureNetId;
    protected $secureKey;
    protected $verb = 'POST';

    /**
     * @return mixed
     */
    public function getGatewayUrl()
    {
        return $this->gatewayUrl;
    }

    /**
     * @param null $gatewayUrl
     */
    public function setGatewayUrl($gatewayUrl = null)
    {
        $this->gatewayUrl = $gatewayUrl;
    }

    /**
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param null $origin
     */
    public function setOrigin($origin = null)
    {
        $this->origin = $origin;
    }

    /**
     * @return bool
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }


    /**
     * @param bool $sandbox
     */
    public function setSandbox($sandbox = true)
    {
        $this->sandbox = $sandbox;
    }

    /**
     * @return mixed
     */
    public function getSecureNetId()
    {
        return $this->secureNetId;
    }

    /**
     * @param null $secureNetId
     */
    public function setSecureNetId($secureNetId = null)
    {
        $this->secureNetId = $secureNetId;
    }

    /**
     * @return mixed
     */
    public function getSecureKey()
    {
        return $this->secureKey;
    }

    /**
     * @param null $secureKey
     */
    public function setSecureKey($secureKey = null)
    {
        $this->secureKey = $secureKey;
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * @param string $verb
     */
    public function setVerb($verb = 'POST')
    {
        $this->verb = $verb;
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    protected function sendRequest($url, $data = array())
    {
        $dataString = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->getVerb());
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Origin: ' . $this->getOrigin(), // In order to successfully use tokenization, include an "origin:" header containing your url. Ex: "origin: www.integratorurl.com"
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataString),
            )
        );

        curl_setopt($ch, CURLOPT_USERPWD, $this->getSecureNetId() . ':' . $this->getSecureKey());
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}