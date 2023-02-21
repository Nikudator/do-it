<?php

namespace app\modules\post\controllers;

use Yii;
use app\modules\post\models\Post;
use app\modules\admin\controllers\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * DefaultController implements the CRUD actions for Post model.
 */
class DefaultController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!\Yii::$app->user->can($action->id)) { //если общего доступа на экшен нет, то проверяем частные случаи доступа
                if ($action->id === 'update') {
                    $_author = $this->findModel(\Yii::$app->request->get('id'))->author_id;
                   if (\Yii::$app->user->can('updateOwnPost', ['author_id' => $_author])) { //проверяем на доступ "редактирования владельцем"
                        return true;
                    }
                }
                if ($action->id === 'delete') {
                    $_author = $this->findModel(\Yii::$app->request->get('id'))->author_id;
                    if (\Yii::$app->user->can('deleteOwnPost', ['author_id' => $_author])) { //проверяем на доступ "редактирования владельцем"
                        return true;
                    }
                }
                throw new ForbiddenHttpException('Отказано в доступе. Не достаточно прав.');
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $data = Post::getAll(10);

        return $this->render('index',[
            'posts'=>$data['posts'],
            'pagination'=>$data['pagination'],
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
