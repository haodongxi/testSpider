<?php
/**
 * Created by PhpStorm.
 * User: hao
 * Date: 16-11-14
 * Time: 下午9:47
 */


$con1;
$arrPid;
$count = 0;
function connectMysql()
{
    // 假定数据库用户名：root，密码：123456，数据库：RUNOOB
    global $con1;

    if ($con1!=NULL)
    {

        return true;
    }

    $con=mysqli_connect("127.0.0.1","root","123456","test");
    if (mysqli_connect_errno($con))
    {
        //echo "连接 MySQL 失败: " . mysqli_connect_error();
        return false;
    }
   else
   {
       //echo "链接成功";
       $con1 = $con;
       return true;
   }

}

function getName($urlStr)
{
    //初始化
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch,CURLOPT_COOKIE,"q_c1=32cae601d89c48a295221fd9a24a2c63|1479005931000|1479005931000; d_c0=\"AGDAYY371gqPTvN2DQtRpBaASq6C0KbSG6c=|1479005931\"; _zap=b7c0d319-fcaf-4352-b94d-13cc640b3924; _xsrf=734b4595af6d337339f40bf72d74cd8f; l_cap_id=\"NTZmYzA3YzQ5MzNkNGJiZjg0OWZkNjk1YmIxMGZlNTY=|1479396219|058934a4154570fac78db6ccb814e3cb571e4bc8\"; cap_id=\"YTkxNzc2YzFkZjFhNGFkOWI3MzI0MjExMGEzNGFmZjE=|1479396219|44208fbbd85ddfd468bfd4a5576f959339bfe661\"; r_cap_id=\"YmVjZmUxZDNjZGNiNDU0NGI0MjExN2QyNmE0YWI4NGI=|1479396220|1cb5d1a3e6c1ffd82336919a35aac7bac1b9c1bd\"; login=\"MzIyMzg2ZGI1N2I3NGU3ZmI2NGY0NGUyYTE3YThkNDA=|1479396235|f475a9151c0045b717abc120dea1bd7a55eb9ee1\"; __utmt=1; __utma=51854390.543325777.1479396707.1479396707.1479396707.1; __utmb=51854390.6.10.1479396707; __utmc=51854390; __utmz=51854390.1479396707.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmv=51854390.100--|2=registration_date=20151228=1^3=entry_date=20151228=1; a_t=\"2.0ABDM2gTLOQkXAAAA9FpVWAAQzNoEyzkJAGDAYY371goXAAAAYQJVTYtYVVgAVxtvFtQ5NxttDWNTquLRF_QKI_NEDnPdVwE4seckoBFCXNmnc9JIow==\"; z_c0=Mi4wQUJETTJnVExPUWtBWU1CaGpmdldDaGNBQUFCaEFsVk5pMWhWV0FCWEcyOFcxRGszRzIwTlkxT3E0dEVYOUFvajh3|1479396852|87b49a5e22da1947c17e68e33e5516b469a6ab03");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//执行并获取HTML文档内容
    $output = curl_exec($ch);
//释放curl句柄
    curl_close($ch);
//打印获得的数据
    preg_match_all('/(href="https:\/\/www\.zhihu\.com\/people\/)(.)*?"/',$output,$array);

   getUrl($array[0]);

//var_dump(explode("/",$array[0][0])[4]) ;
    //var_dump(rtrim(explode("/",$array[0][0])[4],'""'));

   // return rtrim(explode("/",$array[0][0])[4],'""'); //返回英文名字
}




function getUrl($arrayUrl)
{

    $clearArrays = array();
    for ($i=0;$i<count($arrayUrl);$i++)
    {
        $str = $arrayUrl[$i];

        $name = rtrim( explode("/",$str)[4],'""');//姓名
        $urlname = explode('"',trim($str,'"'))[1];//个人网址
        saveItem($name,$urlname.'/followees');

        $clearArrays[$i] = "$urlname/followees";
         //getName($urlname.'/followees');
        //var_dump(rtrim($str,'""').'/followees');
        //var_dump($str);
        //var_dump($name);
    }
    curl_mul($clearArrays);
}

function saveItem($name1,$url)
{
    global $con1;
    //echo $name1.$url.'*****';
   // var_dump(addslashes($name1));
  //var_dump(addslashes($url));
    if (!reduceMix($name1))
    {
        return;
    }

    $sql="insert into testurl values ('".$name1."','".$url."')";
    if (connectMysql() == true)
    {
       mysqli_query($con1,$sql);
    }
}

function reduceMix($name)
{
    global $con1;
    $sql="select * from testurl where name='".$name."'";
    if (connectMysql() == true)
    {
        $resultNum =  mysqli_query($con1,$sql)->num_rows;
        if ($resultNum>0)
            return false;
        else
            return true;
    }
}

function queryUrl()
{
    $sql = "select * from";
}

function curl_mul($urls)
{


    $mh = curl_multi_init();
    $contentArray = array();
    foreach ($urls as $i => $url)
    {
        $conn[$i] = curl_init($url);
        curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn[$i], CURLOPT_HEADER, 0);
        curl_setopt($conn[$i],CURLOPT_COOKIE,"q_c1=32cae601d89c48a295221fd9a24a2c63|1479005931000|1479005931000; d_c0=\"AGDAYY371gqPTvN2DQtRpBaASq6C0KbSG6c=|1479005931\"; _zap=b7c0d319-fcaf-4352-b94d-13cc640b3924; _xsrf=734b4595af6d337339f40bf72d74cd8f; l_cap_id=\"NTZmYzA3YzQ5MzNkNGJiZjg0OWZkNjk1YmIxMGZlNTY=|1479396219|058934a4154570fac78db6ccb814e3cb571e4bc8\"; cap_id=\"YTkxNzc2YzFkZjFhNGFkOWI3MzI0MjExMGEzNGFmZjE=|1479396219|44208fbbd85ddfd468bfd4a5576f959339bfe661\"; r_cap_id=\"YmVjZmUxZDNjZGNiNDU0NGI0MjExN2QyNmE0YWI4NGI=|1479396220|1cb5d1a3e6c1ffd82336919a35aac7bac1b9c1bd\"; login=\"MzIyMzg2ZGI1N2I3NGU3ZmI2NGY0NGUyYTE3YThkNDA=|1479396235|f475a9151c0045b717abc120dea1bd7a55eb9ee1\"; __utmt=1; __utma=51854390.543325777.1479396707.1479396707.1479396707.1; __utmb=51854390.6.10.1479396707; __utmc=51854390; __utmz=51854390.1479396707.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmv=51854390.100--|2=registration_date=20151228=1^3=entry_date=20151228=1; a_t=\"2.0ABDM2gTLOQkXAAAA9FpVWAAQzNoEyzkJAGDAYY371goXAAAAYQJVTYtYVVgAVxtvFtQ5NxttDWNTquLRF_QKI_NEDnPdVwE4seckoBFCXNmnc9JIow==\"; z_c0=Mi4wQUJETTJnVExPUWtBWU1CaGpmdldDaGNBQUFCaEFsVk5pMWhWV0FCWEcyOFcxRGszRzIwTlkxT3E0dEVYOUFvajh3|1479396852|87b49a5e22da1947c17e68e33e5516b469a6ab03");
        curl_setopt($conn[$i], CURLOPT_SSL_VERIFYPEER, false); // 设置不将爬取代码写到浏览器，而是转化为字符串
        curl_multi_add_handle ($mh,$conn[$i]);
    }

    /*
    do {
        curl_multi_exec($mh,$active);
    } while ($active);
    */
    //======================执行批处理句柄=================================
    $active = null;
    do {
        $mrc = curl_multi_exec($mh, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);


    while ($active and $mrc == CURLM_OK) {

        if(curl_multi_select($mh) === -1){
            usleep(100);
        }
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    }
//====================================================================


    foreach ($urls as $i => $url) {
        $data = curl_multi_getcontent($conn[$i]); // 获得爬取的代码字符串
        $contentArray[$i] = $data;

         // 将字符串写入文件。当然，也可以不写入文件，比如存入数据库
    } // 获得数据变量，并写入文件

    foreach ($urls as $i => $url) {
        curl_multi_remove_handle($mh,$conn[$i]);
        curl_close($conn[$i]);
    }

    curl_multi_close($mh);
    
   foreach($contentArray as $i => $key)
    {
        preg_match_all('/(href="https:\/\/www\.zhihu\.com\/people\/)(.)*?"/',$contentArray[$i],$array);
        getUrl($array[0]);
    }

}

//getName("https://www.zhihu.com/people/hao-dong-xi/followees");
$arrUrls = array();
$arrUrls[0] = "https://www.zhihu.com/people/hao-dong-xi/followees";
curl_mul($arrUrls);