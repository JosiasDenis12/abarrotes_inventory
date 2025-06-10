<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * ProductSearch represents the model behind the search form of `app\models\Product`.
 */
class ProductSearch extends Model
{
    public $id;
    public $name;
    public $code;
    public $category_id;
    public $supplier_id;
    public $price;
    public $cost_price;
    public $stock;
    public $min_stock;
    public $description;
    public $unit;
    public $location;
    public $image;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'supplier_id', 'stock', 'min_stock'], 'integer'],
            [['name', 'code', 'description', 'unit', 'location', 'image'], 'safe'],
            [['price', 'cost_price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find();

        // add conditions that should always apply here
        $query->joinWith(['category', 'supplier']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'product.id' => $this->id,
            'product.price' => $this->price,
            'product.cost_price' => $this->cost_price,
            'product.category_id' => $this->category_id,
            'product.supplier_id' => $this->supplier_id,
            'product.stock' => $this->stock,
            'product.min_stock' => $this->min_stock,
        ]);

        $query->andFilterWhere(['like', 'product.name', $this->name])
            ->andFilterWhere(['like', 'product.code', $this->code])
            ->andFilterWhere(['like', 'product.description', $this->description])
            ->andFilterWhere(['like', 'product.unit', $this->unit])
            ->andFilterWhere(['like', 'product.location', $this->location]);

        return $dataProvider;
    }
}