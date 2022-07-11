<?php
require_once __DIR__ . '/common.php';

$cacheTimeFile = __DIR__ . '/cache_time.txt';
$lastTime = file_get_contents($cacheTimeFile);
if (time() - $lastTime < 120) {
    exit('request limit' . PHP_EOL);
} else {
    file_put_contents($cacheTimeFile, time());
}

$pdo = getPdo();
$page = 1;
do {
    if (false === getData($page++)) break;
    sleep(1);
} while(true);
function getData($page = 1)
{
    global $pdo;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cloud.tencent.com/voc/gateway/DescribeRequirements');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $postdata = '{"Page":' . $page . ',"Size":10,"Status":[],"ProdType":[],"Owner":false,"Content":""}';
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'accept-encoding: deflate',
        'accept-language: zh-CN,zh;q=0.9',
        'content-length: ' . strlen($postdata),
        'content-type: application/json',
        'origin: https://cloud.tencent.com',
        'referer: https://cloud.tencent.com/voc/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36 Edg/96.0.1054.29',
    ]);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $msg = '获取出错[' . curl_errno($ch).']:' . curl_error($ch);
        curl_close($ch);
        exit($msg);
    } else {
        curl_close($ch);
    }

    $json = json_decode($response, true);
    if (!$json['success'] || $json['code'] != 200) {
        exit('接口出错');
    }

    $fields = ['ID','ProdType','Title','Describe','Status','Uin','CreateAt','UpdateAt','UserType','Images','CurrentStatus','PicPaths','FilePaths','ProdTypeID','ApprovedCount','ReadCount','CommentCount','CollectionCount'];
    /*
    if (time() > mktime(23, 59, 59, 1, 3, 2022)) {
        $fields = ['ID','ProdType','Title','Describe','Status','Uin','CreateAt','UpdateAt','UserType','Images','CurrentStatus','PicPaths','FilePaths','ProdTypeID','ReadCount','CommentCount','CollectionCount'];
    }
    */

    foreach ($json['data']['Requirements'] as $requirement) {
        if (1745 > $requirement['ID']) return false;
        //if (2717 > $requirement['ID']) return false;
        if ($pdo->query("SELECT COUNT(*) FROM `requirements` where `ID`={$requirement['ID']}")->fetchColumn()) {
            echo "[*]ID：{$requirement['ID']}；状态：{$requirement['CurrentStatus']}；标题：{$requirement['Title']}；";
            $sql = "UPDATE `requirements` SET ";
            $temp = [];
            foreach($fields as $field) {
                $temp[] = "`{$field}`=:{$field}";
            }
            $sql .= implode(',', $temp);
            $sql .= " WHERE `ID`=:ID LIMIT 1";
        } else {
            echo "[+]ID：{$requirement['ID']}；状态：{$requirement['CurrentStatus']}；标题：{$requirement['Title']}；";
            $sql = "INSERT INTO `requirements`(";
            $temp = [];
            foreach($fields as $field) {
                $temp[] = "`{$field}`";
            }
            $sql .= implode(',', $temp);
            $sql .= ") VALUES (";
            $temp = [];
            foreach($fields as $field) {
                $temp[] = ":{$field}";
            }
            $sql .= implode(',', $temp);
            $sql .= ")";
        }
        if (!$sth = $pdo->prepare($sql)) {
            echo "错误：准备要执行的语句失败<br/>" . PHP_EOL;
            continue;
        }
        foreach($fields as $field) {
            $sth->bindParam(':' . $field, $requirement[$field]);
        }
        if ($sth->execute()) {
            echo "成功<br/>" . PHP_EOL;
        } else {
            echo "失败：" . $sth->errorInfo()[2] . "<br/>" . PHP_EOL;
            echo $sql . PHP_EOL;
        }
    }

    if ($page * 10 >= $json['data']['Total']) {
        return false;
    } else {
        return true;
    }
}