<?php 
$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->baseUrl.'/umeditor/themes/default/css/umeditor.css');
$cs->registerScriptFile(Yii::app()->baseUrl.'/umeditor/umeditor.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->baseUrl.'/umeditor/lang/zh-cn/zh-cn.js', CClientScript::POS_END);
?>
<script>
    URL= window.UEDITOR_HOME_URL||"<?php echo Yii::app()->baseUrl;?>/umeditor/";
    (function(){window.UMEDITOR_CONFIG={UMEDITOR_HOME_URL:URL}})();
</script>
<textarea id="<?php echo CHtml::activeId($model,'content');?>" name="<?php echo CHtml::activeName($model,'content');?>" style="width:620px;height:200px;">
<?php echo zmf::text(array('action'=>'edit','encode'=>'yes'),$content);?>
</textarea>
<script> 
    var width=<?php echo isset($editorWidth) ? $editorWidth : 620;?>;
    var height=<?php echo isset($editorHeight) ? $editorHeight : 200;?>;
    var editorId='<?php echo CHtml::activeId($model, 'content'); ?>';
    var titleId='<?php echo CHtml::activeId($model, 'title'); ?>';
    var hashUuid='<?php echo $hashUuid; ?>';
    var bookId='<?php echo $bookId; ?>';
    var internal;
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
           initialStyle:'.edui-container .edui-editor-body{background:transparent !important}.edui-editor-body .edui-body-container p{font-size:13px;line-height:1.42857143;margin:0 0 10px;word-break: break-all;}.edui-container .edui-toolbar{z-index:1 !important}',
           removeFormatTags:'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var',
           removeFormatAttributes: 'class,style,lang,width,height,align,hspace,valign',
           imageScaleEnabled: false,
           dropFileEnabled: false,
           indentValue: '0em',
           textarea: editorId
        });
        myeditor.addListener("keyup", function () {
            checkBtnStatus();
        });
        $('#add-post-btn').click(function () {
            submitChapterForm();
        });
        $('#add-chapter-form input,#add-chapter-form textarea').on('change',function () {
            checkBtnStatus();
        });
        }
    );
    function checkBtnStatus(){
        var inputAll=true;
        $('#add-chapter-form .bitian').each(function () {
            var _dom = $(this);
            var _val = _dom.val();
            if (!_val) {
                inputAll=false;
            }
        });
        var inputstr = myeditor.getContentTxt();
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
    function saveDrafts(){
        var inputstr = myeditor.getContentTxt();
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