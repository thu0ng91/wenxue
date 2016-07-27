<?php
/**
 * @filename ChaptersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-11 10:54:32 */
 ?>
<header class="top-header">
    <?php if($this->showLeftBtn){?>
    <div class="header-left">
        <i class="fa fa-chevron-left" onclick="history.back()"></i>
    </div>
    <?php }?>
    <div class="header-center">
        <h1 id="main-status">写文章</h1>
    </div>
    <div class="header-right">
        <?php echo CHtml::link('<i class="fa fa-book"></i>',array('book/view','id'=>$model->bid),array('target'=>'_blank'));?>        
        <?php echo CHtml::link('<i class="fa fa-user"></i>',array('author/view','id'=>$model->aid),array('target'=>'_blank'));?>
        <?php echo CHtml::link('<i class="fa fa-clipboard"></i>',array('author/drafts'),array('target'=>'_blank'));?>
        <?php echo CHtml::link('<i class="fa fa-bell-o"></i><span id="top-nav-count" class="top-nav-count">0</span>',array('user/notice'),array('class'=>'top-notices','target'=>'_blank'));?>        
    </div>
</header>
<div class="module ui-container">
    <div class="module-body padding-body">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'add-chapter-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'title'); ?>
            <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control bitian','placeholder'=>'请输入章节标题')); ?>
            <?php echo $form->error($model,'title'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'chapterNum'); ?>
            <?php echo $form->numberField($model,'chapterNum',array('size'=>60,'maxlength'=>6,'class'=>'form-control bitian','placeholder'=>'章节号(大于0的整数，越小章节越靠前）')); ?>
            <?php echo $form->error($model,'chapterNum'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php echo $form->textArea($model,'content',array('rows'=>6,'class'=>'form-control bitian','placeholder'=>'章节正文')); ?>
            <?php //$this->renderPartial('//common/editor_addChapter', array('model' => $model,'content' => $model->content!='' ? Chapters::text($model->content) : '','editorWidth'=>620,'editorHeight'=>360,'hashUuid'=>$hashUuid,'bookId'=>$model->bid)); ?>
            <?php echo $form->error($model,'content'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'postscript'); ?>
            <?php echo $form->textArea($model,'postscript',array('rows'=>2,'maxlength'=>512,'class'=>'form-control','placeholder'=>'关于本章节还有话说')); ?>
            <?php $scoreArr=  Chapters::exPsPosition('admin'); foreach ($scoreArr as $val=>$label){?>
            <label class="radio-inline"><input value="<?php echo $val;?>" type="radio" name="<?php echo CHtml::activeName($model, 'psPosition');?>" <?php if($model->psPosition==$val){?>checked="checked"<?php }?>> <?php echo $label;?></label>
            <?php }?>
        </div>
        <div class="form-group">
            <button type="button" id="add-post-btn" class="btn btn-success" disabled="disabled">保存</button>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->
<div class="footer-bg"></div>
<script>
    var editorId='<?php echo CHtml::activeId($model, 'content'); ?>';
    var titleId='<?php echo CHtml::activeId($model, 'title'); ?>';
    var hashUuid='<?php echo $hashUuid; ?>';
    var bookId='<?php echo $model->bid; ?>';
    var internal;
    $(function () {
        $('textarea').autoResize({
            // On resize:  
            onResize: function () {
                $(this).css({opacity: 0.8});
            },
            // After resize:  
            animateCallback: function () {
                $(this).css({opacity: 1});
            },
            // Quite slow animation:  
            animateDuration: 100,
            // More extra space:  
            extraSpace: 20
        });
        $('#add-post-btn').click(function () {
            submitChapterForm();
        });
        $('#add-chapter-form input,#add-chapter-form textarea').on('change',function () {
            checkBtnStatus();
        });
    })
    
    function checkBtnStatus(){
        var inputAll=true;
        $('#add-chapter-form .bitian').each(function () {
            var _dom = $(this);
            var _val = _dom.val();
            if (!_val) {
                inputAll=false;
            }
        });
        var inputstr = $('#'+editorId).val();
        if (inputstr != '') {
            $(window).bind('beforeunload', function () {
                return '你输入的内容可能未保存，确定离开此页面吗？';
            });
            if(inputAll){
                $('#add-post-btn').removeAttr('disabled');
                $('#preview-btn').removeAttr('disabled');
            }
        } else {
            $('#add-post-btn').attr('disabled', 'disabled');
            $('#preview-btn').attr('disabled', 'disabled');
        }
    }
    function checkChapterContent() {
        $('#add-chapter-form .bitian').each(function () {
            var _dom = $(this);
            var _title = _dom.attr('placeholder');
            var _val = _dom.val();
            if (!_val) {
                dialog({msg: _title});
                return false;
            }
        });
        var inputstr = $('#'+editorId).val();
        if (!inputstr) {
            return false;
        }
        return true;
    }
    function submitChapterForm() {
        if (!checkChapterContent()) {
            return false;
        }else{
            $('#add-chapter-form').submit();
        }
    }
    function saveDrafts(){
        var inputstr = $('#'+editorId).val();
        var title=$('#'+titleId).val();
        if(!title || !inputstr || !hashUuid){
            return false;
        }
        $.post(zmf.ajaxUrl, {action:'saveDraft',title: title, content: inputstr, hash: hashUuid,bookId:bookId,YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            ajaxReturn = true;
            result = eval('(' + result + ')');
            if (result['status'] == '1') {
                $('#main-status').text(result['msg']);
            } else {
                $('#main-status').text(result['msg']);
            }
        });
    }
</script>
