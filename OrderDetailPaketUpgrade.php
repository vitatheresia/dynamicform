<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_detail_paket_upgrade".
 *
 * @property int $odpu_id
 * @property int $odpu_detcus_id
 * @property int $odpu_upg_id
 * @property int $odpu_qty
 */
class OrderDetailPaketUpgrade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_detail_paket_upgrade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['odpu_detcus_id', 'odpu_upg_id', 'odpu_qty', 'odpu_harga'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'odpu_id' => 'Odpu ID',
            'odpu_detcus_id' => 'No. Invoice',
            'odpu_upg_id' => 'Paket Upgrade',
            'odpu_qty' => 'Qty',
            'odpu_harga' => 'Harga',
        ];
    }
    public function getDetailCustomer()
    {
        return $this->hasOne(DetailCustomer::className(), ['detcus_id' => 'odpu_detcus_id']);
    }
}
