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
        
        // change the id into fbid.
        $payload['fbid'] = $payload['id'];
        unset($payload['id']);
        
        // Find an existing user in db
        $user = R::findOne(USER, 'fbid = :fbid ', array(
            ':fbid' => $payload['fbid']
        ));
        
        // the date
        $currentDate = new \DateTime();
        
        if (! $user) {
            $user = R::xdispense(USER);
            $user->createdate = $currentDate->format('Y-m-d H:i:s');
        }
        
        $user->import($payload);
        
//         print_r($user);
//         exit();

        // update the lastupdated timestamp
        
        $user->lastupdate = $currentDate->format('Y-m-d H:i:s');
        
        R::store($user);
    }
}


