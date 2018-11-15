<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\models\Rates;
    use app\models\Units;
    use app\models\CategoryServices;
    use app\modules\managers\controllers\AppManagersController;

/**
 * Тарифы
 */
class SettingsController extends AppManagersController {
    
    /*
     * Тарифы, главная страница
     */
    public function actionIndex() {
        
        $model = new Rates();
        
        $service_categories = CategoryServices::getCategoryNameArray();
        $units = Units::getUnitsArray();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('rate-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referre);
            }
            Yii::$app->session->setFlash('rate-admin', [
                'success' => true,
                'message' => 'Новый тариф успешно добавлен',
            ]);
        }
        
        return $this->render('index', [
            'model' => $model,
            'service_categories' => $service_categories,
            'units' => $units,
        ]);
    }
    
}
