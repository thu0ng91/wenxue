<?php 
$url=zmf::config('domain').Yii::app()->createUrl('posts/view',array('id'=>$data['id']));
$qrcode=  zmf::qrcode($url, 'posts', $data['id']);
?>
<div class="post-item" id="post-<?php echo $data['id'];?>">
    <?php if($data['faceimg']){?>
    <a href="<?php echo $url;?>" title="<?php echo $data['title'];?>"><img src="<?php echo $data['faceimg'];?>" class="img-responsive"/></a>
    <?php }?>
    <div class="module">
        <h4><?php echo CHtml::link($data['title'], array('posts/view', 'id' => $data['id'])); ?></h4>
        <p><?php echo zmf::subStr($data['content'],140);?></p>
        <?php if(!empty($data['tagids'])){?>
        <p class="post-list-tags"><i class="fa fa-tags"></i><?php foreach($data['tagids'] as $_tag){echo CHtml::link($_tag['title'],array('index/index','tagid'=>$_tag['id']));}?></p>
        <?php }?>
        <div class="post-item-footer">
            <div class="left-actions">
                <span class="favor-num"><i class="fa fa-heart-o"></i> <?php echo $data['favorite'];?></span>
                <span class="comment-num"><i class="fa fa-comment-o"></i> <?php echo $data['comments'];?></span>
            </div>
            <div class="dropdown right-actions">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-ellipsis-h"></i></a>  
                <ul class="dropdown-menu">
                  <li><?php echo CHtml::link('分享','javascript:;',array('action'=>'share','action-qrcode'=>$qrcode,'action-url'=>$url,'action-img'=>$qrcode,'action-title'=>$data['title']));?></li>
                  <li><?php echo CHtml::link('评论',array('posts/view','id'=>$data['id'],'#'=>'add-comments'));?></li>
                  <?php if(($this->uid && $this->uid==$data['uid']) || $this->userInfo['isAdmin']){?>
                  <li role="separator" class="divider"></li>
                  <li><?php echo CHtml::link('编辑',array('admin/posts/update','id'=>$data['id']),array('target'=>'_blank'));?></li>
                  <li><?php echo CHtml::link('删除','javascript:;',array('action'=>'del-content','action-type'=>'post','action-data'=>$data['id'],'action-confirm'=>1,'action-target'=>'post-'.$data['id']));?></li>
                  <?php }?>
                </ul>
              </div>
        </div>
    </div>
</div>