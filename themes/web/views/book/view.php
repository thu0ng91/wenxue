<style>
    .book-starInfo{
        width: 200px;
        border-left: 1px solid #e6e6e6;
        height: 100%;
        padding-left: 15px;
        display: block
    }
    .book-star-num{
        font-size: 28px;
        width: 48px
    }
    .book-star-detail{
        font-size: 12px;
        line-height: 16px;
        display: block
    }
    .book-star-detail-item{
        display: block;
        clear: both
    }
    .star-title{
        float: left;
        margin-right: 5px;
    }
    .star-width{
        float: left;
        height: 8px;
        background: red;
        margin-top: 4px;
    }
    .star-percent{
        float: left;
        margin-left: 5px;
    }
</style>
<div class="container">
    <div class="main-part">
        <div class="module">
            <h1><?php echo $info['title']; ?></h1>
            <div class="media">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object" src="<?php echo $info['faceImg']; ?>" alt="<?php echo $info['title']; ?>">
                    </a>
                </div>
                <div class="media-body">
                    <p>作者：<?php echo CHtml::link($info['title'], array('author/view', 'id' => $info['aid'])); ?></p>
                    <p>分类：<?php echo $info['title']; ?></p>
                    <p>收藏：<?php echo $info['favorites']; ?></p>
                    <p>点击：<?php echo $info['hits']; ?></p>
                    <p>总字：<?php echo $info['words']; ?></p>
                    <p>状态：<?php echo $info['bookStatus']; ?></p>
                </div>
                <div class="media-right">
                    <div class="book-starInfo">
                        <p>初心创文评分</p>
                        <div class="media">
                            <div class="media-left">
                                <p class="book-star-num">8.6</p>
                            </div>
                            <div class="media-body">
                                <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half"></i></p>
                                <p>123人评价</p>                                
                            </div>
                        </div>
                        <div class="book-star-detail">
                            <div class="book-star-detail-item">
                                <span class="star-title">5星</span>
                                <span class="star-width" style="width:62px"></span>
                                <span class="star-percent">86%</span>
                            </div>
                            <div class="book-star-detail-item">
                                <span class="star-title">4星</span>
                                <span class="star-width" style="width:15px"></span>
                                <span class="star-percent">86%</span>
                            </div>
                            <div class="book-star-detail-item">
                                <span class="star-title">3星</span>
                                <span class="star-width" style="width:8px"></span>
                                <span class="star-percent">86%</span>
                            </div>
                            <div class="book-star-detail-item">
                                <span class="star-title">2星</span>
                                <span class="star-width" style="width:6px"></span>
                                <span class="star-percent">86%</span>
                            </div>
                            <div class="book-star-detail-item">
                                <span class="star-title">1星</span>
                                <span class="star-width" style="width:2px"></span>
                                <span class="star-percent">86%</span>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="module-header">
                内容简介
            </div>
            <div class="module-body">
                <p><?php echo $info['content']; ?></p>
            </div>  
            <div class="module-header">
                目录
            </div>
            <div class="module-body">
                <div class="list-group">
                <?php foreach ($chapters as $chapter){?>
                <?php echo CHtml::link($chapter['title'],array('book/chapter','cid'=>$chapter['id']),array('class'=>'list-group-item'));?>
                <?php }?>
                </div>
            </div>
        </div>
        <div class="module">
            <div class="module-header">
                点评
            </div>
            <div class="module-body">
                <p><?php echo $info['content']; ?></p>
            </div>
        </div>
    </div>
</div>