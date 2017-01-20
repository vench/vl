<?php

namespace AppBundle\Service;

use Yandex\Disk\DiskClient;
use Symfony\Component\Filesystem\Filesystem;


use Yandex\OAuth\OAuthClient;


/**
 * Description of Api
 *
 * @author vench
 */
class Api {

    /**
     * 
     */
    const FILE_DEST = 'images/tmp/';
    
    
    const NAME_COOKIE_TOKEN = 'cookie_token';
    
    /**
     *
     * @var string
     */
    private $clientId;

    /**
     *
     * @var string 
     */
    private $clientSecret;

    /**
     * 
     * @param string $clientId
     */
    public function setClientId($clientId) {
        $this->clientId = $clientId;
    }
    
    /**
     * 
     * @param string $clientSecret
     */
    public function setSecret($clientSecret) {
        $this->clientSecret = $clientSecret;
    }
    

    /**
     * 
     * @param string $path
     * @return array
     */
    public function directoryContents($path) {
        $diskClient = $this->getDiskClient();
        $dirContent = $diskClient->directoryContents($path);

        return array_map(function($item) {
            return [
                'path' => $item['href'],
                'createDate' => date('Y-m-d в H:i:s', strtotime($item['creationDate'])),
                'modifedDate' => date('Y-m-d в H:i:s', strtotime($item['lastModified'])),
                'name' => $item['displayName'],
                'type' => $item['resourceType'],
                'contentType' => $item['contentType'],
                'length' => $item['contentLength'],
            ];
        }, $dirContent
        );
    }
    
    /**
     * 
     * @param string $path
     * @return boolean
     */
    public function delete($path) {
        $diskClient = $this->getDiskClient();
        return $diskClient->delete($path);
    }
    
    /**
     * 
     * @param string $path
     * @return string|boolean
     */
    public function downloadFile($path) {
        $diskClient = $this->getDiskClient();
        
        $fs = new Filesystem();
        $dest = self::FILE_DEST;
        
        if(!$fs->exists($dest)) {
            $fs->mkdir($dest);
        } 
        
        return  $diskClient->downloadFile($path, $dest);
    }
    
    /**
     * 
     * @param type $file
     * @param type $path
     * @param string $fileName Description
     */
    public function uploadFile($file, $path = '/', $fileName = null) {
        $diskClient = $this->getDiskClient();
         
        if(is_null($fileName)) {
            $fileName = $file;
        }

        $diskClient->uploadFile(
            $path,
            array(
                'path' => $file,
                'size' => filesize($file),
                'name' => $fileName
            )
        );
    }
    
    /**
     * 
     * @return type
     */
    public function getLogin() { 
        $diskClient = $this->getDiskClient();
        return $diskClient->getLogin();
    }
    
    /**
     * 
     */
    public function authRedirect() {
        $client = new OAuthClient($this->clientId); 
        $client->authRedirect(true); 
        $state = 'yandex-php-library';
        $client->authRedirect(true, OAuthClient::CODE_AUTH_TYPE, $state);
    } 
    
    
    /**
     * 
     * @param string $code
     * @return string
     * @throws \AppBundle\Service\AuthRequestException
     */
    public function getTokenByCodeCallBack($code) {
        $client = new OAuthClient($this->clientId, $this->clientSecret);

        try { 
            $client->requestAccessToken( $code);
        } catch (AuthRequestException $ex) {
            throw $ex;
        }
 
        return $client->getAccessToken(); 
    }
    

    /**
     * 
     * @return DiskClient
     */
    private function getDiskClient() {
        $diskClient = new DiskClient($this->getAccessToken());
        $diskClient->setServiceScheme(DiskClient::HTTPS_SCHEME);
        return $diskClient;
    }

    /**
     * 
     * @return string
     */
    private function getAccessToken() {         
        return  filter_input(INPUT_COOKIE, self::NAME_COOKIE_TOKEN);
    }

}
