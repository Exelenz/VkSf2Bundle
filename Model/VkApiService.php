<?php

namespace Exelenz\VkSf2Bundle\Model;

use Exelenz\vkPhpSdk\interfaces\IOauth2Proxy;
use Exelenz\vkPhpSdk\interfaces\IVkPhpSdk;

class VkApiService implements VkApiServiceInterface
{
    protected $oauth;
    protected $vkapi;

    public function __construct (IOauth2Proxy $oauth, IVkPhpSdk $vkapi)
    {
        $this->oauth = $oauth;
        $this->vkapi = $vkapi;
    }

    public function auth ()
    {
        if($this->oauth->authorize() === true) {
            $this->vkapi->setAccessToken($this->oauth->getAccessToken());
            $this->vkapi->setUserId($this->oauth->getUserId());
        }
    }

    public function getUserData ()
    {
        $result = $this->vkapi->api('getProfiles', array(
                'uids' => $this->vkapi->getUserId(),
                'fields' => 'uid, first_name, last_name, nickname, screen_name, photo_big',
            ));

        return $result['response'][0];
    }

    public function getUserFriends ()
    {
        $result = $this->vkapi->api('friends.get', array(
                'fields' => 'uid, first_name, last_name, nickname, sex',
            ));

        return $result['response'][0];
    }

}