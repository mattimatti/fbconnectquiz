<?php
namespace App\Model;

use RedBeanPHP\SimpleModel;

class QuizAnswer extends SimpleModel
{

    /**
     */
    public function toArray()
    {
        $exported = $this->unbox()->export();
        return $exported;
    }
}


