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
    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <div class="thumbnail">
                    <img src="<?php echo $model->faceImg;?>" alt="更改封面图片" id="book-faceImg">
                    <div class="caption">
                        <?php $this->renderPartial('//common/_singleUpload',array('model'=>$model,'fieldName'=>'faceImg','type'=>'book','fileholder'=>'filedata','targetHolder'=>'book-faceImg','imgsize'=>'w120'));?>
                        <div class="clearfix"></div>
                        <p class="text-center" style="margin-top: 5px"><a href="javascript:;" class="btn btn-default btn-block openGallery" role="button"  data-holder="book-faceImg" data-field="<?php echo CHtml::activeId($model, 'faceImg');?>">从相册选</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-9">
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
                <?php echo $form->textArea($model,'desc',array('rows'=>4, 'cols'=>50,'class'=>'form-control bitian','placeholder'=>'请填写小说推荐语')); ?>
                <?php echo $form->error($model,'desc'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'content'); ?>
                <?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50,'class'=>'form-control bitian','placeholder'=>'请填写小说简介')); ?>
                <?php echo $form->error($model,'content'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'iAgree'); ?>
                <div class="row">
                    <div class="col-xs-4">
                        <?php echo $form->textField($model,'iAgree',array('size'=>60,'maxlength'=>3,'class'=>'form-control bitian','placeholder'=>'请阅读并同意本站协议')); ?>
                        <?php echo $form->error($model,'iAgree'); ?>
                    </div>
                    <div class="col-xs-8">
                        <p>输入“<font style="color: red">我同意</font>”，即表示接受<?php echo CHtml::link('本站协议',array('site/info','code'=>'terms'),array('target'=>'_blank'));?>。</p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox"><label><?php echo CHtml::activeCheckBox($model, 'shoufa');?> 作品在本站首发</label></div>
                <p class="help-block">首发作品将会获得更多展示和置顶。</p>
            </div>            
            <div class="form-group text-center">
                <?php echo CHtml::button($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary','id'=>'create-book-btn')); ?>
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
