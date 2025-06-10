<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $code
 * @property double $price
 * @property double $cost_price
 * @property int $category_id
 * @property int $supplier_id
 * @property int $stock
 * @property int $min_stock
 * @property string $unit
 * @property string $location
 * @property string $image
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Category $category
 * @property Supplier $supplier
 * @property SaleItem[] $saleItems
 */
class Product extends \yii\db\ActiveRecord
{
    const LOW_STOCK_THRESHOLD = 10; // Cambia este valor según tu necesidad

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code', 'price', 'category_id', 'supplier_id', 'stock', 'min_stock'], 'required'],
            [['description'], 'string'],
            [['price', 'cost_price'], 'number'],
            [['category_id', 'supplier_id', 'stock', 'min_stock'], 'integer'],
            [['name', 'code', 'unit', 'location', 'image'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplier_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'code' => 'Código',
            'price' => 'Precio de Venta',
            'cost_price' => 'Precio de Costo',
            'category_id' => 'Categoría',
            'supplier_id' => 'Proveedor',
            'stock' => 'Existencias',
            'min_stock' => 'Stock Mínimo',
            'unit' => 'Unidad',
            'location' => 'Ubicación',
            'image' => 'Imagen',
            'created_at' => 'Fecha de Creación',
            'updated_at' => 'Última Actualización',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    /**
     * Gets query for [[SaleItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSaleItems()
    {
        return $this->hasMany(SaleItem::className(), ['product_id' => 'id']);
    }

    /**
     * Check if product is low in stock
     *
     * @return boolean
     */
    public function isLowStock()
    {
        return $this->stock <= $this->min_stock;
    }
}
