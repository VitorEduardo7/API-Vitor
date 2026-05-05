<?php

// 1. CONFIGURAÇÃO DE CABEÇALHO: Define que a resposta será um JSON em UTF-8
header("Content-Type: application/json; charset=UTF-8");

// 2. IDENTIFICAÇÃO DO MÉTODO: Verifica se é GET, POST, etc.[cite: 1, 2]
$metodo = $_SERVER['REQUEST_METHOD'];

// 3. BANCO DE DADOS EM ARQUIVO: Define o nome do arquivo JSON[cite: 1, 2]
$arquivo = 'usuarios.json';

// 4. VERIFICAÇÃO DE EXISTÊNCIA: Se o arquivo não existir, cria um com array vazio[cite: 1, 2]
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// 5. LEITURA DOS DADOS: Transforma o conteúdo do JSON em um array PHP[cite: 1, 2]
$usuarios = json_decode(file_get_contents($arquivo), true);

// 6. ROTEAMENTO DA API: Decide o que fazer com base no método HTTP[cite: 1, 2]
switch ($metodo) {
    
    case 'GET':
        // Se houver um ID na URL (ex: ?id=2), busca um usuário específico
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $usuario_encontrado = null;

            foreach ($usuarios as $usuario) {
                if ($usuario['id'] == $id) {
                    $usuario_encontrado = $usuario;
                    break;
                }
            }

            if ($usuario_encontrado) {
                echo json_encode($usuario_encontrado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404); // Erro: Não encontrado[cite: 1, 2]
                echo json_encode(["erro" => "Usuário não encontrado."], JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Se não houver ID, retorna a lista completa[cite: 1, 2]
            echo json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':
        // Pega os dados enviados no corpo (Body) da requisição[cite: 1, 2]
        $dados = json_decode(file_get_contents('php://input'), true);
        
        // Validação básica: Nome e Email são obrigatórios
        if (!isset($dados["nome"]) || !isset($dados["email"])) {
            http_response_code(400); // Erro: Requisição inválida[cite: 1]
            echo json_encode(["erro" => "Nome e email são obrigatórios."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // GERAÇÃO AUTOMÁTICA DE ID: Verifica o maior ID e soma 1[cite: 2]
        $novo_id = 1;
        if (!empty($usuarios)) {
            $ids = array_column($usuarios, 'id');
            $novo_id = max($ids) + 1;
        }

        // Monta o novo usuário[cite: 2]
        $novo_usuario = [
            "id" => $novo_id,
            "nome" => $dados["nome"],
            "email" => $dados["email"]
        ];

        // Adiciona ao array e salva no arquivo[cite: 1, 2]
        $usuarios[] = $novo_usuario;
        file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Retorna sucesso e os dados do usuário criado[cite: 2]
        echo json_encode([
            "mensagem" => "Usuário inserido com sucesso!",
            "usuario" => $novo_usuario
        ], JSON_UNESCAPED_UNICODE);
        break;

    default:
        // Caso tentem usar DELETE ou PUT, que ainda não foram implementados[cite: 1, 2]
        http_response_code(405); 
        echo json_encode(["erro" => "Método não permitido!"], JSON_UNESCAPED_UNICODE);
        break;
}

?>