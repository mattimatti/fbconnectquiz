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

    /**
     *
     * @return multitype:string
     */
    public function getOptions()
    {
        $quiz = array();
        
        $question = array();
        $question['title'] = 'Which Star Wars Character Are You?!';
        $quiz['question'] = $question;
        
        $quiz['answers'] = array();
        
        $answer = array();
        $answer['id'] = 0;
        $answer['title'] = 'Han Solo';
        $answer['image'] = 'A.png';
        $answer['message'] = "You have a strong and determined personality, but beneath your tough exterior you have a loving heart and an inner bravery that will help you through the tough times. You learn from your mistakes and always stay true to yourself!";
        $quiz['answers'][] = $answer;
        
        $answer = array();
        $answer['id'] = 1;
        $answer['title'] = 'Luke Skywalker';
        $answer['image'] = 'B.jpg';
        $answer['message'] = "You're courageous, eager for adventure and an all-round hero. Sometimes you find it hard to control your emotions, but you always have the happiness and wellbeing of others at heart!";
        $quiz['answers'][] = $answer;
        
        $answer = array();
        $answer['id'] = 2;
        $answer['title'] = 'Princess Leia';
        $answer['image'] = 'C.png';
        $answer['message'] = "You're level-headed, courageous, and with a sharp-tongued wit surpassed only by your beauty. You've been through some tough times but always come out stronger in the end!";
        $quiz['answers'][] = $answer;
        
        $answer = array();
        $answer['id'] = 3;
        $answer['title'] = 'Chewbacca';
        $answer['image'] = 'D.png';
        $answer['message'] = "You may look tough, and even scary to some people, but deep down you're a big softie. You're loyal, affectionate and humble - but if you think something isn't fair you're not afraid to say so! ";
        $quiz['answers'][] = $answer;
        
        $answer = array();
        $answer['id'] = 4;
        $answer['title'] = 'Finn';
        $answer['image'] = 'E.png';
        $answer['message'] = "Despite what life has thrown at you, you have a good heart and true empathy for other people. You are brave, intelligent and with the strength to face down whatever life throws at you!";
        $quiz['answers'][] = $answer;
        
        $answer = array();
        $answer['id'] = 5;
        $answer['title'] = 'Rey';
        $answer['image'] = 'F.png';
        $answer['message'] = "You have a heart full of generosity and a desire to help others, often putting their needs before your own. You have a great imagination that sets you apart from the majority of others!";
        $quiz['answers'][] = $answer;
        
        return $quiz;
    }

    /**
     *
     * @param int $quizid            
     * @return \RedBeanPHP\OODBBean
     */
    function getQuiz($quizid)
    {
        return $this->getOptions();
        
        return R::load('quiz', $quizid);
    }

    /**
     *
     * @param int $quizid            
     */
    function getQuestion($quizid)
    {
        $quiz = $this->getOptions();
        return $quiz['question'];
        
        return $this->getQuiz($quizid)->question;
    }

    /**
     *
     * @param int $quizid            
     */
    function getAnswers($quizid)
    {
        $quiz = $this->getOptions();
        return $quiz['answers'];
        
        return $this->getQuiz($quizid)->ownAnswerList;
    }

    /**
     *
     * @param int $quizid            
     * @param int $answerId            
     */
    function getAnswer($quizid, $answerId)
    {
        $quiz = $this->getOptions();
        return $quiz['answers'][$answerId];
        
        return $this->getQuiz($quizid)->ownAnswerList;
    }
}