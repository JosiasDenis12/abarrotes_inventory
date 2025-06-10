<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sale_item".
 *
 * @property int $id
 * @property int $sale_id
 * @property int $product_id
 * @property int $quantity
 * @property double $unit_price
 * @property double $total_price
 * @property double $discount
 *
 * @property Product $product
 * @property Sale $sale
 */
class SaleItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sale_id', 'product_id', 'quantity', 'unit_price'], 'required'],
            [['sale_id', 'product_id', 'quantity'], 'integer'],
            [['unit_price', 'total_price', 'discount'], 'number'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['sale_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sale::className(), 'targetAttribute' => ['sale_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sale_id' => 'Venta',
            'product_id' => 'Producto',
            'quantity' => 'Cantidad',
            'unit_price' => 'Precio Unitario',
            'total_price' => 'Precio Total',
            'discount' => 'Descuento',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Sale]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSale()
    {
        return $this->hasOne(Sale::className(), ['id' => 'sale_id']);
    }

    /**
     * Calculate total price based on quantity and unit price
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->total_price = ($this->quantity * $this->unit_price) - $this->discount;
            return true;
        }
        return false;
    }
}
