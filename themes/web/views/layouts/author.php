<?php 
$this->beginContent('/layouts/common'); 
?>
<style>
    .author-module{
        display:block;
        height:100%;
        padding:0
    }
    .author-module .main-part{
        border-right:1px solid #e6e6e6
    }
    .author-module .aside-part{
        width:315px
    }
    .author-bg-container{
        width: 960px;
        height: 300px;        
        position:relative;
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover
    }
    .author-header{
        width:100px;
        height:200px;
        position:absolute;
        top:50%;
        left:50%;
        margin-top:-100px;
        margin-left:-50px;
    }
    .author-avatar-fixed{
        width: 100px;
        height: 100px;
        margin: 0 auto;
        padding:4px;
        background:rgba(255,255,255,.3);
        border-radius:200px;
    }
    .author-avatar-fixed img{
        border-radius: 200px;
        width: 100%;
        height: 100%;
    }
    .author-header{
        text-align:center
    }
    .author-header h1{
        text-aligin:center;
        font-size:24px;
        font-weight:700;
        color:#fff;
        padding-top:0;
        margin-top:15px;
        margin-bottom:0;
        padding-bottom:10px
    }
    .change-skin{
        position: absolute;
        width: 24px;
        height: 24px;
        right: 0;
        top: 0;
        background: url(<?php echo zmf::config('baseurl').'common/images/skin.gif';?>) no-repeat center
    }
    .author-content{
        margin-top:25px;
        float:left
    }
    .author-side-info{
        padding:15px 10px 0 10px;
        border-bottom:1px solid #e6e6e6
    }
    .author-side-info .info-label{
        float:left;
        min-width:40px;
        font-weight:700
    }
    .author-side-info .txt{
        color:#999
    }
    .author-side-num {
        border-bottom: 1px solid #eee;
        margin-bottom: 0;
        float: left;
        width: 100%;
        padding:0 0 0 10px
    }
    .author-side-num .item {
        text-decoration: none;
        padding: 15px 30px 8px 0;
        float: left
    }
    .author-side-num .item+.item {
        padding-left: 20px;
        border-left: 1px solid #eee;
    }
    .author-side-num .item strong {
        font-size: 16px;
        font-weight: 700;
        color: #666;
    }
    .author-side-num .item label {
        font-size: 13px;
        font-weight: 400;
        vertical-align: 1px;
        color: #666;
        cursor: pointer;
    }
    
    
    .author-side-navbar{
        clear:both
    }
    .author-side-navbar a{
        display:block;
        width:100%;
        padding:10px 0 10px 10px;
        text-decoration: none
    }
    .author-side-navbar .item+.item {
        border-top: 1px solid #eee;
    }
    .author-side-navbar .active{
        color:#fff;
        background:#93ba5f
    }
    .author-side-navbar .fa{
        margin-right:6px
    }
    
    .author-content-holder{
        padding:10px 0 10px 15px
    }
    .author-content-holder .media{
        border-bottom:1px solid #e6e6e6;
        margin-bottom:10px;
        padding-bottom:10px;
        margin-top:0;
        padding-right:10px;
    }
    .author-content-holder .media img{
        width:120px;
        height:160px;
    }
    .author-content-holder .media .right-actions{
        float:right;
        display:inline-block
    }
    .author-content-holder .success{
        color: #589013
    }
    .author-content-holder .danger{
        color: #BF1031
    }
    .author-following{
        padding-top:15px;
    }
    .author-following a{
        text-align:center
    }
    .create-book-form{
        padding-right:15px;
    }
    .create-book-form .thumbnail{
        border:none;
        padding:0
    }
    .create-book-form .thumbnail img{
        width:120px;
        height:160px;
    }
</style>
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse">
            <?php $this->renderPartial('/layouts/_nav');?>
            <?php $this->renderPartial('/layouts/_user');?>
        </div>
    </div> 
</div>

<div class="container">
    <div class="author-bg-container" style="background-image: url(<?php echo zmf::getThumbnailUrl($this->authorInfo['skinUrl']);?>);">
        <?php if($this->adminLogin){?>
        <a href="<?php echo Yii::app()->createUrl('author/setting',array('type'=>'skin'));?>" title="更好皮肤">
            <div class="change-skin"></div>
        </a>
        <?php }?>
        <div class="author-header">
            <div class="author-avatar-fixed">
                <img src="<?php echo $this->authorInfo['avatar'];?>"/>
            </div>
            <h1><?php echo $this->authorInfo['authorName'];?></h1>
            <?php if($this->userInfo['authorId']!=$this->authorInfo['id']){if($this->favorited){?>
            <p><?php echo CHtml::link('<i class="fa fa-check"></i> 已关注','javascript:;',array('class'=>'btn btn-default btn-small','action'=>'favorite','action-data'=>$this->authorInfo['id'],'action-type'=>'author'));?></p>
            <?php }else{?>
            <p><?php echo CHtml::link('<i class="fa fa-plus"></i> 关注','javascript:;',array('class'=>'btn btn-danger btn-small','action'=>'favorite','action-data'=>$this->authorInfo['id'],'action-type'=>'author'));?></p>
            <?php }}?>            
        </div>
    </div>
    <div class="author-content module author-module">
        <div class="main-part ">
            <?php echo $content; ?>
        </div>
        <div class="aside-part">
            <div class="author-side-info">
                <p><span class="info-label">入住</span><span class="txt"><?php echo zmf::time($this->authorInfo['cTime'],'Y-m-d');?></span></p>
                <p><span class="info-label">简介</span><span class="txt"><?php echo $this->authorInfo['content']!='' ? $this->authorInfo['content'] : '未设置';?></span></p>
                <p><span class="color-grey"><?php echo CHtml::link('<i class="fa fa-exclamation-triangle"></i> 举报','javascript:;',array('action'=>'report','action-type'=>'author','action-id'=>$this->authorInfo['id'],'action-title'=>  $this->authorInfo['authorName']));?></span></p>
            </div>
            <div class="author-side-num">
                <a class="item" href="<?php echo Yii::app()->createUrl('author/fans',array('id'=>$this->authorInfo['id']));?>">
                    <span>追随者</span><br>
                    <strong><?php echo $this->authorInfo['favors'];?></strong>
                    <label> 人</label>
                </a>
                <a class="item" href="<?php echo Yii::app()->createUrl('author/view',array('id'=>$this->authorInfo['id']));?>">
                    <span>作品数</span><br>
                    <strong><?php echo $this->authorInfo['posts'];?></strong>
                </a>
                <a class="item" href="javascript:;">
                    <span>热度</span><br>
                    <strong><?php echo $this->authorInfo['score'];?></strong>
                </a>
            </div>
            <div class="author-side-navbar">
                <?php echo CHtml::link('<i class="fa fa-list"></i> 作品<span></span>',array('author/view','id'=>$this->authorInfo['id']),array('class'=>'item'.($this->selectNav == 'index' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-star"></i> 追随者',array('author/fans','id'=>$this->authorInfo['id']),array('class'=>'item'.($this->selectNav == 'fans' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-comments"></i> 作者专区',array('posts/index','type'=>'author','aid'=>$this->authorInfo['id']),array('class'=>'item'));?>
                <?php if($this->adminLogin){?>
                <?php echo CHtml::link('<i class="fa fa-plus"></i> 新作品',array('author/createBook'),array('class'=>'item'.($this->selectNav == 'createBook' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-file"></i> 草稿箱',array('author/drafts'),array('class'=>'item'.($this->selectNav == 'drafts' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-edit"></i> 编辑资料',array('author/setting','type'=>'info'),array('class'=>'item'.($this->selectNav == 'updateinfo' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-lock"></i> 修改密码',array('author/setting','type'=>'passwd'),array('class'=>'item'.($this->selectNav == 'updatepasswd' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-cog"></i> 设置皮肤',array('author/setting','type'=>'skin'),array('class'=>'item'.($this->selectNav == 'updateskin' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-sign-out"></i> 退出管理',array('author/logout'),array('class'=>'item'));?>
                <?php }elseif($this->uid && $this->userInfo['authorId']==$this->authorInfo['id']){?>
                <?php echo CHtml::link('<i class="fa fa-cog"></i> 进入作者管理中心',array('user/authorAuth'),array('class'=>'item'));?>
                <?php }?>
            </div>
        </div>
    </div>
    <p class="text-center color-grey">本站全部作品（包括小说、书评和帖子）版权为原创作者所有 本网站仅为网友写作提供上传空间储存平台。本站所收录作品、互动话题、书库评论及本站所做之广告均属其个人行为
与本站立场无关。网站页面版权为初心创文所有，任何单位，个人未经授权不得转载、复制、分发，以及用作商业用途。</p>
</div>
<div class="footer-bg" id="footer-bg"></div>
<?php $this->endContent(); ?>