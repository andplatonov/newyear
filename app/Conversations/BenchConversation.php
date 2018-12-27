<?php

namespace App\Conversations;

use App\Http\Controllers\MailController;
use App\Question;
use App\Answer;
use App\PreQuestion;
use App\Interviewer;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class BenchConversation extends Conversation
{
    protected $quizQuestions;
    protected $userPoints = 0;
    protected $userCorrectAnswers = 0;
    protected $questionCount = 0;
    protected $currentQuestion = 1;
    protected $data;
    protected $Fill;
    protected $FillCount;
    protected $Mask;
    protected $MaskCount;
    protected $Buzo;
    protected $BuzoCount;
    protected $Akin;
    protected $AkinCount;

    public function run()
    {

        $this->Fill = [1, 5, 12, 14, 18];
        $this->Mask = [3, 8, 11, 16, 20];
        $this->Buzo = [2, 7, 9, 13, 19];
        $this->Akin = [4, 6, 10, 15, 17];
        $this->FillCount = 0;
        $this->MaskCount = 0;
        $this->BuzoCount = 0;
        $this->AkinCount = 0;
        $this->data = [];
        $this->quizQuestions = Question::all();
        $this->questionCount = $this->quizQuestions->count();
        $this->quizQuestions = $this->quizQuestions->keyBy('id');
        $this->showInfo();
    }

    private function showInfo()
    {
        $this->say('Узнайте, кто вы в последнюю неделю перед новым годом: Филипп Киркоров, Илон Маск, Ольга Бузова или нога Акинфеева. Ответьте на 5 вопросов.');
        $this->checkForNextQuestion();
    }

    private function checkForNextQuestion()
    {
        if ($this->quizQuestions->count()) {
            return $this->askQuestion($this->quizQuestions->first());
        }

        $this->showResult();

    }

    private function askQuestion(Question $question)
    {

        if($question->pre_questions){
            foreach ($question->pre_questions as $pre_question){
                $this->say($pre_question->text);
            }
        }

        if($question->post_questions){
            foreach ($question->post_questions as $post_question){
                $this->say($post_question->text);
            }
        }

        $questionTemplate = BotManQuestion::create($question->text);

        foreach ($question->answers as $answer) {
            $questionTemplate->addButton(Button::create($answer->text)->value($answer->id));
        }

        $this->ask($questionTemplate, function (BotManAnswer $answer) use ($question) {


            $answerIDs = [];
            foreach ($question->answers as $qanswer){
                array_push($answerIDs, $qanswer->id);
            }

            if(!in_array($answer->getText(), $answerIDs)){
                $this->say('Извините, не могу считать ответ. Ответьте на вопрос снова, используя кнопки ответов :)');
                return $this->checkForNextQuestion();
            }

            if($this->Fill[$question->id-1] == $answer->getText()){
                $this->FillCount += 1;
            }else if ($this->Mask[$question->id-1] == $answer->getText()){
                $this->MaskCount += 1;
            }else if ($this->Buzo[$question->id-1] == $answer->getText()){
                $this->BuzoCount += 1;
            }else if ($this->Akin[$question->id-1] == $answer->getText()){
                $this->AkinCount += 1;
            }

            $this->quizQuestions->forget($question->id);

            $this->checkForNextQuestion();
        });
    }

    private function showResult()
    {

        $arr = array(
            'Филипп Киркоров' => $this->FillCount,
            'Илон Маск' => $this->MaskCount,
            'Ольга Бузова' => $this->BuzoCount,
            'Нога Акинфеева' => $this->AkinCount);
        arsort($arr);

        $name = key($arr);

        $url = '';

        if($name == 'Филипп Киркоров'){
            $url = 'https://3f6e4e8f.ngrok.io/image/fill.jpg';
        }else if($name == 'Илон Маск'){
            $url = 'https://3f6e4e8f.ngrok.io/image/mask.jpg';
        }else if($name == 'Ольга Бузова'){
            $url = 'https://3f6e4e8f.ngrok.io/image/buzo.jpg';
        }else if($name == 'Нога Акинфеева'){
            $url = 'https://3f6e4e8f.ngrok.io/image/akin.jpg';
        }

        $attachment = new Image($url);

        $message = OutgoingMessage::create('Вы — '.$name)
            ->withAttachment($attachment);
        // Reply message object
        $this->bot->reply($message);

        $this->say('Создаем чат-ботов под любые задачи: сбор заявок, опросы сотрудников, розыгрыши или тесты.');


        $questionTemplate = BotManQuestion::create('Нужен чат-бот? Напиши свой номер телефона и мы перезвоним, чтобы обсудить задачу.');

        $this->ask($questionTemplate, function (BotManAnswer $answer) {

            $this->say('Счастливого Нового года! команда Platonov agency');
            return MailController::send($answer->getText());

//            $this->say('Ваш ответ: '.$answer->getText());

        });
    }
}
