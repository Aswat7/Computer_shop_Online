<?php
require_once __DIR__ . '/../../config/database.php';
(new ReviewController($conn))->add();
