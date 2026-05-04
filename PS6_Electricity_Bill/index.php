<?php
$units = "";
$total_bill = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $units = $_POST["units"];

    if ($units === "") {
        $error = "Please enter electricity units.";
    } elseif (!is_numeric($units) || $units < 0) {
        $error = "Please enter a valid positive number.";
    } else {
        $units = (float)$units;

        if ($units <= 50) {
            $total_bill = $units * 3.50;
        } elseif ($units <= 150) {
            $total_bill = (50 * 3.50) + (($units - 50) * 4.00);
        } elseif ($units <= 250) {
            $total_bill = (50 * 3.50) + (100 * 4.00) + (($units - 150) * 5.20);
        } else {
            $total_bill = (50 * 3.50) + (100 * 4.00) + (100 * 5.20) + (($units - 250) * 6.50);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Bill Calculator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #74ebd5, #9face6);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            width: 100%;
            max-width: 420px;
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-size: 16px;
            color: #444;
            display: block;
            margin-bottom: 8px;
        }

        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #bbb;
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            background: #4a69bd;
            color: white;
            border: none;
            padding: 12px;
            font-size: 17px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #1e3799;
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            background: #eafaf1;
            border-left: 5px solid #27ae60;
            border-radius: 8px;
            color: #1e8449;
            font-size: 18px;
            text-align: center;
        }

        .error {
            margin-top: 20px;
            padding: 15px;
            background: #fdecea;
            border-left: 5px solid #e74c3c;
            border-radius: 8px;
            color: #c0392b;
            font-size: 16px;
            text-align: center;
        }

        @media (max-width: 500px) {
            .container {
                padding: 20px 15px;
            }

            h2 {
                font-size: 22px;
            }

            input[type="number"], button {
                font-size: 15px;
                padding: 10px;
            }

            .result, .error {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Electricity Bill Calculator</h2>

        <form method="POST" action="">
            <label for="units">Enter Units Consumed:</label>
            <input type="number" name="units" id="units" step="any" min="0" value="<?php echo htmlspecialchars($units); ?>" required>
            <button type="submit">Calculate Bill</button>
        </form>

        <?php if ($total_bill !== "") { ?>
            <div class="result">
                Total Electricity Bill = Rs. <?php echo number_format($total_bill, 2); ?>
            </div>
        <?php } ?>

        <?php if ($error !== "") { ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php } ?>
    </div>

</body>
</html>