<?php
namespace App\Facebook;

use App\Helper\Session;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;
use Monolog\Logger;
use Facebook\FacebookRequest;
use RedBeanPHP\R;
use App\Model\User;

class Connect
{

    /**
     *
     * @var Facebook
     */
    protected $facebook;

    /**
     *
     * @var Logger
     */
    protected $logger;

    /**
     *
     * @var array
     */
    protected $user;

    /**
     *
     * @var Session
     */
    protected $session;

    function __construct(array $settings = array(), $session, $logger)
    {
        $this->session = $session;
        $this->facebook = new \Facebook\Facebook($settings);
        $this->logger = $logger;
    }

    /**
     *
     * @return boolean
     */
    public function hasAccessToken()
    {
        return ($this->getAccessToken() != null);
    }

    /**
     *
     * @return Ambigous <\App\Helper\mixed, unknown, mixed>
     */
    public function getAccessToken()
    {
        if ($this->session->get('facebook_access_token')) {
            return $this->session->get('facebook_access_token');
        }
        
        $helper = $this->facebook->getJavaScriptHelper();
        
        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            $this->logger->error('error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            $this->logger->error('Facebook SDK returned an error: ' . $e->getMessage());
        }
        if (! isset($accessToken)) {
            $this->logger->error('No cookie set or no OAuth data could be obtained from cookie.');
        } else {
            
            $this->logger->debug('Got new access token! : ' . $accessToken);
            $this->logger->debug('Store access token : ' . $accessToken);
            
            $this->session->set('facebook_access_token', (string) $accessToken);
        }
        
        return $this->session->get('facebook_access_token');
    }

    /**
     */
    public function retriveProfile()
    {
        $response = $this->facebook->get("/me?fields=id,name", $this->getAccessToken());
        $this->user = $response->getGraphUser();
        $this->logger->debug('retriveProfile found id: ' . $this->user['id']);
        
        $this->upsertUser($this->user);
        
        return $this->user;
    }

    public function upsertUser($user)
    {
       User::upsert($user);
    }

    /**
     */
    public function retriveFriends()
    {
        $response = $this->facebook->get('/me/friends', $this->getAccessToken());
        $graphObject = $response->getGraphEdge();
        $this->logger->debug('retriveFriends');
        $this->logger->debug($graphObject->asJson());
        return $graphObject;
    }

    /**
     *
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
    }
}