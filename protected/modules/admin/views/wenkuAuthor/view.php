<?php

/**
 * @filename WenkuAuthorController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:15:59 */
$this->renderPartial('_nav');?>
<div class="row">
    <div class="col-xs-10">
        <?php $this->widget('zii.widgets.CDetailView', array(
            'data' => $model,
            'attributes' => array(
                'id',
                'title',
                array(
                    'label' => $model->getAttributeLabel('dynasty'),
                    'value' => $model->dynastyInfo->title
                ),
                array(
                    'label' => $model->getAttributeLabel('status'),
                    'value' => Posts::exStatus($model->status)
                )   
            ),
        ));?>
    </div>
    <div class="col-xs-2">
        <?php echo CHtml::link('编辑',array('update','id'=>$model->id),array('class'=>'btn btn-primary btn-block'));?>
        <?php echo CHtml::link('预览',array('/wenku/author','id'=>$model->id),array('class'=>'btn btn-default btn-block','target'=>'_blank'));?>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 com-sm-6">
        <?php $aboutInfos=$model->aboutInfos;foreach($aboutInfos as $aboutInfo){?>
        <div class="module">
            <div class="module-header <?php echo $aboutInfo->status==Posts::STATUS_PASSED ? 'text-success' : 'text-danger';?>"><?php echo $aboutInfo['title'] ? $aboutInfo['title'] : WenkuAuthorInfo::exClassify($aboutInfo['classify']);?></div>
            <div class="module-body">
                <?php echo $aboutInfo['content'];?>
                <p><span class="<?php echo $aboutInfo->status==Posts::STATUS_PASSED ? 'text-success' : 'text-danger';?>"><?php echo Posts::exStatus($aboutInfo->status);?></span>，<?php echo CHtml::link('编辑',array('wenkuAuthorInfo/update','id'=>$aboutInfo['id']),array('target'=>'_blank'));?></p>
            </div>
        </div>
        <?php } ?>
        <p><?php echo CHtml::link('添加翻译',array('wenkuAuthorInfo/create','pid'=>$model->id),array('class'=>'btn btn-primary'));?></p>
    </div>
    <div class="col-xs-6 com-sm-6">
        <div class="module">
            <div class="module-header">相关作品</div>
            <div class="module-body">
                <div class="list-group">
                <?php $posts=$model->postsInfo;foreach($posts as $post){?>
                <?php echo CHtml::link($post['title'].'<span class="pull-right '.($post['status']==Posts::STATUS_PASSED ? 'text-success' : 'text-danger').'">'.Posts::exStatus($post['status']).'</span>',array('wenkuPosts/update','id'=>$post['id']),array('target'=>'_blank','class'=>'list-group-item'));?>
                <?php }?>
                </div>
                <p class="text-center">
                    <span class="input-group-btn">     
                        <?php echo CHtml::link('新增作品',array('wenkuPosts/create','author'=>$model->id),array('target'=>'_blank','class'=>'btn btn-primary'));?>
                        <?php echo CHtml::link('显示所有作品',array('wenkuAuthor/showOne','id'=>$model->id),array('class'=>'btn btn-danger'));?>
                    </span>
                    
                </p>
            </div>
        </div>
    </div>
</div>
