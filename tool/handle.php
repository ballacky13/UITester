<?php
    // include databse connector
    include_once('conn_db.php');

    $modify_tag = $_REQUEST['modify_tag'];
    $task_id = $_REQUEST['task_id'];
    $task_name = trim($_REQUEST['task_name']);
    $task_target_uri = trim($_REQUEST['task_target_uri']);
    $task_inject_uri = 'case/'.$task_id.'.js';
    $description = $_REQUEST['description'];
    $timer = $_REQUEST['timer'];
    $svn = $_REQUEST['svn'];
    $creator = 'admin';
    $productline = $_REQUEST['productline'];
    $project = $_REQUEST['project'];

    //file_put_contents('../'.$task_inject_uri, $task_inject_script);

    //���ձ����λ��
    //$task_inject_uri = 'http://uitest.taobao.net/'.$task_inject_uri;

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
            description = "' . $description . '",
            timer = "' . $timer . '",
			svn = "' . $svn . '"
            WHERE id = ' . $task_id;

    }
    // add for new record
    else {
        $sql = '
            INSERT INTO list (
                task_name,
                task_target_uri,
                task_inject_uri,
                description,
				timer,
				svn,
				creator,
				createtime,
				productline,
				project
            ) VALUES (
                "' . $task_name . '" ,
                "' . $task_target_uri . '",
                "' . $task_inject_uri . '",
                "' . $description . '",
                "' . $timer . '",
                "' . $svn . '",
                "' . $creator . '",
                " now() ",
                "' . $productline . '",
                "' . $project . '"
            );
        ';

    }

    $result = mysql_query($sql);

	//echo $sql;
	//echo $result;


header('Location: list.php?result='.$result);
    //$output = '<p>����' . ($result === true ? '�ɹ�' : 'ʧ��') . '��<a href="list.php">����</a> �б�ҳ</p>';

    //print_r('��ִ�� sql: ' . $sql);
    //print_r($output);

    if ($result === false){
    //    print_r('��ѯ������Ϣ [' . mysql_errno() . ']: ' . mysql_error());
    }

?>