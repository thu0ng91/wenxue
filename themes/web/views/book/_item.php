<?php 
if($this->adminLogin){
    $urlArr=array('author/book','bid'=>$data['id']);    
}else{
    $urlArr=array('book/view','id'=>$data['id']);
}
$url=  Yii::app()->createUrl('book/view',array('id'=>$data['id']));
?>
<div class="media">
    <div class="media-left">
        <a href="#">
            <img class="media-object" src="<?php echo $data['faceImg'];?>" alt="<?php echo $data['title'];?>">
        </a>
    </div>
    <div class="media-body">
        <h4><?php echo CHtml::link($data['title'],$urlArr);?></h4>
        <p><?php if($data['scorer']>0){echo Books::starCss($data['score']);?> （<?php echo $data['scorer'];?>人评价）<?php }else{?><span class="color-grey">暂无评分</span><?php }?></p>
        <p class="help-block"><?php echo $data['desc'];?></p>
        <p class="help-block">
            <span><?php echo Books::exStatus($data['bookStatus']);?></span>
            <span>总字<?php echo $data['words'];?></span>
            <span><?php echo zmf::time($data['cTime'],'Y-m-d');?></span>
            <span><?php echo CHtml::link('分享','javascript:;',array('action'=>'share','action-qrcode'=>$qrcode,'action-url'=>$url,'action-img'=>$qrcode,'action-title'=>$data['title']));?></span>
            <?php if($this->adminLogin){?>
            <span class="right-actions">                
                <?php echo CHtml::link('预览',array('book/view','id'=>$data['id']));?>
                <?php echo CHtml::link('编辑',array('author/updateBook','bid'=>$data['id']));?>
                <?php echo CHtml::link('章节',array('author/book','bid'=>$data['id']));?>
                <?php echo CHtml::link('续写',array('author/addChapter','bid'=>$data['id']));?>
            </span>
            <?php }?>
        </p>
    </div>
</div>