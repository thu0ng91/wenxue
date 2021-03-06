<?php if($data['status']==Posts::STATUS_PASSED){?>
<div class="media ui-border-b" id="tip-<?php echo $data['id']; ?>">
    <div class="media-left">
        <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $data['truename'], array('data-original'=>$data['avatar'],'class'=>'lazy img-circle a64 media-object')),array('user/index','id'=>$data['uid']));?>
    </div>
    <div class="media-body">
        <p>
            <b><?php echo CHtml::link(CHtml::encode($data['truename']),array('user/index','id'=>$data['uid']));?></b>
            <?php if($data['chapterTitle']!=''){?>
            点评
            <b>
                <?php if($data['bookTitle']!=''){?>
                <?php echo CHtml::link(CHtml::encode($data['bookTitle']),array('book/view','id'=>$data['bid']));?>
                <?php }?>
                <?php echo CHtml::link(CHtml::encode($data['chapterTitle']),array('book/chapter','cid'=>$data['logid']));?>
            </b>
            <?php }?>
            <?php echo Books::starCss($data['score']*2);?>            
            <span class="pull-right">
                <?php echo GroupPowers::link('favorChapterTip',$this->userInfo,'<i class="fa '.($data['favorited'] ? 'fa-thumbs-up' : 'fa-thumbs-o-up').'"></i> '.$data['favors'],'javascript:;',array('action'=>'favorite','action-data'=>$data['id'],'action-type'=>'tip'),true);?>
            </span>
        </p>
        <p><?php echo nl2br(CHtml::encode($data['content'])); ?></p>
        <p class="color-grey">
            <?php echo zmf::formatTime($data['cTime']); ?>
            <?php echo CHtml::link($data['comments'].'评论','javascript:;',array('action'=>'getContents','data-id'=>$data['id'],'data-type'=>'tipComments','data-target'=>'comments-tipComments-'.$data['id'],'data-loaded'=>0));?>
            <span class="pull-right">
                <?php if($this->uid==$data['uid']){
                    echo CHtml::link('编辑',array('book/editTip','tid'=>$data['id'])).'&nbsp;';
                    echo CHtml::link('删除','javascript:;',array('action'=>'del-content','action-type'=>'tip','action-data'=>  $data['id'],'action-confirm'=>1,'action-target'=>'tip-'.$data['id']));
                 }else{
                     echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'tip','action-id'=>$data['id'],'action-title'=>  zmf::subStr($data['content'],20)));
                 }?>
            </span>
        </p>
        <div class="comments-list" id="comments-tipComments-<?php echo $data['id'];?>-box">
            <i class="icon-spike" style="display: inline;left:80px"></i>
            <div id="comments-tipComments-<?php echo $data['id'];?>"></div>
            <div id="comments-tipComments-<?php echo $data['id'];?>-form"></div>
        </div>
    </div>
</div>
<?php }else{?>
<div class="alert alert-danger">
    你的点评包含敏感词，暂不能显示。
</div>
<?php } 
