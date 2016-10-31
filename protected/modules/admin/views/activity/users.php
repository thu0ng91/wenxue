<?php

/**
 * @filename users.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-12-15  11:16:43 
 */
$this->breadcrumbs=array(
    '首页'=>array('index/index'),
    '活动列表'=>array('activity/index'),
    $activityInfo['title']=>array('activity/view','id'=>$activityInfo['id']),
    '参赛列表'
);
?>
<p class="text-right">
    <?php echo CHtml::link('新增用户',array('addUser','id'=>$acid),array('class'=>'btn btn-primary'));?>
</p>
<table class="table table-hover">
    <tr>
        <th style="width: 80px;">序号</th>        
        <th>用户名</th>        
        <th>职业</th>
        <th class="text-center">投票<?php echo CHtml::link('<i class="fa fa-caret-down"></i>',array('activity/users','id'=>$activityInfo['id'],'order'=>'votes'));?></th>
        <th style="width: 150px"></th>
    </tr>
    <?php foreach ($posts as $row): ?> 
    <tr>
        <td><?php echo $row['voteOrder'];?></td>
        <td><?php echo CHtml::link($row['username'],array('user/view','id'=>$row['id']));?></td>
        <td><?php echo User::jobs($row['jobid']);?></td>
        <td class="text-center"><?php echo $row['votes'];?></td>
        <td>
            <?php echo CHtml::link('编号','javascript:;',array('onclick'=>"toggleDialog('user','{$row['alid']}')"));?>
            <?php echo CHtml::link('通知',array('tools/qunfa','type'=>'ones','touids'=>$row['id'],'title'=>'您的报名已经审核通过！','content'=>'尊敬的婚礼人，您在「'.$activityInfo['title'].'」活动的报名已经通过审核！请关注活动页面 '.zmf::config('domain').Yii::app()->createUrl('/activity/view',array('id'=>$activityInfo['id']))),array('target'=>'_blank'));?>    
            <?php echo CHtml::link('统计',array('posts/stat','id'=>$row['id'],'aid'=>$activityInfo['id']));?>
            <?php echo CHtml::link('删除',array('delUser','alid'=>$row['alid'],'aid'=>$row['aid']));?>
        </td>
    </tr>    
    <?php endforeach; ?>
</table>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
<script>
    var orderType,orderId;
    function toggleDialog(type,id){
        var html = '<div class="form-group"><label for="feedback-contact">编号</label><input type="text" id="voteOrder-num" class="form-control" placeholder="设置编号"/></div>';
        orderType=type;
        orderId=id;
        dialog({msg: html, title: '意见反馈', action: 'voteOrder'});
        $("button[action=voteOrder]").unbind('click').click(function () {
            voteOrder();
        });
    }
    function voteOrder(){
        var url="<?php echo Yii::app()->createUrl('admin/ajax/setVoteOrder');?>";
        var num=$('#voteOrder-num').val();
        if(!num){
            alert('请填写序号');
            return false;
        }
        $.post(url, {type: orderType, id: orderId, num: num, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
            result = $.parseJSON(result);
            if(result.status===1){
                closeDialog();
                dialog({msg:'已修改'});
            }else{
                alert(result.msg);
            }
            return false;
        })
    }
</script>