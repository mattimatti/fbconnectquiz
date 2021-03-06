<?php
namespace App\Model;

use RedBeanPHP\SimpleModel;
use RedBeanPHP\R;

class QuizUser extends SimpleModel
{

    /**
     * Insert or update a facebook user.
     *
     * @param array $payload            
     */
    public static function upsert($payload)
    {
        
        R::freeze(false);
        
        // if no session set add it
        if(!isset($payload['session'])){
            $payload['session'] = session_id();
        }
        
        // Find an existing user in db by session
        $user = R::findOne(USER, 'session = :session AND ip = :ip ', array(
            ':session' => $payload['session'],
            ':ip' => $payload['ip'],
        ));
        
        // if we have a payload with id set the facebook id
        if(isset($payload['id'])){
            // change the id into fbid.
            $payload['fbid'] = '' . $payload['id'];
            unset($payload['id']);
        }

        
        if(isset($payload['email'])){
            $emailuser = R::findOne(USER, 'email = :email ', array(
                ':email' => $payload['email']
            ));
            
            if($emailuser){
                $user = $emailuser;
            }
        }

        
        if(isset($payload['fbid'])){
            $fbiduser = R::findOne(USER, 'fbid = :fbid ', array(
                ':fbid' => $payload['fbid']
            ));
            
            if($fbiduser){
                $user = $fbiduser;
            }
        }
        
        
        
        
        // the date
        $currentDate = new \DateTime();
        
        if (! $user) {
            $user = R::xdispense(USER);
            $user->createdate = $currentDate->format('Y-m-d H:i:s');
        }
        
        $user->import($payload);
        
        // update the lastupdated timestamp
        $user->lastupdate = $currentDate->format('Y-m-d H:i:s');
        
        R::store($user);
    }
}


