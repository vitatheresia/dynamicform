<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $cust_id
 * @property string $cust_no_invoice
 * @property string $cust_nama
 * @property int $cust_telp
 * @property string $cust_email
 *
 * @property DetailCustomer $detailCustomer
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cust_no_invoice', 'cust_nama', 'cust_telp', 'cust_email'], 'required'],
            [['cust_telp'], 'integer'],
            [['cust_no_invoice'], 'string', 'max' => 10],
            [['cust_nama'], 'string', 'max' => 50],
            [['cust_email'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cust_id' => 'Cust ID',
            'cust_no_invoice' => 'No Invoice',
            'cust_nama' => 'Nama',
            'cust_telp' => 'No. HP / Telp',
            'cust_email' => 'Email',
        ];
    }

    /**
     * Gets query for [[DetailCustomer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetailCustomer()
    {
        return $this->hasOne(DetailCustomer::className(), ['detcus_cust_id' => 'cust_id']);
    }
}
