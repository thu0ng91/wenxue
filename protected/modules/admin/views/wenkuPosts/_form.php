<?php
/**
 * @filename WenkuPostsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:17:08 */
 ?>
<div class="content">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'posts-posts-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
        <div class="form-group">
		<?php echo $form->labelEx($model,'author'); ?>
                <?php $this->widget('CAutoComplete',
                    array(
                       'name'=>'suggest_author',
                       'url'=>array('wenkuAuthor/search'),
                       'max'=>10, //specifies the max number of items to display
                       'minChars'=>1,
                       'delay'=>500, //number of milliseconds before lookup occurs
                       'matchCase'=>false, //match case when performing a lookup?
                       'value'=>$model->authorInfo->title, 
                       'htmlOptions'=>array('class'=>'form-control'),
                       'methodChain'=>".result(function(event,item){ $('#".CHtml::activeId($model, 'author')."').val(item[1]);})",
                       ));
                ?>
		<?php echo $form->hiddenField($model,'author'); ?>
		<?php echo $form->error($model,'author'); ?>
	</div>    
        <div class="form-group">
            <?php echo $form->labelEx($model,'dynasty'); ?>
            <?php echo $form->dropDownlist($model,'dynasty',  WenkuColumns::getAll(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
            <?php echo $form->error($model,'dynasty'); ?>
	</div>
	<div class="form-group">
            <?php echo $form->labelEx($model,'colid'); ?>
            <?php echo $form->dropDownlist($model,'colid',  WenkuColumns::getAll(WenkuColumns::CLASSIFY_TYPES),array('class'=>'form-control','empty'=>'--请选择--')); ?>
            <?php echo $form->error($model,'colid'); ?>
	</div>	
	<div class="form-group">
            <?php echo $form->labelEx($model,'title'); ?>
            <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'title'); ?>
	</div>
	<div class="form-group">
            <?php echo $form->labelEx($model,'second_title'); ?>
            <?php echo $form->textField($model,'second_title',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'second_title'); ?>
	</div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'faceImg'); ?>     
            <p><img src="<?php echo zmf::getThumbnailUrl($model->faceImg, 'a120', 'faceImg');?>" alt="修改头像" id="user-avatar" style="width: 120px;height: 120px;"></p>
            <?php $this->renderPartial('/common/_singleUpload',array('model'=>$model,'fieldName'=>'faceImg','type'=>'faceImg','fileholder'=>'filedata','targetHolder'=>'user-avatar','imgsize'=>'a120','progress'=>true));?>
            <?php echo $form->hiddenField($model,'faceImg',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'faceImg'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'bgImg'); ?>     
            <p><img src="<?php echo zmf::getThumbnailUrl($model->bgImg, 'a120', 'faceImg');?>" alt="修改背景图" id="user-bgImg" style="width: 120px;height: 120px;"></p>
            <?php $this->renderPartial('/common/_singleUpload',array('model'=>$model,'fieldName'=>'bgImg','type'=>'faceImg','fileholder'=>'filedata','targetHolder'=>'user-bgImg','imgsize'=>'a120','progress'=>true));?>
            <?php echo $form->hiddenField($model,'bgImg',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'bgImg'); ?>
        </div>
	<div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php $this->renderPartial('//common/editor_um', array('model' => $model,'content' => $model->content,'mini'=>true)); ?>
            <?php echo $form->error($model,'content'); ?>
	</div>	    
        <div class="form-group">
            <?php echo $form->labelEx($model,'status'); ?>
            <?php echo $form->dropDownlist($model,'status',  Posts::exStatus('admin'),array('class'=>'form-control','empty'=>'--请选择--')); ?>
            <?php echo $form->error($model,'status'); ?>
	</div>	
	<div class="form-group">
		<?php echo CHtml::submitButton('提交',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->