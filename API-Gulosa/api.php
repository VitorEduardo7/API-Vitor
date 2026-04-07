<?php
    header("Content-type: application/json");

    $metodo = $_SERVER['REQUEST_METHOD'];

    // echo "Metódo da requisição: " .$metodo;


    switch ($metodo) {
    case 'GET':
    echo "AQUI AÇÕES DO MÉTODO GET";
    break;
    case 'POST':
    echo "AQUI AÇÕES DO MÉTODO POST";
    break;
    default:
    echo "MÉTODO NÃO ENCONTRADO!";
    break;
    }