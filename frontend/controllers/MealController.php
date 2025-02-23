<?php

namespace frontend\controllers;

use common\models\Meal;
use common\models\MealForm;
use common\models\search\MealSearch;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * MealController implements the CRUD actions for Meal model.
 */
class MealController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Meal models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MealSearch();

        return $this->render('index', [
            'dataProvider' =>  $searchModel->search($this->request->queryParams),
        ]);
    }

    public function actionUpload()
    {
        $model = new MealForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->picture = UploadedFile::getInstance($model, 'picture');
            if ($model->upload()) {
                $id = \Yii::$app->gemini->mealInquiry($model);
                return Yii::$app->response->redirect(['meal/success', 'id' => $id]);
            }
        }

        return $this->render('upload', [
                'model' => $model,
        ]);
    }

    public function actionSuccess($id)
    {
        $model = $this->findModel($id);
        $sum = Meal::find()
            ->select([
                'SUM(calories) AS calories',
                'SUM(protein) AS protein',
                'SUM(fat) AS fat',
                'SUM(carbohydrates) AS carbohydrates',
                'SUM(fiber) AS fiber'
            ])
            ->where(['user_id' => Yii::$app->user->id, 'date' => $model->date])
            ->asArray()
            ->one();
        $sum = array_merge(['calories' => 0, 'protein' => 0, 'fat' => 0, 'carbohydrates' => 0, 'fiber' => 0], $sum);

        $today = new DateTime();
        $modelDate = new DateTime($model->date); // @todo should be a date object
        $diff = $today->diff($modelDate);

        $sum['title'] = match ($diff->days) {
            0 => 'Today So Far',
            1 => 'Yesterday\'s Sum',
            default => $modelDate->format('M jS'),
        };

        return $this->render('success', ['model' => $model, 'sum' => $sum]);
    }

    /**
     * Displays a single Meal model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Meal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Meal();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Meal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Meal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Meal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Meal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MealSearch::findOne(['id' => $id, 'user_id' => Yii::$app->user->id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
