<?php

namespace app\controllers;

use Yii;
use app\models\Sale;
use app\models\SaleItem;
use app\models\SaleSearch;
use app\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * SaleController implements the CRUD actions for Sale model.
 */
class SaleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin', 'manager'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sale models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SaleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sale model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $saleItems = SaleItem::find()
            ->with('product') // Eager loading de la relación product
            ->where(['sale_id' => $id])
            ->all();
        
        Yii::error('Sale Items for sale ID ' . $id . ': ' . json_encode($saleItems));

        return $this->render('view', [
            'model' => $model,
            'saleItems' => $saleItems
        ]);
    }

    /**
     * Creates a new Sale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /**
 * Creates a new Sale model.
 * If creation is successful, the browser will be redirected to the 'view' page.
 * @return mixed
 */
public function actionCreate()
{
    $model = new Sale();
    $model->date = date('Y-m-d');
    $model->generateInvoiceNumber();
    
    // Get products for dropdown
    $products = Product::find()->all();

    if ($model->load(Yii::$app->request->post())) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->created_by = Yii::$app->user->id;
            
            // Asegurarse de que los valores numéricos sean correctos
            $model->total_amount = floatval($model->total_amount);
            $model->amount_paid = floatval($model->amount_paid);
            $model->tax_amount = 0; // O calcular según tus necesidades
            $model->discount_amount = 0; // O calcular según tus necesidades
            
            if (!$model->save()) {
                Yii::error('Error al guardar la venta: ' . json_encode($model->errors));
                throw new \Exception('Error al guardar la venta: ' . json_encode($model->errors));
            }
            
            // Save sale items
            $productIds = Yii::$app->request->post('product_id', []);
            $quantities = Yii::$app->request->post('quantity', []);
            $prices = Yii::$app->request->post('price', []);
            
            $total = 0;
            foreach ($productIds as $i => $productId) {
                if (!empty($productId) && !empty($quantities[$i]) && !empty($prices[$i])) {
                    $saleItem = new SaleItem();
                    $saleItem->sale_id = $model->id;
                    $saleItem->product_id = $productId;
                    $saleItem->quantity = intval($quantities[$i]);
                    $saleItem->unit_price = floatval($prices[$i]);
                    $saleItem->discount = 0;
                    
                    if (!$saleItem->save()) {
                        Yii::error('Error al guardar el detalle de venta: ' . json_encode($saleItem->errors));
                        throw new \Exception('Error al guardar el detalle de venta: ' . json_encode($saleItem->errors));
                    }
                    
                    // Update product stock
                    $product = Product::findOne($productId);
                    if ($product) {
                        $product->stock -= $saleItem->quantity;
                        if ($product->stock < 0) {
                            throw new \Exception('Stock insuficiente para el producto: ' . $product->name);
                        }
    
                        if (!$product->save()) {
                            throw new \Exception('Error al actualizar el stock del producto: ' . implode(', ', $product->getErrorSummary(true)));
                        }
                        
                        // Notificación de bajo stock
                        if ($product->stock <= \app\models\Product::LOW_STOCK_THRESHOLD) {
                            Yii::$app->mailer->compose()
                                ->setTo(['josiascaballero29@gmail.com', 'admin2@tucorreo.com']) // Cambia por los correos de tus admins
                                ->setSubject('Alerta de Bajo Stock - Inventosmart')
                                ->setHtmlBody("
                                    <h2>¡Atención!</h2>
                                    <p>El producto <b>{$product->name}</b> tiene un stock bajo en <b>Inventosmart</b>.</p>
                                    <ul>
                                        <li><b>ID del producto:</b> {$product->id}</li>
                                        <li><b>Nombre:</b> {$product->name}</li>
                                        <li><b>Stock actual:</b> {$product->stock}</li>
                                        <li><b>Umbral mínimo:</b> " . \app\models\Product::LOW_STOCK_THRESHOLD . "</li>
                                    </ul>
                                    <p>Por favor, realiza el pedido de reposición lo antes posible.</p>
                                ")
                                ->send();
                        }
                    }
                    
                    $total += $saleItem->total_price;
                }
            }
            
            // Update sale total if needed
            if (abs($model->total_amount - $total) > 0.01) {
                $model->total_amount = $total;
                if (!$model->save()) {
                    Yii::error('Error al actualizar el total de la venta: ' . json_encode($model->errors));
                    throw new \Exception('Error al actualizar el total de la venta: ' . json_encode($model->errors));
                }
            }
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Venta registrada exitosamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::error('Error en la transacción de venta: ' . $e->getMessage());
        }
    }

    return $this->render('create', [
        'model' => $model,
        'products' => $products,
    ]);
}

/**
 * Search products for Select2
 * @return array
 */
public function actionProductSearch()
{
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
    $q = Yii::$app->request->get('q', '');
    
    $query = \app\models\Product::find()
        ->select(['id', 'name', 'code', 'price', 'stock'])
        ->where(['>', 'stock', 0]);
    
    if (!empty($q)) {
        $query->andWhere(['or', 
            ['like', 'name', $q],
            ['like', 'code', $q]
        ]);
    }
    
    $products = $query->limit(20)->all();
    
    $results = [];
    foreach ($products as $product) {
        $results[] = [
            'id' => $product->id,
            'text' => $product->name . ' (' . $product->code . ') - $' . number_format($product->price, 2) . ' - Stock: ' . $product->stock,
            'price' => $product->price,
            'stock' => $product->stock
        ];
    }
    
    return ['results' => $results];
}

/**
 * Get product info via AJAX
 */
public function actionGetProductInfo($id)
{
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
    $product = Product::findOne($id);
    if ($product) {
        return [
            'price' => $product->price,
            'stock' => $product->stock
        ];
    }
    return ['error' => 'Producto no encontrado'];
}

    /**
     * Generate sales report
     */
    public function actionReport()
    {
        $fromDate = Yii::$app->request->post('from_date', date('Y-m-01'));
        $toDate = Yii::$app->request->post('to_date', date('Y-m-d'));
        
        $sales = Sale::find()
            ->where(['between', 'date', $fromDate, $toDate])
            ->orderBy(['date' => SORT_DESC])
            ->all();
            
        // Get top selling products
        $topProducts = Yii::$app->db->createCommand("
            SELECT p.name, p.code, SUM(si.quantity) as total_sold, SUM(si.total_price) as total_revenue
            FROM sale_item si
            JOIN product p ON si.product_id = p.id
            JOIN sale s ON si.sale_id = s.id
            WHERE s.date BETWEEN :fromDate AND :toDate
            GROUP BY p.id
            ORDER BY total_sold DESC
            LIMIT 10
        ", [':fromDate' => $fromDate, ':toDate' => $toDate])->queryAll();
        
        return $this->render('report', [
            'sales' => $sales,
            'topProducts' => $topProducts,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'totalSales' => count($sales),
            'totalAmount' => array_sum(array_column($sales, 'total_amount')),
        ]);
    }

    /**
     * Print receipt for a sale
     */
    public function actionReceipt($id)
    {
        $sale = $this->findModel($id);
        $saleItems = SaleItem::find()->where(['sale_id' => $id])->all();
        
        return $this->renderPartial('receipt', [
            'sale' => $sale,
            'saleItems' => $saleItems,
        ]);
    }

    /**
     * Deletes an existing Sale model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);
            
            // Restaurar el stock de los productos
            $saleItems = SaleItem::find()->where(['sale_id' => $id])->all();
            foreach ($saleItems as $item) {
                $product = Product::findOne($item->product_id);
                if ($product) {
                    $product->stock += $item->quantity;
                    if (!$product->save()) {
                        throw new \Exception('Error al restaurar el stock del producto: ' . implode(', ', $product->getErrorSummary(true)));
                    }
                }
            }
            
            // Eliminar los items de la venta
            SaleItem::deleteAll(['sale_id' => $id]);
            
            // Eliminar la venta
            if (!$model->delete()) {
                throw new \Exception('Error al eliminar la venta');
            }
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Venta eliminada exitosamente.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::error('Error al eliminar la venta: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sale::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}