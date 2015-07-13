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

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
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

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'image' => $image,
                'tags' => $tags,
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
        $model = $this->findModel($id);

        Tagging::deleteAll(['taggable_type' => Tagging::TAGGABLE_VIDEO ,'taggable_id' => $id]);
        $assets = Asset::find()
            ->where(['assetable_type' => Asset::ASSETABLE_VIDEO ,'assetable_id' => $id])
            ->orWhere(['assetable_type' => Asset::ASSETABLE_VIDEOFILE ,'assetable_id' => $id])
            ->all();
        foreach ($assets as $asset) {
            $asset->delete();
        }
        $model->delete();

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
