<?php
require_once "database.php";  // Include the database connection

function reset_progress() {
    // Get current date and time in the server's timezone
    $current_date = new DateTime('now', new DateTimeZone('America/Chicago')); // Change to your time zone if needed
    $current_day = $current_date->format('Y-m-d');
    $current_day_of_week = $current_date->format('N'); // 1 (Monday) to 7 (Sunday)
    $current_day_of_month = $current_date->format('d');
    $current_datetime = $current_date->format('Y-m-d H:i:s');


    error_log("Reset progress called at $current_datetime");

    // Reset user habits
    reset_user_habits($current_day, $current_day_of_week, $current_day_of_month, $current_datetime);

    // Reset team habits
    reset_team_habits($current_day, $current_day_of_week, $current_day_of_month, $current_datetime);

    // Reset company habits
    reset_company_habits($current_day, $current_day_of_week, $current_day_of_month, $current_datetime);
}

function reset_user_habits($current_day, $current_day_of_week, $current_day_of_month, $current_datetime) {
    global $conn;  // Declare $conn as global to access it here
    $sql = "SELECT * FROM user_habits";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $user_id = $row['user_id'];
        $habit_type_id = $row['habit_type_id'];
        $time_frame = $row['time_frame'];

        if ($time_frame == 'Daily') {
            if ($current_day != date('Y-m-d', strtotime($row['last_updated']))) {
                $update_sql = "UPDATE user_habits SET progress = 0, last_updated = ? WHERE user_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $user_id, $habit_type_id);
                $stmt->execute();
            }
        } elseif ($time_frame == 'Weekly') {
            if ($current_day_of_week == 7) {
                $update_sql = "UPDATE user_habits SET progress = 0, last_updated = ? WHERE user_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $user_id, $habit_type_id);
                $stmt->execute();
            }
        } elseif ($time_frame == 'Monthly') {
            if ($current_day_of_month == 1) {
                $update_sql = "UPDATE user_habits SET progress = 0, last_updated = ? WHERE user_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $user_id, $habit_type_id);
                $stmt->execute();
            }
        }
    }
}

function reset_team_habits($current_day, $current_day_of_week, $current_day_of_month, $current_datetime) {
    global $conn;  // Declare $conn as global to access it here
    $sql = "SELECT * FROM team_habits";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $team_id = $row['team_id'];
        $habit_type_id = $row['habit_type_id'];
        $time_frame = $row['time_frame'];

        if ($time_frame == 'Daily') {
            if ($current_day != date('Y-m-d', strtotime($row['last_updated']))) {
                $update_sql = "UPDATE team_habits SET progress = 0, last_updated = ? WHERE team_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $team_id, $habit_type_id);
                $stmt->execute();
            }
        } elseif ($time_frame == 'Weekly') {
            if ($current_day_of_week == 7) {
                $update_sql = "UPDATE team_habits SET progress = 0, last_updated = ? WHERE team_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $team_id, $habit_type_id);
                $stmt->execute();
            }
        } elseif ($time_frame == 'Monthly') {
            if ($current_day_of_month == 1) {
                $update_sql = "UPDATE team_habits SET progress = 0, last_updated = ? WHERE team_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $team_id, $habit_type_id);
                $stmt->execute();
            }
        }
    }
}

function reset_company_habits($current_day, $current_day_of_week, $current_day_of_month, $current_datetime) {
    global $conn;  // Declare $conn as global to access it here
    $sql = "SELECT * FROM company_habits";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $company_id = $row['company_id'];
        $habit_type_id = $row['habit_type_id'];
        $time_frame = $row['time_frame'];

        if ($time_frame == 'Daily') {
            if ($current_day != date('Y-m-d', strtotime($row['last_updated']))) {
                $update_sql = "UPDATE company_habits SET progress = 0, last_updated = ? WHERE company_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $company_id, $habit_type_id);
                $stmt->execute();
            }
        } elseif ($time_frame == 'Weekly') {
            if ($current_day_of_week == 7) {
                $update_sql = "UPDATE company_habits SET progress = 0, last_updated = ? WHERE company_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $company_id, $habit_type_id);
                $stmt->execute();
            }
        } elseif ($time_frame == 'Monthly') {
            if ($current_day_of_month == 1) {
                $update_sql = "UPDATE company_habits SET progress = 0, last_updated = ? WHERE company_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $company_id, $habit_type_id);
                $stmt->execute();
            }
        }
    }
}
?>
