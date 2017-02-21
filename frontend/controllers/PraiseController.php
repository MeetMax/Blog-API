<?php

namespace frontend\controllers;

use common\models\User;
use Yii;
use app\models\Praise;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PraiseController implements the CRUD actions for Praise model.
 */
class PraiseController extends Controller
{
    /**
     * 新增赞
     */
    public function actionCreate()
    {
        $model = new Praise();
        $token=Yii::$app->request->headers->get('token');
        if(User::accessTokenIsValid($token)){
            $userModel=User::findOne(['access_token'=>$token]);
            $article_id=Yii::$app->request->post('article_id');
            $data=$model->find()->where(['user_id'=>$userModel->id,'article_id'=>$article_id])->exists();
            if(!$data){
                $model->user_id=$userModel->id;
                $model->article_id=$article_id;
                if ($model->save()) {
                    return '成功！';
                } else {
                    return '失败！';
                }
            }else
            {
                return '已赞！';
            }
        }
    }
    /**
     * 取消赞
     */
    public function actionDelete($id)
    {
        $model = new Praise();
        $token=Yii::$app->request->headers->get('token');
        if(User::accessTokenIsValid($token)){
            $userModel=User::findOne(['access_token'=>$token]);
            $exist=$model->find()->where(['user_id'=>$userModel->id,'article_id'=>$id])->exists();
            if($exist){
                $praise=$model->findOne(['user_id'=>$userModel->id,'article_id'=>$id]);
                if ($praise->delete()) {
                    return '删除成功！';
                }
            }
        }
    }

    /**
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Praise::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
