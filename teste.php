<?php
	include 'classes/DB.php';
	echo '<pre>';
	echo 'Pesquisa:<br>';
	//print_r(DB::consult('select * from clientes'));
	$cliente = DB::consult('select * from clientes where cliente_id = :cliente_id', ['cliente_id' => 1]);
	print_r($cliente);
	
	$paramsInsert = [
		'nome' => 'asd',
		'cpf' => 'asd',
		'email' => 'asd',
		'datanasc' => '2020-04-30',
		'telefone' => 'asd',
		'sexo' => 'F',
		'emergencia' => 1,
		'detalhar_assunto' => 'asd'
	];
	echo 'Nome do cliente: ' . $cliente[0]->nome; // Cliente número zero (registro zero) pego o nome dele
	echo '<br>Insert: ';
	print_r(DB::insert('clientes', $paramsInsert));
	$paramsInsert['cpf'] = 'oi';
	print_r(DB::insert('clientes', $paramsInsert));
	$paramsUpdate = [
		'detalhar_assunto' => 'meu lindo assunto'
	];
	$paramsWhere = [
		'cpf' => 'oi' // aqui geralmente vai o codigo do cliente
	];
	echo '<br>Update: ';
	print_r(DB::update('clientes', $paramsUpdate, $paramsWhere));
	$paramsDelete = [
		'cpf' => 'asd'  // aqui geralmente vai o codigo do cliente
	];
	echo '<br>Delete: ';
	print_r(DB::delete('clientes', $paramsDelete));
	
	echo '<br>Fim';
?>
