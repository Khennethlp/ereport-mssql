<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'load_docs') {

    $sql = "SELECT * FROM m_report_title";
    $stmt = $conn->prepare($sql);

    $c = 0;
    $stmt->execute();
    if ($stmt->rowCount()) {
        while ($k = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $c++;
            echo '<tr >';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $k['main_doc'] . '</td>';
            echo '<td>' . $k['sub_doc'] . '</td>';
            echo '<td style="cursor: pointer;" data-toggle="modal" data-target="#update_docs" onclick="get_docs(&quot;' . $k['id'] . '~!~' . $k['main_doc'] . '~!~' . $k['sub_doc'] . ' &quot;)"><i class="fas fa-ellipsis-h"></i></td>';
            echo '</tr>';
        }
    }
}

if ($method == 'add_new_docs') {

    $main_doc = $_POST['main_doc'];
    $sub_doc = $_POST['sub_doc'];

    $sql = "INSERT INTO m_report_title (main_doc, sub_doc) VALUES (:main_doc, :sub_doc)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':main_doc', $main_doc);
    $stmt->bindParam(':sub_doc', $sub_doc);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'update_docs') {
    $id = $_POST['id'];
    $main_doc = $_POST['main_doc'];
    $sub_doc = $_POST['sub_doc'];

    $sql = "UPDATE m_report_title SET main_doc = :main_doc, sub_doc = :sub_doc WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':main_doc', $main_doc);
    $stmt->bindParam(':sub_doc', $sub_doc);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'del_docs') {
    $id = $_POST['id'];

    $sql = "DELETE FROM m_report_title WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'load_trainings') {

    $sql = "SELECT * FROM t_training_group";
    $stmt = $conn->prepare($sql);

    $c = 0;
    $stmt->execute();
    if ($stmt->rowCount()) {
        while ($k = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $c++;
            echo '<tr >';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $k['training_title'] . '</td>';
            echo '<td style="cursor: pointer;" data-toggle="modal" data-target="#update_training" onclick="get_train(&quot;' . $k['id'] . '~!~' . $k['training_title'] . ' &quot;)"><i class="fas fa-ellipsis-h"></i></td>';
            echo '</tr>';
        }
    }
}

if ($method == 'add_new_training') {

    $training_title = $_POST['training_title'];

    $sql = "INSERT INTO t_training_group (training_title) VALUES (:training_title)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':training_title', $training_title);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'update_training') {
    $id = $_POST['id'];
    $training_title = $_POST['t_title'];

    $sql = "UPDATE t_training_group SET training_title = :training_title WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':training_title', $training_title);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'del_training') {
    $id = $_POST['id'];

    $sql = "DELETE FROM t_training_group WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

