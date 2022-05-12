<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DetailCustomer */

$this->title = 'Update Detail Customer: ' . $model->detcus_id;
$this->params['breadcrumbs'][] = ['label' => 'Detail Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->detcus_id, 'url' => ['view', 'detcus_id' => $model->detcus_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="detail-customer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsOrderDetailPaketFoto' => $modelsOrderDetailPaketFoto,
        //'modelsOrderDetailPaketUpgrade' => $modelsOrderDetailPaketUpgrade,
    ]) ?>

</div>
