<?php include ("../dbconn.php");
$has_children = 1;
$get_pid = 0;
if (isset($_GET["pid"])) {
    $get_pid = $_GET["pid"];
}
include "../layouts/session.php"; ?>
<?php include "../layouts/main.php"; ?>
<?php
if (isset($_GET["method"]) && $_GET["method"] == "delete" && isset($_GET["id"])) {
    //daily_report_sql_del
    $tablename = "daily_report";
    $id = $_GET["id"];
    $sql = "update $tablename set is_delete = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $stmt->close();
        echo '<script type="text/javascript">';
        echo 'window.location.href="daily_report_index.php";';
        echo '</script>';
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<head>
    <?php includeFileWithVariables(
        "layouts/title-meta.php",
        ["title" => "daily_report"]
    ); ?>
    <?php include "../layouts/head-css.php"; ?>
</head>

<body>
    <div id="layout-wrapper">
        <?php include "../layouts/menu.php"; ?>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <?php
                    includeFileWithVariables("../layouts/page-title.php", [
                        "pagetitle" => "首页",
                        "title" => "项目列表",
                        "url" => "daily_report_index.php",
                    ]); ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h5 class="card-title mb-0">daily_report</h5>
                                    <div>
                                        <a href="daily_report_index.php?pid=<?= $get_pid ?>&method=add" type="button"
                                            class="btn btn-success add-btn"><i
                                                class="ri-add-line align-bottom me-1"></i>
                                            add</a>
                                        <?php if (isset($_GET["pid"])) { ?>
                                            <a href="daily_report_index.php" type="button"
                                                class="btn btn-danger waves-effect waves-light" accesskey="b">Back</a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="<?php if (isset($_GET["pid"])) {
                                        echo "example";
                                    } else {
                                        echo "";
                                    } ?>"
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>name</th>
                                                <th>num_year</th>
                                                <th>num_month</th>
                                                <th>num_week</th>
                                                <th>is_even</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($_GET["pid"])) {
                                                $tablename = "daily_report";
                                                if ($has_children == 0) {
                                                    $sql = "select * FROM $tablename WHERE is_delete = 0 order by id desc";
                                                } else {
                                                    $sql = "select * FROM $tablename WHERE is_delete = 0 and pid=$get_pid order by sort asc, id asc";
                                                }
                                                $result = $conn->query($sql);
                                                if ($result) {
                                                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                                                    foreach ($rows as $row) {
                                                        echo "<tr>";
                                                        if ($has_children == 0) {
                                                            echo "<td>" . $row['id'] . "</td>";
                                                            echo "<td><a href='daily_report_index.php?id=" . $row['id'] . "&method=edit'>" . $row['name'] . "</a></td>";
                                                        } else {
                                                            echo "<td><a href='daily_report_index.php?pid=" . $row['id'] . "'>" . $row['id'] . "</a></td>";
                                                            echo "<td><a href='daily_report_index.php?pid=" . $row['id'] . "'>" . $row['name'] . "</a></td>";
                                                        } ?>
                                                        <td><?= $row['num_year'] ?></td>
                                                        <td><?= $row['num_month'] ?></td>
                                                        <td><?= $row['num_week'] ?></td>
                                                        <td><?= $row['is_even'] ?></td>
                                                        <td>
                                                            <div class=' d-inline-block'>
                                                                <a href='daily_report_index.php?id=<?= $row['id'] ?>&method=view'><i
                                                                        class='ri-eye-fill align-bottom me-2 text-muted'></i>
                                                                    View</a>
                                                                <a class='edit-item-btn'
                                                                    href='daily_report_index.php?id=<?= $row['id'] ?>&method=edit'><i
                                                                        class='ri-pencil-fill align-bottom me-2 text-muted'></i>
                                                                    Edit</a>
                                                                <a class='edit-item-btn'
                                                                    href='daily_report_index.php?id=<?= $row['id'] ?>&method=delete'><i
                                                                        class='ri-delete-bin-fill align-bottom me-2 text-muted'></i>
                                                                    Delete</a>
                                                            </div>
                                                        </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    echo "Error: " . $sql . "<br>" . $conn->error;
                                                }
                                                $conn->close();
                                            } else {
                                                $tablename = "daily_report";
                                                $sql = "select * FROM $tablename WHERE is_delete = 0 AND pid=0";
                                                $result = $conn->query($sql);
                                                function renderRow($row, $level)
                                                {
                                                    global $conn, $tablename;
                                                    $indent = str_repeat("&nbsp;", $level * 4) . ($level > 0 ? "- " : "&nbsp;&nbsp;");
                                                    ?>
                                                    <tr>
                                                        <td><a
                                                                href='daily_report_index.php?pid=<?= $row['id'] ?>'><?= $row['id'] ?></a>
                                                        </td>
                                                        <td class='indent' style='padding-left: <?= ($level * 20) ?>px;'>
                                                            <?= $indent ?><a
                                                                href='daily_report_index.php?pid=<?= $row['id'] ?>'><?= $row['name'] ?></a>
                                                        </td>
                                                        <td><?= $row['num_year'] ?></td>
                                                        <td><?= $row['num_month'] ?></td>
                                                        <td><?= $row['num_week'] ?></td>
                                                        <td><?= $row['is_even'] ?></td>
                                                        <td>
                                                            <div class='d-inline-block'>
                                                                <a
                                                                    href='daily_report_index.php?id=<?= $row['id'] ?>&method=view'><i
                                                                        class='ri-eye-fill align-bottom icon text-muted'></i>
                                                                    View</a>
                                                                <a class='edit-item-btn'
                                                                    href='daily_report_index.php?id=<?= $row['id'] ?>&method=edit'><i
                                                                        class='ri-pencil-fill align-bottom icon text-muted'></i>
                                                                    Edit</a>
                                                                <a class='edit-item-btn'
                                                                    href='daily_report_index.php?id=<?= $row['id'] ?>&method=delete'><i
                                                                        class='ri-delete-bin-fill align-bottom icon text-muted'></i>
                                                                    Delete</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $sub_sql = "select * FROM $tablename WHERE is_delete = 0 AND pid=" . $row['id'];
                                                    $sub_result = $conn->query($sub_sql);
                                                    if ($sub_result && $sub_result->num_rows > 0) {
                                                        $sub_rows = $sub_result->fetch_all(MYSQLI_ASSOC);
                                                        foreach ($sub_rows as $sub_row) {
                                                            renderRow($sub_row, $level + 1);
                                                        }
                                                    }
                                                }
                                                if ($result) {
                                                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                                                    foreach ($rows as $row) {
                                                        renderRow($row, 0);
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4'>Error: " . $conn->error . "</td></tr>";
                                                }
                                                $conn->close();
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "../layouts/footer.php"; ?>
        </div>
    </div>
    <?php include "../layouts/vendor-scripts.php"; ?>
    <script>
        //id,name,name_en,name_abbr,address_en,address_cn,google_review,is_china,map_baidu,map_google,tel,opening,is_lawfirm,
        $("#example").DataTable({
            "columnDefs": [{
                "targets": [0, 1, -1],
                "visible": true,
            },
            {
                "targets": "_all",
                "visible": false,
            }
            ],
            "order": [
                [0, "desc"]
            ],
            "pageLength": 100
        });
    </script>
</body>

</html>