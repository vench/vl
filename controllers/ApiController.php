<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Json;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;
use app\components\image\ImageData;
use app\models\ImageDb;

/**
 * Description of ApiController
 *
 * @author vench
 */
class ApiController extends Controller {

    /**
     * 
     * @return type
     */
    public function behaviors() {
        return [
            'negotiator' => [
                'class' => ContentNegotiator::className(),
                'only' => ['save', 'list', 'delete'], 
                'formats' => [                    
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['save', 'list', 'image', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ], 
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'save' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 
     * @param int $id
     */
    public function actionSave($id = null) {

        $image = !is_null($id) ?
                $this->getImageDb($id) : new \app\models\ImageDb();

        $request = Yii::$app->request;

        $width = $request->post('width');
        $height = $request->post('height');
        $strImage = $request->post('strImage');

        $image->attributes = [
            'width' => $width,
            'height' => $height,
            'data' => $strImage,
            'user_id' => \Yii::$app->user->id,
        ];

        $result = [
            'success' => false,
        ];

        if ($image->validate() && $image->save()) {
            $result['success'] = true;
            $result['id'] = $image->getPrimaryKey();
        } else {
            $result['errors'] = $image->getErrors();
        }

        echo Json::encode($result);
        Yii::$app->end();
    }

    /**
     * 
     */
    public function actionList() {
        $result = ImageDb::find()->andWhere('user_id=:user_id', [
                    ':user_id' => \Yii::$app->user->id,
                ])->asArray()->all();

        echo Json::encode($result);
        Yii::$app->end();
    }
    
    
    /**
     * 
     * @param int $id
     * @param string $type
     * @todo Тут конечно нужно сохранять промежуточные изображения и актуализировать их по мере изменений
     */
    public function actionImage($id, $type = 'png') {
        $model = $this->getImageDb($id);
        $image = ImageData::createByType($model, $type);
        $image->render();
        Yii::$app->end();
    }
    
    /**
     * 
     * @param int $id
     */
    public function actionDelete($id) {
        $model = $this->getImageDb($id);
        $model->delete();
        echo Json::encode(['status' => 'OK']);
        Yii::$app->end();
    }

    /**
     * 
     * @param int $id
     * @return \app\models\ImageDb
     * @throws \yii\web\HttpException
     */
    private function getImageDb($id) {
        $image = \app\models\ImageDb::find()->andWhere('id=:id ', [
                    ':id' => $id,
                ])->one();

        if (is_null($image)) {
            throw new \yii\web\HttpException(404);
        }

        if (\Yii::$app->user->id != $image->user_id) {
            throw new \yii\web\HttpException(403);
        }
        return $image;
    }

}
