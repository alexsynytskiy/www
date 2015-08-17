<?php

namespace backend\controllers;

use Yii;
use common\models\Album;
use common\models\AlbumSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;
use common\models\Asset;
use common\models\Tagging;
use common\models\Relation;
use common\models\Match;

/**
 * AlbumController implements the CRUD actions for Album model.
 */
class AlbumController extends Controller
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
     * Lists all Album models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AlbumSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Album model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $images = $model->getAssets(Asset::THUMBNAIL_BIG);
        $coverImage = $model->getFrontendAsset(Asset::THUMBNAIL_BIG);
        return $this->render('view', [
            'model' => $model,
            'images' => $images,
            'coverImage' => $coverImage,
        ]);
    }

    /**
     * Creates a new Album model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Album();
        $model->tags = [];

        // default values
        $model->is_public = 1;
        $model->user_id = Yii::$app->user->id;

        $matchModel = new \common\models\MatchSearch();
        $relation = new Relation();
        $relation->relationable_type = Relation::RELATIONABLE_ALBUM;
        $matches = $matchModel::find()
            ->orderBy(['date' => SORT_DESC])
            ->limit(10)
            ->all();
        $matchesList = [];
        foreach ($matches as $match) {
            $matchDate = date('d.m.Y', strtotime($match->date));
            $matchesList[$match->id] = $match->name.' ('.$matchDate.')';
        }

        if ($model->load(Yii::$app->request->post())) {

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

            // Save cover image
            $uploadedFile = UploadedFile::getInstance($model, 'coverImage');
            if(!empty($uploadedFile))
            {
                // Save origionals 
                $originalAsset = new Asset();
                $originalAsset->assetable_type = Asset::ASSETABLE_ALBUM_COVER;
                $originalAsset->assetable_id = $model->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_ALBUM_COVER);

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_ALBUM_COVER;
                    $asset->assetable_id = $model->id;
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->saveAsset();
                }
            }

            // Save images
            $model->images = UploadedFile::getInstances($model, 'images');
            if($model->images)
            {
                foreach ($model->images as $image)
                {
                    // Save origionals 
                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_ALBUM;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $image;
                    $asset->saveAsset();

                    // Save thumbnails 
                    $imageID = $asset->id;
                    $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_ALBUM);

                    foreach ($thumbnails as $thumbnail) {
                        $asset = new Asset();
                        $asset->parent_id = $imageID;
                        $asset->thumbnail = $thumbnail;
                        $asset->assetable_type = Asset::ASSETABLE_ALBUM;
                        $asset->assetable_id = $model->id;
                        $asset->uploadedFile = $image;
                        $asset->saveAsset();
                    }
                }
            }

            $relation->relationable_id = $model->id;
            $relation->relationable_type = Relation::RELATIONABLE_ALBUM;
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
     * Updates an existing Album model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $tags = $model->getTags();
        $coverImage = $model->getFrontendAsset(Asset::THUMBNAIL_BIG);
        $allAssets = $model->getAssets();
        $assets = [];
        foreach ($allAssets as $asset) {
            if($asset->thumbnail == Asset::THUMBNAIL_BIG) {
                $assets[] = $asset;
            }
        }
        $assetKeys = [];
        foreach ($assets as $asset) {
            $assetKeys[] = $asset->id;
        }
        $model->imagesData = implode(';', $assetKeys);
        $model->tags = [];
        foreach ($tags as $tag) {
            $model->tags[] = $tag->id;
        }

        $relation = Relation::find()
            ->where([
                'relationable_id' => $model->id,
                'relationable_type' => Relation::RELATIONABLE_ALBUM,
            ])->one();
        $matchModel = new \common\models\MatchSearch();
        $matchesList = [];
        if(!isset($relation)) {
            $relation = new Relation();
            $relation->relationable_type = Relation::RELATIONABLE_ALBUM;
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
        $model->description = html_entity_decode($model->description);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            // Set slug
            $model->slug = $model->genSlug($model->title);

            // Save cover image
            $uploadedFile = UploadedFile::getInstance($model, 'coverImage');
            if(!empty($uploadedFile))
            {
                // Save origionals 
                $originalAsset = $model->getCoverImageAsset();
                if(!isset($originalAsset->id)) {
                    $originalAsset = new Asset();
                }
                $originalAsset->assetable_type = Asset::ASSETABLE_ALBUM_COVER;
                $originalAsset->assetable_id = $model->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_ALBUM_COVER);

                foreach ($thumbnails as $thumbnail) {
                    $asset = $model->getCoverImageAsset($thumbnail);
                    if(!isset($asset->id)) {
                        $asset = new Asset();
                    }
                    $asset->assetable_type = Asset::ASSETABLE_ALBUM_COVER;
                    $asset->assetable_id = $model->id;
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->saveAsset();
                }
            }

            // Remove selected images
            $currentAssetKeys = explode(';', $model->imagesData);
            if(count($currentAssetKeys) > 0)
            {
                foreach ($assets as $asset) {
                    if(!in_array($asset->id, $currentAssetKeys))
                    {
                        $imageID = $asset->parent_id;
                        foreach ($allAssets as $allAssetModel) {
                            if($allAssetModel->parent_id  == $imageID || $allAssetModel->id == $imageID) {
                                $allAssetModel->delete();
                            }
                        }
                    }
                }
            }
            
            // Remove not existing images
            $imageIDs = [];
            foreach($allAssets as $asset)
            {
                $imageID = $asset->parent_id;
                if(!in_array($imageID, $imageIDs) && !file_exists($asset->getFilePath()))
                {
                    $imageIDs[] = $imageID;
                    foreach ($allAssets as $allAssetModel) {
                        if($allAssetModel->parent_id  == $imageID || $allAssetModel->id == $imageID) {
                            $allAssetModel->delete();
                        }
                    }
                }   
            }

            // Save images
            $uploadedFiles = UploadedFile::getInstances($model, 'images');
            if($uploadedFiles)
            {
                foreach ($uploadedFiles as $image)
                {
                    // Save origionals 
                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_ALBUM;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $image;
                    $asset->saveAsset();

                    // Save thumbnails 
                    $imageID = $asset->id;
                    $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_ALBUM);

                    foreach ($thumbnails as $thumbnail) {
                        $asset = new Asset();
                        $asset->parent_id = $imageID;
                        $asset->thumbnail = $thumbnail;
                        $asset->assetable_type = Asset::ASSETABLE_ALBUM;
                        $asset->assetable_id = $model->id;
                        $asset->uploadedFile = $image;
                        $asset->saveAsset();
                    }
                }
            }

            $existingTags = [];
            // Remove tags
            foreach ($tags as $tag) {
                if(!in_array($tag->id, $model->tags)) {
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
            foreach ($newTags as $newTag)
            {
                $cached_tag_list[] = $newTag->name;
            }
            $model->cached_tag_list = implode(', ', $cached_tag_list);

            if(!isset($relation->relationable_id)) {
                $relation->relationable_id = $model->id;
                $relation->relationable_type = Relation::RELATIONABLE_ALBUM;
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
        }
        else
        {
            return $this->render('update', [
                'model' => $model,
                'images' => $assets,
                'coverImage' => $coverImage,
                'tags' => $tags,
                'relation' => $relation,
                'matchModel' => $matchModel,
                'matchesList' => $matchesList,
            ]);
        }
    }

    /**
     * Deletes an existing Album model.
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
     * @return mixed
     */
    public function actionImageDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [];
    }

    /**
     * Finds the Album model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Album the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Album::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
