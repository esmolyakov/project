<?php

    namespace app\modules\managers\models\form;
    use app\models\Houses;
    use app\models\CharacteristicsHouse;
    use Yii;
    use yii\base\Model;
    use yii\widgets\ActiveForm;

class HouseForm extends Model
{
    private $_houses;
    private $_characteristicsHouse;

    public function rules()
    {
        return [
            [['Houses'], 'required'],
            [['CharacteristicsHouse'], 'safe'],
        ];
    }

    public function afterValidate()
    {
        if (!Model::validateMultiple($this->getAllModels())) {
            $this->addError(null);
        }
        parent::afterValidate();
    }

    /*
     * Создание новой записи
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        
        if (!$this->houses->save()) {
            $transaction->rollBack();
            return false;
        }
        if (!$this->saveCharacteristic()) {
            $transaction->rollBack();
            return false;
        }
        $transaction->commit();
        return true;
    }
    
    
    public function saveCharacteristic() 
    {
        $keep = [];
        foreach ($this->characteristicsHouse as $characteristic) {
            $characteristic->characteristics_house_id = $this->houses->houses_id;
            if (!$characteristic->save(false)) {
                return false;
            }
            $keep[] = $characteristic->characteristics_id;
        }
        $query = CharacteristicsHouse::find()->andWhere(['characteristics_house_id' => $this->houses->houses_id]);
        if ($keep) {
            $query->andWhere(['not in', 'characteristics_id', $keep]);
        }
        foreach ($query->all() as $characteristic) {
            $characteristic->delete();
        }        
        return true;
    }

    public function getHouses()
    {
        return $this->_houses;
    }

    public function setHouses($house)
    {
        if ($house instanceof Houses) {
            $this->_houses = $house;
        } else if (is_array($house)) {
            $this->_houses->setAttributes($house);
        }
    }

    public function getCharacteristicsHouse()
    {
        if ($this->_characteristicsHouse === null) {
            $this->_characteristicsHouse = $this->houses->isNewRecord ? [] : $this->houses->characteristic;
        }
        return $this->_characteristicsHouse;
    }

    private function getCharacteristicHouse($key)
    {
        $characteristic = $key && strpos($key, 'new') === false ? CharacteristicsHouse::findOne($key) : false;
        if (!$characteristic) {
            $characteristic = new CharacteristicsHouse();
            $characteristic->loadDefaultValues();
        }
        return $characteristic;
    }

    public function setCharacteristicsHouse($characteristics)
    {
        unset($characteristics['__id__']); // remove the hidden "new Parcel" row
        $this->_characteristicsHouse = [];
        foreach ($characteristics as $key => $characteristic) {
            if (is_array($characteristic)) {
                $this->_characteristicsHouse[$key] = $this->getCharacteristicHouse($key);
                $this->_characteristicsHouse[$key]->setAttributes($characteristic);
            } elseif ($characteristic instanceof CharacteristicsHouse) {
                $this->_characteristicsHouse[$characteristic->characteristics_id] = $characteristic;
            }
        }
    }
    
    public function errorSummary($form)
    {
        $errorLists = [];
        foreach ($this->getAllModels() as $id => $model) {
            $errorList = $form->errorSummary($model, [
              'header' => '<p>Пожалуйста, исправьте ошибки в заполнении формы <b>' . $id . '</b></p>',
            ]);
            $errorList = str_replace('<li></li>', '', $errorList); // remove the empty error
            $errorLists[] = $errorList;
        }
        return implode('', $errorLists);
    }

    private function getAllModels()
    {
        $models = [
            'Дом' => $this->houses,
        ];
        foreach ($this->characteristicsHouse as $id => $characteristic) {
            $models['Характеристика ' . $id] = $this->characteristicsHouse[$id];
        }
        return $models;
    }
}