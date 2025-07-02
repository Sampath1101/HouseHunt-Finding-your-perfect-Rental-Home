<?php
$error = '';
$location = '';
$budget = '';
$duration = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = trim($_POST['location'] ?? '');
    $budget = trim($_POST['budget'] ?? '');
    $duration = $_POST['duration'] ?? '';

    if ($location === '') {
        $error = "Please enter a location.";
    } elseif (!is_numeric($budget) || $budget <= 0) {
        $error = "Please enter a valid positive budget.";
    } elseif ($duration !== '1' && $duration !== '3') {
        $error = "Please select a valid rental duration.";
    } else {
        $budget = (float)$budget;

        // Validation rules
        if ($duration === '1' && $budget < 2000) {
            $error = "For 1 month rental, budget must be at least $2000.";
        } elseif ($duration === '3' && $budget < 5000) {
            $error = "For 3 months rental, budget must be at least $5000.";
        } else {
            // Redirect to Google Maps search for rental homes at location
            $query = urlencode($location . " rental homes");
            $mapsUrl = "https://www.google.com/maps/search/?api=1&query=$query";
            header("Location: $mapsUrl");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>HouseHunt - Find Your Perfect Rental Home</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      margin: 0; padding: 0;
    }
    .container {
      max-width: 400px;
      margin: 80px auto;
      background: white;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
      text-align: center;
    }
    input[type=text], input[type=number], select {
      width: 100%;
      padding: 10px;
      margin: 10px 0 20px;
      box-sizing: border-box;
      font-size: 16px;
    }
    button {
      background-color: #4CAF50;
      border: none;
      padding: 12px;
      color: white;
      width: 100%;
      font-size: 18px;
      cursor: pointer;
      border-radius: 5px;
    }
    button:hover {
      background-color: #45a049;
    }
    .error {
      color: red;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>HouseHunt</h1>
    <p>Find Your Perfect Rental Home</p>
    <?php if ($error): ?>
      <div class="error"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <form method="post" action="">
      <input
        type="text"
        name="location"
        placeholder="Enter location (city, neighborhood...)"
        value="<?=htmlspecialchars($location)?>"
        required
      />
      <input
        type="number"
        name="budget"
        placeholder="Enter your budget in $"
        value="<?=htmlspecialchars($budget)?>"
        min="1"
        required
      />
      <select name="duration" required>
        <option value="">Select Rental Duration</option>
        <option value="1" <?= $duration === '1' ? 'selected' : '' ?>>1 Month</option>
        <option value="3" <?= $duration === '3' ? 'selected' : '' ?>>3 Months</option>
      </select>
      <button type="submit">Search </button>
    </form>
  </div>
</body>
</html>
