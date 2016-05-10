<tr>
    <td>
        <?php echo CHtml::encode($data->title); ?>
        <?php if($data->status==Posts::STATUS_NOTPASSED){?><span style="color: red">[草稿]</span><?php }?>
    </td>
    <td class="text-right">
        <?php echo $data->hits;?> / <?php echo $data->comments;?> / <?php echo $data->favorites;?>
    </td>
    <td>
        <?php echo CHtml::link('预览',array('/zazhi/view','zid'=>  $data->id),array('target'=>'_blank'));?>
        <?php echo CHtml::link('文章',array('posts/index','zid'=>  $data->id),array('target'=>'_blank'));?>
        &nbsp;|&nbsp;
        <?php echo CHtml::link('新增文章',array('posts/create','zid'=> $data->id),array('target'=>'_blank'));?> 
        <?php echo CHtml::link('排序',array('zazhi/order','zid'=> $data->id),array('target'=>'_blank'));?> 
        <?php echo CHtml::link('编辑',array('zazhi/update','id'=> $data->id),array('target'=>'_blank'));?>        
        <?php echo CHtml::link('删除','javascript:;',array('action'=>'del-content','action-type'=>'zazhi','action-data'=>  $data->id,'action-confirm'=>1));?>
    </td>    
</tr>