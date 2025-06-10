<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $sale app\models\Sale */
/* @var $saleItems app\models\SaleItem[] */

$this->title = 'Ticket de Venta #' . $sale->invoice_number;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= Html::encode($this->title) ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            max-width: 80mm;
            margin: 0 auto;
            padding: 5mm;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            text-align: left;
            padding: 3px 0;
        }
        .table th:last-child, .table td:last-child {
            text-align: right;
        }
        .totals {
            margin-top: 10px;
            text-align: right;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .dashed {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">ABARROTES TENDEJOSN SAN FRANCISCO</div>
        <div>Av. Principal #123, Col. Centro</div>
        <div>Tel: (123) 456-7890</div>
        <div>RFC: ABTS123456ABC</div>
    </div>
    
    <div class="dashed"></div>
    
    <div class="info">
        <div><strong>TICKET DE VENTA: <?= $sale->invoice_number ?></strong></div>
        <div>FECHA: <?= Yii::$app->formatter->asDate($sale->date) ?></div>
        <div>HORA: <?= Yii::$app->formatter->asTime(date('Y-m-d H:i:s')) ?></div>
        <div>ATENDIÓ: <?= $sale->createdBy ? $sale->createdBy->username : 'Sistema' ?></div>
    </div>
    
    <div class="dashed"></div>
    
    <table class="table">
        <thead>
            <tr>
                <th>CANT</th>
                <th>DESCRIPCIÓN</th>
                <th>P.UNIT</th>
                <th>IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($saleItems as $item): ?>
            <tr>
                <td><?= $item->quantity ?></td>
                <td><?= $item->product->name ?></td>
                <td><?= number_format($item->unit_price, 2) ?></td>
                <td><?= number_format($item->total_price, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="dashed"></div>
    
    <div class="totals">
        <div>SUBTOTAL: $<?= number_format($sale->total_amount - $sale->tax_amount, 2) ?></div>
        <div>IVA: $<?= number_format($sale->tax_amount, 2) ?></div>
        <div>DESCUENTO: $<?= number_format($sale->discount_amount, 2) ?></div>
        <div><strong>TOTAL: $<?= number_format($sale->total_amount, 2) ?></strong></div>
        <div>EFECTIVO: $<?= number_format($sale->amount_paid, 2) ?></div>
        <div>CAMBIO: $<?= number_format($sale->amount_paid - $sale->total_amount, 2) ?></div>
    </div>
    
    <div class="footer">
        <div>FORMA DE PAGO: <?= $sale->payment_method ?></div>
        <div class="dashed"></div>
        <div>¡GRACIAS POR SU COMPRA!</div>
        <div>VUELVA PRONTO</div>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 500);
        };
    </script>
</body>
</html>
