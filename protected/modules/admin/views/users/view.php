<?php

/**
 * @filename UsersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-03-04 09:47:48 */
$this->renderPartial('_nav');
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'truename',
        'phone',
        'contact',
        'avatar',
        'content',
        'sex',            
        array(
            'label' => $model->getAttributeLabel('authorId'),
            'type'=>'html',
            'value' => $model->authorId ? CHtml::link($model->authorInfo->authorName,array('authors/view','id'=>$model->authorId),array('target'=>'_blank')) : ''
        ),
        array(
            'label' => $model->getAttributeLabel('cTime'),
            'value' => zmf::time($model->cTime)
        ),
        array(
            'label' => $model->getAttributeLabel('ip'),
            'value' => long2ip($model->ip)
        ),
        'favors',
        'favord',
        'favorAuthors',
        'skinUrl',
        'phoneChecked',
        'email',
        'groupid',
        'score',
        'gold',
        'exp',
        'level',
        'levelTitle',
        'levelIcon',
        'hits',
        'isAdmin',
        array(
            'label' => $model->getAttributeLabel('status'),
            'value' => Posts::exStatus($model->status)
        )
    )
));
