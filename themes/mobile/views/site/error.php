<style>
    body{
        background: #fff
    }
    .error-tips h1{
        font-size: 96px;
        text-shadow: 10px 15px 10px #ccc;
    }
    .error-tips p{
        color: #ccc
    }
    .error-tips .error-msg{
        color: #333
    }
</style>
<div class="alert text-center error-tips">
    <h1><?php echo $code; ?></h1>  
    <p>您要求访问不存在的页面，臣妾做不到啊！</p>
    <p class="error-msg"><?php echo CHtml::encode($message); ?></p>   
    <p class="color-grey"><span id="wait">5</span>秒后返回上一页</p>
    <p>
        <a href="javascript:;" onclick="history.back()" class="btn btn-danger btn-sm">返回上一页</a>
        <?php echo CHtml::link('返回首页',  zmf::config('baseurl'),array('class'=>'btn btn-danger btn-sm'));?>
    </p>
</div>
<script type="text/javascript">
(function(){var wait = document.getElementById('wait');var interval = setInterval(function(){var time = --wait.innerHTML;if(time <= 0) {history.back();clearInterval(interval);};}, 1000);})();
</script>