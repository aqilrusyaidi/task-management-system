<?php  

function get_all_my_notifications($conn, $id){
    $sql = "SELECT id, message, recipient, type, is_read, date 
            FROM notifications 
            WHERE recipient=? 
            ORDER BY id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $notifications ?: []; // always return array
}


function count_notification($conn, $id){
    $sql = "SELECT id FROM notifications WHERE recipient=? AND is_read=0";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->rowCount();
}


function insert_notification($conn, $data){
    $sql = "INSERT INTO notifications (message, recipient, type) VALUES(?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}


function notification_make_read($conn, $recipient_id, $notification_id){
    $sql = "UPDATE notifications SET is_read=1 WHERE id=? AND recipient=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$notification_id, $recipient_id]);
}