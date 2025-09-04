<?php
// Configurações do banco
$host    = "localhost";
$usuario = "root";
$senha   = "";
$banco   = "mural";

// Conexão MySQLi
$conexao = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conexao) {
    die("Erro ao conectar: " . mysqli_connect_error());
}

// Suportar acentos e Ç
mysqli_set_charset($conexao, "utf8");
?>