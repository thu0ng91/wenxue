<div class="module-header">作品</div>
<div class="author-content-holder">
    <?php 
    if(!empty($posts)){
        foreach ($posts as $post){
            $this->renderPartial('/book/_item',array('data'=>$post,'adminLogin'=>$this->adminLogin,'from'=>'author'));
        }
    }elseif($this->adminLogin){?>
    <p class="help-block text-center">这里空空如也，快来<?php echo CHtml::link('发表小说',array('author/createBook'));?>吧！</p>
    <?php }else{?>
    <p class="help-block text-center">他很懒，什么也没写</p>
    <?php }?>
</div>