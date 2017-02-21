<?php
namespace frontend\controllers;
use common\models\LoginForm;
use \common\models\User;
use frontend\models\SignupForm;
use yii\rest\Controller;
use \yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\filters\Cors;

class UserController extends Controller
{
    public $modelClass='common\models\User';
    
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
   public function behaviors()
    {
        return ArrayHelper::merge (parent::behaviors(), [
            'authenticator' => [
                'class' =>yii\filters\auth\HttpBearerAuth::className(),
                //'tokenParam'=>'token',
                'optional' => [
                    'login',
                    'sign-up',
                    'view',
                    'verify-token'
                ]
            ],
            [
                'class' => Cors::className(),
            ]
        ]);
    }
   /* public function actions()
    {
        $actions=parent::actions();
        //注销系统自带的实现方法
        unset($actions['index'],$actions['update'],$actions['create'],$actions['delete'],$actions['view']);
        return $actions;
    }*/

  /*  public function actionUpdate($id){
        $model=$this->findModel($id);
        if($model->load(Yii::$app->request->getBodyParams(),'')&&$model->save()){
            return $model;
        }
        return array_values($model->getFirstErrors())[0];
    }*/


    /**
     *根据用户id查询用户信息
     */
    public function actionView($id){
        $model=User::findOne(['id'=>$id]);
        return[
            'username'=>$model->username,
            'email'=>$model->email
        ];
    }

    /**
     * 验证token和原密码，修改密码
     */
    public function actionUpdate($id){
        $authorization=Yii::$app->request->getHeaders()['authorization'];
        $token=substr($authorization,(int)strpos($authorization,' ')+1);
        $model=User::findOne(['id'=>$id,'access_token'=>$token]);
        if($model!==null){
            $password=Yii::$app->request->getBodyParam('password');
            $newPassword=Yii::$app->request->getBodyParam('newPassword');
            if($model->validatePassword($password)){
               $model->setPassword($newPassword);
                if($model->save(false)){
                    return '修改密码成功！';
                }
            }else{
                return $model->errors;
            }
        }
    }

    protected function findModel($id){
        if(($model=User::findOne($id))!==null)
        {
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 根据token寻找对应Model
     */
    protected function findModelByToken($token){
        if(($model=User::findOne(['access_token'=>$token]))!==null){
            return $model;
        }else{
            return false;
        }
    }

    /**
     * 登录
     */
    public function actionLogin(){
        $model=new LoginForm();
        $model->setAttributes(Yii::$app->request->post());
        if($user=$model->login()){
            if($user instanceof IdentityInterface){
                return $user->access_token;
            }else{
                return false;
            }
        }else{
           return false;
        }
    }
    /**
     * 注册
     */
    public function actionSignUp(){
        $model=new SignupForm();
        $model->setAttributes(Yii::$app->request->post());
        if($user=$model->signup()){
            return $user->access_token;
        }else{
            $user->errors;
        }
    }
    /**
     * 验证token是否有效
     */
    public function actionVerifyToken(){
        $token=Yii::$app->request->post('token');
        $user=$this->findModelByToken($token);
        if($user!==false){
            if($user::accessTokenIsValid($token)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }
}