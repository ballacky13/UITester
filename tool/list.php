<?php include_once('./common/header.php'); ?>

<div class="sub-nav">
    <a href="http://uitest.taobao.net/UITester/tool/record/record.html" class="new-record minibtn"">¼��������</a>
</div>

<div class="case-list">
    <ul>
        <?php
        include_once('conn_db.php');

        $sql = 'select * from list';
        $query_list_result = mysql_query($sql);

        $result_num = mysql_num_rows($query_list_result);

        for ($idx = 0; $idx < $result_num; $idx++) {
            $result_item = mysql_fetch_assoc($query_list_result);
            $className = "";
            if ($result_item['totalSpecs'] == 0) {
                $className = "";
            }
            else if ($result_item['totalSpecs'] != 0 && $result_item['failedSpecs'] == 0) {
                $className = "passed";
            }
            else {
                $className = "failed";
            }


            echo('
                                <li class="' . $className . '">
            <div class="name"><p><span>' . $result_item['task_name'] . '</span></p>

                <div class="top-action">
                    <a class="case" href="' . $result_item['task_inject_uri'] . '">��������</a>
                    <a class="url" href="' . $result_item['task_target_uri'] . '">����ҳ��</a>

                </div>
            </div>

            <div class="result">����������<span class="total-specs"><em>' . $result_item['totalSpecs'] . '</em></span> <em>|</em> ʧ������������<span
                class="failed-specs"><em>' . $result_item['failedSpecs'] . '</em></span>

                <div class="bottom-action">
                    <a class="del minibtn" href="handle.php?modify_tag=remove&task_id=' . $result_item['id'] . '">ɾ��</a>
                    <a class="url minibtn" href="apply.php?task_id=' . $result_item['id'] . '">����</a>
                    <a class="record minibtn" href=href="http://uitest.taobao.net/UITester/tool/record/record.html?id=' . $result_item['id'] . '">¼��</a>
                </div>
            </div>

        </li>

                            ');
        }
        ?>


    </ul>


</div>


<?php include_once('./common/footer.php'); ?>