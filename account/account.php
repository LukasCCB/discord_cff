<?php
/*
 * Copyright (c) 05/07/23, 08:26.
 * Created By WebZow Soluções Digitais.
 * Site: https://webzow.com
 * Discord: https://discord.gg/TgCccsKSYu
 */

require_once("../config/database.php");
require_once("../config/response.php");

$db_global = DBConnection::getConnection('CF_SA_GAME');

try {

    $query = "SELECT * FROM CF_MEMBER";

    // Array para armazenar os parâmetros da consulta
    $params = array();

    // Default Filters
    if (isset($_GET['order'])) {
        $order = $_GET['order'];
        $query .= " ORDER BY USN $order";
    }
    // End Default Filters

    // Custom Filters
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        $query .= " WHERE USER_ID = :user_id";
        $params['user_id'] = $user_id;
    }

    if (isset($_GET['email'])) {
        $email = $_GET['email'];
        $query .= " WHERE EMAIL = :email";
        $params['email'] = $email;
    }

    if (isset($_GET['discord'])) {
        $discord = $_GET['discord'];
        if (empty($params)) {
            $query .= " WHERE";
        } else {
            $query .= " AND";
        }
        $query .= " DISCORD_ID = :discord";
        $params['discord'] = $discord;
    }
    // END Custom Filters

    $statement = $db_global->prepare($query);
    $statement->execute($params);

    /*if (isset($_GET['discord']))
        $results = $statement->fetch();
    else*/
        $results = $statement->fetchAll();

    if (empty($results)) {
        returnJSON([
            'status' => false,
            'data' => "Nenhuma conta encontrada!"
        ]);
    }

    /*if (isset($_GET['discord'])) {
        // Response
        returnJSON([
            'status' => true,
            'data' => $results
        ]);
    }*/

    $accounts = array();

    foreach ($results as $row) {
        $account = array(
            'USN' => $row['USN'],
            'USER_ID' => $row['USER_ID'],
            'DISCORD_ID' => $row['DISCORD_ID'],
            'EMAIL' => $row['EMAIL'],
            'ISACTIVE' => $row['ISACTIVE'],
            'REG_DATE' => $row['REG_DATE']
        );
        $accounts[] = $account;
    }

    // Response
    returnJSON([
        'status' => true,
        'data' => $accounts
    ]);

} catch (PDOException $e) {

    returnJSON([
        'status' => false,
        'data' => "Erro na consulta:" . $e->getMessage()
    ]);
}