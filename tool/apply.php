<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="gbk">
    <title>UI Tester</title>

    <link href="http://uitest.taobao.net/UITester/lib/jasmine.css" rel="stylesheet">
    <script src="http://a.tbcdn.cn/s/kissy/1.2.0/kissy.js"></script>
    <!-- <script src="task.js"></script> -->
</head>
<body>

<?php
$task_id = trim($_REQUEST['task_id']);

if ($task_id === '') {
    echo('δָ������ task_id');
    exit;
}

include_once('conn_db.php');

$sql = 'select * from list where id = ' . $task_id;

$query_result = mysql_query($sql);
$result_item = mysql_fetch_assoc($query_result);

$iframe_uri = $result_item['task_target_uri'];
$iframe_uri .= (strpos($iframe_uri, '?') !== false) ? '&' : '?';

$iframe_uri .= 'inject-taskid=' . $result_item['id'];
$iframe_uri .= '&__TEST__';

?>
<style>
    body{
        font-size:12px;
    }
    ul{
        list-style:none;
    }
    .configs{
        margin:0;
        padding:0;
        margin-bottom:10px;
        margin-left:10px;
    }
    #run-status{
        margin-bottom:10px;
    }
    #run-iframe.show{

    }
    #run-iframe.hide{
        position:absolute;;
        top:-1000px;

        left:-1000px;
        visibility:hidden;
    }
    #result{
        margin-bottom:5px;
    }

</style>

<div id="run-status">���ڲ���......</div>
<ul class="configs">
    <li><label><input type="checkbox" id="is-auto-run"/>��ʱִ��</label><span>(��ǰ���ڲ��ܹر�)</span>
        <ul id="show-run-config" style="display:none;">
    <li><label>���ʱ�䣺<select id="long-time">
        <option value="1">1Сʱ</option>
        <option value="6">6Сʱ</option>
        <option value="12">12Сʱ</option>
        <option value="24">24Сʱ</option>
    </select></label></li>

    <li><label>�ʼ���ַ��<input type="text" id="J_MailTo" name="to" placeholder="�����������ַ"></label></li>
</ul>
</li>
<li><label><input type="checkbox" id="is-show-page">��ʾҳ��</label></li>

</ul>

<div id="result"></div>

<iframe class="hide" id="run-iframe" src="<?php echo $iframe_uri ?>" frameborder="0" style="border: 1px solid #000" width="100%"
        height="600px"></iframe>


<script src="../lib/kissy-aio.js"></script>
<script src="../lib/jquery-1.7.2.min.js"></script>
<script src="http://uitest.taobao.net/UITester/lib/jasmine.js"></script>
<script src="http://uitest.taobao.net/UITester/lib/jasmine-html.js" charset="UTF-8"></script>
<script src="http://uitest.taobao.net/UITester/lib/event-simulate.js"></script>
<script src="record/postmsg.js"></script>
<script src="outerRun.js"></script>
</body>
</html>