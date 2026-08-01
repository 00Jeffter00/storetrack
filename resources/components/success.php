<?php

if(isset($_SESSION["success"])) {
    echo "
        <div class='text-success pt-2 mb-4 text-center'>
            ✅ " .$_SESSION['success'] . "
        </div>
    ";

    unset($_SESSION["success"]);
}