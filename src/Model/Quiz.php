<?php
namespace App\Model;

use RedBeanPHP\SimpleModel;

class Quiz extends SimpleModel
{

    /**
     *
     * @return Question
     */
    public function question()
    {
        $questions = $this->ownQuestion;
        return array_shift($questions);
    }

    /**
     *
     * @return Answer[]
     */
    public function answers()
    {
        return $this->ownAnswerList;
    }

    /**
     */
    public function toArray()
    {
        $exported = $this->unbox()->export();
        
        $exported['question'] = $this->question()->toArray();
        
        $exported['answers'] = array();
        foreach ($this->answers() as $answer) {
            $exported['answers'][] = $answer->toArray();
        }
        
        return $exported;
    }
}


