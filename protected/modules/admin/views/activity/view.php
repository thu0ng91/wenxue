<?php
$this->renderPartial('_nav');
?>
<div class="col-xs-10 col-sm-10">
<?php 
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'title',
        array(
            'label' => $model->getAttributeLabel('type'),
            'value' => Activity::types($model->type)
        ),
        array(
            'label' => $model->getAttributeLabel('faceimg'),
            'type'=>'raw',
            'value' => '<p><img src="'.Attachments::faceImg($model,'w650','activity').'" class="img-responsive"/></p>'
        ),
        array(
            'label' => $model->getAttributeLabel('desc'),
            'type'=>'raw',
            'value' => nl2br($model->desc)
        ),
        array(
            'label' => $model->getAttributeLabel('content'),
            'type'=>'raw',
            'value' => zmf::text(array(),$model->content,true,'w600')
        ),
        array(
            'label' => $model->getAttributeLabel('startTime'),
            'value' => zmf::time($model->startTime)
        ),
        array(
            'label' => $model->getAttributeLabel('expiredTime'),
            'value' => zmf::time($model->expiredTime)
        ),
        array(
            'label' => $model->getAttributeLabel('voteStart'),
            'value' => zmf::time($model->voteStart)
        ),
        array(
            'label' => $model->getAttributeLabel('voteEnd'),
            'value' => zmf::time($model->voteEnd)
        ),       
        'voteNum',
        array(
            'label' => $model->getAttributeLabel('voteType'),
            'value' => Activity::voteTypes($model->voteType)
        ),
    ),
));
?>
</div>
<div class="col-xs-2 col-sm-2">
    <?php echo $model->type=='posts' ? CHtml::link('参赛作品',array('activity/posts','id'=>$model->id),array('class'=>'btn btn-default btn-block')) : CHtml::link('参赛用户',array('activity/users','id'=>$model->id),array('class'=>'btn btn-default btn-block'));?>
    <div class="thumbnail" style="margin-top: 15px">
        <img src="<?php echo $qrcodeUrl;?>" class="img-responsive"/>
    </div>
</div>