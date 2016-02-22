<?php
namespace App\Quiz;

use RedBeanPHP\R;
use App\Model\Quiz;

class QuizService
{

    /**
     */
    function alter()
    {
        R::freeze(true);
        R::exec('ALTER TABLE `quiz_user` ADD `session` VARCHAR(191)  NULL  DEFAULT NULL  AFTER `session`;');
    }

     /**
     */
    function populate()
    {
        
        // do not execute this function
        return;
        
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
        $answer->sharemessage = "I have a strong and determined personality, but beneath my tough exterior I have a loving heart and an inner bravery that helps me through the tough times. I learn from my mistakes and always stay true to myself! Who would you be?";
        $answer->image = 'A';
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Luke Skywalker';
        $answer->message = "You're courageous, eager for adventure and an all-round hero. Sometimes you find it hard to control your emotions, but you always have the happiness and wellbeing of others at heart!";
        $answer->sharemessage = "I'm courageous, eager for adventure and an all-round hero. Sometimes I find it hard to control my emotions, but I always have the happiness and wellbeing of others at heart! Who would you be?";
        $answer->image = 'B';
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Princess Leia';
        $answer->image = 'C';
        $answer->message = "You're level-headed, courageous, and with a sharp-tongued wit surpassed only by your beauty. You've been through some tough times but always come out stronger in the end!";
        $answer->sharemessage = "I'm level-headed, courageous, and with a sharp-tongued wit surpassed only by my beauty. I've been through some tough times but always come out stronger in the end! Who would you be?";
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Chewbacca';
        $answer->image = 'D';
        $answer->message = "You may look tough, and even scary to some people, but deep down you're a big softie. You're loyal, affectionate and humble - but if you think something isn't fair you're not afraid to say so! ";
        $answer->sharemessage = "I may look tough, and even scary to some people, but deep down I'm a big softie. I'm loyal, affectionate and humble - but if I think something isn't fair I'm not afraid to say so! Who would you be?";
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Finn';
        $answer->image = 'E';
        $answer->message = "Despite what life has thrown at you, you have a good heart and true empathy for other people. You are brave, intelligent and with the strength to face down whatever life throws at you!";
        $answer->sharemessage = "Despite what life has thrown at me, I have a good heart and true empathy for other people. I am brave, intelligent and with the strength to face down whatever life throws at me! Who would you be?";
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $answer = R::xdispense(ANSWER);
        $answer->title = 'Rey';
        $answer->image = 'F';
        $answer->message = "You have a heart full of generosity and a desire to help others, often putting their needs before your own. You have a great imagination that sets you apart from the majority of others!";
        $answer->sharemessage = "I have a heart full of generosity and a desire to help others, often putting their needs before my own. I have a great imagination that sets me apart from the majority of others! Who would you be?";
        $answer->{QUIZ} = $quiz;
        $answer->{QUESTION} = $question;
        R::store($answer);
        
        $quiz_user = array(
            'createdate' => '2016-02-08 19:20:37',
            'name' => 'Matteo Monti Matteo Monti Matteo Monti Matteo Monti Matteo Monti',
            'email' => 'mmonti@gmail.commmonti@gmail.commmonti@gmail.commmonti@gmail.commmonti@',
            'gender' => 'maleorfemale',
            'ip' => '151.237.238.110',
            'fbid' => '1a01544915asdasdasdasdasdasd51609838e16',
            'lastupdate' => '2016-02-08 19:20:37',
            'city' => 'Leccooranotherlocationwhatever',
            'country' => 'Leccooranotherlocationwhatever',
            'latitude' => '45.85a',
            'longitude' => '9.38333a'
        );
        
        $user = R::xdispense(USER);
        $user->import($quiz_user);
        R::store($user);
        
        R::wipe(USER);
        
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

    /**
     */
    function getCumulativeResults()
    {
        $sql = "select country as name, sum( case when email != '' then 1 else 0 end) as quantity, sum( 1) as overall from quiz_user group by country";
        $data = R::getAll($sql);
        
        return $data;
    }


    /**
     */
    function getResults()
    {
        return R::find(USER, "where email !='' ");
    }
}