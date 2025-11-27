<?php
// public/logout.php

require_once __DIR__ . '/../src/Security/Auth.php';

logout_user();

header('Location: index.php');
exit;
