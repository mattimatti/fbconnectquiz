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
            
            $this->logger->debug('Store new access token : ' . $accessToken);
            
            $this->session->set('facebook_access_token', (string) $accessToken);
        }
        
        return $this->session->get('facebook_access_token');
    }

    /**
     */
    public function retriveProfile()
    {

        $this->logger->debug('retriveProfile ' . "/me?fields=id,name,email,gender,location");
        
        $response = $this->facebook->get("/me?fields=id,name,email,gender,location", $this->getAccessToken());
        $this->user = $response->getGraphUser();
        
        
        
        $userArray = json_decode($this->user->asJson(), true);
        $this->logger->debug(print_r($userArray,true));
        
        if(isset($userArray['location'])){
            unset($userArray['location']);
        }
        
        $userArray['ip'] = ''.$this->get_ip_address();
        
        // upsert user
        $this->upsertUser($userArray);
        
        return $this->user;
    }

    /**
     * Store or update the User
     * 
     * @param unknown $user            
     */
    public function upsertUser($user)
    {
        $this->logger->debug('upsertUser');
        User::upsert($user);
    }
    
    
    
    public function get_ip_address(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
    
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return 'unknown';
    }
    
    
    
    
    /**
     * 
     * @param array $profile The user profile.
     * @return Ambigous <\Facebook\GraphNodes\GraphEdge, \Facebook\GraphNodes\GraphNode>|NULL
     */
    public function retriveLocation($profile)
    {
        $this->logger->debug('retriveLocation');
        $this->logger->debug(print_r($profile, true));
        
        $profileArr = json_decode($profile->asJson(),true);
        
        if(isset($profileArr['location']['id'])){
            
            $locationid = $profileArr['location']['id'];
            
            $response = $this->facebook->get($locationid.'?fields=location', $this->getAccessToken());
            $graphObject = $response->getGraphObject();
           
            
            $graphArr = json_decode($graphObject->asJson(),true);
            
            $locationArr = $graphArr['location'];
            
            unset($locationArr['id']);
            $locationArr['id'] = $profileArr['id'];

            $this->logger->debug('Upsert user');
            $this->logger->debug(print_r($locationArr, true));
            
            User::upsert($locationArr);
            
            return $graphObject;
        }
        return null;
    }
    
    
    

    /**
     * retrieve a list of friends that have connected to the same app.
     * requires special permission
     * 
     * @return Ambigous <\Facebook\GraphNodes\GraphEdge, \Facebook\GraphNodes\GraphNode>
     */
    public function retriveFriends()
    {
        $this->logger->debug('retriveFriends');
        $response = $this->facebook->get('/me/friends', $this->getAccessToken());
        $graphObject = $response->getGraphEdge();
        $this->logger->debug($graphObject->asJson());
        return $graphObject;
    }

    
    
    /**
     * Retrieve the user's absolute friends list
     * 
     * @return Ambigous <\Facebook\GraphNodes\GraphEdge, \Facebook\GraphNodes\GraphNode>
     */
    public function retriveAllFriends()
    {
        $this->logger->debug('retriveAllFriends');
        $response = $this->facebook->get('/me/friendlists', $this->getAccessToken());
        $graphObject = $response->getGraphEdge();
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