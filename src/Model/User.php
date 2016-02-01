<?php
namespace App\Model;

use RedBeanPHP\SimpleModel;
use RedBeanPHP\R;

class User extends SimpleModel
{

    /**
     * Insert or update a facebook user.
     * 
     * @param unknown $user            
     */
    public static function upsert($payload)
    {
        R::freeze(false);
        
        $payload['fbid'] = $payload['id'];
        unset($payload['id']);
        
        $user = R::findOne('user', 'fbid = :fbid ', array(
            ':fbid' => $payload['fbid']
        ));
        
        if (! $user) {
            
            $user = R::dispense('user');
            $user->import($payload);
        }
        
        $dateFoo = new \DateTime();
        $user->lastupdate = $dateFoo->format('Y-m-d H:i:s');
        
        R::store($user);
    }
}


