<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_detail_paket_foto".
 *
 * @property int $odpf_id
 * @property int $odpf_detcus_id
 * @property int $odpf_paket_id
 * @property int $odpf_qty
 */
class OrderDetailPaketFoto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_detail_paket_foto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['odpf_paket_id', 'odpf_qty'], 'required'],
            [['odpf_detcus_id', 'odpf_paket_id', 'odpf_qty', 'odpf_harga',], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'odpf_id' => 'Odpf ID',
            'odpf_detcus_id' => 'Odpf Detcus ID',
            'odpf_paket_id' => 'Paket Foto',
            'odpf_qty' => 'Qty',
            'odpf_harga' => 'Harga',
        ];
    }

    public function getPaketFoto()
    {
        return $this->hasOne(PaketFoto::className(), ['paket_id' => 'odpf_paket_id']);
    }
}
