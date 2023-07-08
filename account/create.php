<?php
/*
 * Copyright (c) 05/07/23, 08:26.
 * Created By WebZow Soluções Digitais.
 * Site: https://webzow.com
 * Discord: https://discord.gg/TgCccsKSYu
 */

require_once ("../config/autoload.php"); // Load .env vars
require_once ("../config/database.php");
require_once ("../config/response.php");

$db_global = DBConnection::getConnection('CF_SA_GAME');

try {
    // Verificar se todos os parâmetros foram fornecidos
    if (isset($_GET['user_id']) && isset($_GET['pwd']) && isset($_GET['email']) && isset($_GET['discordID']) && isset($_GET['serverID'])) {

        $user_id = $_GET['user_id'];
        $pwd = $_GET['pwd'];
        $email = $_GET['email'];
        $discordID = $_GET['discordID'];
        $serverID = $_GET['serverID'];

        // Chamar a stored procedure PROC_WEB_USER_INFO_INS
        $query = "{CALL PROC_WEB_USER_INFO_INS(:user_id, :pwd, :email, :discordID, :p_Result)}";
        $statement = $db_global->prepare($query);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $statement->bindParam(':pwd', $pwd, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':discordID', $discordID, PDO::PARAM_STR);
        $statement->bindParam(':p_Result', $p_Result, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 10);
        $statement->execute();

        if ($p_Result == -1) {
            returnJSON([
                'status' => false,
                'data' => "Usuário ".$user_id." já existe."
            ]);
        } elseif ($p_Result == -2) {
            returnJSON([
                'status' => false,
                'data' => "E-mail já cadastrado."
            ]);
        } elseif ($p_Result == -3) {
            returnJSON([
                'status' => false,
                'data' => "Erro desconhecido ao criar a conta. Contate o suporte"
            ]);
        }
        elseif ($p_Result == -4) {
            returnJSON([
                'status' => false,
                'data' => "Seu Discord já está vinculado em uma conta CF Fantasy!"
            ]);
        } elseif ($p_Result == 0) {
            returnJSON([
                'status' => true,
                'data' => "Conta ".$user_id." criada com sucesso!"
            ]);
        } else {
            returnJSON([
                'status' => false,
                'data' => "Valor de retorno desconhecido. Contate o suporte"
            ]);
        }
    } else {
        // Response em caso de parâmetros faltando
        returnJSON([
            'status' => false,
            'data' => "Parâmetros faltando. Por favor, forneça user_id, pwd, email, discordID e serverID na URL."
        ]);
    }

} catch (PDOException $e) {
    returnJSON([
        'status' => false,
        'data' => "Erro na consulta: " . $e->getMessage()
    ]);
}