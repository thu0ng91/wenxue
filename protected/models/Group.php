<?php

/**
 * This is the model class for table "{{group}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:13
 * The followings are the available columns in table '{{group}}':
 * @property string $id
 * @property string $title
 * @property string $faceImg
 * @property string $desc
 * @property string $tasks
 * @property string $members
 * @property integer $status
 * @property string $cTime
 */
class Group extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{group}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('title', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 50),
            array('faceImg, desc', 'length', 'max' => 255),
            array('tasks, members, cTime,initScore,initExp', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, faceImg, desc, tasks, members, status, cTime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => '标题',
            'faceImg' => '封面图',
            'desc' => '描述',
            'tasks' => '任务数',
            'members' => '成员数',
            'status' => '推荐',
            'cTime' => '创建时间',
            'initScore' => '初始化积分',
            'initExp' => '初始化经验',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('faceImg', $this->faceImg, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('tasks', $this->tasks, true);
        $criteria->compare('members', $this->members, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Group the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function listAll(){
        $items=  Group::model()->findAll();
        return CHtml::listData($items, 'id', 'title');
    }
    
    public static function getOne($id){
        return Group::model()->findByPk($id);
    }
    
    /**
     * 更新团队的成员数
     * @param int $gid
     * @return bool
     */
    public static function updateMemberCount($gid){
        $count=  Users::model()->count('groupid=:gid',array(':gid'=>$gid));
        return self::model()->updateByPk($gid, array(
            'members'=>$count
        ));
    }
    
    /**
     * 更新团队的任务数
     * @param int $gid
     * @return bool
     */
    public static function updateTaskCount($gid){
        $now=  zmf::now();
        $count= GroupTasks::model()->count('gid=:gid AND ((startTime=0 OR startTime<=:cTime) AND (endTime=0 OR endTime>=:cTime))',array(':gid'=>$gid,':cTime'=>$now));
        return self::model()->updateByPk($gid, array(
            'tasks'=>$count
        ));
    }

}
