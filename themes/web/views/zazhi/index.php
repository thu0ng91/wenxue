<div class="main-part">
<?php foreach($posts as $val){?>
<?php $this->renderPartial('/zazhi/_zazhi',array('data'=>$val));?>
<?php } ?>
</div>
