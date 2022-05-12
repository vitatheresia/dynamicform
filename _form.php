<?php


use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use common\models\Customer;
use common\models\PaketFoto;
use common\models\PaketUpgrade;
use yii\web\View;
use yii\web\JqueryAsset;
use kidzen\dynamicform\DynamicFormWidget;

?>

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2><small>Silahkan Input Jadwal Pemesanan</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="customer-form" data-parsley-validate class="form-horizontal form-label-left">
                <?php $form = ActiveForm::begin([
                    'options' => ['enctype'=>'multipart/form-data'],
                    'id' => 'dynamic-form', 
                   
                ]); ?>
                <div class="form-group col-sm-6">
                    <?php 
                        $customer = ArrayHelper::map(Customer::find()->all(), 'cust_id', function($model, $default){
                            return $model["cust_no_invoice"];
                        });

                    ?>
                    <?= $form->field($model, 'detcus_cust_id')->widget(Select2::classname(), [
                        'data' => $customer,
                        'language' => 'en',
                        'options' => [
                            //'value' => (!$model->isNewRecord ? $selected : ''), 
                            'placeholder' => 'Input No Invoice',
                            'id' => 'noinvoice'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]); 
                    ?>
                    
                </div>
                <div class="form-group col-sm-6">
                    <?= $form->field($model, 'detcus_tanggal')->widget(DatePicker::classname(), [
                        'name' => 'detcus_tanggal',
                        'options' => [
                            'autocomplete' => 'off',
                            'id' => 'tanggal'
                        ],
                        'type' => DatePicker::TYPE_INPUT,
                        'value' => 'detcus_tanggal',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'todayHighlight' => true,
                            ]
                        ]); 
                    ?>
                </div>

                <div class="form-group">
                    <label for="inputNamaCustomer">Nama Customer</label>
                    <input type="text" class="form-control" id="namacustomer" placeholder="Nama Customer" readonly>
                </div>

                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items', // required: css class selector
                    'widgetItem' => '.item', // required: css class
                    'limit' => 4, // the maximum times, an element can be cloned (default 999)
                    'min' => 1, // 0 or 1 (default 1)
                    'insertButton' => '.add-item', // css class
                    'deleteButton' => '.remove-item', // css class
                    'model' => $modelsOrderDetailPaketFoto[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'odpf_paket_id',
                        'odpf_qty',
                        'odpf_harga'
                    ],
                ]); ?>
                <table class="table">
                    <thead class="thead-dark">
                        <tr class="text-center">
                            <th class="text-center">Paket Foto</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">
                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="container-items">
                        <?php foreach ($modelsOrderDetailPaketFoto  as $i => $modelOrderDetailPaketFoto): ?>
                        <tr class="item">
                            <td class="text-center">
                                <?php
                                    // necessary for update action.
                                    if (! $modelOrderDetailPaketFoto->isNewRecord) {
                                        echo Html::activeHiddenInput($modelOrderDetailPaketFoto, "[{$i}]odpf_id");
                                    }
                                ?>
                                <?= $form->field($modelOrderDetailPaketFoto, "[{$i}]odpf_paket_id")->label(false)->widget(Select2::classname(), [
                                    'options' => [
                                        'class' => 'odpf_paket_id',
                                        'placeholder' => 'Pilih Nama Paket'
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'data' => ArrayHelper::toArray(PaketFoto::find()->all(), [
                                            PaketFoto::class => [
                                                'id' => function (PaketFoto $paketfoto) {
                                                    return $paketfoto->paket_id;
                                                },
                                                'text' => function (PaketFoto $paketfoto) {
                                                    return $paketfoto->paket_nama;
                                                },
                                                'paket_nama',
                                                'paket_harga',
                                            ],
                                            ]), 
                                        ],
                                    ]); 
                                ?>
                            </td>
                            <td class="text-center">
                                <?= $form->field($modelOrderDetailPaketFoto, "[{$i}]odpf_qty", ['addClass' => 'form-control odpf_qty'])->label(false)->textInput(['maxlength' => true, 'type' => 'number', 'autocomplete' => 'off']) ?>
                            </td>
                            <td class="text-center">
                               <?= $form->field($modelOrderDetailPaketFoto, "[{$i}]odpf_harga", ['addClass' => 'form-control odpf_harga'])->label(false)->textInput(['disabled' => true, 'maxlength' => true, 'type' => 'number']) ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tbody>
                        <tr>
                            <td colspan="2" class="text-center"> Sub Total Paket</td>
                            <td colspan="2"><input type="number" id="subtotal" class="form-control" readonly="readonly"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center"> Down Payment</td>
                            <td colspan="2">
                                <?= $form->field($model, 'detcus_pay', ['addClass' => 'form-control detcus_pay'])->label(false)->textInput(['class'=>'form-control']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center">Sisa Pelunasan</td>
                            <td colspan="2"><input type="number" id="sisa_pelunasan" class="form-control" readonly="readonly"></td>
                        </tr>
                    </tbody>
                </table>
                <?php DynamicFormWidget::end(); ?>
                
                <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper_upgrade',
                        'widgetBody' => '.container-items-upgrade', // required: css class selector
                        'widgetItem' => '.item-upgrade', // required: css class
                        'limit' => 4, // the maximum times, an element can be cloned (default 999)
                        'min' => 0, // 0 or 1 (default 1)
                        'insertButton' => '.add-item-upgrade', // css class
                        'deleteButton' => '.remove-item-upgrade', // css class
                        'model' => $modelsOrderDetailPaketUpgrade[0],
                        'formId' => 'dynamic-form',
                        'formFields' => [
                            'odpu_upg_id',
                            'odpu_qty',
                        ],
                    ]); ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Paket Upgrade</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">
                                <button type="button" class="add-item-upgrade btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="container-items-upgrade">
                        <?php foreach ($modelsOrderDetailPaketUpgrade as $x => $modelOrderDetailPaketUpgrade): ?>
                        <tr class="item-upgrade">
                            <td class="text-center">
                                <?php
                                    // necessary for update action.
                                    if (! $modelOrderDetailPaketUpgrade->isNewRecord) {
                                        echo Html::activeHiddenInput($modelOrderDetailPaketUpgrade, "[{$x}]odpu_id");
                                    }
                                ?>
                                <?= $form->field($modelOrderDetailPaketUpgrade, "[{$x}]odpu_upg_id")->label(false)->widget(Select2::classname(), [
                                    'options' => [
                                        'class' => 'odpu_upg_id',
                                        'placeholder' => 'Paket Upgrade'
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'data' => ArrayHelper::toArray(PaketUpgrade::find()->all(), [
                                            PaketUpgrade::class => [
                                                'id' => function (PaketUpgrade $paketupgrade) {
                                                    return $paketupgrade->upg_id;
                                                },
                                                'text' => function (PaketUpgrade $paketupgrade) {
                                                    return $paketupgrade->upg_nama;
                                                },
                                                'upg_nama',
                                                'upg_harga',
                                            ],
                                            ]), 
                                        ],
                                    ]); 
                                    ?>
                            </td>
                            <td class="text-center">
                                <?= $form->field($modelOrderDetailPaketUpgrade, "[{$x}]odpu_qty", ['addClass' => 'form-control odpu_qty'])->label(false)->textInput(['maxlength' => true, 'type' => 'number']) ?>
                            </td>
                            <td class="text-center">
                                <?= $form->field($modelOrderDetailPaketUpgrade, "[{$x}]odpu_harga", ['addClass' => 'form-control odpu_harga'])->label(false)->textInput(['disabled' => true, 'maxlength' => true, 'type' => 'number']) ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="remove-item-upgrade btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                    <tbody>
                        <tr>
                            <td colspan="2" class="text-center"> Sub Total Upgrade</td>
                            <td colspan="2"><input type="number" id="subtotalupgrade" class="form-control" readonly="readonly"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center">Grand Total</td>
                            <td colspan="2"><input type="number" id="grandtotal" class="form-control" readonly="readonly"></td>
                        </tr>
                    </tbody>
                </table>
                <?php DynamicFormWidget::end(); ?>
                <div class="col-md-6 col-md-offset-6">
                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-lg']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs('
        function initSelect2DropStyle(a,b,c){
            initS2Loading(a,b,c);
        }
        function initSelect2Loading(a,b){
            initS2Loading(a,b);
        }
    ',
    yii\web\View::POS_HEAD
);

$this->registerJsFile(
    Yii::$app->request->baseUrl .'/js/detail-customer/_form.js',
    ['depends' => [JqueryAsset::class], View::POS_READY]
);


$script = <<<JS
 $('#noinvoice').change(function(){
     var cust_id = $(this).val();
     $.get('index.php?r=customer/get-customer-detail', { cust_id : cust_id }, function(data) {
         $('#namacustomer').attr('value', data.customer.cust_nama);
     })
 });

JS;
$this->registerJs($script);

