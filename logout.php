<?php
    session_start();
?>

<html>
    <body>
        <?php
            session_destroy();
            header("location:login.php");
        ?>
    </body>
</html>