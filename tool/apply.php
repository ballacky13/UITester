<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="gbk">
	<title>UI Tester</title>
    <!--
    <link rel="stylesheet" href="http://a.tbcdn.cn/tbsp/tbsp.css" />
    -->
    <link rel="stylesheet" href="task.css" />

    <script src="http://a.tbcdn.cn/s/kissy/1.2.0/kissy.js"></script>
    <script src="task.js"></script>
</head>
<body>
<a class="J_AddTask" href="">�������</a>

<?php for ($idx = 0; $idx < 2; $idx++){ ?>
<div class="task J_Task">
    <form action="" method="GET" class="task-config J_TaskConfig">
        <table>
            <tr>
                <th>������ַ</th>
                <td>
                    <input type="text" name="target_uri" class="test-input J_TestURI" value="<?php
                        if ($idx == 1){ echo('http://www.baidu.com'); }
                    ?>" />
                </td>
                <td>&nbsp;</td>
            </tr><tr>
                <th>������ַ</th>
                <td class="J_CaseSetting">
                    <input type="text" name="inject_uri[]" class="test-input J_CaseURI" value="<?php
                        $testCaseURI = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                        $slashLastPos = strrpos($testCaseURI, '/');

                        $testCaseURI = substr($testCaseURI, 0, $slashLastPos);

                            
                        if ($idx == 1){ echo('http://' . $testCaseURI . '/test/test-inject.js'); }
                    ?>"  />
                </td>
                <td>
                    <a class="J_AddCase" href="">[+]</a>
                </td>
            </tr>
        </table>

        <a href="" class="J_StartTest">��������</a>
    </form>

    <div class="J_TaskResult task-result">
        ����һ�����Ա���
    </div>

    <!-- ��� frame �ĵط� -->
    <div class="test-frame J_TestFrame"></div>
</div>
<?php } ?>
</body>
</html>
