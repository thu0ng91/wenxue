<?php 
$attri=isset($attribute)?$attribute:'content';
?>
<link href="<?php  echo Yii::app()->baseUrl.'/umeditor/themes/default/css/umeditor.css';?>" type="text/css" rel="stylesheet">
<script>
    URL= window.UEDITOR_HOME_URL||"<?php echo Yii::app()->baseUrl;?>/umeditor/";
    (function(){window.UMEDITOR_CONFIG={UMEDITOR_HOME_URL:URL}})();
</script>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/umeditor/umeditor.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/umeditor/lang/zh-cn/zh-cn.js', CClientScript::POS_END);
?>
<textarea id="<?php echo CHtml::activeId($model,$attri);?>" name="<?php echo CHtml::activeName($model,$attri);?>" style="width:600px;height:200px;">
<?php echo zmf::text(array('action'=>'edit','encode'=>'yes'),$content);?>
</textarea>
<input id="textareaid" type="hidden" value="<?php echo CHtml::activeId($model,$attri);?>"/>
<script> 
    var width=<?php echo isset($editorWidth) ? $editorWidth : 620;?>;
    var height=<?php echo isset($editorHeight) ? $editorHeight : 200;?>;
    var textarea='<?php echo CHtml::activeId($model,$attri);?>';
    var editorId='<?php echo CHtml::activeId($model, $attri); ?>';
    $(function(){
        myeditor=UM.getEditor(editorId, {
           //UMEDITOR_HOME_URL : URL, 
           toolbar: [],
           lang:'zh-cn', //语言
           wordCount:true, //关闭字数统计       
           initialFrameWidth:width, //宽度
           initialFrameHeight:height, //高度
           zIndex:0,
           focus:true,
           pasteplain:true,
           elementPathEnabled : false,       
           contextMenu:[],       
           autoHeightEnabled:true,
           initialStyle:'.edui-container .edui-editor-body{background:transparent !important}.edui-editor-body .edui-body-container p{font-size:13px;line-height:1.42857143;margin:0 0 10px;}.edui-container .edui-toolbar{z-index:1 !important}',
           removeFormatTags:'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var',
           removeFormatAttributes: 'class,style,lang,width,height,align,hspace,valign',
           imageScaleEnabled: false,
           dropFileEnabled: false,
           indentValue: '0em',
           textarea: textarea
        });
        myeditor.addListener("keyup", function () {
            var inputstr = myeditor.getContentTxt();
            if (inputstr != '') {
                $(window).bind('beforeunload', function () {
                    return '您输入的内容可能未保存，确定离开此页面吗？';
                });
                $('#submit-btn').removeAttr('disabled');
                $('#preview-btn').removeAttr('disabled');
            } else {
                $('#submit-btn').attr('disabled', 'disabled');
                $('#preview-btn').attr('disabled', 'disabled');
            }
        });
        $('#submit-btn').click(function () {
            submitChapterForm();
        })
        }
    );
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
        var inputstr = myeditor.getContentTxt();
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
</script>