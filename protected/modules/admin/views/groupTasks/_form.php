<?php
/**
 * @filename GroupTasksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:21:04 */
 ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'group-tasks-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'gid'); ?>        
        <?php echo $form->dropDownlist($model,'gid',  Group::listAll(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <?php echo $form->error($model,'gid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'tid'); ?>       
        <?php echo $form->dropDownlist($model,'tid', Task::listAll(),array('class'=>'form-control','empty'=>'--请选择--')); ?>
        <p class="help-block">每个用户组只能领取一次同一任务</p>
        <?php echo $form->error($model,'tid'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'type'); ?>        
        <?php echo $form->dropDownlist($model,'type', zmf::yesOrNo('admin'),array('class'=>'form-control','onclick'=>'toggleThis()')); ?>
        <p class="help-block">该任务是否只能参与一次</p>
        <?php echo $form->error($model,'type'); ?>
    </div>
    <div id="toggleArea" style="display: <?php echo $model->type==GroupTasks::TYPE_ONETIME ? 'none' : '';?>">
        <div class="form-group">
            <?php echo $form->labelEx($model,'continuous'); ?>        
            <?php echo $form->dropDownlist($model,'continuous', zmf::yesOrNo('admin'),array('class'=>'form-control')); ?>
            <p class="help-block">比如，是否需要连续5天发帖（当任务是一次性任务时，此项只能为No）</p>
            <?php echo $form->error($model,'continuous'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'days'); ?>        
            <?php echo $form->textField($model,'days',array('class'=>'form-control')); ?>
            <p class="help-block">持续天数，必须不小于1（当任务是一次性任务时，此项只能为1）</p>
            <?php echo $form->error($model,'days'); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'num'); ?>        
        <?php echo $form->textField($model,'num',array('class'=>'form-control')); ?>
        <p class="help-block">每天需要操作几次</p>
        <?php echo $form->error($model,'num'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'score'); ?>        
        <?php echo $form->textField($model,'score',array('class'=>'form-control')); ?>
        <p class="help-block">完成任务后获得的积分数，为空则不奖励</p>
        <?php echo $form->error($model,'score'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'exp'); ?>        
        <?php echo $form->textField($model,'exp',array('class'=>'form-control')); ?>
        <p class="help-block">完成任务后获得的经验值，为空则不奖励</p>
        <?php echo $form->error($model,'exp'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'startTime'); ?>
        <?php 
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'model'=>$model,
        'attribute'=>'startTime',
        'language'=>'zh-cn',
        'value'=>date('Y/m/d',$model->startTime),			    
                    'options'=>array(
                        'showAnim'=>'fadeIn',
                    ),	
                    'htmlOptions'=>array(
                        'readonly'=>'readonly',
                        'class'=>'form-control',
                        'value'=>date('Y/m/d',($model->startTime) ? $model->startTime :'')
                ),		    
                ));
        ?>
        <?php echo $form->error($model,'startTime'); ?>
    </div>    
    <div class="form-group">
        <?php echo $form->labelEx($model,'endTime'); ?>
        <?php 
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'model'=>$model,
        'attribute'=>'endTime',
        'language'=>'zh-cn',
        'value'=>date('Y/m/d',$model->endTime),			    
                    'options'=>array(
                        'showAnim'=>'fadeIn',
                    ),	
                    'htmlOptions'=>array(
                        'readonly'=>'readonly',
                        'class'=>'form-control',
                        'value'=>date('Y/m/d',($model->endTime) ? $model->endTime :'')
                ),		    
        ));
        ?>
        <?php echo $form->error($model,'endTime'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->
<script>
    function toggleThis(){
        var v=$('#GroupTasks_type').val();
        if(v==='1'){
            $('#toggleArea').hide();
        }else{
            $('#toggleArea').show();
        }
    }
</script>