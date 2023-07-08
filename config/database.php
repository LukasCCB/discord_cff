<?php
/*
 * Copyright (c) 05/07/23, 08:24.
 * Created By WebZow Soluções Digitais.
 * Site: https://webzow.com
 * Discord: https://discord.gg/TgCccsKSYu
 */

class DBConnection {
    private static $connections = array();

    public static function getConnection($dbName) {
        // Verificar se a conexão já existe
        if (isset(self::$connections[$dbName])) {
            return self::$connections[$dbName];
        }

        // Dados de conexão
        $server = getenv('DB_HOST').','.getenv('DB_PORT');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');

        // Configurar opções da conexão
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );

        try {
            // Criar a conexão
            $dsn = "sqlsrv:Server={$server};Database={$dbName}";
            $connection = new PDO($dsn, $username, $password, $options);

            // Armazenar a conexão na lista de conexões
            self::$connections[$dbName] = $connection;

            return $connection;
        } catch (PDOException $e) {
            // Lidar com erros de conexão
            echo "Erro na conexão com o banco de dados: " . $e->getMessage();
            exit();
        }
    }
}
