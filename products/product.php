<!-- channel.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> App </title>
    <!-- Add these links in the head section of your HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style >
        #app{
            margin-top: 3% !important;
        }
        .modal-fullpage {
            width: 100% !important;
            max-width: 100vw !important;
            margin: 0;
        }
    </style>

</head>
<body>
<!-- Include Vue.js -->
<script src="https://cdn.jsdelivr.net/npm/vue@3"></script>

<!-- Your HTML content here -->
<div id="app">

    <!-- Product List Component -->
    <product-list :products="products"></product-list>

</div>

<!-- Include your main JavaScript file -->
<script type="module" src="../js/components/product/product.js" defer></script>

</body>
</html>


