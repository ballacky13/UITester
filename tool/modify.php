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

            <form method="POST" action="save.php">
                <table class="add-case-table">
                    <colgroup>
                        <col class="property" />
                        <col class="value" />
                    </colgroup>

                    <tr>
                        <th>����</th>
                        <td>
                            <input type="text" name="target_name" class="input-box" />
                        </td>
                    </tr>
                    <tr>
                        <th>������ַ</th>
                        <td>
                            <input type="text" name="target_uri" class="input-box" />
                        </td>
                    </tr>
                    <tr>
                        <th>������ַ</th>
                        <td>
                            <input type="text" name="inject_uris[]" class="input-box" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="����" />
                        </td>
                    </tr>
                </table>
            </form>

        </div>
        <div id="footer"></div>
    </div>
</body>
</html>
