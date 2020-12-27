<?php

session_start();
unset($_SESSION['username']);
unset($_SESSION['is_auth']);
unset($_SESSION['id']);
unset($_SESSION['avatar']);
header("Location: /index.php");
