<?php
	
	class DB
	{
		const HOST = 'localhost';
		const DB_NAME = 'database_form';
		const USER = 'root';
		const PASSWORD = '';
		
		private static $conn = null;
		
		private final function __construct()
		{
		}
		
		private final function __clone()
		{
		}
		
		private static final function getInstance()
		{
			if (!isset(self::$conn)) {
				$dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DB_NAME . ';charset=utf8';
				$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
				
				try {
					self::$conn = new PDO($dsn, self::USER, self::PASSWORD, $options);
				} catch (PDOException $e) {
					self::showError($e);
				}
			}
			
			return self::$conn;
		}
		
		private static final function showError(PDOException $e)
		{
			echo '<pre>Ocorreu um erro ao fazer a consulta.' . $e->getMessage() . '</pre>';
		}
		
		public static final function insert($table, $params)
		{
			$sqlParams = '';
			$sqlCampos = '';
			
			foreach ($params as $param => $value) {
				if ($sqlParams !== '') {
					$sqlParams .= ', ';
					$sqlCampos .= ', ';
				}
				
				$sqlParams .= ':' . $param;
				$sqlCampos .= $param;
			}
			
			$sql = "INSERT INTO $table ($sqlCampos) VALUES ($sqlParams)";
			
			return self::alter($sql, $params);
		}
		
		public static final function update($table, $params, $paramsWhere = [])
		{
			$campos = '';
			$where = '';
			
			foreach ($params as $param => $value) {
				if ($campos !== '') {
					$campos .= ', ';
				}
				
				$campos .= $param . ' = :' . $param;
			}
			
			if (!empty($paramsWhere)) {
				foreach ($paramsWhere as $campo => $value) {
					if ($where !== '') {
						$where .= ' AND ';
					}
					
					$where .= $campo . ' = :' . $campo;
				}
				
				$where = 'WHERE ' . $where;
			}
			
			$sql = "UPDATE $table
					SET $campos
					$where";
			
			$params += $paramsWhere;
			
			return self::alter($sql, $params);
		}
		
		public static final function delete($table, $params)
		{
			$where = '';
			
			foreach ($params as $campo => $value) {
				if ($where !== '') {
					$where .= ' AND ';
				}
				
				$where .= $campo . ' = :' . $campo;
			}
			
			$sql = "DELETE FROM $table WHERE $where";
			
			return self::alter($sql, $params);
		}
		
		public static final function consult($select, $params = [])
		{
			$data = [];
			$bd = self::getInstance();
			
			if ($bd === null) {
				return $data;
			}
			
			try {
				$result = $bd->prepare($select);
				$result->execute($params);
				
				while ($return = $result->fetchObject('stdClass')) {
					$data[] = $return;
				}
			} catch (PDOException $e) {
				self::showError($e);
			}
			
			return $data;
		}
		
		private static function alter($sql, $params)
		{
			$bd = self::getInstance();
			
			if ($bd === null) {
				return false;
			}
			
			try {
				if (empty($params)) {
					$bd->exec($sql);
				} else {
					$result = $bd->prepare($sql);
					$result->execute($params);
					$result->rowCount();
				}
				
				return true;
			} catch (PDOException $e) {
				self::showError($e);
				return false;
			}
		}
		
	}