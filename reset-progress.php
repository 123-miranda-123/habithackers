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

function reset_user_habits($current_day, $current_day_of_week, $current_day_of_month, $current_datetime){

    global $conn;  // Declare $conn as global to access it here
    $sql = "SELECT * FROM user_habits";
    $result = $conn->query($sql);

    if (!$result) {
        error_log("Failed to fetch user habits: " . $conn->error);
        return;
    }

    while ($row = $result->fetch_assoc()) {
        $user_id = $row['user_id'];
        $habit_type_id = $row['habit_type_id'];
        $time_frame = $row['time_frame'];
        $last_updated = $row['last_updated'];

        error_log("Checking habit for user_id: $user_id, habit_type_id: $habit_type_id, time_frame: $time_frame, last_updated: $last_updated");

        // Extract date component from last_updated
        $last_updated_date = date('Y-m-d', strtotime($last_updated));

        // Determine if progress should reset
        $should_reset = false;

        if ($time_frame === 'Daily' && $current_day !== $last_updated_date) {
            $should_reset = true;
        } elseif ($time_frame === 'Weekly' && $current_day_of_week == 1 && $last_updated_date !== $current_day) {
            $should_reset = true;
        } elseif ($time_frame === 'Monthly' && $current_day_of_month == 1 && $last_updated_date !== $current_day) {
            $should_reset = true;
        }

        
        if ($should_reset) {
            $update_sql = "UPDATE user_habits SET progress = 0, last_updated = ? WHERE user_id = ? AND habit_type_id = ?";
            $stmt = $conn->prepare($update_sql);

            

            if ($stmt) {
                $stmt->bind_param("sii", $current_datetime, $user_id, $habit_type_id);
                if ($stmt->execute()) {
                    error_log("Progress reset for user_id: $user_id, habit_type_id: $habit_type_id, time_frame: $time_frame");
                } else {
                    error_log("Failed to reset progress for user_id: $user_id, habit_type_id: $habit_type_id. Error: " . $stmt->error);
                }
            } else {
                error_log("Failed to prepare statement for user_id: $user_id, habit_type_id: $habit_type_id. Error: " . $conn->error);
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
        $last_updated = $row['last_updated'];

        error_log("Checking team habit for team_id: $team_id, habit_type_id: $habit_type_id, time_frame: $time_frame, last_updated: $last_updated");

        if ($time_frame == 'Daily') {
            if ($current_day != date('Y-m-d', strtotime($last_updated))) {
                $update_sql = "UPDATE team_habits SET progress = 0, last_updated = ? WHERE team_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $team_id, $habit_type_id);
                $stmt->execute();
                error_log("Daily team habit reset for team_id: $team_id, habit_type_id: $habit_type_id");
            }
        } elseif ($time_frame == 'Weekly') {
            if ($current_day_of_week == 1 && $current_day != date('Y-m-d', strtotime($last_updated))) {
                $update_sql = "UPDATE team_habits SET progress = 0, last_updated = ? WHERE team_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $team_id, $habit_type_id);
                $stmt->execute();
                error_log("Weekly team habit reset for team_id: $team_id, habit_type_id: $habit_type_id");
            }
        } elseif ($time_frame == 'Monthly') {
            if ($current_day_of_month == 1 && $current_day != date('Y-m-d', strtotime($last_updated))) {
                $update_sql = "UPDATE team_habits SET progress = 0, last_updated = ? WHERE team_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $team_id, $habit_type_id);
                $stmt->execute();
                error_log("Monthly team habit reset for team_id: $team_id, habit_type_id: $habit_type_id");
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
        $last_updated = $row['last_updated'];

        error_log("Checking company habit for company_id: $company_id, habit_type_id: $habit_type_id, time_frame: $time_frame, last_updated: $last_updated");

        if ($time_frame == 'Daily') {
            if ($current_day != date('Y-m-d', strtotime($last_updated))) {
                $update_sql = "UPDATE company_habits SET progress = 0, last_updated = ? WHERE company_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $company_id, $habit_type_id);
                $stmt->execute();
                error_log("Daily company habit reset for company_id: $company_id, habit_type_id: $habit_type_id");
            }
        } elseif ($time_frame == 'Weekly') {
            if ($current_day_of_week == 1 && $current_day != date('Y-m-d', strtotime($last_updated))) {
                $update_sql = "UPDATE company_habits SET progress = 0, last_updated = ? WHERE company_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $company_id, $habit_type_id);
                $stmt->execute();
                error_log("Weekly company habit reset for company_id: $company_id, habit_type_id: $habit_type_id");
            }
        } elseif ($time_frame == 'Monthly') {
            if ($current_day_of_month == 1 && $current_day != date('Y-m-d', strtotime($last_updated))) {
                $update_sql = "UPDATE company_habits SET progress = 0, last_updated = ? WHERE company_id = ? AND habit_type_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $current_datetime, $company_id, $habit_type_id);
                $stmt->execute();
                error_log("Monthly company habit reset for company_id: $company_id, habit_type_id: $habit_type_id");
            }
        }
    }
}
?>