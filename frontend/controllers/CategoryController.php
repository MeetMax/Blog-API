<?php

namespace frontend\controllers;

use app\models\Article;
use common\models\User;
use Yii;
use app\models\Category;
use yii\data\ActiveDataProvider;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{

    /**
     * 允许跨域请求
     */
    public function behaviors()
    {
        return ArrayHelper::merge([
            [
                'class' => Cors::className(),
            ],
        ], parent::behaviors());
    }
    /**
     * 返回所有分类和该分类下的文章数量
     */
    public function actionIndex()
    {
        $model=new Category();
        $data=$model->find()->asArray()->all();
        foreach ($data as $k=>$v){
            $data[$k]['article_count']=Article::find()->where(['cat_id'=>$v['id']])->count();
        }
        return $data;
    }
    public function actionOptions()
    {
        $model=new Category();
        $data=$model->find()->asArray()->all();
        foreach ($data as $k=>$v){
            $data[$k]['article_count']=Article::find()->where(['cat_id'=>$v['id']])->count();
        }
        return $data;
    }

    /**
     * 根据id获得分类
     */
    public function actionView($id)
    {
       return $this->findModel($id);
    }

    /**
     * 权限用户创建新的分类
     */
    public function actionCreate()
    {
        $model = new Category();
        $userModel=new User();
        $token=Yii::$app->request->headers->get('token');
        if($userModel::accessTokenIsValid($token)){
           $role=$userModel::findOne(['access_token'=>$token])->role;
        }else{
            $role=0;
        }
        if($role==1){
            if ($model->load(Yii::$app->request->post(),'') && $model->save()) {
                return true;
            } else {
                return false;
            }
        }else
        {
            return false;
        }

    }

    /**
     * 权限用户更新分类
     */
    public function actionUpdate($id)
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
            if ($model->load(Yii::$app->request->getBodyParams(),'') && $model->save()) {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 权限用户删除分类
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
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 根据id寻找model
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
