<?php

/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 27.01.2016
 * Time: 10:09
 */
class Questions {
	/** @var string Текст вопроса */
	public $text;
	public $answers; //массив с правильными ответами
	public $points = 5; //Количество баллов за правильный ответ
	public $correctAnswer; // правильный ответ
	public $hint; //подсказка
}

function createQuestion() {
	$questions = array();

	$q                = new Questions;
	$q->text          = 'Какая планета располагается четвертой по счету от Солнца?';
	$q->answers       = array('a' => 'Венера', 'b' => 'Марс', 'c' => 'Юпитер', 'd' => 'Меркурий');
	$q->points        = 10;
	$q->correctAnswer = 'b';
	$q->hint          = 'Известная планета красного цвета.';

	$questions[] = $q;

	$q                = new Questions;
	$q->text          = 'Какой город является столицей Великобритании?';
	$q->answers       = array('a' => 'Париж', 'b' => 'Москва', 'c' => 'Нью-Йорк', 'd' => 'Лондон');
	$q->points        = 5;
	$q->correctAnswer = 'd';
	$q->hint          = 'Куда хочет уехать жить Г.Лепс?';

	$questions[] = $q;

	$q                = new Questions;
	$q->text          = 'Кто придумал теорию относительности?';
	$q->answers       = array('a' => 'Джон Леннон', 'b' => 'Джим Моррисон', 'c' => 'Альберт Эйнштейн', 'd' => 'Исаак Ньютон');
	$q->points        = 30;
	$q->correctAnswer = 'c';
	$q->hint          = 'Высунул язык на фотографии.';

	$questions[] = $q;
	return $questions;
}

function printQuestion($questions) {

	$i = 1;

	foreach ( $questions as $question ) {
		echo "\n{$i} вопрос: {$question->text}" . PHP_EOL;

		echo "Варианты ответов:\n";
		foreach ( $question->answers as $letter => $answer ) {
			echo "{$letter} - {$answer}" . PHP_EOL;
		}
		$i++;
	}

}

function checkAnswer($inputAnswers, $questions) {

	$notCorrect     = '';
	$hint           = '';
	$point          = 0; //набранное количство баллов
	$maxPoint       = 0; //максимально возможное кол-во баллов
	$correctAnswers = 0; //кол-во верных ответов
	$error          = false;

	if ( count($inputAnswers) != count($questions) ) {
		exit('Количество ответов не сопадает с количеством ответов');
	}

	for ( $i = 0; $i < count($questions); $i++ ) {

		$question    = $questions[$i];
		$inputAnswer = $inputAnswers[$i];

		if ( $inputAnswer == $question->correctAnswer ) {
			$point += $question->points;
			$correctAnswers++;
		} else {
			$notCorrect .= $question->text . PHP_EOL;
			$hint .= $question->hint . PHP_EOL;
			$error = true;
		}
		$maxPoint += $question->points;
	}
	$result = 'Вы набрали ' . $point . ' баллов, из максимально возможных ' . $maxPoint . PHP_EOL;

	if ( $error == true ) {
		$result .= 'Не верно дан ответ на вопрос: ' . $notCorrect . 'Подсказка: ' . $hint;
	}

	return $result;
}

$inputAnswers = array('a', 'b', 'c');
$questions    = createQuestion();

echo checkAnswer($inputAnswers, $questions);