<?php include_once('./common/header.php'); ?>

<link rel="stylesheet" href="css/form.css">


<h1>����һ���������</h1>

<form method="POST" action="handle.php">
	<table class="add-case-table">
		<colgroup>
			<col class="property" />
			<col class="value" />
		</colgroup>

		<?php
			$task_name = '';
			$task_target_uri = '';
			$task_inject_uri = '';
			$modify_tag = '';


			echo('
				<tr>
					<th>��Ʒ:</th>
					<td>
						<select name="productline" required>
							<option value="">--��ѡ���Ʒ��--</option>
							<option value="84">����ҵ��</option>
							<option value="86">��Ʒƽ̨</option>
							<option value="87">ҵ��ȫ</option>
							<option value="94">����ƽ̨</option>
							<option value="105">�̻�ƽ̨</option>
							<option value="106">SNS</option>
							<option value="118">��Ӫ����</option>
							<option value="206">�ⲿ��Ʒ��</option>
							<option value="257">UED</option>
							<option value="336">ͨ�ò�Ʒ</option>

						</select>
						<select name="project" required>
							<option value="">--��ѡ���Ʒ--</option>
							<option value="1">�ҵ��Ա�</option>
							<option value="2">�Խ��</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>����:</th>
					<td>
						<input type="text" name="task_name" class="input-box" required/>
					</td>
				</tr>
				<tr>
					<th>����:</th>
					<td>
						<textarea type="text" name="description" class="input-box"></textarea>
					</td>
				</tr>
				<tr>
					<th>����ҳ��:</th>
					<td>
						<input type="url" name="task_target_uri" class="input-box"/>
					</td>
				</tr>
				<tr>
					<th>SVN:</th>
					<td>
						<input type="text" name="svn" class="input-box"/>
					</td>
				</tr>
				<tr>
					<th>��ʱ:</th>
					<td>
						<input type="hidden" name="timer">
						<input type="checkbox" name="timers" value="1" />����һ 
						<input type="checkbox" name="timers" value="2" />���ڶ� 
						<input type="checkbox" name="timers" value="3" />������ 
						<input type="checkbox" name="timers" value="4" />������ 
						<input type="checkbox" name="timers" value="5" />������ 
						<input type="checkbox" name="timers" value="6" />������ 
						<input type="checkbox" name="timers" value="7" />������
							<br/>	
						ÿ��ع��ʱ���: <input type="text" name="first" value="18:30" size="5" style="width:40px">
						�ع�Ƶ��: <input type="text" name="interval" value="" size="5" style="width:40px">
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

<?php include_once('./common/footer.php'); ?>

<script src="http://a.tbcdn.cn/s/kissy/1.2.0/kissy-min.js"></script>
<script>
KISSY.use('sizzle', function(S) {
	var $ = KISSY.all;

	$('form').on('submit', function() {
		var values = S.map($('[name=timers]:checked'), function(timers) {
			return $(timers).val();
		});
		var time = $('[name=timer_time]').val();

		$('[name=timer]').val(values.join(','));
	});
});
</script>