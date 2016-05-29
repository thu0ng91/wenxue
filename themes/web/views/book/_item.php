<?php 
if($adminLogin){
    $urlArr=array('author/book','bid'=>$data['id']);    
}else{
    $urlArr=array('book/view','id'=>$data['id']);
}
$url=  Yii::app()->createUrl('book/view',array('id'=>$data['id']));
?>
<div class="media">
    <div class="media-left">
        <a href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$data['id']));?>" title="<?php echo $data['title'];?>">
            <img class="media-object lazy" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['faceImg'];?>" alt="<?php echo $data['title'];?>">
        </a>
    </div>
    <div class="media-body">
        <h4><?php echo CHtml::link($data['title'],$urlArr);?></h4>
        <p><?php if($data['scorer']>0){echo Books::starCss($data['score']);?> （<?php echo $data['scorer'];?>人评价）<?php }else{?><span class="color-grey">暂无评分</span><?php }?></p>
        <p class="help-block ui-nowrap"><?php echo $data['desc'];?></p>
        <p class="help-block">
            <span class="<?php echo $data['bookStatus']==Books::STATUS_PUBLISHED ? 'success' : 'danger';?>"><?php echo Books::exStatus($data['bookStatus']);?></span>
            <span>总字<?php echo $data['words'];?></span>
            <span><?php echo zmf::time($data['cTime'],'Y-m-d');?></span>
            <?php if($data['bookStatus']==Books::STATUS_PUBLISHED){?>
            <span><?php echo CHtml::link('分享','javascript:;',array('action'=>'share','action-qrcode'=>$qrcode,'action-url'=>$url,'action-img'=>$qrcode,'action-title'=>$data['title']));?></span>
            <?php }?>
            <?php if($adminLogin){?>
            <span class="right-actions">                
                <?php echo CHtml::link('预览',array('book/view','id'=>$data['id']));?>
                <?php echo CHtml::link('章节',array('author/book','bid'=>$data['id']));?>
                <?php echo $data['bookStatus']==Books::STATUS_NOTPUBLISHED ? CHtml::link('发表','javascript:;',array('action'=>'publishBook','data-id'=>$data['id'])) : '';?>   
                <?php echo CHtml::link('编辑',array('author/updateBook','bid'=>$data['id']));?>                
                <?php echo CHtml::link('续写',array('author/addChapter','bid'=>$data['id']));?>
            </span>
            <?php }?>
        </p>
    </div>
</div>