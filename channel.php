
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Channels List</title>
    <!-- Include Vue.js -->
    <script src="https://unpkg.com/vue@3"></script>
    <!-- Add some styling (you can adjust this based on your preferences) -->
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div id="app">
    <h1>Channels List</h1>
    <!-- Use the custom component to display the table -->
    <channels-table :channels="channels"></channels-table>
</div>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="./js/channel.js"></script>
</body>
</html>

