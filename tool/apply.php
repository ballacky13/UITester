<?php include_once('./common/header.php'); ?>

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
echo '<script>var taskInfo = {id:' . $result_item['id'] . '}</script>'

?>


<div class="run-case-nav">

    <input type="checkbox" id="run-auto"/><a class="run-auto">��ʱִ��</a>

           <span class="run-auto-set" style="display:none;">

               ���ʱ�䣺<select id="long-time">
               <option value="1">1����</option>
               <option value="2">2����</option>
               <option value="5">5����</option>
               <option value="10">10����</option>
               <option value="30">30����</option>

               <option value="60">1Сʱ</option>
               <option value="120">2Сʱ</option>
               <option value="3600">6Сʱ</option>
               <option value="72000">12Сʱ</option>
               <option value="144000">24Сʱ</option>
           </select>

            �ʼ���ַ��<input type="text" id="J_MailTo" name="to" placeholder="�����������ַ"/>
               <span class="gray">(��ʱ����ʱ���벻Ҫ�ر�Щҳ�棬��������ͨ��ʱ���ἴʱͨ���ʼ�֪ͨ��)</span>
</span>

</div>
<div class="run-case">


    <div id="run-result">���ڲ���......</div>

    <iframe id="run-iframe" src="<?php echo $iframe_uri ?>" frameborder="0" width="100%"
        ></iframe>


    <script src="http://uitest.taobao.net/lib/kissy-aio.js"></script>
    <script src="http://uitest.taobao.net/lib/jquery-1.7.2.min.js"></script>
    <script src="http://uitest.taobao.net/lib/jasmine.js"></script>
    <script src="http://uitest.taobao.net/lib/jasmine-html.js" charset="UTF-8"></script>
    <script src="http://uitest.taobao.net/lib/event-simulate.js"></script>
    <script src="http://uitest.taobao.net/tool/record/postmsg.js"></script>
    <script src="http://uitest.taobao.net/tool/js/outerRun.js"></script>
    <link rel="stylesheet" type="text/css" href="http://uitest.taobao.net/lib/jasmine.css">


</div>


<?php include_once('./common/footer.php'); ?>