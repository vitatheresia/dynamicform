<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use common\models\DetailCustomer;
use common\models\OrderDetailPaketFoto;
use common\models\OrderDetailPaketUpgrade;
use common\models\OrderDetailTema;
use common\models\DetailServiceSpk;
use common\models\TemaFoto;
use common\models\TemaFotoSearch;
use common\models\DetailCustomerSearch;
use common\models\Model;
use common\models\Transaksi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * DetailCustomerController implements the CRUD actions for DetailCustomer model.
 */
class DetailCustomerController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all DetailCustomer models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DetailCustomerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DetailCustomer model.
     * @param int $detcus_id Detcus ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($detcus_id)
    {
        $model = $this->findModel($detcus_id);
        $modelsOrderDetailPaketFoto = $model->detcus_cust_id;
        $modelsOrderDetailPaketUpgrade = $model->detcus_cust_id;

        return $this->render('view', [
            'model' => $model, 
            'modelsOrderDetailPaketFoto' => $modelsOrderDetailPaketFoto,
            'modelsOrderDetailPaketUpgrade' => $modelOrderDetailPaketUpgrade
        ]);
    }

    /**
     * Creates a new DetailCustomer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new DetailCustomer();
        $modelTrans = new Transaksi();
        $modelsOrderDetailPaketFoto = [new OrderDetailPaketFoto];
        $modelsOrderDetailPaketUpgrade = [new OrderDetailPaketUpgrade];
       
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                //ubah format tanggal sebelum disave ke database
               $model->detcus_tanggal = \Yii::$app->formatter->asDate($model->detcus_tanggal, 'yyyy-MM-dd');

               //form tabularinput
                $modelsOrderDetailPaketFoto = Model::createMultiple(OrderDetailPaketFoto::classname());
                $modelsOrderDetailPaketUpgrade = Model::createMultiple(OrderDetailPaketUpgrade::classname());
                Model::loadMultiple($modelsOrderDetailPaketFoto, Yii::$app->request->post());
                Model::loadMultiple($modelsOrderDetailPaketUpgrade, Yii::$app->request->post());

                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ArrayHelpepr::merge(
                        ActiveForm::validateMultiple($modelsOrderDetailPaketFoto),
                        ActiveForm::validateMultiple($modelsOrderDetailPaketUpgrade),
                        ActiveForm::validateMultiple($model)
                    );
                }

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelsOrderDetailPaketFoto) && $valid;
                $valid = Model::validateMultiple($modelsOrderDetailPaketUpgrade) && $valid;
                $valid = $modelTrans->validate() && $valid;
                
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($modelsOrderDetailPaketFoto as $modelOrderDetailPaketFoto) 
                            {
                                $modelOrderDetailPaketFoto->odpf_detcus_id = $model->detcus_id;
                                if (! ($flag = $modelOrderDetailPaketFoto->save(false)))  {
                                        $transaction->rollBack();
                                        break;
                                    }
                            }
                            foreach ($modelsOrderDetailPaketUpgrade as $modelOrderDetailPaketUpgrade) 
                            {
                                $modelOrderDetailPaketUpgrade->odpu_detcus_id = $model->detcus_id;
                                if (! ($flag = $modelOrderDetailPaketUpgrade->save(false)))  {
                                $transaction->rollBack();
                                break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return $this->redirect(['view', 'detcus_id' => $model->detcus_id]);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }   else {
                    Yii::$app->session->setFlash('error', 'gagal melakukan validasi pada model');
                } 
             }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'modelsOrderDetailPaketFoto' => (empty($modelsOrderDetailPaketFoto)) ? [new OrderDetailPaketFoto] : $modelsOrderDetailPaketFoto,
            'modelsOrderDetailPaketUpgrade' => (empty($modelsOrderDetailPaketUpgrade)) ? [new OrderDetailPaketFoto] : $modelsOrderDetailPaketUpgrade,
            'modelTrans' => $modelTrans,
        ]);
    }

    /**
     * Updates an existing DetailCustomer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $detcus_id Detcus ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($detcus_id)
    {
        $model = $this->findModel($detcus_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'detcus_id' => $model->detcus_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DetailCustomer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $detcus_id Detcus ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($detcus_id)
    {
        $this->findModel($detcus_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DetailCustomer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $detcus_id Detcus ID
     * @return DetailCustomer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($detcus_id)
    {
        if (($model = DetailCustomer::findOne(['detcus_id' => $detcus_id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPototema()
    {
        $modelODTema = TemaFoto::find()->all();
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('pototema', [
                'medias' => $modelODTema,
            ]);
        }else {
           return $this->render('pototema', [
               'medias' => $modelODTema
           ]);
        } 
    }

    public function actionCetakspk() {
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('cetakspk');
        
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '@page{size: 500mm 200mm}', 
             // set mPDF properties on the fly
            //'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            // 'methods' => [ 
            //     'SetHeader'=>['Krajee Report Header'], 
            //     'SetFooter'=>['{PAGENO}'],
            // ]
        ]);

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        /**
         * We set more options as showing in "vendors/kartik-v/yii2-mpdf/src/Pdf.php/Pdf/options" method
         * What we do, we merge the options array to the existing one.
         */
        $pdf->options = array_merge($pdf->options , [
            'fontDir' => array_merge($fontDirs, [ Yii::$app->basePath . '/themes/assets/fonts/custom']),  // make sure you refer the right physical path
            'fontdata' => array_merge($fontData, [
                'thsarabun' => [
                    'R' => 'THSarabunNew.ttf',
                    'I' => 'THSarabunNew Italic.ttf',
                    'B' => 'THSarabunNew Bold.ttf',
                ]
            ])
        ]);
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionPreview($detcus_id)
    {
        return $this->renderAjax('preview', [
            'model' => $this->findModel($detcus_id),
        ]);
    }
}
