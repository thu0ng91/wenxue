<div class="ui-container">
    <ul class="ui-list ui-list-link ui-border-tb">
        <?php foreach($posts as $val){?>
        <?php $this->renderPartial('/posts/_zazhi',array('data'=>$val));?>
        <?php } ?>
    </ul>
</div>