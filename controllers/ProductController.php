<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\Category;
use app\models\Supplier;
use app\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'low-stock'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('create_product');
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('update_product');
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('delete_product');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        // Get categories and suppliers for dropdown
        $categories = Category::find()->all();
        $suppliers = Supplier::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            // Handle image upload
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                $imageName = 'product_' . time() . '.' . $model->image->extension;
                $model->image->saveAs('uploads/products/' . $imageName);
                $model->image = $imageName;
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Producto creado exitosamente.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->image;

        // Get categories and suppliers for dropdown
        $categories = Category::find()->all();
        $suppliers = Supplier::find()->all();

       if ($model->load(Yii::$app->request->post())) {
            // Handle image upload
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                $imageName = 'product_' . time() . '.' . $model->image->extension;
                $model->image->saveAs('uploads/products/' . $imageName);
                $model->image = $imageName;
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Producto actualizado exitosamente.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'Producto eliminado exitosamente.');
        } catch (\yii\db\IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'No se puede eliminar el producto porque está relacionado con ventas.');
        }
        return $this->redirect(['index']);
    }

    /**
     * Lists all low stock Product models.
     * @return mixed
     */
    public function actionLowStock()
    {
        $query = Product::find()->where('stock <= min_stock');
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('low-stock', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
