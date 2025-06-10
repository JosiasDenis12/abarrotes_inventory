<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\Product;

class InventoryController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new \app\models\ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Estadísticas rápidas
        $total = \app\models\Product::find()->count();
        $inStock = \app\models\Product::find()->where(['>', 'stock', 0])->count();
        $lowStock = \app\models\Product::find()->where('stock <= min_stock AND stock > 0')->count();
        $outStock = \app\models\Product::find()->where(['<=', 'stock', 0])->count();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => $total,
            'inStock' => $inStock,
            'lowStock' => $lowStock,
            'outStock' => $outStock,
        ]);
    }
}