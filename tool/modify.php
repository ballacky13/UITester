<?php include_once('./common/header.php'); ?>

<link rel="stylesheet" href="css/form.css">
<style>
.other {
	position: absolute;
	top: 70px;
	right: 161px;
	font-size: 14px;
	line-height: 2.5;
	border: 1px solid #eee;
	padding: 10px 10px 10px 30px;
	min-width: 200px;
}

</style>

<h1>�޸�����</h1>


<?php
$task_name = '';
$task_target_uri = '';
$task_inject_uri = '';
$modify_tag = '';

$task_id = trim($_REQUEST['task_id']);

if ($task_id !== ''){

	include_once('conn_db.php');

	$sql = 'select * from list where id = ' . $task_id;
	$taskResult = mysql_query($sql);

	if (mysql_num_rows($taskResult) > 0){
		$result_item = mysql_fetch_assoc($taskResult);
		$task_name = $result_item['task_name'];
		$task_target_uri = $result_item['task_target_uri'];
		$description = $result_item['description'];
		$timer = $result_item['timer'];
		$first = $result_item['first'];
		$interval = $result_item['interval'];
		$task_inject_uri = $result_item['task_inject_uri'];
		$svn = $result_item['svn'];
		$description = $result_item['description'];
		$url = $result_item['url'];
		$creator = $result_item['creator'];
		$createtime = $result_item['createtime'];
		$result = $result_item['result'];
		$productline = $result_item['productline'];
		$project = $result_item['project'];

		$modify_tag = 'modify';
	}
}

?>


<form method="POST" action="handle.php">
	<table class="add-case-table">
		<colgroup>
			<col class="property" />
			<col class="value" />
		</colgroup>
<?php

			echo('
				<tr>
					<th>����</th>
					<td>
						<input type="text" name="task_name" class="input-box" value="' . $task_name . '" />
					</td>
				</tr>
				<tr>
					<th>����</th>
					<td>
						<textarea name="description" class="input-box">' . $description . '</textarea>
					</td>
				</tr>
				<tr>
					<th>����ҳ��</th>
					<td>
						<input type="url" name="task_target_uri" class="input-box" value="' . $task_target_uri . '" />
					</td>
				</tr>
				<tr>
					<th>����������ַ</th>
					<td>
						<input type="text" name="svn" class="input-box" value="' . $svn . '" />
					</td>
				</tr>
				<tr>
					<th>��ʱ</th>
					<td>
						<input type="hidden" name="timer" value="' . $timer . '" />
						<input type="checkbox" name="timers" value="1" />����һ 
						<input type="checkbox" name="timers" value="2" />���ڶ� 
						<input type="checkbox" name="timers" value="3" />������ 
						<input type="checkbox" name="timers" value="4" />������ 
						<input type="checkbox" name="timers" value="5" />������ 
						<input type="checkbox" name="timers" value="6" />������ 
						<input type="checkbox" name="timers" value="7" />������
							<br/>	
						ÿ��ع��ʱ���: <input type="text" name="first" value="' . $first . '" size="5" style="width:40px"> 
						���ʱ��: <input type="text" name="interval" value="' . $interval . '" size="5" style="width:40px">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="hidden" name="modify_tag" value="' . $modify_tag . '" />
						<input type="hidden" name="task_id" value="' . $task_id . '" />
						<input type="submit" value="����" />
					</td>
				</tr>
			');
		?>
	</table>
</form>

<div class="other">
	<ul>
		<li>����id: <?php echo $task_id  ?>
		<li>��Ʒ��: <?php 
		
		$productlines = array(
			"84" => '����ҵ��',
			"86" => '��Ʒƽ̨',
			"87" => 'ҵ��ȫ',
			"94" => '����ƽ̨',
			"105" => '�̻�ƽ̨',
			"106" => 'SNS',
			"118" => '��Ӫ����',
			"206" => '�ⲿ��Ʒ��',
			"257" => 'UED',
			"336" => 'ͨ�ò�Ʒ'		
		);
		echo $productlines[$productline]
		
		?>
		<li>��Ʒ: <?php
		
		$projects = array(
			"1" => '�ҵ��Ա�',
			"2" => '�Խ��'
		);
		echo $projects[$project];
		
		?>
		<li>������: <?php echo $creator  ?>
		<li>����ʱ��: <?php echo $createtime  ?>
	</ul>
</div>

<br/>
<hr>
<br/>

<?php include_once('./common/footer.php'); ?>


<script src="http://a.tbcdn.cn/s/kissy/1.2.0/kissy-min.js"></script>
<script>
KISSY.ready(function(S) {
	var $ = S.all;
	$('.J_ViewResult').on('click', function(ev) {
		ev.preventDefault();

		if(S.trim($('#result').html()) !== '') {
			$('#result').toggle();
		} else {
			$('#result').html('û�н��');
		}
	});

	var values = $('[name=timer]').val().split(',');
	$('[name=timers]').each(function(timers) {
		if(S.indexOf(timers.val(), values) > -1) {
			timers.attr('checked', true);
		} else {
			timers.attr('checked', false);
		}
	});
});
</script>