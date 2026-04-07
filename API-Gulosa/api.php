<?php
    header("Content-type: application/json");

    $usuarios = [
        ["id" => 1, "nome" => "Maria Souza", "email" => "maria@gmail.com"],
        ['id' => 2, "nome" => "João Silva", "email" => "joao@email.com"]
        ];

        echo json_encode($usuarios);