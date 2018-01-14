# JiNexus WorldpayUS REST API SDK PHP

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)
[![Latest Stable Version](https://poser.pugx.org/jinexus/worldpayus-restapi-sdk-php/v/stable)](https://packagist.org/packages/jinexus/worldpayus-restapi-sdk-php)
[![Total Downloads](https://poser.pugx.org/jinexus/worldpayus-restapi-sdk-php/downloads)](https://packagist.org/packages/jinexus/worldpayus-restapi-sdk-php)
[![License](https://poser.pugx.org/jinexus/worldpayus-restapi-sdk-php/license)](https://packagist.org/packages/jinexus/worldpayus-restapi-sdk-php)
[![Donate](https://img.shields.io/badge/donate-Paypal-blue.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5CYMGYYYS98PN)

`JiNexus WorldpayUS REST API SDK PHP` is a PHP SDK for Worldpay Total REST API.

- File issues at https://github.com/JiNexus/worldpayus_restapi_sdk_php/issues
- Documentation is at https://github.com/JiNexus/worldpayus_restapi_sdk_php
- Official Worldpay US Documentation is at https://www.worldpay.com/us/developers/apidocs/getstarted.html

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install `JiNexus WorldpayUS REST API SDK PHP`.

```bash
$ composer require jinexus/worldpayus-restapi-sdk-php
```

This will install `JiNexus WorldpayUS REST API SDK PHP` and all required dependencies. `JiNexus WorldpayUS REST API SDK PHP` requires PHP 5.6 or latest.

## Basic Usage

### Create an instance and set the necessary settings
```php
<?php 
use JiNexus\WorldpayUs\WorldpayUs;

$secureNet = '1811021'; // This is just a sample value and must be replace with your own secure net value
$secureKey = 'vj8iZ0cHYTEX'; // This is just a sample value and must be replace with your own secure key
$config = [
    'publicKey' => '13ab2cd4-5efg-67hi-8jka-910l1112m8', // This is just a sample value and must be replace with your own public key
    'developerApplication' => [
        'developerId' => '12345678', // This is just a sample value and must be replace with your own developer ID
        'version' => '1.2',
    ],
];

// Create an Instance
$worldpayUs = new WorldpayUs($secureNet, $secureKey, $config);

// Set your origin in order to successfully use tokenization, this is most likely your base URI.
$worldpayUs->setOrigin($this->getBaseUrl());

// Set the gateway URL, I'll be using the demo API that WorldpayUS provided.
$worldpayUs->setGatewayUrl('https://gwapi.demo.securenet.com/api');

// Set your own HTTP Verb. Values are: 'POST', 'GET', 'PUT', 'PATCH' and 'DELETE' (Default: POST)
$worldpayUs->setVerb('POST');
```

### Authorization Only 
##### This method authorizes a transaction but does not capture the transaction for settlement. 
```php
<?php 
$data = [
    'amount' => 11.00,
    'card' => [
        'number' => '4444 3333 2222 1111',
        'cvv' => '999',
        'expirationDate' => '07/2021',
        'address' => [
            'line1' => '123 Main St.',
            'city' => 'Austin',
            'state' => 'TX',
            'zip' => '78759',
            'country' => 'US',
        ],
        'firstName' => 'Jim',
        'lastName' => 'Test',
    ],
    'extendedInformation' => [
        'typeOfGoods' => 'PHYSICAL',
    ],
];

$response = $worldpayUs->authorize($data);
$result = json_decode($response, true);
```

### Prior Auth Capture
##### This call allows a previously authorized transaction to be captured for settlement.
```php
<?php 
$data = [
    'amount' => 11.00,
    'transactionId' => 'REPLACE ME',
];

$response = $worldpayUs->capture($data);
$result = json_decode($response, true);
```

### Charge - Authorization and Capture
##### This call authorizes the transaction and, if successful, captures it.
```php
<?php 
$data = [
    'amount' => 11.00,
    'card' => [
        'number' => '4444 3333 2222 1111',
        'cvv' => '999',
        'expirationDate' => '07/2021',
        'address' => [
            'line1' => '123 Main St.',
            'city' => 'Austin',
            'state' => 'TX',
            'zip' => '78759',
            'country' => 'US',
        ],
    ],
    'extendedInformation' => [
        'typeOfGoods' => 'PHYSICAL',
    ],
];

$response = $worldpayUs->charge($data);
$result = json_decode($response, true);
```

### Charge using Secondary Account
##### This call authorizes the transaction on a secondary account using a vault account linked to the primary merchant account and, if successful, captures it.
```php
<?php 
$data = [
    'amount' => 11.00,
    'paymentVaultToken' => [
        'customerId' => '2000007',
        'paymentMethodID' => '1',
    ],
    'vaultCredentials' => [
        'secureNetId' => '7004183',
        'secureNetKey' => 'xyzabcdefghi',
    ],
    'extendedInformation' => [
        'typeOfGoods' => 'PHYSICAL',
    ],
];

$response = $worldpayUs->charge($data);
$result = json_decode($response, true);
```

### Charge using Tokenization
##### A token which has been returned from the creation of a token using the PreVault/Card or PreVault/Check action can be used in replacement of the actual card or check account information. This token can also be added to the Vault for future re-use.
```php
<?php 
$data = [
    'amount' => 11.00,
    'paymentVaultToken' => [
        'paymentMethodId' => '1211121',
        'publicKey' => $worldpayUs->config['publicKey'],
    ],
    'addToVault' => true,
    'extendedInformation' => [
        'typeOfGoods' => 'PHYSICAL',
    ],
];

$response = $worldpayUs->charge($data);
$result = json_decode($response, true);
```

### Close a Batch
##### Closing the current open batch settles all captured transactions in the batch, and can be accomplished with a single standalone POST.
```php
<?php 
$response = $worldpayUs->closeBatch();
$result = json_decode($response, true);
```

### Retrieve a Closed Batch
##### Once a batch is closed, you can obtain a list of all transactions associated with it using this call. If the call is successful, the method will return an array of all the transactions that were part of the batch, including the full details of each as returned during the original authorization.
```php
<?php 
$batchId = 'REPLACE ME';

$response = $worldpayUs->retrieveCloseBatch($batchId);
$result = json_decode($response, true);
```

### Retrieve the Current Batch
##### Calling this method retrieves the current open batch. No parameters are necessary. If successful, it returns an array of the transactions in the open batch, along with the full details of each as returned during the original authorization.
```php
<?php 
$response = $worldpayUs->retrieveCurrentBatch();
$result = json_decode($response, true);
```

### Refund a Transaction
##### The Refund method must be linked to a settled transaction. This is done by specifying the transactionId from the original Authorization or Charge as part of the request. By default, this method refunds the FULL amount of the transaction. However, you can perform a partial refund by passing a specific amount. If a refund is attempted on a transaction that has not yet settled, the PayOS API will automatically run a Void on the transaction. The transactionType in this case will switch to Void.
```php
<?php 
$data = [
    'transactionId' => 'REPLACE ME',
];

$response = $worldpayUs->refund($data);
$result = json_decode($response, true);
```

### Void a Transaction
##### Voiding a transaction will cancel the transaction prior to settlement.
```php
<?php 
$data = [
    'transactionId' => 'REPLACE ME',
];

$response = $worldpayUs->void($data);
$result = json_decode($response, true);
```

### Create a Customer
##### Creates a customer record in the Vault. All payment accounts in the Vault are associated with a customer, so before adding a payment account, it is necessary to create a customer record. A single customer may have multiple stored payment accounts, any of which may be set for recurring billing or used to run transactions.
```php
<?php 
$data = [
    'firstName' => 'Juan',
    'lastName' => 'dela Cruz',
    'phone' => '512-122-1211',
    'emailAddress' => 'some@emailaddress.com',
    'sendEmailReceipts' => true,
    'notes' => 'This is test notes',
    'address' => [
        'line1' => '123 Main St.',
        'city' => 'Austin',
        'state' => 'TX',
        'zip' => '78759',
        'country' => 'US',
    ],
    'company' => 'Test company',
    'userDefinedFields' => [
        [
            'udfname' => 'udf1',
            'udfvalue' => 'udf1_value',
        ],
        [
            'udfname' => 'udf2',
            'udfvalue' => 'udf2_value',
        ],
        [
            'udfname' => 'udf3',
            'udfvalue' => 'udf3_value',
        ],
    ],
];

$response = $worldpayUs->createCustomer($data);
$result = json_decode($response, true);
```

### Retrieve a Customer
##### Retrieves a customer record from the Vault.
```php
<?php 
$customerId = 'REPLACE ME WITH EXISTING CUSTOMER ID';

$response = $worldpayUs->retrieveCustomer($customerId);
$result = json_decode($response, true);
```

### Update a Customer
##### Updates a customer record in the Vault.
```php
<?php 
$customerId = 'REPLACE ME WITH EXISTING CUSTOMER ID';

$data = [
    'customerId' => $customerId,
    'firstName' => 'Updated First Name',
    'lastName' => 'Updated Last Name',
    'phone' => '512-111-1111',
    'emailAddress' => 'some@emailaddress.com',
    'sendEmailReceipts' => true,
    'notes' => 'This is test notes',
    'address' => [
        'line1' => '123 Main St.',
        'city' => 'Austin',
        'state' => 'TX',
        'zip' => '78759',
        'country' => 'US',
    ],
    'company' => 'Test company update',
    'userDefinedFields' => [
        [
            'udfname' => 'udf1',
            'udfvalue' => 'udf1_value',
        ],
        [
            'udfname' => 'udf2',
            'udfvalue' => 'udf2_value',
        ],
        [
            'udfname' => 'udf3',
            'udfvalue' => 'udf3_value',
        ],
    ],
];

$response = $worldpayUs->updateCustomer($customerId, $data);
$result = json_decode($response, true);
```

### Create a Payment Account
##### Creates a payment method record in the Vault. A Vault account stores a payment method. Each Vault payment account is linked to a specific customer ID. Once a Vault account is created and associated with a customer, it can be used for subsequent charges or for recurring billing. The payment method can be a credit card, pinless debit, or ACH payment account.
```php
<?php 
$customerId = 'REPLACE ME WITH EXISTING CUSTOMER ID';

$data = [
    'customerId' => $customerId,
    'card' => [
        'number' => '4444 3333 2222 1111',
        'cvv' => '999',
        'expirationDate' => '07/2021',
        'address' => [
            'line1' => '123 Main St.',
            'city' => 'Austin',
            'state' => 'TX',
            'zip' => '78759',
            'country' => 'US',
        ],
        'firstName' => 'Jim',
        'lastName' => 'Test',
    ],
    'phone' => '512-250-7865',
    'notes' => 'Create a vault account',
    'accountDuplicateCheckIndicator' => 0,
    'primary' => true,
    'userDefinedFields' => [
        [
            'udfname' => 'udf1',
            'udfvalue' => 'udf1_value',
        ],
        [
            'udfname' => 'udf2',
            'udfvalue' => 'udf2_value',
        ],
        [
            'udfname' => 'udf3',
            'udfvalue' => 'udf3_value',
        ],
    ],
];

$response = $worldpayUs->createPaymentAccount($customerId, $data);
$result = json_decode($response, true);
```

### Retrieve a Payment Account
##### Retrieves a payment account record from the Vault.
```php
<?php 
$customerId = 'REPLACE ME WITH EXISTING CUSTOMER ID';
$paymentMethodId = 'REPLACE ME WITH EXISTING PAYMENT METHOD ID';

$response = $worldpayUs->retrievePaymentAccount($customerId, $paymentMethodId);
$result = json_decode($response, true);
```

### Update a Payment Account
##### Updates an existing payment account record in the Vault.
```php
<?php 
$customerId = 'REPLACE ME WITH EXISTING CUSTOMER ID';
$paymentMethodId = 'REPLACE ME WITH EXISTING PAYMENT METHOD ID';

$data = [
    'customerId' => $customerId,
    'paymentMethodId' => $paymentMethodId,
    'card' => [
        'number' => '4444 3333 2222 1111',
        'cvv' => '999',
        'expirationDate' => '07/2021',
        'address' => [
            'line1' => '123 Main St.',
            'city' => 'Austin',
            'state' => 'TX',
            'zip' => '78759',
            'country' => 'US',
        ],
        'firstName' => 'Jim',
        'lastName' => 'Updated',
    ],
    'phone' => '512-250-7865',
    'notes' => 'Update a vault account',
    'accountDuplicateCheckIndicator' => 0,
    'primary' => true,
    'userDefinedFields' => [
        [
            'udfname' => 'udf1',
            'udfvalue' => 'udf1_value',
        ],
        [
            'udfname' => 'udf2',
            'udfvalue' => 'udf2_value',
        ],
        [
            'udfname' => 'udf3',
            'udfvalue' => 'udf3_value',
        ],
        [
            'udfname' => 'udf4',
            'udfvalue' => 'udf4_value',
        ],
    ],
];

$response = $worldpayUs->updatePaymentAccount($customerId, $paymentMethodId, $data);
$result = json_decode($response, true);
```

### Delete a Payment Account
##### Removes an existing payment account record from the Vault.
```php
<?php 
$customerId = 'REPLACE ME WITH EXISTING CUSTOMER ID';
$paymentMethodId = 'REPLACE ME WITH EXISTING PAYMENT METHOD ID';

$data = [
    'customerId' => $customerId,
    'paymentMethodId' => $paymentMethodId,
];

$response = $worldpayUs->deletePaymentAccount($customerId, $paymentMethodId, $data);
$result = json_decode($response, true);
```

### Create Customer and Payment
##### Creates a customer and payment record in the Vault. All payment accounts in the Vault are associated with a customer, this call will add the customer and the payment associated to the customer.
```php
<?php 
$data = [
    'firstName' => 'REPLACE ME',
    'lastName' => 'REPLACE ME',
    'phone' => 'REPLACE ME',
    'emailAddress' => 'replace_me@emailaddress.com',
    'sendEmailReceipts' => true,
    'notes' => 'This is test notes',
    'address' => [
        'line1' => '123 Main St.',
        'city' => 'Austin',
        'state' => 'TX',
        'zip' => '78759',
        'country' => 'US',
    ],
    'company' => 'Test company',
    'userDefinedFields' => [
        [
            'udfname' => 'udf1',
            'udfvalue' => 'udf1_value',
        ],
        [
            'udfname' => 'udf2',
            'udfvalue' => 'udf2_value',
        ],
        [
            'udfname' => 'udf3',
            'udfvalue' => 'udf3_value',
        ],
    ],
    'customerDuplicateCheckIndicator' => 1,
    'card' => [
        'number' => '4444 3333 2222 1111',
        'cvv' => '999',
        'expirationDate' => '07/2021',
        'address' => [
            'line1' => '123 Main St.',
            'city' => 'Austin',
            'state' => 'TX',
            'zip' => '78759',
            'country' => 'US',
        ],
        'firstName' => 'REPLACE ME',
        'lastName' => 'REPLACE ME',
    ],
    'primary' => true,
    'accountDuplicateCheckIndicator' => 1,
];

$response = $worldpayUs->createCustomerPaymentAccount($data);
$result = json_decode($response, true);
```

### Update Customer and Payment
##### Updates a customer and payment record in the Vault. All payment accounts in the Vault are associated with a customer, this call will update the customer and the payment associated to the customer. Updates are only applicable on primary payment Id.
```php
<?php 
$customerId = 'REPLACE ME WITH EXISTING CUSTOMER ID';
$paymentMethodId = 'REPLACE ME WITH EXISTING PAYMENT METHOD ID';

$data = [
    'customerId' => $customerId,
    'paymentMethodId' => $paymentMethodId,
    'firstName' => 'REPLACE ME',
    'lastName' => 'REPLACE ME',
    'phone' => 'REPLACE ME',
    'emailAddress' => 'replace_me@emailaddress.com',
    'sendEmailReceipts' => true,
    'notes' => 'This is updated test notes',
    'company' => 'Test company',
    'customerDuplicateCheckIndicator' => 1,
    'card' => [
        'number' => '4444 1111 1111 1111',
        'cvv' => '999',
        'expirationDate' => '08/2021',
        'address' => [
            'line1' => '123 Main St.',
            'city' => 'Austin',
            'state' => 'TX',
            'zip' => '78749',
            'country' => 'US',
        ],
        'firstName' => 'REPLACE ME',
        'lastName' => 'REPLACE ME',
    ],
    'primary' => true,
    'accountDuplicateCheckIndicator' => 1,
];

$response = $worldpayUs->updateCustomerPaymentAccount($data);
$result = json_decode($response, true);
```

## To Do's

- Create a Unit Test
- Utilize Exception
- I need to find a better way to utilize the setSandbox method
- Add more resources from Worldpay US
- Improve Documentation (Here's comes the most boring part~) #grumble

## Contributing

Before contributing please read the [Contributing File](CONTRIBUTING.md) for details.

## Security

If you discover security related issues, please email [jinvirle@gmail.com](mailto:jinvirle@gmail.com) instead of using the issue tracker.

## Credits

- [Jimvirle Calago](https://github.com/JiNexus)
- [WorldpayUS REST](https://www.worldpay.com/us/developers/apidocs/getstarted.html)
- [All Contributors](../../contributors)

## Dependency

- [WorldpayUS REST](https://www.worldpay.com/us/developers/apidocs/getstarted.html)

## License

The `JiNexus WorldpayUS REST API SDK PHP` is an open source project that is licensed under the [BSD 3-Clause License](https://opensource.org/licenses/BSD-3-Clause). See [License File](LICENSE.md) for more information.
JiNexus reserves the right to change the license of future releases.

## Donations

Donations are **greatly appreciated!**

A man has to code for food. A man must do what he feels needs to be done, thereby give credit where credit is due.

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5CYMGYYYS98PN)