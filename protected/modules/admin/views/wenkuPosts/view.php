<?php

/**
 * @filename WenkuPostsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:17:08 */
$this->renderPartial('_nav');?>
<div class="row">
    <div class="col-xs-10">
        <?php $this->widget('zii.widgets.CDetailView', array(
            'data' => $model,
            'attributes' => array(
                'id',      
                array(
                    'label' => $model->getAttributeLabel('dynasty'),
                    'value' => $model->dynastyInfo->title
                ),
                'colid',
                array(
                    'label' => $model->getAttributeLabel('author'),
                    'value' => $model->authorInfo->title
                ),
                'title',
                array(
                    'label' => $model->getAttributeLabel('content'),
                    'type'=>'html',
                    'value' => $model->content
                ),   
                array(
                    'label' => $model->getAttributeLabel('status'),
                    'value' => Posts::exStatus($model->status)
                )     
            )
        ));
        ?>
    </div>
    <div class="col-xs-2">
        <?php echo CHtml::link('编辑',array('update','id'=>$model->id),array('class'=>'btn btn-primary btn-block'));?>
        <?php echo CHtml::link('预览',array('/wenku/post','id'=>$model->id),array('class'=>'btn btn-default btn-block','target'=>'_blank'));?>
    </div>
</div>
<?php $aboutInfos=$model->aboutInfos;foreach($aboutInfos as $aboutInfo){?>
<div class="media" style="border-bottom: 1px solid #F8F8F8;margin-bottom: 10px">
    <div class="media-body">
        <p><b><?php echo WenkuPostInfo::exClassify($aboutInfo['classify']);?></b>,<?php echo CHtml::link('编辑',array('wenkuPostInfo/update','id'=>$aboutInfo['id']),array('target'=>'_blank'));?></p>
        <?php echo $aboutInfo['content'];?>
    </div>
</div>
<?php } ?>
<p><?php echo CHtml::link('添加翻译',array('wenkuPostInfo/create','pid'=>$model->id),array('class'=>'btn btn-primary'));?></p>