<?php

namespace backend\controllers;

use Yii;
use common\models\Post;
use common\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;
use common\models\Asset;
use common\models\Source;
use common\models\Tagging;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
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
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->tags = [];

        // default values
        $model->allow_comment = 1;
        $model->is_public = 1;
        $model->comments_count = 0;
        $model->content_category_id = 1;
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post())) {

            // Set slug
            $model->slug = $model->genSlug($model->title);

            // Save source
            $source = new Source;
            $source->name = $model->source_title;
            $source->url = $model->source_url;
            if(!$source->modelExist()) {
                $source->save();
            }

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
            $model->image = UploadedFile::getInstance($model, 'image');
            if($model->image)
            {
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_POST);

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->thumbnail = $thumbnail;
                    $asset->assetable_type = Asset::ASSETABLE_POST;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $model->image;
                    $asset->saveAsset();
                }

                $asset = new Asset();
                $asset->assetable_type = Asset::ASSETABLE_POST;
                $asset->assetable_id = $model->id;
                $asset->uploadedFile = $model->image;
                $asset->saveAsset();
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
     * Updates an existing Post model.
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

        $model->title = html_entity_decode($model->title);
        $model->content = html_entity_decode($model->content);

        // var_dump($model);
        // die;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // Set slug
            $model->slug = $model->genSlug($model->title);

            // Set image
            $model->image = UploadedFile::getInstance($model, 'image');
            if($model->image)
            {
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_POST);
                $saveOrigin = false;
                foreach ($assets as $asset)
                {
                    if($asset->thumbnail && in_array($asset->thumbnail, $thumbnails))
                    {
                        $asset->uploadedFile = $model->image;
                        $asset->saveAsset();
                        $thumbnails = array_diff($thumbnails, [$asset->thumbnail]);
                    }
                    // Save original image
                    elseif (empty($asset->thumbnail))
                    {
                        $saveOrigin = true;
                        $asset->uploadedFile = $model->image;
                        $asset->saveAsset();
                    }
                }

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->thumbnail = $thumbnail;
                    $asset->assetable_type = Asset::ASSETABLE_POST;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $model->image;
                    $asset->saveAsset();
                }

                if(!$saveOrigin)
                {
                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_POST;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $model->image;
                    $asset->saveAsset();
                }
            }

            // Save source
            $source = new Source;
            $source->name = strip_tags($model->source_title);
            $source->url = strip_tags($model->source_url);
            if(!$source->modelExist()) {
                $source->save();
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
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $post = $this->findModel($id);

        Tagging::deleteAll(['taggable_type' => Tagging::TAGGABLE_POST ,'taggable_id' => $id]);
        $assets = Asset::find()->where(['assetable_type' => Asset::ASSETABLE_POST ,'assetable_id' => $id])->all();
        foreach ($assets as $asset) {
            $asset->delete();
        }
        $post->delete();

        // if(Yii::$app->request->referrer){
        //     return $this->redirect(Yii::$app->request->referrer);
        // }else{
            return $this->redirect(['index']);
        // }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
