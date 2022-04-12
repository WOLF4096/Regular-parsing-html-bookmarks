<?php
$out = $_POST["out"];
$flo = $_FILES["file"]["name"];
if ($out == "html" or $flo == ""){
$te1 = microtime(true);
?>
<!-- 狼介（WOLF4096）    Email: wolf4096@foxmail.com    QQ: 2275203821
 _       __   ____     __     ______   __ __   ____    ____    _____
| |     / /  / __ \   / /    / ____/  / // /  / __ \  / __ \  / ___/
| | /| / /  / / / /  / /    / /_     / // /_ / / / / / /_/ / / __ \ 
| |/ |/ /  / /_/ /  / /___ / __/    /__  __// /_/ /  \__, / / /_/ / 
|__/|__/   \____/  /_____//_/         /_/   \____/  /____/  \____/  

https://github.com/WOLF4096    All Platform ID: WOLF4096
如有修改建议、添加功能、修复Bug等问题，请与本狼联系（不吃人）
-->
<html>
    <head>
        <meta charset="utf-8">
        <title>使用正则解析 HTML 书签 - 狼介(WOLF4096)</title>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <meta name="keywords" content="狼介,WOLF4096,Furry,Wolf,福瑞控,兽人控,野生开发狼,EDA,PHP,HTML" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <style>
            body {background-color: #ccc;padding: 32px;}
            .div_bj {background-color: #eee;padding: 20px;border-radius: 10px;}
            .urltab{float: left;height: 24px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;padding: 4px;font-size: 14px;border-bottom: 1px solid #ccc;}
            .u0{width: 4%;}
            .u1{width: 5%;}
            .u2{width: 14%;}
            .u3{width: 25%;}
            .u4{width: 30%;float: none;}
        </style>
    </head>
    <body>
        <div class="div_bj">
            <div style="height: 80px;">
                <form method="post" enctype="multipart/form-data" style="font-size: 14px;">
                    <b>使用正则解析 HTML 书签</b>&nbsp;&nbsp;
                    <input type="file" name="file" id="file" /><br/>
                    <input type="radio" name="out" value="html" checked>显示 HTML&nbsp;&nbsp;
                    <input type="radio" name="out" value="csv0">输出 CSV_VScode浏览&nbsp;&nbsp;
                    <input type="radio" name="out" value="csv1">输出 CSV_Excel 浏览&nbsp;&nbsp;
                    <input type="submit" name="submit" value="提交" /><br/><?php if($flo <> ""){echo "文件名：".$flo."\n";};?>
                </form>
            </div>
<?php
}
if (($_FILES["file"]["type"] == "text/html") && ($_FILES["file"]["size"] < 1024000)){//文件为html，且小于1000KB
	if ($_FILES["file"]["error"] > 0){
	    echo "Error";
	}else{
	    if(!is_dir('tmp_html')){//没有文件夹则创建
            mkdir('tmp_html');
        }
        $filetime = "tmp_html/".time();//临时文件名
		move_uploaded_file($_FILES["file"]["tmp_name"],$filetime);
        $file = fopen($filetime, "r") or exit("无法打开文件!");
        if ($out == "html" or $out == ""){
            echo '
            <div>
                <div class="urltab u0">序号</div>
                <div class="urltab u1">分类</div>
                <div class="urltab u2">标题</div>
                <div class="urltab u3">链接</div>
                <div class="urltab u2">创建时间</div>
                <div class="urltab u4">图标</div>
            </div>';
        }else{
            $filn = $flo.'.csv';
            $top = "序号,分类,标题,链接,创建时间,图标\n";
        }
        while(!feof($file)){
            $str = fgets($file);
            preg_match('/">(.*?)</', $str, $matches);
            $tit = $matches[1];
            preg_match('/\HREF="(.*?)\"/', $str, $matches);
            $url = $matches[1];
            preg_match('/ADD_DATE="(.*?)\"/', $str, $matches);
            $tim = $matches[1];
            if ($tim <> 0){
                $tim = date('Y-m-d H:i:s',$tim);
            }
            preg_match('/\ICON="(.*?)\"/', $str, $matches);
            $icn = $matches[1];
            if ($tit <> "" and substr($url, 0, 4) <> "http"){
                $fen = $tit;
            }else if ($tit <> "" and $url <> ""){
                $inn ++;
                if ($out == "html" or $out == ""){
                    echo'
            <div>
                <div class="urltab u0">'.$inn.'</div>
                <div class="urltab u1">'.$fen.'</div>
                <div class="urltab u2">'.$tit.'</div>
                <div class="urltab u3">'.$url.'</div>
                <div class="urltab u2">'.$tim.'</div>
                <div class="urltab u4">'.$icn.'</div>
            </div>';
                }else{
                    $top .= "$inn,$fen,$tit,$url,$tim,$icn\n";
                }
            }
        }
        fclose($file);//关闭文件
        unlink($filetime);//处理完后删除临时文件
	}
}
if ($out == "csv0" and $flo <> ""){
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filn);
    echo mb_convert_encoding($top, "UTF-8");//适合记事本、VScode
}elseif ($out == "csv1" and $flo <> ""){
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filn);
    echo mb_convert_encoding($top, "CP936");//适合Excel
}else{
    echo '
        </div>
    </body>
</html>';
    $te2 = microtime(true);
    $cms = (int)(($te2 - $te1)*1000000);
    echo "\n<!--后端处理时间：$cms μs-->\n\n";
}
?>
