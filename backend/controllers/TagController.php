<?php

namespace backend\controllers;

use Yii;
use common\models\Tag;
use common\models\TagsCloud;
use common\models\TagSearch;
use common\models\Tagging;
use common\models\Post;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\db\Query;

/**
 * TagController implements the CRUD actions for Tag model.
 */
class TagController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->enableCsrfValidation = false;
        if (!empty(Yii::$app->user) && !Yii::$app->user->can("admin")) {
            throw new \yii\web\ForbiddenHttpException('Вы не можете выполнить это действие.');
        }

        parent::init();
    }

    /**
     * Lists all Tag models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tag model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tag();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tag model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $tag = $this->findModel($id);
        $taggings = Tagging::find()
            ->where([
                'taggable_type' => Tagging::TAGGABLE_POST,
                'tag_id' => $id,
            ])->all();

        foreach ($taggings as $tagging) {
            $post = Post::findOne($tagging->taggable_id);

            if($post) {
                $cached_tag_list = [];
                $newTags = $post->getTags();
                foreach ($newTags as $newTag) {
                    if(strcmp($tag->name, $newTag->name ) !== 0) {
                        $cached_tag_list[] = $newTag->name;
                    }
                }
                $post->cached_tag_list = implode(', ', $cached_tag_list);
                $post->save(true,['cached_tag_list']);
            }
        }

        Tagging::deleteAll(['tag_id' => $tag->id]);
        $tag->delete();

        return $this->redirect(['index']);
    }

    /**
     * Rebuilds table of top tags
     *     
     */
    public function actionTagsCloudRebuild() {
        $success = false;

        $connection = Yii::$app->db;

        $query = 'DELETE FROM '.TagsCloud::tableName();
        $cmd = $connection
             ->createCommand($query)
             ->execute();

        $query = 'SELECT tag_id AS i, count(tag_id) AS c FROM taggings GROUP BY tag_id ORDER BY c DESC LIMIT 50';
        $cmd = $connection->createCommand($query);
        $newTopTags = $cmd->queryAll();

        $weight = 10;
        $count = 0;
        foreach ($newTopTags as $topTag) {
            $count++;
            if(50 % $count == 0) {
                $weight -= 2;
            }

            $cmd = $connection
                 ->createCommand()
                 ->batchInsert(TagsCloud::tableName(), ['tag_id', 'weight'], [
                                                                                  [$topTag['i'], $weight],
                                                                               ])->execute();
        }

        $success = true;

        return $this->render('rebuildSuccess', [
                'success' => $success,
            ]);        
    }

    /**
     * Display list of tags in json format
     *
     * @param string $query Query for search
     * @return mixed Json data
     */
    public function actionTagList($query = null) {
        if($query == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $search = urldecode($query);
        $query = new Query;
        $query->select('id as value, name as text')
            // ->distinct()
            ->from(Tag::tableName())
            ->where(['like', 'name', $search])
            // ->orderBy('name')
            ->limit(10);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = array_values($data);
        echo Json::encode($out);
    }

    /**
     * Finds the Tag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
