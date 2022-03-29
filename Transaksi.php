<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaksi".
 *
 * @property int $trans_id
 * @property int $trans_detcus_id
 * @property string $trans_status
 * @property int $trans_pay
 */
class Transaksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trans_detcus_id', 'trans_status', 'trans_pay'], 'required'],
            [['trans_detcus_id', 'trans_pay'], 'integer'],
            [['trans_status'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'trans_id' => 'Trans ID',
            'trans_detcus_id' => 'No Invoice',
            'trans_status' => 'Status Pembayaran',
            'trans_pay' => 'Harga di Bayar',
        ];
    }

    public function getDetailCustomer()
    {
        return $this->hasOne(DetailCustomoer::className(), ['trans_detcus_id' => 'detcus_id']);
    }
}
