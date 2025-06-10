<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "sale".
 *
 * @property int $id
 * @property string $invoice_number
 * @property string $date
 * @property int $customer_id
 * @property double $total_amount
 * @property double $tax_amount
 * @property double $discount_amount
 * @property double $amount_paid
 * @property string $payment_method
 * @property string $notes
 * @property int $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property Customer $customer
 * @property SaleItem[] $saleItems
 */
class Sale extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale';
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
            [['invoice_number', 'total_amount', 'amount_paid', 'payment_method'], 'required'],
            [['date'], 'safe'],
            [['customer_id', 'created_by'], 'integer'],
            [['total_amount', 'tax_amount', 'discount_amount', 'amount_paid'], 'number'],
            [['notes'], 'string'],
            [['invoice_number', 'payment_method'], 'string', 'max' => 255],
            [['invoice_number'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_number' => 'Número de Factura',
            'date' => 'Fecha',
            'customer_id' => 'Cliente',
            'total_amount' => 'Monto Total',
            'tax_amount' => 'Impuestos',
            'discount_amount' => 'Descuento',
            'amount_paid' => 'Monto Pagado',
            'payment_method' => 'Método de Pago',
            'notes' => 'Notas',
            'created_by' => 'Creado Por',
            'created_at' => 'Fecha de Creación',
            'updated_at' => 'Última Actualización',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[SaleItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSaleItems()
    {
        return $this->hasMany(SaleItem::className(), ['sale_id' => 'id']);
    }

    /**
     * Generate an invoice number
     */
    public function generateInvoiceNumber() 
    {
        $lastSale = self::find()->orderBy(['id' => SORT_DESC])->one();
        $nextId = $lastSale ? $lastSale->id + 1 : 1;
        $this->invoice_number = 'INV-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }
}
