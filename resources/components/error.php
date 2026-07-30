<?php

if(isset($_SESSION["error"])) {
    echo "
        <div class='text-danger pt-2 mb-4 text-center'>
            ⚠️ " .$_SESSION['error'] . "
        </div>
    ";

    unset($_SESSION["error"]);
}