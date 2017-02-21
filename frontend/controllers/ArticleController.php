<?php

namespace frontend\controllers;

use app\models\Comment;
use app\models\Praise;
use Codeception\Lib\Generator\Helper;
use common\models\User;
use Symfony\Component\Console\Command\HelpCommand;
use Yii;
use app\models\Article;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\helpers\Json;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

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
     * 返回文章列表
     */
    public function actionIndex()
    {
        $model=new Article();
        $condition=[];
        $page=1;
        //判断分类id是否为空
        if(!empty(Yii::$app->request->get('id'))){
            $cat_id=Yii::$app->request->get('id');
            $condition=['cat_id'=>$cat_id];
        }
        //判断page是否为空
        if(!empty(Yii::$app->request->get('page'))){
            $page=Yii::$app->request->get('page');
        }
        //判断热门文章是否为空
        if(!empty(Yii::$app->request->get('by'))){
            $hot=Yii::$app->request->get('by');
            if($hot=='hot'){
                $data=$model->find()->with('category')->orderBy(['visit'=>SORT_DESC,'updated_at'=>SORT_DESC])->where($condition)->offset(($page-1)*10)->asArray()->limit(10)->all();
            }else{
                $data=$model->find()->with('category')->where($condition)->offset(($page-1)*25)->asArray()->limit(25)->all();
            }
        }else{
            $data=$model->find()->with('category')->where($condition)->offset(($page-1)*25)->asArray()->limit(25)->all();
        }
        foreach ($data as $k=>$v){
            $data[$k]['date']=date("Y-m-d H:i",$v['updated_at']);
            $data[$k]['like']=Praise::find()->where(['article_id'=>$v['id']])->count();
            $data[$k]['content']=mb_substr($v['content'],0,100,'utf-8').'……';
            $data[$k]['comment_count']=Comment::find()->where(['article_id'=>$v['id']])->count();
        }
        return $data;
    }
    public function actionOptions(){

    }
    /**
     * 根据id查找文章
     */
    public function actionView($id)
    {
        $model=new Article();
        $data=$model::find()->where(['id'=>$id])->asArray()->with([
            'user'=>function($query){
                $query->select(['username']);
            },'category'=>function($query){
                $query->select('name');
            }
        ])->one();
        $data['cat_name']=$data['category']['name'];
        $data['author']=$data['user']['username'];
        $token=Yii::$app->request->headers->get('token');
        $condition=['article_id'=>$id];
        $data['like_status']=false;
        if(!empty($token)){
            $userModel=User::findOne(['access_token'=>$token]);
            $condition=['user_id'=>$userModel->id,'article_id'=>$id];
            $data['like_status']=Praise::find()->where($condition)->exists();
        }
        $data['like']=Praise::find()->where($condition)->count();
        $data['comment_count']=Comment::find()->where(['article_id'=>$id])->count();
        return $data;
    }

    /**
     * 权限用户添加文章
     */
    public function actionCreate()
    {
        $model = new Article();
        $userModel=new User();
        $token=Yii::$app->request->headers->get('token');
        if($userModel::accessTokenIsValid($token)){
            $userModel=$userModel::findOne(['access_token'=>$token]);
            $role=$userModel->role;
        }else{
            $role=0;
        }
        if($role==1)
        {
            if ($model->load(Yii::$app->request->post(),'')) {
                $model->updated_at=time();
                $model->user_id=$userModel->id;
                if($model->save()){
                    return true;
                }else{
                    return false;
                }
            } else {
                return false;
            }
        }else
        {
            return false;
        }
    }
    /**
     * 权限用户修改文章
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
        if($role==1)
        {
            if ($model->load(Yii::$app->request->getBodyParams(),'')) {
                $model->updated_at=time();
                if($model->save()){
                    return true;
                }
            } else {
                return false;
            }
        }else
        {
            return false;
        }
    }

    /**
     * 权限用户，删除文章
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userModel=new User();
        $token=Yii::$app->request->headers->get('token');
        //验证token是否有效
        if($userModel::accessTokenIsValid($token)){
            $role=$userModel::findOne(['access_token'=>$token])->role;
        }else{
            $role=0;
        }
        if($role==1)
        {
            if ($this->findModel($id)->delete()) {
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
     * 根据分类搜索文章列表
     */
    public function actionArticleList(){
        $cat_id=Yii::$app->request->get('id');
        return Article::find()->where(['cat_id'=>$cat_id])->all();
    }
    
    /**
     * 获取热门文章列表
     */
    public function actionHot(){
        $data = Article::find()->orderBy(['visit' => SORT_DESC, 'updated_at' => SORT_DESC])->asArray()->select('id,title ')->limit(5)->all();
        foreach ($data as $k => $v) {
            $data[$k]['like'] = Praise::find()->where(['article_id' => $v['id']])->count();
            $data[$k]['comment_count'] = Comment::find()->where(['article_id' => $v['id']])->count();
        }
        return $data;
    }

    /**
     *根据ID寻找Model
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
