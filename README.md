Rest Api Sms driver
===================
Rest Api Sms driver created to work with more than one sms service provider with same interface.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist aminkt/sms-driver-rest-api "*"
```

or add

```
"aminkt/sms-driver-rest-api": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
$sms = new \aminkt\sms\Sms();
// Ingnore below line if you don't want change default driver.
$sms->setDrviver('\your\driver\Class');
$sms->setLoginData([`LOGIN_DATA`]);
$sms->sendSms([
    'message'=>'Your test message.',
    'numbers'=>['09120813856', '+989121234567']
]);
```
> `LOGIN_DATA` depends on what driver is used to send sms, there is a list of supported drivers is blew
User your own driver
-----
If you want you can simply write your own driver and attach it to this library.

And if you want you can publish it in this library by your own name.

If you want create your own driver please flow below steps:

1. Create your class and use psr-7 namespcae.
2. Extend your class from `aminkt\sms\drivers\AbstracDriver`
3. Implement your codes.
4. Attach your driver to library as described before
5. Publish your code in `sms-driver-rest-api` library

List of drivers
-----
List of driver that published and avilable right now.

| Driver name   | namespace                  | createdBy                                    | publish date |
| ------------- |:--------------------------:| :-------------------------------------------:| :-----------:|
| Pardad        | \aminkt\sms\drivers\Pardad | [Amin Keshavarz](https://gitlab.com/aminkt/) | 17 / 8 /2017 
| KaveNegar     | \aminkt\sms\drivers\KaveNegar | [mr-exception](https://gitlab.com/mr-exception/) | 5 / 2 /2018 
|

Login Data for Pardad
------
| parameters | type | description|
| ------------- | ------ | -------|
| username | string | username for auth |
| password | string | password for auth |


Login Data for KaveNegar
------
| parameters | type | description|
| ------------- | ------ | -------|
| token | string | token given in cpanel |

> Be aware use of this library and its driver is alowed for all but you should keep recources and authors names.

Help us to improve
-----
If you create your own driver we become so happy if you pulblish it in our library.


[Amin Keshavarz](https://gitlab.com/aminkt/)

[Mr-Exception](https://gitlab.com/mr-exception/)


[Mail to Aminn Keshavarz](mailto: ak_1596@yahoo.com)