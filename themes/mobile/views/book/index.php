<div class="module books-category">
    <?php if(!empty($colInfo)){?>
    <div class="module-header"><?php echo $colInfo['title'];?></div>
    <?php }?>
    <div class="module-body">
        <?php if(!empty($posts)){?>
        <ul class="ui-list ui-list-link ui-border-t">
        <?php foreach ($posts as $post){$this->renderPartial('/book/_item',array('data'=>$post,'adminLogin'=>false));}?>
        </ul>
        <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>        
        <?php }else{?>
        <p class="help-block text-center">暂无该分类的作品</p>
        <?php }?>
    </div>
</div>