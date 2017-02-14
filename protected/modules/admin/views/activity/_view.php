<?php $ckinfo=  Activity::checkStatus($data->id, 'vote', $data);?>
<tr>
    <td>
        <?php echo CHtml::encode($data->title); ?>
    </td>
    <td>
        <?php if($ckinfo['status']===-1){echo '投稿未开始';}elseif($ckinfo['status']===-2){echo '已结束';}elseif($ckinfo['status']===-4){echo '投票未开始';}elseif($ckinfo['status']===1){echo '正在进行';}?>
    </td>
    <td>
        <?php echo CHtml::link('预览',array('/activity/view','id'=>$data->id),array('target'=>'_blank'));?>
        <?php echo CHtml::link('参与作品',array('posts','id'=>$data->id));?>
        <?php echo CHtml::link('详情',array('view','id'=>$data->id));?>
        <?php echo CHtml::link('编辑',array('update','id'=>$data->id));?>
        <?php echo CHtml::link('删除',array('delete','id'=>$data->id));?>
    </td>
</tr>