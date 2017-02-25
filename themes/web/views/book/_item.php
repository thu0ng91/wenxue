<?php 
if($adminLogin){
    $urlArr=array('author/book','bid'=>$data['id']);    
}else{
    $urlArr=array('book/view','id'=>$data['id']);
}
$url= zmf::config('domain').Yii::app()->createUrl('book/view',array('id'=>$data['id']));
$qrcode=zmf::qrcode($url, 'book', $data['id']);
?>
<div class="media">
    <div class="media-left">
        <a href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$data['id']));?>" title="<?php echo $data['title'];?>" target="_blank">
            <img class="media-object lazy w78" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['faceImg'];?>" alt="<?php echo $data['title'];?>">
        </a>
    </div>
    <div class="media-body">
        <h4><?php echo CHtml::link($data['title'],$urlArr,array('target'=>'_blank')).($data['shoufa'] ? '<span class="shoufa" title="此作品在初心首发">首</span>' : '');?></h4>
        <p><?php if($data['scorer']>0){echo Books::starCss($data['score']);?> （<?php echo $data['scorer'];?>人评价）<?php }else{?><span class="color-grey">暂无评分</span><?php }?></p>
        <p class="help-block"><?php echo zmf::subStr($data['desc'],100);?></p>
        <p class="help-block">
            <?php if($from!='author'){?>
            <?php echo CHtml::link($data['authorName'],array('author/view','id'=>$data['aid']),array('target'=>'_blank'));?>
            <?php }?>
            <span class="<?php echo $data['bookStatus']==Books::STATUS_PUBLISHED ? 'text-success' : 'text-danger';?>"><?php echo Books::exStatus($data['bookStatus']);?></span>
            <span>总字<?php echo $data['words'];?></span>        
            <span><?php echo CHtml::link('分享','javascript:;',array('action'=>'share','action-qrcode'=>$qrcode,'action-url'=>$url,'action-img'=>$qrcode,'action-title'=>$data['title']));?></span>
            <span><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'book','action-id'=>$data['id'],'action-title'=>$data['title']));?></span>            
            <?php if($adminLogin){?>
            <span class="right-actions">
                <?php echo CHtml::link('预览',array('book/view','id'=>$data['id']));?>
                <?php echo CHtml::link('章节',array('author/book','bid'=>$data['id']));?>
                <?php echo $data['bookStatus']==Books::STATUS_NOTPUBLISHED ? CHtml::link('发表','javascript:;',array('action'=>'publishBook','data-id'=>$data['id'])) : '';?>   
                <?php echo CHtml::link('编辑',array('author/updateBook','bid'=>$data['id']));?>                
                <?php echo $data['bookStatus']!=Books::STATUS_FINISHED ? CHtml::link('续写',array('author/addChapter','bid'=>$data['id'])):'';?>
            </span>
            <?php }?>
        </p>
    </div>
</div>