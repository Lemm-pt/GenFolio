<?php
// Gerar hash para a senha "123456"
$senha = '123456';
$hash = password_hash($senha, PASSWORD_DEFAULT);
echo "Senha: " . $senha . "<br>";
echo "Hash: " . $hash;