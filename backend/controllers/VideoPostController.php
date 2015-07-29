<?php

namespace backend\controllers;

use Yii;
use common\models\VideoPost;
use common\models\VideoPostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;
use common\models\Asset;
use common\models\Tagging;
use common\models\Relation;
use common\models\Match;

/**
 * VideoPostController implements the CRUD actions for VideoPost model.
 */
class VideoPostController extends Controller
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
            throw new ForbiddenHttpException('Вы не можете выполнить это действие.');
        }

        parent::init();
    }

    /**
     * Lists all VideoPost models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VideoPostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VideoPost model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $image = $model->getAsset(Asset::THUMBNAIL_NEWS);
        return $this->render('view', [
            'model' => $model,
            'image' => $image,
        ]);
    }

    /**
     * Creates a new VideoPost model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VideoPost();
        $model->tags = [];

        // default values
        $model->is_public = 1;
        $model->user_id = Yii::$app->user->id;
        $model->is_pin = 0;

        $matchModel = new \common\models\MatchSearch();
        $relation = new Relation();
        $relation->relationable_type = Relation::RELATIONABLE_POST;
        $matches = $matchModel::find()
            ->orderBy(['date' => SORT_DESC])
            ->limit(10)
            ->all();
        $matchesList = [];
        foreach ($matches as $match) {
            $matchDate = date('d.m.Y', strtotime($match->date));
            $matchesList[$match->id] = $match->name.' ('.$matchDate.')';
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // Set slug
            $model->slug = $model->genSlug($model->title);

            // Save the model to have a record number
            if(!$model->save())
            {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            // Adding new tags
            if(is_array($model->tags))
            {
                foreach ($model->tags as $id) {
                    $model->addTag($id);
                }
            }

            $cached_tag_list = [];
            $newTags = $model->getTags();
            foreach ($newTags as $newTag) {
                $cached_tag_list[] = $newTag->name;
            }
            $model->cached_tag_list = implode(', ', $cached_tag_list);

            // Set image
            $uploadedFile = UploadedFile::getInstance($model, 'image');
            if($uploadedFile)
            {
                // Save origionals 
                $asset = new Asset();
                $asset->assetable_type = Asset::ASSETABLE_VIDEO;
                $asset->assetable_id = $model->id;
                $asset->uploadedFile = $uploadedFile;
                $asset->saveAsset();

                // Save thumbnails 
                $imageID = $asset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_VIDEO);

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->assetable_type = Asset::ASSETABLE_VIDEO;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->saveAsset();
                }
            }

            // Save videofile
            $videoFile = UploadedFile::getInstance($model, 'video');
            if($videoFile)
            {
                // Save origionals 
                $asset = new Asset();
                $asset->assetable_type = Asset::ASSETABLE_VIDEOFILE;
                $asset->assetable_id = $model->id;
                $asset->uploadedFile = $videoFile;
                $asset->saveVideoAsset();
            }

            $relation->relationable_id = $model->id;
            $relation->relationable_type = Relation::RELATIONABLE_VIDEO;
            if($relation->load(Yii::$app->request->post()) && $model->validate()) {

                if($relation->parent_id != '' && is_array($relation->parent_id)) {
                    $relation->parent_id = $relation->parent_id[0];
                }
                if($relation->parent_id && is_numeric($relation->parent_id)) {
                    $relation->save();
                }
            }

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'relation' => $relation,
                'matchModel' => $matchModel,
                'matchesList' => $matchesList,
            ]);
        }
    }

    /**
     * Updates an existing VideoPost model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = $model->getAsset();
        $tags = $model->getTags();
        $assets = $model->getAssets();
        $model->tags = [];
        foreach ($tags as $tag) {
            $model->tags[] = $tag->id;
        }
        $videoAsset = $model->getVideoAsset();

        $relation = Relation::find()
            ->where([
                'relationable_id' => $model->id,
                'relationable_type' => Relation::RELATIONABLE_VIDEO,
            ])->one();
        $matchModel = new \common\models\MatchSearch();
        $matchesList = [];
        if(!isset($relation)) {
            $relation = new Relation();
            $relation->relationable_type = Relation::RELATIONABLE_VIDEO;
        }
        if(!isset($relation->match)) {
            $matches = $matchModel::find()
                ->orderBy(['date' => SORT_DESC])
                ->limit(10)
                ->all();
            foreach ($matches as $match) {
                $matchDate = date('d.m.Y', strtotime($match->date));
                $matchesList[$match->id] = $match->name.' ('.$matchDate.')';
            }
        } else {
            $matchModel->championship_id = $relation->match->championship_id;
            $matchModel->league_id = $relation->match->league_id;
            $matchModel->season_id = $relation->match->season_id;
            $matchModel->command_home_id = $relation->match->command_home_id;
            $matchModel->command_guest_id = $relation->match->command_guest_id;
            $matchDate = date('d.m.Y', strtotime($relation->match->date));
            $matchesList[$relation->match->id] = $relation->match->name.' ('.$matchDate.')';
        }

        $model->title = html_entity_decode($model->title);
        $model->content = html_entity_decode($model->content);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // Set slug
            $model->slug = $model->genSlug($model->title);

            // Set image
            $uploadedFile = UploadedFile::getInstance($model, 'image');
            if($uploadedFile)
            {
                // Remove old assets
                foreach ($assets as $asset) {
                    $asset->delete();
                }

                // Save origionals 
                $asset = new Asset();
                $asset->assetable_type = Asset::ASSETABLE_VIDEO;
                $asset->assetable_id = $model->id;
                $asset->uploadedFile = $uploadedFile;
                $asset->saveAsset();

                // Save thumbnails 
                $imageID = $asset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_VIDEO);

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->assetable_type = Asset::ASSETABLE_VIDEO;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->saveAsset();
                }
            }

            // Save videofile
            $videoFile = UploadedFile::getInstance($model, 'video');
            if($videoFile)
            {
                // Remove old assets
                $videoAsset->delete();

                // Save origionals 
                $asset = new Asset();
                $asset->assetable_type = Asset::ASSETABLE_VIDEOFILE;
                $asset->assetable_id = $model->id;
                $asset->uploadedFile = $videoFile;
                $asset->saveVideoAsset();
            }

            $existingTags = [];
            // Remove tags
            foreach ($tags as $tag) {
                if(!is_array($model->tags) || !in_array($tag->id, $model->tags)) {
                    $model->removeTag($tag->id);
                } else $existingTags[] = $tag->id;
            }
            // Adding new tags
            if(is_array($model->tags))
            {
                foreach ($model->tags as $id) {
                    if(!in_array($id, $existingTags)) {
                        $model->addTag($id);
                    }
                }
            }

            $cached_tag_list = [];
            $newTags = $model->getTags();
            foreach ($newTags as $newTag) {
                $cached_tag_list[] = $newTag->name;
            }
            $model->cached_tag_list = implode(', ', $cached_tag_list);

            if(!isset($relation->relationable_id)) {
                $relation->relationable_id = $model->id;
                $relation->relationable_type = Relation::RELATIONABLE_VIDEO;
            }
            if($relation->load(Yii::$app->request->post()) && $model->validate()) {

                if($relation->parent_id != '' && is_array($relation->parent_id)) {
                    $relation->parent_id = $relation->parent_id[0];
                }
                if($relation->parent_id && is_numeric($relation->parent_id)) {
                    $relation->save();
                } elseif(isset($relation->id)) {
                    $relation->delete();
                }
            }

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'image' => $image,
                'videoAsset' => $videoAsset,
                'tags' => $tags,
                'relation' => $relation,
                'matchModel' => $matchModel,
                'matchesList' => $matchesList,
            ]);
        }
    }

    /**
     * Deletes an existing VideoPost model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the VideoPost model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VideoPost the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VideoPost::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
