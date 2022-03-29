<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "detail_customer".
 *
 * @property int $detcus_id
 * @property int $detcus_cust_id
 * @property int $detcus_paket_id
 * @property int $detcus_frame_id
 * @property int $detcus_album_id
 * @property int $detcus_foto_id
 * @property int $detcus_harga
 * @property string $detcus_ket
 */
class DetailCustomer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detail_customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['detcus_cust_id', 'detcus_ket', 'detcus_tanggal', ], 'required'],
            // id detail tidak direquired
            [['detcus_ket', 'detcus_tanggal', ], 'required']
            [['detcus_cust_id', ], 'integer'],
            [['detcus_ket'], 'string', 'max' => 70],
            [['detcus_tanggal', 'detcus_cust_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'detcus_id' => 'Detcus ID',
            'detcus_cust_id' => 'No. Invoice',
            'detcus_ket' => 'Keterangan',
            'detcus_tanggal' => 'Tanggal'
        ];
    }
    public function getOrderDetailPaketFoto()
    {
        return $this->hasOne(OrderDetailPaketFoto::className(), ['odpf_detcus_id' => 'detcus_id']);
    }

    public function getOrderDetailPaketUpgrade()
    {
        return $this->hasOne(OrderDetailPaketUpgrade::className(), ['odpu_detcus_id' => 'detcus_id']);
    }

    public function getCustomer()
    {
            return $this->hasOne(Customer::className(), ['cust_id' => 'detcus_cust_id']);
    }

    public function getTransaksi()
    {
        return $this->hasOne(Transaksi::className(), ['trans_detcus_id' => 'detcus_id']);
    }

    public function getOrderDetailTema()
    {
        return $this->hasOne(OrderDetailTema::className(), ['odtema_detcus_id' => 'detcus_id']);
    }

    public function getDetailServiceSpk()
    {
        return $this->hasOne(DetailServiceSpk::className(), ['serv_detcus_id' => 'detcus_id']);
    }

    public function getFoto()
    {
        return $this->hasOne(Foto::className(), ['foto_detcus_id' => 'detcus_id']);
    }

}
