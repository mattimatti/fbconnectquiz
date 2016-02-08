<?php
namespace App\Model;

use RedBeanPHP\SimpleModel;

class QuizQuestion extends SimpleModel
{

    /**
     */
    public function toArray()
    {
        $exported = $this->unbox()->export();
        return $exported;
    }
}


