<?php
namespace App\Services;

use CyberSource\Authentication\Core\MerchantConfiguration as MerchantConfiguration;
use CyberSource\Logging\LogConfiguration as LogConfiguration;
use CyberSource\Configuration as Configuration;

class ExternalConfiguration
{
    private $merchantConfig;
    private $authType;
    private $merchantID;
    private $apiKeyID;
    private $secretKey;
    private $useMetaKey;
    private $portfolioID;
    private $keyAlias;
    private $keyPass;
    private $keyFilename;
    private $keyDirectory;
    private $runEnv;
    private $enableClientCert;
    private $clientCertDirectory;
    private $clientCertFile;
    private $clientCertPassword;
    private $clientId;
    private $clientSecret;
    private $enableLogging;
    private $debugLogFile;
    private $errorLogFile;
    private $logDateFormat;
    private $logFormat;
    private $logMaxFiles;
    private $logLevel;
    private $enableMasking;

    //initialize variable on constructor
    function __construct()
    {
        $this->authType = "http_signature";//http_signature/jwt
        $this->merchantID = "zanaco_zacl";
        $this->apiKeyID = "b8eebe57-c13c-4504-8a31-833567e4c4a0";
        $this->secretKey = "dW4Ekgg2garB9zdM+YZw8LXf237qEJrH4gpELvf+WV8=";

        // MetaKey configuration [Start]
        $this->useMetaKey = false;
        $this->portfolioID = "";
        // MetaKey configuration [End]

        $this->keyAlias = "testrest";
        $this->keyPass = "testrest";
        $this->keyFilename = "testrest";
        $this->keyDirectory = "Resources/";
        $this->runEnv = "apitest.cybersource.com";

        //OAuth related config
        $this->enableClientCert = false;
        $this->clientCertDirectory = "Resources/";
        $this->clientCertFile = "";
        $this->clientCertPassword = "";
        $this->clientId = "";
        $this->clientSecret = "";

        // New Logging
        $this->enableLogging = true;
        $this->debugLogFile = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "Log" . DIRECTORY_SEPARATOR . "debugTest.log";
        $this->errorLogFile = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "Log" . DIRECTORY_SEPARATOR . "errorTest.log";
        $this->logDateFormat = "Y-m-d\TH:i:s";
        $this->logFormat = "[%datetime%] [%level_name%] [%channel%] : %message%\n";
        $this->logMaxFiles = 3;
        $this->logLevel = "debug";
        $this->enableMasking = true;

        $this->merchantConfigObject();
    }

    //creating merchant config object
    function merchantConfigObject()
    {
        if (!isset($this->merchantConfig)) {
            $config = new MerchantConfiguration();
            $config->setauthenticationType(strtoupper(trim($this->authType)));
            $config->setMerchantID(trim($this->merchantID));
            $config->setApiKeyID($this->apiKeyID);
            $config->setSecretKey($this->secretKey);
            $config->setKeyFileName(trim($this->keyFilename));
            $config->setKeyAlias($this->keyAlias);
            $config->setKeyPassword($this->keyPass);
            $config->setUseMetaKey($this->useMetaKey);
            $config->setPortfolioID($this->portfolioID);
            $config->setKeysDirectory(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $this->keyDirectory);
            $config->setRunEnvironment($this->runEnv);

            // New Logging
            $logConfiguration = new LogConfiguration();
            $logConfiguration->enableLogging($this->enableLogging);
            $logConfiguration->setDebugLogFile($this->debugLogFile);
            $logConfiguration->setErrorLogFile($this->errorLogFile);
            $logConfiguration->setLogDateFormat($this->logDateFormat);
            $logConfiguration->setLogFormat($this->logFormat);
            $logConfiguration->setLogMaxFiles($this->logMaxFiles);
            $logConfiguration->setLogLevel($this->logLevel);
            $logConfiguration->enableMasking($this->enableMasking);
            $config->setLogConfiguration($logConfiguration);

            $config->validateMerchantData();
            $this->merchantConfig = $config;
        } else {
            return $this->merchantConfig;
        }
    }

    function ConnectionHost()
    {
        $merchantConf = $this->merchantConfigObject();
        $config = new Configuration();
        $config->setHost($merchantConf->getHost());
        $config->setLogConfiguration($merchantConf->getLogConfiguration());
        return $config;
    }

    function FutureDate($format){
        if($format){
            $rdate = date("Y-m-d",strtotime("+7 days"));
            $retDate = date($format,strtotime($rdate));
        }
        else{
            $retDate = date("Y-m",strtotime("+7 days"));
        }
        echo $retDate;
        return $retDate;
    }

    function CallTestLogging($testId, $apiName, $responseMessage){
        $runtime = date('d-m-Y H:i:s');
        $file = fopen("./CSV_Files/TestReport/TestResults.csv", "a+");
        fputcsv($file, array($testId, $runtime, $apiName, $responseMessage));
        fclose($file);
    }

    function downloadReport($downloadData, $fileName){
        $filePathName = __DIR__. DIRECTORY_SEPARATOR .$fileName;
        $file = fopen($filePathName, "w");
        fwrite($file, $downloadData);
        fclose($file);
        return __DIR__.'\\'.$fileName;
    }
}
?>