<?php

class Tclass {

	public static function generateModel($tables=array(), $table_prefix='', $is_force=false) {
		$db = SDb::getDbEngine("pdo_mysql");
		if (!$db) {
			die("DbEngine not exits");
		}
		$db->init();
		if (!$tables) {
			$tables = $db->query('show tables', null, null, $db::QUERY_COLUMN);
		} elseif (is_string($tables)) {
			$tables = explode(',', $tables);
		}
		if (!$tables) {
			return false;
		}
		foreach ($tables as $table) {
			$tableName = $table;
			if ($table_prefix) {
				$table = str_replace($table_prefix, '', $table);
			}
			if (strpos($table, '_') === false) {
				$className = $table;
			} else {
				$className = str_replace('_', '', $table);
			}
			$filename = "./app/model/{$className}.class.php";

			if (file_exists($filename) && $is_force == false) {
				continue;
			}
			$fields = $db->selectFields($tableName, true);
			$fields_str = '';
			if ($fields) {
				foreach ($fields as $field) {
					$fields_str .= chr(13) . "\n	public $" . $field . ";";
				}
			}
			$tablePK = $db->selectPK($tableName);
			$content = '';
			$content = file_get_contents('./app/components/model.tpl');
			$content = str_replace('{/className/}', $className, $content);
			$content = str_replace('{/fields/}', $fields_str, $content);
			$content = str_replace('{/tableName/}', $tableName, $content);
			$content = str_replace('{/tablePK/}', $tablePK, $content);

			file_put_contents($filename, $content);
//			pf(htmlspecialchars($content), 1);
		}
	}
}

?>