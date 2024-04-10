<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guestbook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .entry {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Guestbook</h1>
        <!-- Display existing entries -->
        <?php
            function OpenCon()
            {
                $dbhost = "localhost";
                $dbuser = "root";
                $dbpass = "";
                $db = "guestbook";
                $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n". $conn -> error);
                return $conn;
            }
            function CloseCon($conn)
            {
                $conn -> close();
            }

            // Open database connection
            $conn = OpenCon();

            // Fetch data from the database
            $sql = "SELECT name, content FROM entries";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<div class="entry">';
                    // Display the content fetched from the database
                    echo "<p><strong>Name:</strong> " . $row["name"] . "</p>";
                    echo "<p><strong>Content:</strong> " . $row["content"]. "</p>";
                    echo '</div>';
                }
            } else {
                echo "No entries found.";
            }

            // Close database connection
            CloseCon($conn);
        ?>

        <!-- Add a form to submit new entries -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" required></textarea>
            </div>
            <input type="submit" value="Submit">
        </form>

        <?php
            // Process form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Open database connection
                $conn = OpenCon();

                // Get form data
                $name = $_POST['name'];
                $content = $_POST['content'];

                // Insert data into the database
                $sql = "INSERT INTO entries (name, content) VALUES ('$name', '$content')";

                if ($conn->query($sql) === TRUE) {
                    echo '<p>New entry added successfully.</p>';
                } else {
                    echo '<p>Error: ' . $sql . '<br>' . $conn->error;
                }

                // Close database connection
                CloseCon($conn);
            }
        ?>
    </div>
</body>
</html>