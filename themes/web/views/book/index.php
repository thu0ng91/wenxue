<div class="container">
    <ol class="breadcrumb">
        <li><a href="#">初心创文首页</a></li>
        <li><a href="#">分类</a></li>
    </ol>
    <div class="main-part">
        <div class="module">
            <div class="module-header">分类标题</div>
            <div class="module-body">
                <?php if(!empty($posts)){
                    foreach ($posts as $post){
                        $this->renderPartial('/book/_item',array('data'=>$post,'adminLogin'=>false));
                        }                        
                    }?>
            </div>
        </div>
    </div>
    <div class="aside-part">
        <div class="module">
            <div class="module-header">推荐作者</div>
            <div class="module-body">
                
            </div>
        </div>
        <div class="module">
            <div class="module-header">推荐作品</div>
            <div class="module-body">
                
            </div>
        </div>
    </div>
</div>
<?php

