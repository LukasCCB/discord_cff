<?php
/*
 * Copyright (c) 08/07/23, 15:27.
 * Created By WebZow Soluções Digitais.
 * Site: https://webzow.com
 * Discord: https://discord.gg/TgCccsKSYu
 */

class Environment
{
    /**
     * Método para carregar as variáveis de ambiente do projeto.
     * @param string $dir
     */
    public static function load($dir)
    {
        // Verificar se o arquivo .env existe
        if (!file_exists($dir.'/.env'))
        {
            return false;
        }

        // Definir as variaveis de ambiente.w
        $lines = file($dir.'/.env');

        foreach ($lines as $line)
        {
            @putenv(trim($line));
        }
    }
}