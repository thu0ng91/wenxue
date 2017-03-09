<?php

/**
 * @filename SitemapController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2017-3-9  10:00:14 
 */
class SitemapController extends Q {

    public function actionAll() {
        $domain = zmf::config('baseurl');
        $time = zmf::time('', 'Y-m-d');
        //小说分类,小说,作者,论坛版块,帖子,文库作者,文库
        $str = <<<BLOG
<sitemap>\r\n<loc>{$domain}sitemap/booklists-1.xml</loc>\r\n<lastmod>{$time}</lastmod>\r\n</sitemap>\r\n
<sitemap>\r\n<loc>{$domain}sitemap/books-1.xml</loc>\r\n<lastmod>{$time}</lastmod>\r\n</sitemap>\r\n
<sitemap>\r\n<loc>{$domain}sitemap/authors-1.xml</loc>\r\n<lastmod>{$time}</lastmod>\r\n</sitemap>\r\n
<sitemap>\r\n<loc>{$domain}sitemap/forums-1.xml</loc>\r\n<lastmod>{$time}</lastmod>\r\n</sitemap>\r\n
<sitemap>\r\n<loc>{$domain}sitemap/threads-1.xml</loc>\r\n<lastmod>{$time}</lastmod>\r\n</sitemap>\r\n
BLOG;
        //文库作者
        $wAuthor = WenkuAuthor::model()->count('status=1');
        $len = ceil($wAuthor / 1000);
        for ($i = 1; $i <= $len; $i++) {
            $str.= <<<BLOG
<sitemap>\r\n<loc>{$domain}sitemap/wenkuAuthors-{$i}.xml</loc>\r\n<lastmod>{$time}</lastmod>\r\n</sitemap>\r\n
BLOG;
        }
        //文库
        $wPosts = WenkuPosts::model()->count('status=1');
        $len2 = ceil($wPosts / 1000);
        for ($i = 1; $i <= $len2; $i++) {
            $str.= <<<BLOG
<sitemap>\r\n<loc>{$domain}sitemap/wenkuPosts-{$i}.xml</loc>\r\n<lastmod>{$time}</lastmod>\r\n</sitemap>\r\n
BLOG;
        }
        $content = <<<SITEMAP
<?xml version="1.0" encoding="UTF-8"?>\r\n<sitemapindex>{$str}</sitemapindex>
SITEMAP;
        header("Content-Type: text/xml; charset=utf-8");
        echo $content;
    }

    public function actionList() {
        $type = zmf::val('type', 1);
        if (!in_array($type, array('booklists', 'books', 'authors', 'forums', 'threads', 'wenkuAuthors', 'wenkuPosts'))) {
            throw new CHttpException(404, '遭了，页面不存在 ');
        }
        $_page = zmf::val('page', 2);
        $page = $_page > 0 ? $_page : 1;
        $start = ($page - 1) * 1000;
        $str = '';
        $domain = zmf::config('domain');
        $time = zmf::time('', 'Y-m-d');
        switch ($type) {
            case 'booklists':
                $cols = Column::allCols();
                foreach ($cols as $colid => $colTitle) {
                    $_url = $domain . Yii::app()->createUrl('/book/index', array('colid' => $colid));
                    $str.= <<<BLOG
<url><loc>$_url</loc><lastmod>{$time}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>
BLOG;
                }
                break;
            case 'books':
                $books = Books::model()->findAll(array(
                    'select' => 'id',
                    'condition' => 'status=1',
                    'order' => 'id ASC'
                ));
                foreach ($books as $val) {
                    $_url = $domain . Yii::app()->createUrl('/book/view', array('id' => $val['id']));
                    $str.= <<<BLOG
<url><loc>$_url</loc><lastmod>{$time}</lastmod><changefreq>daily</changefreq><priority>1</priority></url>
BLOG;
                }
                break;
            case 'authors':
                $authors = Authors::model()->findAll(array(
                    'select' => 'id',
                    'condition' => 'status=1',
                    'order' => 'id ASC'
                ));
                foreach ($authors as $val) {
                    $_url = $domain . Yii::app()->createUrl('/author/view', array('id' => $val['id']));
                    $str.= <<<BLOG
<url><loc>$_url</loc><lastmod>{$time}</lastmod><changefreq>weekly</changefreq><priority>1</priority></url>
BLOG;
                }
                break;
            case 'forums':
                $forums = PostForums::model()->findAll(array(
                    'select' => 'id',
                    'order' => 'id ASC'
                ));
                foreach ($forums as $val) {
                    $_url = $domain . Yii::app()->createUrl('/posts/index', array('forum' => $val['id']));
                    $str.= <<<BLOG
<url><loc>$_url</loc><lastmod>{$time}</lastmod><changefreq>daily</changefreq><priority>0.8</priority></url>
BLOG;
                }
                break;
            case 'threads':
                $threads = PostThreads::model()->findAll(array(
                    'select' => 'id',
                    'condition' => 'status=1',
                    'order' => 'id ASC'
                ));
                foreach ($threads as $val) {
                    $_url = $domain . Yii::app()->createUrl('/posts/view', array('id' => $val['id']));
                    $str.= <<<BLOG
<url><loc>$_url</loc><lastmod>{$time}</lastmod><changefreq>daily</changefreq><priority>0.8</priority></url>
BLOG;
                }
                break;
            case 'wenkuAuthors':
                $sql = "SELECT id FROM {{wenku_author}} WHERE status=1 ORDER BY id ASC LIMIT $start,1000";
                $authors = Yii::app()->db->createCommand($sql)->queryAll();

                foreach ($authors as $val) {
                    $_url = $domain . Yii::app()->createUrl('/wenku/author', array('id' => $val['id']));
                    $str.= <<<BLOG
<url><loc>$_url</loc><lastmod>{$time}</lastmod><changefreq>weekly</changefreq><priority>0.6</priority></url>
BLOG;
                }
                break;
            case 'wenkuPosts':
                $sql = "SELECT id FROM {{wenku_posts}} WHERE status=1 ORDER BY id ASC LIMIT $start,1000";
                $authors = Yii::app()->db->createCommand($sql)->queryAll();
                foreach ($authors as $val) {
                    $_url = $domain . Yii::app()->createUrl('/wenku/post', array('id' => $val['id']));
                    $str.= <<<BLOG
<url><loc>$_url</loc><lastmod>{$time}</lastmod><changefreq>daily</changefreq><priority>0.6</priority></url>
BLOG;
                }
                break;
            default:
                break;
        }
        $content = <<<SITEMAP
<?xml version="1.0" encoding="UTF-8"?>\r\n<urlset>{$str}</urlset>
SITEMAP;
        header("Content-Type: text/xml; charset=utf-8");
        echo $content;
    }

}
