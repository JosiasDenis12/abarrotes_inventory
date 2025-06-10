<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Product;
use app\models\Sale;
use app\models\Supplier;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use yii\web\BadRequestHttpException;
use yii\base\InvalidArgumentException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'dashboard'],
                'rules' => [
                    [
                        'actions' => ['logout', 'dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        
        return $this->redirect(['site/dashboard']);
    }

    /**
     * Dashboard page.
     *
     * @return string
     */
    public function actionDashboard()
    {
        $lowStockProducts = Product::find()
            ->where('stock <= min_stock')
            ->limit(5)
            ->all();
            
        $recentSales = Sale::find()
            ->orderBy(['id' => SORT_DESC])
            ->limit(5)
            ->all();
            
        $totalProducts = Product::find()->count();
        $totalSuppliers = Supplier::find()->count();
        $totalSales = Sale::find()->count();
        $salesAmount = Sale::find()->sum('total_amount');
        
        return $this->render('dashboard', [
            'lowStockProducts' => $lowStockProducts,
            'recentSales' => $recentSales,
            'totalProducts' => $totalProducts,
            'totalSuppliers' => $totalSuppliers,
            'totalSales' => $totalSales,
            'salesAmount' => $salesAmount,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Guest access action.
     *
     * @return Response
     */
    public function actionGuest()
    {
        // Setup guest access session
        Yii::$app->session->set('guest_mode', true);
        return $this->redirect(['site/dashboard']);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signup action.
     *
     * @return Response|string
     */
    public function actionSignup()
    {
        $model = new \app\models\SignupForm();
        if ($model->load(Yii::$app->request->post()) && $user = $model->signup()) {
            Yii::$app->session->setFlash('success', '¡Registro exitoso! Ahora puedes iniciar sesión.');
            return $this->redirect(['login']);
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    
    /**
     * Export action.
     *
     * @return Response
     */
    public function actionExport()
    {
        return $this->render('export');
    }

    /**
     * Export sales data as CSV.
     *
     * @return void
     */
    public function actionExportCsv()
    {
        $sales = \app\models\Sale::find()->all();

        $filename = 'sales_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        // Línea de título
        fputcsv($output, ['Inventario VENTA Inventosmart Abarrotes Tendejon San Francisco']);
        // Encabezados
        fputcsv($output, [
            'ID Venta',
            'Folio',
            'Fecha',
            'Total Venta',
            'Método de Pago',
            'Monto Pagado',
            'Cambio'
        ]);

        foreach ($sales as $sale) {
            fputcsv($output, [
                $sale->id,
                $sale->invoice_number,
                $sale->date,
                $sale->total_amount,
                $sale->payment_method,
                $sale->amount_paid,
                $sale->amount_paid - $sale->total_amount,
            ]);
        }
        fclose($output);
        exit;
    }

    /**
     * Export sales data as PDF.
     *
     * @return void
     */
    public function actionExportPdf()
    {
        $sales = \app\models\Sale::find()->all();

        // Ruta absoluta al logo
        $logoPath = \Yii::getAlias('@webroot/img/logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $mimeType = mime_content_type($logoPath); // Detecta el tipo de imagen automáticamente
            $logoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($logoData);
        }

        $html = "<table width='100%'><tr>
            <td width='20%' align='left'>";
        if ($logoBase64) {
            $html .= "<img src='{$logoBase64}' style='height:60px;'>";
        }
        $html .= "</td>
            <td width='80%' align='center'>
                <h2>INVENTARIOS VENTA Inventosmart Abarrotes Tendejon San Francisco</h2>
            </td>
        </tr></table>";

        $html .= "<table border='1' cellpadding='5' cellspacing='0' width='100%'>";
        $html .= "<thead>
            <tr>
                <th>ID Venta</th>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Total Venta</th>
                <th>Método de Pago</th>
                <th>Monto Pagado</th>
                <th>Cambio</th>
            </tr>
        </thead><tbody>";

        foreach ($sales as $sale) {
            $html .= "<tr>
                <td>{$sale->id}</td>
                <td>{$sale->invoice_number}</td>
                <td>{$sale->date}</td>
                <td>{$sale->total_amount}</td>
                <td>{$sale->payment_method}</td>
                <td>{$sale->amount_paid}</td>
                <td>" . ($sale->amount_paid - $sale->total_amount) . "</td>
            </tr>";
        }
        $html .= "</tbody></table>";

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('ventas_' . date('Ymd_His') . '.pdf', 'D');
        exit;
    }

    /**
     * Export productos data as CSV.
     *
     * @return void
     */
    public function actionExportProductosCsv()
    {
        $productos = \app\models\Product::find()->all();

        $filename = 'productos_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, ['INVENTARIO PRODUCTOS Inventosmart Abarrotes Tendejon San Francisco']);
        fputcsv($output, [
            'ID',
            'Nombre',
            'Código',
            'Descripción',
            'Precio',
            'Stock',
            'Stock Mínimo',
            'Precio de Costo',
            'Ubicación'
        ]);

        foreach ($productos as $producto) {
            fputcsv($output, [
                $producto->id,
                $producto->name,
                $producto->code,
                $producto->description,
                $producto->price,
                $producto->stock,
                $producto->min_stock,
                $producto->cost_price,
                $producto->location
            ]);
        }
        fclose($output);
        exit;
    }

    /**
     * Export proveedores data as CSV.
     *
     * @return void
     */
    public function actionExportProveedoresCsv()
    {
        $proveedores = \app\models\Supplier::find()->all();

        $filename = 'proveedores_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, ['INVENTARIO PROVEEDORES Inventosmart Abarrotes Tendejon San Francisco']);
        fputcsv($output, [
            'ID',
            'Nombre',
            'Teléfono',
            'Correo',
            'Dirección'
        ]);

        foreach ($proveedores as $proveedor) {
            fputcsv($output, [
                $proveedor->id,
                $proveedor->name,
                $proveedor->phone,
                $proveedor->email,
                $proveedor->address
            ]);
        }
        fclose($output);
        exit;
    }

    /**
     * Export productos data as PDF.
     *
     * @return void
     */
    public function actionExportProductosPdf()
    {
        $productos = \app\models\Product::find()->all();

        $html = "<h2 style='text-align:center;'>INVENTARIO PRODUCTOS Inventosmart Abarrotes Tendejon San Francisco</h2>";
        $html .= "<table border='1' cellpadding='5' cellspacing='0' width='100%'>";
        $html .= "<thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Stock Mínimo</th>
                <th>Precio de Costo</th>
                <th>Ubicación</th>
            </tr>
        </thead><tbody>";

        foreach ($productos as $producto) {
            $html .= "<tr>
                <td>{$producto->id}</td>
                <td>{$producto->name}</td>
                <td>{$producto->code}</td>
                <td>{$producto->description}</td>
                <td>{$producto->price}</td>
                <td>{$producto->stock}</td>
                <td>{$producto->min_stock}</td>
                <td>{$producto->cost_price}</td>
                <td>{$producto->location}</td>
            </tr>";
        }
        $html .= "</tbody></table>";

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('productos_' . date('Ymd_His') . '.pdf', 'D');
        exit;
    }

    /**
     * Export proveedores data as PDF.
     *
     * @return void
     */
    public function actionExportProveedoresPdf()
    {
        $proveedores = \app\models\Supplier::find()->all();

        $html = "<h2 style='text-align:center;'>INVENTARIO PROVEEDORES Inventosmart Abarrotes Tendejon San Francisco</h2>";
        $html .= "<table border='1' cellpadding='5' cellspacing='0' width='100%'>";
        $html .= "<thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Dirección</th>
            </tr>
        </thead><tbody>";

        foreach ($proveedores as $proveedor) {
            $html .= "<tr>
                <td>{$proveedor->id}</td>
                <td>{$proveedor->name}</td>
                <td>{$proveedor->phone}</td>
                <td>{$proveedor->email}</td>
                <td>{$proveedor->address}</td>
            </tr>";
        }
        $html .= "</tbody></table>";

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('proveedores_' . date('Ymd_His') . '.pdf', 'D');
        exit;
    }

    /**
     * Request password reset.
     *
     * @return Response|string
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Revisa tu correo electrónico para más instrucciones.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo enviar el correo. ¿Está registrado el email?');
            }
        }
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Reset password.
     *
     * @param string $token
     * @return Response|string
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Nueva contraseña guardada.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('delete_product')) {
            throw new \yii\web\ForbiddenHttpException('No tienes permiso para eliminar productos.');
        }
        // ... código para eliminar ...
    }
}
