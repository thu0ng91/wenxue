<?php

/**
 * @filename createBook.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-13  17:26:05 
 */
?>

<div class="author-content-holder create-book-form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'books-form',
	'enableAjaxValidation'=>false,
)); ?>
    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->hiddenField($model,'faceImg',array('class'=>'form-control bitian','placeholder'=>'请上传封面图')); ?>
    <div class="module">
        <div class="module-header">创建作品</div>
        <div class="module-body">
            <div class="form-group">
                <ul class="ui-list">
                    <li>
                        <div class="ui-list-img">
                            <img class="lazy w78" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $model->faceImg;?>" id="book-faceImg">                               
                        </div>
                        <div class="ui-list-info">
                            <?php $this->renderPartial('/common/_singleUpload',array('model'=>$model,'fieldName'=>'faceImg','type'=>'book','fileholder'=>'filedata','targetHolder'=>'book-faceImg','imgsize'=>'w120','progress'=>true));?>
                        </div>
                    </li>
                </ul>
                <div id="progress" class="progress">
                    <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
                </div>
            </div>
            <div class="padding-body">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'colid'); ?>
                    <?php echo $form->dropDownlist($model,'colid',  Column::allCols(),array('class'=>'form-control bitian','empty'=>'--请选择--','placeholder'=>'请选择小说分类')); ?>
                    <?php echo $form->error($model,'colid'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'title'); ?>
                    <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control bitian','placeholder'=>'请添加小说名')); ?>
                    <?php echo $form->error($model,'title'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'desc'); ?>
                    <?php echo $form->textArea($model,'desc',array('rows'=>3, 'cols'=>50,'class'=>'form-control bitian','placeholder'=>'请填写小说推荐语')); ?>
                    <?php echo $form->error($model,'desc'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'content'); ?>
                    <?php echo $form->textArea($model,'content',array('rows'=>3, 'cols'=>50,'class'=>'form-control bitian','placeholder'=>'请填写小说简介')); ?>
                    <?php echo $form->error($model,'content'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'iAgree'); ?>
                    <?php echo $form->textField($model,'iAgree',array('size'=>60,'maxlength'=>3,'class'=>'form-control bitian','placeholder'=>'请阅读并同意本站协议')); ?>
                    <p>输入“<font style="color: red">我同意</font>”，即表示接受<?php echo CHtml::link('本站协议',array('site/info','code'=>'terms'));?>。</p>
                    <?php echo $form->error($model,'iAgree'); ?>
                </div>
                <div class="form-group">
                    <div class="checkbox"><label><?php echo CHtml::activeCheckBox($model, 'shoufa');?> 作品在本站首发</label></div>
                </div>            
                <div class="form-group">
                    <?php echo CHtml::button($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-success','id'=>'create-book-btn')); ?>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    var dom=$('#create-book-btn');
    dom.click(function() {
        var oform = $('#books-form');
        dom.attr('disabled', true);
        
        var btnText=dom.text();
        dom.text('验证中...');
        var submit=true;
        $('#books-form .bitian').each(function () {
            var _dom = $(this);
            var _title = _dom.attr('placeholder');
            var _val = _dom.val();
            if (!_val) {
                dialog({msg: _title});
                submit=false;
                return false;
            }
        });
        if(submit){
            oform.submit();
            return true;  
        }
        dom.removeAttr('disabled').text(btnText);
        return false;
    });
</script>
<?php $this->endWidget(); ?>
</div><!-- form -->
