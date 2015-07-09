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
        return $this->render('view', [
            'model' => $model,
            'images' => $images,
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
            
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
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

        $model->title = html_entity_decode($model->title);
        $model->description = html_entity_decode($model->description);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            // Set slug
            $model->slug = $model->genSlug($model->title);

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

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            return $this->render('update', [
                'model' => $model,
                'images' => $assets,
                'tags' => $tags,
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
        $post = $this->findModel($id);

        Tagging::deleteAll(['taggable_type' => Tagging::TAGGABLE_ALBUM ,'taggable_id' => $id]);
        $assets = Asset::find()->where(['assetable_type' => Asset::ASSETABLE_ALBUM ,'assetable_id' => $id])->all();
        foreach ($assets as $asset) {
            $asset->delete();
        }
        $post->delete();

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
