<div class="module-header">简介</div>
<div class="module-body">
    <ul class="ui-list ui-list-pure ui-border-tb">   
        <li class="ui-border-t">
            <p><span>热度：</span><?php echo $this->authorInfo['score'];?></p>
        </li>
        <li class="ui-border-t">
            <p><span>粉丝：</span><?php echo $this->authorInfo['favors'];?></p>
        </li>
        <li class="ui-border-t">
            <p><span>作品：</span><?php echo $this->authorInfo['posts'];?></p>
        </li>        
        <li class="ui-border-t">
            <p><span>简介：</span><?php echo $this->authorInfo['content']!='' ? $this->authorInfo['content'] : '未设置';?></p>
        </li>
    </ul>    
</div>
<div class="module-header">作品</div>
<div class="author-content-holder">
    <?php 
    if(!empty($posts)){?>
    <ul class="ui-list ui-list-link ui-border-tb">
        <?php foreach ($posts as $post){
            $this->renderPartial('/book/_item',array('data'=>$post,'adminLogin'=>$this->adminLogin));
        }?>
    </ul>
    <?php }elseif($this->adminLogin){?>
    <p class="help-block text-center">这里空空如也，快来<?php echo CHtml::link('发表小说',array('author/createBook'));?>吧！</p>
    <?php }else{?>
    <p class="help-block text-center">他很懒，什么也没写</p>
    <?php }?>
</div>