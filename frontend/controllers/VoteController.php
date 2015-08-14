<?php

namespace frontend\controllers;

use Yii;
use common\models\Vote;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * VoteController implements the CRUD actions for Vote model.
 */
class VoteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->enableCsrfValidation = false;
        parent::init();
    }

    /**
     * Add a new vote
     * @param integer $id
     * @param string $type
     * @param integer $vote
     * @return mixed
     */
    public function actionVote($id, $type, $vote = 1)
    {
        if(!Yii::$app->user->isGuest)
        {
            $voteModel = Vote::find()->where([
                'voteable_type' => $type,
                'voteable_id' => $id,
                'user_id' => Yii::$app->user->id,
            ])->one();
            if(!isset($voteModel->id))
            {
                $voteModel = new Vote;
                $voteModel->voteable_type = $type;
                $voteModel->voteable_id = $id;
                $voteModel->user_id = Yii::$app->user->id;
            } elseif ($voteModel->vote == $vote) {
                $out = ['success' => false, 'error' => 'User has already voted'];
                echo Json::encode($out);
                return;
            }
            $voteModel->vote = $vote;
            if($voteModel->save())
            {
                $rating = Vote::getRating($id, $type);
                $out = ['success' => true, 'rating' => $rating];
            } else {
                $out = ['success' => false, 'error' => $voteModel->getErrors()];
            }
        } else {
            $out = ['success' => false, 'error' => 'User is guest'];
        }
        echo Json::encode($out);
    }
}
