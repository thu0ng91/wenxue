<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-7-10  14:32:59 
 */
$this->renderPartial('_nav');
?>
<?php if(!empty($posts)){?>
<table class="table table-hover" style="margin-top: 25px">
    <tr>
        <th>号码</th>
        <th>验证码</th>
        <th>操作</th>
        <th class="hidden-xs">时间</th>
        <th>状态</th>        
        <th>分类</th>
    </tr>
<?php foreach($posts as $post){?>
    <tr>
        <td><?php echo $post['uid'] ? CHtml::link($post['phone'],array('users/view','id'=>$post['uid'])) : $post['phone'];?><?php echo CHtml::link('<i class="fa fa-align-left">',array('index','phone'=>$post['phone']),array('style'=>'margin-left:5px;opacity:0.5','title'=>'按此号码筛选'));?></i></td>
        <td><?php echo $post['code'];?></td>
        <td><?php echo Msg::exTypes($post['type']);?></td>
        <td class="hidden-xs" title="<?php echo zmf::time($post['cTime']);?>"><?php echo zmf::formatTime($post['cTime']);?></td>
        <td><?php echo Msg::smsStatus($post['status']);?></td>
        <td>
            <?php if($post['sendType']==Msg::TYPE_SMS){?><i class="fa fa-envelope"></i><?php }else{?>
            <i class="fa fa-mobile-phone"></i> <?php echo Msg::voiceStatus($post['voiceStatus']);?>
            <?php }?>
        </td>
    </tr>
<?php } ?>
</table>
<?php } ?>
<?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>