<?php

/*
 * Copyright (c) 05/07/23, 08:22.
 * Created By WebZow Soluções Digitais.
 * Site: https://webzow.com
 * Discord: https://discord.gg/TgCccsKSYu
 */

$result = array(
    'status' => 'success',
    'data' => 'Api /v1/ are waiting for requests.'
);

//return the json response :
header('Content-Type: application/json');
echo json_encode($result, true);
exit();