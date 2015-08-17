<?php

namespace common\modules\user\controllers;

use common\models\CommentSearch;
use Yii;
use common\modules\user\models\User;
use common\modules\user\models\UserKey;
use common\modules\user\models\UserAuth;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\db\Query;

use yii\web\UploadedFile;
use common\models\Asset;

/**
 * AdminController implements the CRUD actions for User model.
 */
class AdminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        // check for admin permission (`tbl_role.can_admin`)
        // note: check for Yii::$app->user first because it doesn't exist in console commands (throws exception)
        $this->enableCsrfValidation = false;
        if (!empty(Yii::$app->user) && (!Yii::$app->user->can("admin") || !Yii::$app->user->can("changeUser"))) {
            throw new ForbiddenHttpException('Вы не можете выполнить это действие.');
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
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
     * List all User models
     *
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var \common\modules\user\models\search\UserSearch $searchModel */
        $searchModel = Yii::$app->getModule("user")->model("UserSearch");
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * List all User models
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionComments($id)
    {
        $user = $this->findModel($id);
        $searchModel = new CommentSearch();
        $queryParams = Yii::$app->request->getQueryParams();
        $queryParams['CommentSearch']['user_id'] = $user->id;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('comments', [
            'user' => $user,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Display a single User model
     *
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $user = $this->findModel($id);
        $avatar = $user->getAsset(Asset::THUMBNAIL_CONTENT);
        return $this->render('view', [
            'user' => $user,
            'avatar' => $avatar,
        ]);
    }

    /**
     * Create a new User model. If creation is successful, the browser will
     * be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var \common\modules\user\models\User $user */
        /** @var \common\modules\user\models\Profile $profile */

        $user = Yii::$app->getModule("user")->model("User");
        $user->setScenario("admin");
        $profile = Yii::$app->getModule("user")->model("Profile");

        $post = Yii::$app->request->post();
        if ($user->load($post) && $user->validate() && $profile->load($post) && $profile->validate()) {

            $uploadedFile = UploadedFile::getInstance($user,'avatar');

            $user->save(false);
            $profile->setUser($user->id)->save(false);

            // If image was uploaded
            if(!empty($uploadedFile))
            {
                // Save origionals 
                $originalAsset = new Asset();
                $originalAsset->assetable_type = Asset::ASSETABLE_USER;
                $originalAsset->assetable_id = $user->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_USER);

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_USER;
                    $asset->assetable_id = $user->id;
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->cropData = $user->cropData;
                    $asset->saveCroppedAsset();
                }
            }

            return $this->redirect(['view', 'id' => $user->id]);
        }

        // render
        return $this->render('create', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Update an existing User model. If update is successful, the browser
     * will be redirected to the 'view' page.
     *
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // set up user and profile
        $user = $this->findModel($id);
        $user->setScenario("admin");
        $profile = $user->profile;
        $avatar = $user->getAsset(Asset::THUMBNAIL_CONTENT);

        // load post data and validate
        $post = Yii::$app->request->post();
        if ($user->load($post) && $user->validate() && $profile->load($post) && $profile->validate())
        {
            $uploadedFile = UploadedFile::getInstance($user,'avatar');

            // If image was uploaded
            if(!empty($uploadedFile))
            {
                // Save origionals 
                $originalAsset = $user->getAsset();
                if(!isset($originalAsset->id)) {
                    $originalAsset = new Asset();
                }
                $originalAsset->assetable_type = Asset::ASSETABLE_USER;
                $originalAsset->assetable_id = $user->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_USER);

                foreach ($thumbnails as $thumbnail) {
                    $asset = $user->getAsset($thumbnail);
                    if(!isset($asset->id)) {
                        $asset = new Asset();
                    }
                    $asset->assetable_type = Asset::ASSETABLE_USER;
                    $asset->assetable_id = $user->id;
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->cropData = $user->cropData;
                    $asset->saveCroppedAsset();
                }
            }

            $user->save(false);
            $profile->setUser($user->id)->save(false);
            return $this->redirect(['view', 'id' => $user->id]);
        }

        // render
        return $this->render('update', [
            'user' => $user,
            'avatar' => $avatar,
            'profile' => $profile,
        ]);
    }

    /**
     * Delete all users, who have status - pending.
     */
    public function actionDeletePendingUsers()
    {
        User::deleteAll(['status' => User::STATUS_UNCONFIRMED_EMAIL]);
        return $this->redirect(['index']);
    }

    /**
     * Delete an existing User model. If deletion is successful, the browser
     * will be redirected to the 'index' page.
     *
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // delete profile and userkeys first to handle foreign key constraint
        $user = $this->findModel($id);
        // Sidash
        if($user->id == 1) return $this->redirect(['index']);
        $profile = $user->profile;
        UserKey::deleteAll(['user_id' => $user->id]);
        UserAuth::deleteAll(['user_id' => $user->id]);
        $profile->delete();
        $user->delete();
        $assets = Asset::getAssets($user->id, Asset::ASSETABLE_USER, null);
        foreach ($assets as $asset) {
            $asset->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Find the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var \common\modules\user\models\User $user */
        $user = Yii::$app->getModule("user")->model("User");
        if (($user = $user::findOne($id)) !== null) {
            return $user;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Display list of all users in json format
     *
     * @param string $search - username
     * @return mixed
     */
    public function actionUserList($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $query = new Query;
            $query->select('id, username AS text')
                ->from(User::tableName())
                ->where(['like', 'username', $search])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => $this->findModel($id)->username];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo Json::encode($out);
    }
}
