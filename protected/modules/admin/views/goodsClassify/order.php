<?php
$this->renderPartial('_nav');
Yii::app()->clientScript->registerCss('sortable', "  
#sortable ,#relatives,#hotcols,#coltags_diqu,#coltags_dibiao,#coltags_caixi {list-style-type: none; margin: 0; padding: 0; width: 100%;}  
#sortable li{margin: 2px; padding: 4px;border: 1px solid #f2f2f2; background: #f8f8f8;cursor:move}", 'screen', CClientScript::POS_HEAD);
if (!empty($items)) {
    $this->widget('zii.widgets.jui.CJuiSortable', array(
        'id' => 'sortable',
        'items' => $items,
        'options' => array(
            'delay' => '300',
        ),
    ));
?>
<a href="javascript:;" onclick="changeOrder();" class="btn btn-primary">确认排序</a> 
<script>
    function changeOrder(){
        var ids='';
        $('#sortable li').each(function(){
            ids+=$(this).attr('id')+'#';
        });   
        $.post('<?php echo Yii::app()->createUrl('admin/goodsClassify/orderClassify');?>', {ids: ids, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
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