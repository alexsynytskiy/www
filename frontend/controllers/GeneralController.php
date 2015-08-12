<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class GeneralController extends Controller {

    public $layout = 'main_general'; //layout goes here

    public function actionError()
    {
        $exception = Yii::$app->ErrorHandler->exception;
        if($exception) {
            $message = Yii::$app->ErrorHandler->convertExceptionToString($exception);
            // $message = $exception->getMessage();
            $code = $exception->statusCode;
            $parts = explode(':', $message);
            $errorName = $parts[0];
            $name = $errorName.' (#'.$code.')';
            $view = $code == 404 ? 'error404' : 'error';
            return $this->render($view, [
                'message' => $message,
                'code' => $code,
                'name' => $name,
            ]);
        }
    }
}
?>