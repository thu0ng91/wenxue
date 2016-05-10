<div class="ui-container">        
    <?php if(!empty($posts)){?>
    <ul class="ui-list ui-list-link ui-border-tb">
    <?php foreach($posts as $post){?>
    <?php $this->renderPartial('/posts/_item',array('data'=>$post));?>    
    <?php }?>
    </ul>
    
    
    <?php }?>
</div>