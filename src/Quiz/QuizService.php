<?php
namespace App\Quiz;

use RedBeanPHP\R;
use App\Model\Quiz;

class QuizService
{


    /**
     */
    function populate()
    {
        R::freeze(false);
        R::nuke();
        
        $quiz = R::xdispense(QUIZ);
        $quiz->theme = 'starwars';
        $quiz->nextaction = '/';
        
        R::store($quiz);
        
        $question = R::xdispense(QUESTION);
        $question->title = "Which Star Wars Character Are You?!";
        R::store($question);
        
        $question->{QUIZ} = $quiz;
        R::store($question);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Han Solo';
        $answer->message = "You have a strong and determined personality, but beneath your tough exterior you have a loving heart and an inner bravery that will help you through the tough times. You learn from your mistakes and always stay true to yourself!";
        $answer->image = 'A';
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Luke Skywalker';
        $answer->message = "You're courageous, eager for adventure and an all-round hero. Sometimes you find it hard to control your emotions, but you always have the happiness and wellbeing of others at heart!";
        $answer->image = 'B';
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Princess Leia';
        $answer->image = 'C';
        $answer->message = "You're level-headed, courageous, and with a sharp-tongued wit surpassed only by your beauty. You've been through some tough times but always come out stronger in the end!";
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Chewbacca';
        $answer->image = 'D';
        $answer->message = "You may look tough, and even scary to some people, but deep down you're a big softie. You're loyal, affectionate and humble - but if you think something isn't fair you're not afraid to say so! ";
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Finn';
        $answer->image = 'E';
        $answer->message = "Despite what life has thrown at you, you have a good heart and true empathy for other people. You are brave, intelligent and with the strength to face down whatever life throws at you!";
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Rey';
        $answer->image = 'F';
        $answer->message = "You have a heart full of generosity and a desire to help others, often putting their needs before your own. You have a great imagination that sets you apart from the majority of others!";
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        R::freeze(true);
        
        return $quiz;
    }

    /**
     *
     * @param
     *            $quizid
     * @return array
     */
    public function getOptions($quizid)
    {
        $quiz = $this->getQuiz($quizid)->toArray();
        return $quiz;
    }

    /**
     *
     * @param int $quizid            
     * @return Quiz
     */
    function getQuiz($quizid)
    {
        return R::load(QUIZ, $quizid);
    }

    /**
     *
     * @param int $quizid            
     */
    function getQuestion($quizid)
    {
        return $this->getQuiz($quizid)->quizQuestion;
    }

    /**
     *
     * @param int $quizid            
     */
    function getAnswers($quizid)
    {
        return $this->getQuiz($quizid)->ownQuizAnswerList;
    }

    /**
     *
     * @param int $quizid            
     * @param int $answerId            
     */
    function getAnswer($quizid, $answerId)
    {
        return $this->getQuiz($quizid)->ownQuizAnswerList[$answerId];
    }
}