<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Popup with Inputs and Table</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .popup {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .popup h2 {
      margin-top: 0;
    }

    .dropdown, .input-group {
      width: 100%;
      margin: 10px 0;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      padding: 10px 15px;
      margin: 10px 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .open-btn {
      background-color: #007bff;
      color: white;
    }

    .cancel-btn {
      background-color: #f44336;
      color: white;
    }

    .submit-btn {
      background-color: #4caf50;
      color: white;
    }

    .greyed-out {
      filter: brightness(0.5);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table th, table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }

    table th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>
  <div id="main-content">
    <button class="open-btn" onclick="openPopup()">Open Popup</button>

    <!-- Table to hold data -->
    <table>
      <thead>
        <tr>
          <th>Option Selected</th>
          <th>Input 1</th>
          <th>Input 2</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="3">No data available yet.</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div id="overlay" class="overlay">
    <div class="popup">
    <form action="create-habit.php" method="POST">
          <h2>Create a New Habit</h2>
          <div class="input-group">
                <select name="habit-type" id="habit-type" class = "dropdown" required>
                    <option value="" disabled selected>Select Habit Type</option>
                    <option value="exercise">Exercise</option>
                    <option value="reading">Reading</option>
                    <option value="journaling">Journaling</option>
                    <option value="meditation">Meditation</option>
                    <option value="hydration">Hydration</option>
                    <option value="sleep">Sleep</option>
                    <option value="project">Project</option>
                    <option value="skill">Skill Learning</option>
                </select>
            </div>
            <div class="input-group">
                <label for="date">Start Date</label>
                <input type="text" id="date" name="date" placeholder="dd/mm/yyyy" required>
            </div>

            <div class="input-group">
                <label for="number">Frequency</label>
                <input type="number" id="number" name="number" required>
            </div>

            <div class="input-group">
                <select name="habit-type" id="habit-type" class = "dropdown" required>
                    <option value="" disabled selected>Select Time Interval</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>

            <div>
              <button class="cancel-btn" onclick="closePopup()">Cancel</button>
              <button class="submit-btn">Submit</button>
            </div>


  <script>
    function openPopup() {
      document.getElementById('overlay').style.display = 'flex';
      document.getElementById('main-content').classList.add('greyed-out');
    }

    function closePopup() {
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('main-content').classList.remove('greyed-out');
    }
  </script>
</body>
</html>
