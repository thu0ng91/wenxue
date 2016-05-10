<?php

/**
 * @filename order.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-15  12:56:35 
 */
$this->renderPartial('/zazhi/_nav');
$this->menu['章节排序'] = array(
    'link' => array('zazhi/order', 'zid' => $_GET['zid']),
    'active' => true
);
Yii::app()->clientScript->registerCss('sortable', "  
#sortable ,#relatives,#hotcols,#coltags_diqu,#coltags_dibiao,#coltags_caixi {list-style-type: none; margin: 0; padding: 0; width: 100%;}  
#sortable li{margin: 2px; padding: 4px;border: 1px solid #f2f2f2; background: #f8f8f8;cursor:move}", 'screen', CClientScript::POS_HEAD);
if (!empty($chapters)) {
    $this->widget('zii.widgets.jui.CJuiSortable', array(
        'id' => 'sortable',
        'items' => $chapters,
        'options' => array(
            'delay' => '300',
        ),
    ));
?>
<a href="javascript:;" onclick="changeOrder('<?php echo $_GET['zid'];?>');" class="btn btn-primary">确认排序</a> 
<script>
    function changeOrder(zid){
        if(!zid){
            return false;
        }
        var ids='';
        $('#sortable li').each(function(){
            ids+=$(this).attr('id')+'#';
        });   
        $.post('<?php echo Yii::app()->createUrl('/ajax/orderChapters');?>', {zid: zid, ids: ids, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            result = $.parseJSON(result);
            if(result['status']=='1'){  
                alert(result['msg']);
                window.location.reload();
            }else{
                alert(result['msg']);
            }
        })
        
    }
</script>
<?php }?>