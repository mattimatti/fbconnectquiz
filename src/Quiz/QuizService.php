<?php
namespace App\Quiz;

use RedBeanPHP\R;

class QuizService
{

    function __construct()
    {}

    /**
     */
    function populate()
    {
        // R::freeze(false);
        
        // $quiz = R::dispense('quiz');
        // $quiz->theme = 'starwars';
        
        // R::store($quiz);
        
        // $question = R::dispense('question');
        // $question->title = 'hello';
        // R::store($question);
        
        // $quiz->question = $question;
        // R::store($quiz);
        
        // $answer = R::dispense('answer');
        // $answer->text = 'Han Solo';
        // $answer->extended = 'Han Solo is a crack';
        // $answer->image = 'A.png';
        // R::store($answer);
        
        // $quiz->ownAnswerList[] = $answer;
        
        // R::store($quiz);
        
        // R::freeze(true);
    }

    public function getOptions()
    {
        $data = array();
        
        $quiz = array();
        
        $question = array();
        $question['title'] = 'Which Star Wars Character Are You?!';
        $quiz['question'] = $question;
        
        $quiz['answers'] = array();
        
        $letters = array(
            'A.png',
            'B.jpg',
            'C.png',
            'D.png',
            'E.png',
            'F.png'
        );
        
        foreach ($letters as $letter) {
            $answer = array();
            $answer['title'] = $letter . ' Solo';
            $answer['image'] = $letter;
            
            $quiz['answers'][] = $answer;
        }
        
        $data['quiz'] = $quiz;
        return $data;
    }

    /**
     *
     * @param int $quizid            
     * @return \RedBeanPHP\OODBBean
     */
    function getQuiz($quizid)
    {
        return R::load('quiz', $quizid);
    }

    /**
     *
     * @param int $quizid            
     */
    function getQuestion($quizid)
    {
        return $this->getQuiz($quizid)->question;
    }

    /**
     *
     * @param int $quizid            
     */
    function getAnswers($quizid)
    {
        return $this->getQuiz($quizid)->ownAnswerList;
    }
}