<?php 
$urlArr=array('book/view','id'=>$data['id']);
$url=  Yii::app()->createUrl('book/view',array('id'=>$data['id']));

$btnHtml=$btnInfo['status']!==1 ? CHtml::link('<i class="fa fa-heart-o"></i> '.$data['votes'],'javascript:;',array('class'=>'btn btn-xs btn-default btn-block','action-data'=>$data['encodeData'],'action'=>'ajax')) : CHtml::link('<i class="fa fa-heart-o"></i> '.$data['votes'],'javascript:;',array('class'=>'btn btn-xs btn-default btn-block disabled'));

?>
<div class="media">
    <div class="media-left">
        <a href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$data['id']));?>" title="<?php echo $data['title'];?>">
            <img class="media-object lazy w78" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['faceImg'];?>" alt="<?php echo $data['title'];?>">
        </a>
    </div>
    <div class="media-body">
        <h4><?php echo CHtml::link($data['title'],$urlArr);?></h4>
        <p><?php if($data['scorer']>0){echo Books::starCss($data['score']);?> （<?php echo $data['scorer'];?>人评价）<?php }else{?><span class="color-grey">暂无评分</span><?php }?></p>
        <p class="help-block"><?php echo zmf::subStr($data['desc'],100);?></p>
        <p class="help-block">
            <span class="<?php echo $data['bookStatus']==Books::STATUS_PUBLISHED ? 'success' : 'danger';?>"><?php echo Books::exStatus($data['bookStatus']);?></span>
            <span>总字<?php echo $data['words'];?></span>
            <span><?php echo zmf::time($data['cTime'],'Y-m-d');?></span>
            <?php if($data['bookStatus']==Books::STATUS_PUBLISHED){?>            
            <span><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'book','action-id'=>$data['id'],'action-title'=>$data['title']));?></span>
            <?php }?>
        </p>
    </div>
    <div class="media-right">
        <div class="ac-item-right">
            <?php echo $btnHtml;?>    
        </div>
    </div>
</div>