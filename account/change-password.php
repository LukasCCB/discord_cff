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
$hash = "Mo8^&a8!7b8";

try {

    if (isset($_GET['new-pwd']) && isset($_GET['discordID']) && isset($_GET['serverID'])) {

        $pwd = $_GET['new-pwd'];
        $discordID = $_GET['discordID'];
        $serverID = $_GET['serverID'];

        // Verificar se o USER_ID existe na tabela CF_MEMBER
        $query = "SELECT * FROM CF_MEMBER WHERE DISCORD_ID = :discord_id";
        $statement = $db_global->prepare($query);
        $statement->bindParam(':discord_id', $discordID, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // Conta localizada
        if ($result > 0) {
            $hashed_pwd = md5($pwd . $hash);

            // Verificar se a conta já possui um Discord sincronizado
            if (!isset($result['DISCORD_ID']) && !empty($result['DISCORD_ID']) && $result['DISCORD_ID'] !== $discordID) {
                returnJSON([
                    'status' => true,
                    'data' => "Você só pode alterar senha da sua conta vinculada."
                ]);
            } else if ($result['DISCORD_ID'] === $discordID) {

                // Atualizar o valor do DISCORD_ID na tabela CF_MEMBER
                $query = "UPDATE CF_MEMBER SET USER_PASS = :newpwd WHERE DISCORD_ID = :discordID";
                $statement = $db_global->prepare($query);
                $statement->bindValue(':discordID', $discordID);
                $statement->bindValue(':newpwd', $hashed_pwd);
                $statement->execute();

                // Verificar se alguma linha foi afetada pelo UPDATE
                $rowCount = $statement->rowCount();

                if ($rowCount > 0) {

                    returnJSON([
                        'status' => true,
                        'data' => "Sua senha foi alterada com sucesso!"
                    ]);

                } else {

                    returnJSON([
                        'status' => false,
                        'data' => "Nenhum registro foi atualizado. Verifique se o " . $user_id . " existe."
                    ]);
                }

            } else {

                returnJSON([
                    'status' => true,
                    'data' => "Conta CF não vinculada em sua conta do Discord!"
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
            'data' => "Parâmetros faltando. Por favor, forneça new-pwd, discordID e serverID na URL."
        ]);
    }

} catch (PDOException $e) {
    returnJSON([
        'status' => false,
        'data' => "Erro na consulta: " . $e->getMessage()
    ]);
}