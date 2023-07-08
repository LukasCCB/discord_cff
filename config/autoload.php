<?php
/*
 * Copyright (c) 08/07/23, 15:29.
 * Created By WebZow Soluções Digitais.
 * Site: https://webzow.com
 * Discord: https://discord.gg/TgCccsKSYu
 */

@session_start();

// Caminhos absolutos
$dirInt = "webzow/v1/";
DEFINE('DIRPAGE', "https://{$_SERVER['HTTP_HOST']}/{$dirInt}");
$bar = (substr($_SERVER['DOCUMENT_ROOT'], -1) == '/') ? "" : "/";
DEFINE('DIRREQ', "{$_SERVER['DOCUMENT_ROOT']}{$bar}{$dirInt}");
DEFINE("DIR", __DIR__); // Get current dir of file its.

$path = dirname(__DIR__, 1); // Get default level path from project folder "c:/rebing.com.br/aftermatch"
DEFINE("DIRE", $path);

// Init core
require_once(DIRREQ . "config/Environment.php");

Environment::load(DIRREQ); // Load .env