<?php
namespace App\Model;

use RedBeanPHP\SimpleModel;

/**
 *
 * @author mattimatti
 *        
 */
class QuizQuiz extends SimpleModel
{

    /**
     *
     * @return Question
     */
    public function question()
    {
        $questions = $this->questions();
        return array_shift($questions);
    }

    
    /**
     *
     * @return Question[]
     */
    public function questions()
    {
       return $this->ownQuizQuestion;
    }

    /**
     *
     * @return Answer[]
     */
    public function answers()
    {
        return $this->ownQuizAnswerList;
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


