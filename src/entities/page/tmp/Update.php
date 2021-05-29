<?php
namespace fl\cms\entities;

use fl\cms\base\api\FlRecord;


/**
 * This is the model class for API request "get_person_details".
 * @property string $login
 * @author Anatoliy Smirnov Suter <asmirnov@freelemur.com>
 */
class GetPersonDetails extends FlRecord
{
    public const ACTION_NAME = 'get_person_details';
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','login'], 'required'],
            [['user_id'], 'integer', 'min' => 1, 'max' => 999999999],
            [['login'], 'string', 'min' => 3, 'max' => 50]
        ];
    }
    
    public function getActionName() {
        return self::ACTION_NAME;
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
//        $this->on(self::EVENT_BEFORE_PERFORM, [$this, 'eventBeforePerform']);
//        $this->on(self::EVENT_AFTER_PERFORM, [$this, 'eventAfterPerform']);
    }

//    public function eventBeforePerform()
//    {
//        echo __METHOD__ . '<br>';
//    }
//    public function eventAfterPerform()
//    {
//        echo __METHOD__  . '<br>';
//    }
}