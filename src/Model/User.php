<?php
namespace App\Model;

use RedBeanPHP\SimpleModel;
use RedBeanPHP\R;

class User extends SimpleModel
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
        $user = R::findOne('user', 'fbid = :fbid ', array(
            ':fbid' => $payload['fbid']
        ));
        
        // the date
        $currentDate = new \DateTime();
        
        if (! $user) {
            $user = R::dispense('user');
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


