<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RegisterController implements the CRUD actions for User model.
 */
class RegisterController extends Controller
{

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new User(['scenario' => 'insert']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['success']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionSuccess()
    {
        return $this->render('success');
    }
}
