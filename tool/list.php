<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="gbk">
	<title>���������б�</title>
    <link rel="stylesheet" href="common.css" />
</head>
<body>
    <div id="page">
        <div id="head">UITester</div>
        <div id="content">

            <div class="case-list">
                <a href="modify.php" class="add-case">�������</a>

                <table class="case-table">
                    <colgroup>
                        <col class="case-name" />
                        <col class="case-action" />
                    </colgroup>
                	<tr>
                		<th>������</th>
                		<th>����</th>
                	</tr>

                    <?php
                        include_once('conn_db.php');

                        $sql = 'select * from list';
                        $queryListResult = mysql_query($sql);

                        $result_num = mysql_num_rows($queryListResult);

                        for($idx = 0; $idx < $result_num; $idx ++){
                            $result_item = mysql_fetch_assoc($queryListResult);

                            echo('
                                <tr>
                                    <td>' . $result_item['task_name'] . '</td>
                                    <td>
                                        <a href="modify.php?id=' . $result_item['id'] . '" class="J_Edit">�޸�</a>
                                        <a href="handle.php?id=' . $result_item['id'] . '" class="J_Remove">ɾ��</a>
                                        <a href="apply.php?id=' . $result_item['id'] . '" class="J_Run">��������</a>
                                    </td>
                                </tr>
                            ');
                        }
                    ?>
                </table>
            </div>

        </div>
        <div id="footer"></div>
        
    </div>
</body>
</html>
