<?php

namespace frontend\controllers;

use Yii;
use common\models\Question;
use common\models\QuestionSearch;
use common\models\QuestionVote;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'vote' => ['post'],
                    'vote-float' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Vote for selected answer
     * @return mixed Json
     */
    public function actionVote()
    {
        $aid = Yii::$app->request->post('answer');
        $userID = isset(Yii::$app->user->id) ? Yii::$app->user->id : false;
        if($aid && $userID) {
            $answer = $this->findModel($aid);
            if(isset($answer->id) && !is_null($answer->parent_id)) {
                $vote = new QuestionVote();
                $vote->question_id = $answer->parent_id;
                $vote->user_id = $userID;
                if($vote->save()) {
                    $answer->voutes++;
                    $answer->save(false);
                    Yii::$app->getSession()->setFlash('success-question', 'Ваш ответ на опрос успешно сохранен.');
                }
            }
        }
        return Yii::$app->getResponse()->redirect(Url::to('/'));
    }

    /**
     * Vote for selected float answer
     * @return mixed Json
     */
    public function actionVoteFloat()
    {
        $answerValues = Yii::$app->request->post('answer');
        $userID = isset(Yii::$app->user->id) ? Yii::$app->user->id : false;

        // validation
        $answers = [];
        if(is_array($answerValues) && $userID) {
            foreach ($answerValues as $aid => $value) {
                $answer = $this->findModel($aid);
                if(isset($answer)) $answers[] = $answer;
            }
        }
        if(count($answers) == 0) {
            Yii::$app->getSession()->setFlash('error-question', 'Ошибка при сохранении ответа в опросе: Нет выбранных вариантов.');
            return Yii::$app->getResponse()->redirect(Url::to('/'));
        }
        $first = $answers[0];
        $question = $this->findModel($first->parent_id);

        if(isset($question)){
            $question->voutes++;
            $vote = new QuestionVote();
            $vote->question_id = $answer->parent_id;
            $vote->user_id = $userID;
            if(!$vote->validate() || !$question->validate()) {
                return Json::encode(['success' => false, 'msg' => 'vote']);
            }
            $vote->save();
            $question->save();
            foreach ($answers as $answer) {
                $answer->mark = round(($value + $answer->mark * ($question->voutes - 1))/$question->voutes, 4);
                $answer->save();
            }
            Yii::$app->getSession()->setFlash('success-question', 'Ваш ответ на опрос успешно сохранен.');
        }
                
        return Yii::$app->getResponse()->redirect(Url::to('/'));
    }

    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}