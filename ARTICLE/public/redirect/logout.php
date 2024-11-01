<?php
session_start();

session_destroy();

header("Location: ../page.php?id=1");
exit();

