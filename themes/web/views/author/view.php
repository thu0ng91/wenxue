<div class="module-header">作品</div>
<div class="author-content-holder">
<?php
foreach ($posts as $post){
    $this->renderPartial('/book/_item',array('data'=>$post));
}
?>
</div>