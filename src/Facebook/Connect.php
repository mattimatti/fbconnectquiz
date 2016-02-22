<?php
namespace App\Facebook;

use App\Helper\Session;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;
use Monolog\Logger;
use Facebook\FacebookRequest;
use RedBeanPHP\R;
use App\Model\QuizUser;
use GeoIp2\Database\Reader;

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
        $this->logger->debug('retriveProfile ' . "/me?fields=id,name,email,gender");
        
        $response = $this->facebook->get("/me?fields=id,name,email,gender", $this->getAccessToken());
        $this->user = $response->getGraphUser();
        
        $userArray = json_decode($this->user->asJson(), true);
        $this->logger->debug(print_r($userArray, true));
        
        // remove location info if set.
        if (isset($userArray['location'])) {
            unset($userArray['location']);
        }
        
        $userArray['ip'] = '' . $this->get_ip_address();
        
        // upsert user
        $this->upsertUser($userArray);
        
        return $this->user;
    }

    /**
     * Store or update the User profile
     *
     * @param array $payload            
     */
    public function upsertUser($payload)
    {
        $this->logger->debug('upsertUser');
        QuizUser::upsert($payload);
    }

    public function get_ip_address()
    {
        foreach (array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ) as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        // return '87.8.80.102';
        return 'unknown';
    }

    /**
     * Read the location from the
     *
     * @param array $profile            
     */
    public function retriveLocationFromIp()
    {
        $ip = $this->get_ip_address();
        
        $this->logger->debug('retriveLocationFromIp ' . $ip);
        
        if ($ip != 'unknown') {
            
            try {
                
                $reader = new Reader('./GeoLite2-Country.mmdb');
                $location = $reader->country($ip);
                return $location;
            } catch (\Exception $ex) {
                //
            }
        } else {
            $this->logger->debug('unable to retrive user IP');
        }
        
        return null;
    }

    public function storeLocationInProfile($location)
    {
        $this->logger->debug('storeLocationInProfile');
        $this->logger->debug(print_r($location, true));
        
        if ($location->country) {
            
            $locationArr = array();
            $locationArr['session'] = session_id();
            $locationArr['ip'] = $location->traits->ipAddress;
            $locationArr['city'] = $location->country->name;
            $locationArr['country'] = $location->country->isoCode;
            $locationArr['latitude'] = '';
            $locationArr['longitude'] = '';
            
            try {
                
                $this->logger->debug('Upsert user');
                $this->logger->debug(print_r($locationArr, true));
                
                $this->upsertUser($locationArr);
            } catch (\Exception $ex) {
                // $this->logger->error(print_r($ex, true));
            }
        }
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