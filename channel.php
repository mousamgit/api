<!-- channel.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Channel App</title>
    <!-- Add these links in the head section of your HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>
<!-- Include Vue.js -->
<script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-select@4/dist/vue-select.css">
<script src="https://cdn.jsdelivr.net/npm/vue-select@4"></script>

<!-- Your HTML content here -->
<div id="app">



    <!-- Channel List Component -->
    <channel-list :channels="channels"></channel-list>

</div>

<!-- Include your main JavaScript file -->
<script type="module" src="js/channel.js" defer></script>
<!--<script>-->
<!--    import AddChannel from 'js/components/channel/AddChannel.js';-->
<!---->
<!--    export default {-->
<!--        components: {-->
<!--            AddChannel,-->
<!--        },-->
<!--        data() {-->
<!--            return {-->
<!--                isModalOpen: false,-->
<!--                // ... other data ...-->
<!--            };-->
<!--        },-->
<!--        // ... other options ...-->
<!--    };-->
<!--</script>-->


</body>
</html>


