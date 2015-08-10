<?php
 
namespace console\controllers;
 
use Yii;
use yii\console\Controller;
use common\models\Subscribing;
use common\models\Post;
 
/**
 * Test controller
 */
class SubscribingController extends Controller {
 
    public function actionIndex() {
        echo "Sending letters to subscribers begin...\n";

        $currentDayTime = time() - 60*60*24;
        $currentDay = date("Y-m-d H:i:s", $currentDayTime);
        $importantPosts = Post::find()
            ->where([
                'is_public' => 1, 
                'is_index' => 1,
            ])
            ->andWhere(['>', 'created_at', $currentDay])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)->all();
        $ids = [];
        foreach ($importantPosts as $post) {
            $ids[] = $post->id;
        }

        $maxCommentsPosts = Post::find()
            ->where([
                'is_public' => 1, 
            ])
            ->andWhere(['>', 'created_at', $currentDay])
            ->andWhere(['not in', "id", $ids])
            ->orderBy([
                'id' => SORT_DESC,
            ])
            ->limit(3)->all();
        $posts = array_merge($importantPosts ,$maxCommentsPosts);

        // sending
        $subscribings = Subscribing::find()->all();
        $count = 0;
        foreach ($subscribings as $subscribing) {
            if(!filter_var($subscribing->email, FILTER_VALIDATE_EMAIL)) {
                echo "Email is not correct: ".$subscribing->email."\n";
                $subscribing->delete();
                continue;
            }
            $unsubscribeKey = md5($subscribing->id.$subscribing->email);
            $message = Yii::$app->mailer->compose('subscribe-view-html', compact('posts', 'unsubscribeKey'))
                ->setTo($subscribing->email)
                ->setSubject('Новости Динамо');
            $send = $message->send();
            if($send) $count++;
        }
        echo "Posted $count letters. \n";
        echo "Sending letters to subscribers end.\n";
    }
 
}