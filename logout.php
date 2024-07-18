<?php
    session_start();
    session_destroy();
    header("Location: /bookia/index.php");