<?php


namespace common\models;

use yii\db\ActiveRecord;;
use yii\web\ForbiddenHttpException;

/**
 * This is the model class for table "{{%apple}}".
 *
 * @property int $id
 * @property string $color
 * @property float $size
 * @property int $status
 * @property int $fall_at
 * @property int $created_at
 * @property int $updated_at
 *
 */
class Apple extends ActiveRecord
{

    const STATUS_HANGING = 0;
    const STATUS_FALL = 1;
    const STATUS_ROTTEN = 2;
    const STATUS_EATEN = 3;

    private $colors = ['green','red','yellow'];

    public function rules()
    {
        return [
            [['color'], 'string'],
            [['size'], 'double'],
            [['status'], 'integer'],
            [['fall_at', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }


    /**
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function fallToGround(){
        if($this->status != self::STATUS_HANGING){
            throw new ForbiddenHttpException("Яблоко уже на земле");
        }
        $this->status = self::STATUS_FALL;
        $this->fall_at = time();
        if(!$this->save()){
            throw new ForbiddenHttpException( implode ( "<br />" , \yii\helpers\ArrayHelper::getColumn ( $this->errors , 0 , false )));
        }
        return  true;
    }

    /**
     * @param float $sizeEaten
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function eat($sizeEaten){
        $sizeEaten = $sizeEaten / 100;
        if($this->status == self::STATUS_HANGING){
            throw new ForbiddenHttpException("Съесть нельзя, яблоко на дереве");
        }elseif ($this->status == self::STATUS_ROTTEN) {
            throw new ForbiddenHttpException("Съесть нельзя, яблоко гнилое");
        }elseif ($this->status == self::STATUS_EATEN) {
            throw new ForbiddenHttpException("Съесть нельзя, яблоко уже съедено");
        }elseif($this->size < $sizeEaten){
            throw new ForbiddenHttpException("Съесть нельзя, вы пытаетесь съесть больше чем осталось");
        }else{
            $this->size = $this->size - $sizeEaten;
            try {
                $this->save();
            } catch (\Exception $ex) {
                throw new ForbiddenHttpException($ex->getMessage());
            }
        }

        return true;
    }


    /**
     * @param bool $count
     * @return bool
     * @throws \yii\db\Exception
     * @throws ForbiddenHttpException
     */
    public function generateApples($count = false){
        $count = $count ? $count : rand(2,10);
        $data = [];
        $x = 1;
        while ($x <= $count) {
            $data[] = [$this->colors[array_rand($this->colors)]];
            $x++;
        }
        $newData = \Yii::$app->db
            ->createCommand()
            ->batchInsert(Apple::tableName(), ['color'],$data);
        if(!$newData->execute()){
            throw new ForbiddenHttpException('Произошла ошибка при генерации яблок');
        }

        return true;
    }

    public static function checkAppleRotten(){
        self::updateAll(['status' => self::STATUS_ROTTEN],['and',['status' => self::STATUS_FALL], 'fall_at <= NOW() - INTERVAL 5 HOUR']);
    }

}