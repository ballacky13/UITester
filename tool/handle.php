
<?php
    // include databse connector
    include_once('conn_db.php');

    $modify_tag = $_REQUEST['modify_tag'];
    $task_id = $_REQUEST['task_id'];
    $task_name = trim($_REQUEST['task_name']);
    $task_target_uri = trim($_REQUEST['task_target_uri']);
    $task_inject_uri = 'case/'.$task_id.'.js';
    $task_inject_script = $_REQUEST['task_script'];
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    file_put_contents('../'.$task_inject_uri, $task_inject_script);

    //���ձ����λ��
    $task_inject_uri = 'http://uitest.taobao.net/UITester/'.$task_inject_uri;

    // modify current record
    if ($modify_tag === 'remove'){
        $sql = '
            DELETE FROM `list`
            WHERE id = ' . $task_id;

    } else if ($modify_tag === 'modify' || $task_id != ''){
        $sql = '
            UPDATE list SET
            task_name= "' . $task_name . '",
            task_target_uri= "' . $task_target_uri . '",
            task_inject_uri= "' . $task_inject_uri. '",
            username = "' . $username . '",
            password = "' . $password . '"
            WHERE id = ' . $task_id;

    }
    // add for new record
    else {
        $sql = '
            INSERT INTO list (
                task_name,
                task_target_uri,
                task_inject_uri,
                username,
                password
            ) VALUES (
                "' . $task_name . '" ,
                "' . $task_target_uri . '",
                "' . $task_inject_uri . '",
                "' . $username . '",
                "' . $password . '"
            );
        ';

    }

    $result = mysql_query($sql);


header('Location: /UITester/tool/list.php?result='.$result);
    //$output = '<p>����' . ($result === true ? '�ɹ�' : 'ʧ��') . '��<a href="list.php">����</a> �б�ҳ</p>';

    //print_r('��ִ�� sql: ' . $sql);
    //print_r($output);

    if ($result === false){
    //    print_r('��ѯ������Ϣ [' . mysql_errno() . ']: ' . mysql_error());
    }

?>