<?php
include '../login_checking.php';
    include '../functions.php';
    $username = $_SESSION["username"];


?>
<!DOCTYPE html>
<html lang="en">
<head>

    <?php include '../header.php'; ?>
    <script>
    const myapp = Vue.createApp({});
</script>
    <script src="./autofill.js"></script>

    <title>test</title>
</head>
<body>

<div id="app" class="pim-padding">
    <h1>test</h1>
    <form action="" method="post" class="appro-form form-design">
        <autofill  :col1="'code'" :col2="'company'" :db="'customer'" :inputname="'test'"></autofill>
        
    </form>


</form>

</div>


<script>
    const callmyapp = myapp.mount('#app');
</script>
</body>
</html>
