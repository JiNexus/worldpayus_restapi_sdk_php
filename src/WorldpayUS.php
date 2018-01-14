<?php
namespace JiNexus\WorldpayUS;

class WorldpayUS extends WorldpayUSRequest
{
    const LIVE_URL = '';
    const SANDBOX_URL = 'https://gwapi.demo.securenet.com/api';

    protected $config = [
        'publicKey' => '',
        'developerApplication' => [
            'developerId' => null,
            'version' => '',
        ],
    ];

    /**
     * WorldpayUS constructor.
     * @param $secureNetId
     * @param $secureKey
     * @param array $config
     */
    public function __construct($secureNetId, $secureKey, $config = [])
    {
        $this->setSecureNetId($secureNetId);
        $this->setSecureKey($secureKey);
        $this->config = $config;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function authorize($data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Payments/Authorize';
        $this->setVerb('POST');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function capture($data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Payments/Capture';
        $this->setVerb('POST');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function charge($data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Payments/Charge';
        $this->setVerb('POST');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function tokenization($data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/PreVault/Card';
        $this->setVerb('POST');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createCustomer($data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Customers';
        $this->setVerb('POST');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param null $customerId
     * @return mixed
     */
    public function retrieveCustomer($customerId = null)
    {
        $url = rtrim($this->getGatewayUrl(), '/') . '/Customers/' . $customerId;
        $this->setVerb('GET');
        return $this->sendRequest($url);
    }

    /**
     * @param null $customerId
     * @param array $data
     * @return mixed
     */
    public function updateCustomer($customerId = null, $data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Customers/' . $customerId;
        $this->setVerb('PUT');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param null $customerId
     * @param array $data
     * @return mixed
     */
    public function createPaymentAccount($customerId = null, $data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Customers/' . $customerId . '/PaymentMethod';
        $this->setVerb('POST');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param null $customerId
     * @param null $paymentMethodId
     * @return mixed
     */
    public function retrievePaymentAccount($customerId = null, $paymentMethodId = null)
    {
        $url = rtrim($this->getGatewayUrl(), '/') . '/Customers/' . $customerId . '/PaymentMethod/' . $paymentMethodId;
        $this->setVerb('GET');
        return $this->sendRequest($url);
    }

    /**
     * @param null $customerId
     * @param null $paymentMethodId
     * @param array $data
     * @return mixed
     */
    public function updatePaymentAccount($customerId = null, $paymentMethodId = null, $data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Customers/' . $customerId . '/PaymentMethod/' . $paymentMethodId;
        $this->setVerb('PUT');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param null $customerId
     * @param null $paymentMethodId
     * @param array $data
     * @return mixed
     */
    public function deletePaymentAccount($customerId = null, $paymentMethodId = null, $data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Customers/' . $customerId . '/PaymentMethod/' . $paymentMethodId;
        $this->setVerb('DELETE');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createCustomerPaymentAccount($data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Customers/Payments';
        $this->setVerb('POST');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param null $customerId
     * @param array $data
     * @return mixed
     */
    public function updateCustomerPaymentAccount($customerId = null, $data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Customers/' . $customerId . '/Payments';
        $this->setVerb('PUT');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param null $transactionId
     * @return mixed
     */
    public function retrieveTransaction($transactionId = null)
    {
        $url = rtrim($this->getGatewayUrl(), '/') . '/Transactions/' . $transactionId;
        $this->setVerb('GET');
        return $this->sendRequest($url);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function refund($data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Payments/Refund';
        $this->setVerb('POST');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function void($data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Payments/Void';
        $this->setVerb('POST');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function closeBatch($data = array())
    {
        $data = array_merge($data, array('developerApplication' => $this->config['developerApplication']));
        $url = rtrim($this->getGatewayUrl(), '/') . '/Batches/Close';
        $this->setVerb('POST');
        return $this->sendRequest($url, $data);
    }

    /**
     * @param null $batchId
     * @return mixed
     */
    public function retrieveCloseBatch($batchId = null)
    {
        $url = rtrim($this->getGatewayUrl(), '/') . '/Batches/' . $batchId;
        $this->setVerb('GET');
        return $this->sendRequest($url);
    }

    /**
     * @return mixed
     */
    public function retrieveCurrentBatch()
    {
        $url = rtrim($this->getGatewayUrl(), '/') . '/Batches/Current';
        $this->setVerb('GET');
        return $this->sendRequest($url);
    }
}

