<?php

use App\Question;
use App\Answer;
use App\PreQuestion;
use App\PostQuestion;
use Illuminate\Database\Seeder;

class QuestionAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Question::truncate();
        Answer::truncate();
        PreQuestion::truncate();
        PostQuestion::truncate();
        $questions = $this->getData();

        $questions->each(function ($question) {
            $createdQuestion = Question::create([
                'text' => $question['question'],
            ]);

            collect($question['answers'])->each(function ($answer) use ($createdQuestion) {
                Answer::create([
                    'question_id' => $createdQuestion->id,
                    'text' => $answer['text'],
                ]);
            });

            collect($question['pre_questions'])->each(function ($pre_question) use ($createdQuestion) {
                PreQuestion::create([
                    'question_id' => $createdQuestion->id,
                    'text' => $pre_question['text'],
                ]);
            });

            collect($question['post_questions'])->each(function ($post_question) use ($createdQuestion) {
                PostQuestion::create([
                    'question_id' => $createdQuestion->id,
                    'text' => $post_question['text'],
                ]);
            });

        });
    }

    private function getData()
    {
        return collect([
            [
                'pre_questions' => [],
                'question' => 'Какой ваш цвет настроения?',
                'answers' => [
                    ['text' => 'Синий'],
                    ['text' => 'Леопардовый'],
                    ['text' => 'Фиолетовый'],
                    ['text' => 'Скорее да, чем нет'],
                ],
                'post_questions' => [],
            ],[
                'pre_questions' => [],
                'question' => 'Что вы делаете, когда вам приносят новую задачу?',
                'answers' => [
                    ['text' => 'Закатываю глаза'],
                    ['text' => 'Бодро принимаюсь за работу'],
                    ['text' => 'Бросаю в коллегу мандаринкой'],
                    ['text' => 'Начинаю обсуждать корпоратив'],
                ],
                'post_questions' => [],
            ],[
                'pre_questions' => [],
                'question' => 'Что сейчас на ваших плечах?',
                'answers' => [
                    ['text' => 'Мишура'],
                    ['text' => 'Тяжкий груз дедлайнов'],
                    ['text' => 'Пояс из собачьей шерсти'],
                    ['text' => 'Попугай'],
                ],
                'post_questions' => [],
            ],[
                'pre_questions' => [],
                'question' => 'Кто для вас дед мороз?',
                'answers' => [
                    ['text' => 'Волшебник, который исполняет мечты'],
                    ['text' => 'Дед, которого придумали инфантилы'],
                    ['text' => 'Мужчина с узнаваемым брендом и прокачанным пиаром'],
                    ['text' => 'Никто, я с ним не пересекался'],
                ],
                'post_questions' => [],
            ],[
                'pre_questions' => [],
                'question' => 'Кто для вас герой этого года?',
                'answers' => [
                    ['text' => 'Наши футболисты'],
                    ['text' => 'Сын маминой подруги'],
                    ['text' => 'Безусловно, я сам'],
                    ['text' => 'Человек, который ходит на работу в конце декабря'],
                ],
                'post_questions' => [],
            ],
        ]);
    }
}
