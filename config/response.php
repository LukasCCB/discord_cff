<?php
/*
 * Copyright (c) 05/07/23, 08:29.
 * Created By WebZow Soluções Digitais.
 * Site: https://webzow.com
 * Discord: https://discord.gg/TgCccsKSYu
 */

header('Content-Type: application/json');

function returnJSON($data) {
    echo json_encode($data);
    exit();
}