<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Channel App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
<!-- Include Vue.js -->
<script src="https://cdn.jsdelivr.net/npm/vue@3"></script>

<!--  HTML content here -->
<div id="app">
    <channel-details :attributes="attributes" :products="products"></channel-details>
</div>

<!-- Include your main JavaScript file -->
<script type="module" src="../js/channel_details.js" defer></script>

</body>
</html>


