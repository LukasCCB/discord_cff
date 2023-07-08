<?php
/*
 * Copyright (c) 05/07/23, 08:26.
 * Created By WebZow Soluções Digitais.
 * Site: https://webzow.com
 * Discord: https://discord.gg/TgCccsKSYu
 */

require_once ("../config/autoload.php"); // Load .env vars
require_once("../config/database.php");
require_once("../config/response.php");

$db_global = DBConnection::getConnection('CF_SA_GAME');
$hash = "Mo8^&a8!7b8";


try {
    // Verificar se todos os parâmetros foram fornecidos
    if (isset($_GET['user_id']) && isset($_GET['pwd']) && isset($_GET['discordID']) && isset($_GET['serverID'])) {

        $user_id = $_GET['user_id'];
        $pwd = $_GET['pwd'];
        $discordID = $_GET['discordID'];
        $serverID = $_GET['serverID'];

        // Verificar se o USER_ID já existe na tabela CF_SA_GAME
        $query = "SELECT * FROM CF_MEMBER WHERE USER_ID = :user_id";
        $statement = $db_global->prepare($query);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // Conta localizada
        if ($result > 0) {

            // Converter a senha para MD5
            $hashed_pwd = md5($pwd . $hash);

            // Verificar a senha usando HASHBYTES MD5
            $query = "SELECT COUNT(*) AS count FROM CF_MEMBER WHERE USER_ID = :user_id AND USER_PASS = :hashed_pwd";
            $statement = $db_global->prepare($query);
            $statement->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $statement->bindParam(':hashed_pwd', $hashed_pwd, PDO::PARAM_STR);
            $statement->execute();
            $checkPass = $statement->fetch(PDO::FETCH_ASSOC);

            $count = $checkPass['count'];

            if ($count > 0) {

                // Verificar se a conta já possui um Discord sincronizado
                if (!isset($result['DISCORD_ID']) && !empty($result['DISCORD_ID']) && $result['DISCORD_ID'] !== $discordID) {
                    returnJSON([
                        'status' => true,
                        'data' => "Conta CF já vinculada em outra conta de Discord!"
                    ]);
                } else if ($result['DISCORD_ID'] === $discordID) {
                    returnJSON([
                        'status' => true,
                        'data' => "Conta CF já vinculada em sua conta do Discord!"
                    ]);
                } else {

                    // Atualizar o valor do DISCORD_ID na tabela CF_MEMBER
                    $query = "UPDATE CF_MEMBER SET DISCORD_ID = :discordID WHERE USER_ID = :user_id";
                    $statement = $db_global->prepare($query);
                    $statement->bindValue(':discordID', $discordID);
                    $statement->bindValue(':user_id', $user_id);
                    $statement->execute();

                    // Verificar se alguma linha foi afetada pelo UPDATE
                    $rowCount = $statement->rowCount();

                    if ($rowCount > 0) {

                        returnJSON([
                            'status' => true,
                            'data' => "Conta CF Fantasy vinculado com sucesso em sua conta do Discord!"
                        ]);

                    } else {

                        returnJSON([
                            'status' => false,
                            'data' => "Nenhum registro foi atualizado. Verifique se o ".$user_id." existe."
                        ]);
                    }

                }

            } else {
                returnJSON([
                    'status' => false,
                    'data' => "A senha não corresponde à conta."
                ]);
            }

        } else {

            returnJSON([
                'status' => false,
                'data' => "Conta não encontrada."
            ]);
        }
    } else {
        // Response em caso de parâmetros faltando
        returnJSON([
            'status' => false,
            'data' => "Parâmetros faltando. Por favor, forneça user_id, pwd, discordID e serverID na URL."
        ]);
    }

} catch (PDOException $e) {
    returnJSON([
        'status' => false,
        'data' => "Erro na consulta: " . $e->getMessage()
    ]);
}