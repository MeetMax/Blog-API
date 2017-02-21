<?php

namespace frontend\controllers;

use common\models\User;
use Yii;
use app\models\Comment;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;


/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
{

    /**
     *根据文章id返回所有评论
     */
    public function actionIndex()
    {
        $model=new Comment();
        $article_id=Yii::$app->request->get('article_id');
        $data=$model->find()
            ->where(['article_id'=>$article_id])
            ->with(['user'=>function($query){
                $query->select(['username','id']);
            }])
            ->asArray()->all();
        return $data;
    }

    /**
     * 添加评论
     */
    public function actionCreate()
    {
        $model = new Comment();
        $token=Yii::$app->request->headers->get('token');
        $userModel=User::findOne(['access_token'=>$token]);
        if($userModel::accessTokenIsValid($token)){
            if ($model->load(Yii::$app->request->post(),'')) {
                $model->created_at=time();
                $model->user_id=$userModel->id;
                if($model->save()){
                    return '评论成功！';
                }
            } else {
              return $model->errors;
            }
        }

    }
    /**
     * 管理员权限删除评论
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userModel=new User();
        $token=Yii::$app->request->headers->get('token');
        if($userModel::accessTokenIsValid($token)){
            $role=$userModel::findOne(['access_token'=>$token])->role;
        }else{
            $role=0;
        }
        if($role==1) {
            if ($this->findModel($id)->delete()) {
                return '删除评论成功！';
            } else {
                return $model->errors;
            }
        }else{
            return '对不起，您没有权限！';
        }
    }


    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
