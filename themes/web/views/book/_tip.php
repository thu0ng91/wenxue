<div class="media" id="tip-<?php echo $data['id']; ?>">
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
                <?php echo CHtml::link('<i class="fa '.($data['favorited'] ? 'fa-thumbs-up' : 'fa-thumbs-o-up').'"></i> '.$data['favors'],'javascript:;',array('action'=>'favorite','action-data'=>$data['id'],'action-type'=>'tip'));?>
            </span>
        </p>
        <p><?php echo nl2br(CHtml::encode($data['content'])); ?></p>
        <p class="help-block">
            <?php echo zmf::formatTime($data['cTime']); ?>
            <?php if($this->uid){?>
            <span class="pull-right">
                <?php 
                if($this->uid==$data['uid']){
                    echo CHtml::link('编辑',array('book/editTip','tid'=>$data['id'])).'&nbsp;';
                    echo CHtml::link('删除','javascript:;',array('action'=>'del-content','action-type'=>'tip','action-data'=>  $data['id'],'action-confirm'=>1,'action-target'=>'tip-'.$data['id']));
                }?>                
            </span>
            <?php }?>
        </p>
    </div>
</div>