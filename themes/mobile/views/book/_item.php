<?php 
if($adminLogin){
    $urlArr=array('author/book','bid'=>$data['id']);  
    $url=  Yii::app()->createUrl('author/book',array('bid'=>$data['id']));
}else{
    $urlArr=array('book/view','id'=>$data['id']);
    $url=  Yii::app()->createUrl('book/view',array('id'=>$data['id']));
}
?>
<li class="ui-border-t" data-href="<?php echo $url;?>">
    <div class="ui-list-img">
        <img class="lazy w78" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $data['faceImg'];?>" alt="<?php echo $data['title'];?>">        
    </div>
    <div class="ui-list-info">
        <h4 class="ui-nowrap"><?php echo $data['title'];?></h4>
        <p><?php if($data['scorer']>0){echo '<span class="star-color">'.Books::starCss($data['score']).'</span>';?> （<?php echo $data['scorer'];?>人评价）<?php }else{?><span class="color-grey">暂无评分</span><?php }?></p>
        <p class="help-block ui-nowrap-multi"><?php echo zmf::subStr($data['desc'],100);?></p>
    </div>
</li>