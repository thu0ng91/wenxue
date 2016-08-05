<?php
$posts=$moduleInfo['post'];
if(!empty($posts)){
?>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php foreach ($posts as $k=>$val){?>
        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $k;?>" <?php if($k==0){?>class="active"<?php }?>></li>
        <?php }?>
    </ol>
    <div class="carousel-inner" role="listbox">
        <?php foreach ($posts as $k=>$val){?>
        <div class="item<?php if($k==0){?> active<?php }?>">
            <img alt="<?php echo $val['title'];?>" src="<?php echo $val['faceimg'];?>" data-holder-rendered="true">
        </div>
        <?php }?>
    </div>
    <?php if($this->userInfo['isAdmin']){?><div class="column-fixed-btn"><?php echo CHtml::link('<i class="fa fa-edit"></i>',array('admin/showcaseLink/index','sid'=>$moduleInfo['id']),array('target'=>'_blank'));?></div><?php }?>
</div>
<?php }