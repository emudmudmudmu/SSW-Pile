<?php

/**
 * マイグレーション向けコード生成
 *
 * デフォルトのDBからマイグレーションコードを
 * 生成する
 * MySQL用です。
 *
 */
class Sashimi_Task extends Task
{
	// ４空白
	const SPS = '    ';

	/**
	 * @var array 先頭の生成コード
	 */
	private $head = array(
		'/**',
		' * ',
		' */',
		'public function up()',
		'    {',
	);

	/**
	 * @var array 中間の生成コード
	 */
	private $body = array(
		'    }',
		'/**',
		' * ',
		' */',
		'    public function down()',
		'    {',
	);

	/**
	 * @var type 末尾の生成コード
	 */
	private $tail = array(
		'    }',
		'',
		'}',
		'// End of generation',
		'',
	);

	/**
	 * 生成コードの表示
	 *
	 * 使用法：
	 * 		php artisan sashimi
	 *
	 * @return integer 実行結果コード
	 */
	public function run($arguments)
	{
		$outfile = count($arguments) == 0 ? "create_all_table" : $arguments[0];

		echo $this->generator($outfile);

		exit(0);
	}

	/**
	 * 生成コードをファイルに保存する
	 *
	 * 使用法
	 * 		//
	 * 			php artisan sashimi:save create_all_table
	 *
	 * @param string $outfile 出力ファイル名
	 * @return integer 実行結果コード
	 */
	public function save($arguments)
	{
		$outfile = count($arguments) == 0 ? "create_all_table" : $arguments[0];

		$codes = $this->generator($outfile);

		File::put(path('app').'migrations/'.date('Y_m_d_His').'_'.$outfile.'.php', $codes);

		exit(0);
	}

	private function generator($filename)
	{
		$ups = array( );
		$downs = array( );

		// Get table names
		$tables = DB::query("SHOW TABLES");
		foreach ( $tables as $obj ) {

			// Get a table name
			$tablename = head($obj);

			$ups[] = self::SPS.self::SPS."Schema::create('".$tablename."', function (\$table) {";
			$downs[] = self::SPS.self::SPS."Schema::drop('".$tablename."');";

			// Get fields with extra information
			$fields = DB::query("SHOW FULL COLUMNS FROM `".$tablename."`");

			foreach ( $fields as $obj ) {

				// Debug
				$ups[] = $this->fieldsformat($obj, 'Debug Info!', '');

				// Parse field type : like 'decimal(2,3)', 'text', 'varchar(300)', 'int(10) unsigned'
				$ret = preg_match('/(\w+)\(?(\d*)(,(\d*))?\)?\s*(\w*)/', $obj->type, $matches);

				if ( $ret === false ) {
					return 'Intenal regular expression error!';
				}

				$filedtype = $matches[1];
				$columnlength = $matches[2];
				$columnlength2 = $matches[4];
				$attribute = $matches[5];

				// Generate maigration code from field's information
				switch ( $filedtype ) {
					case 'int':
						if ( $obj->extra == "auto_increment" ) {
							$ups[] = self::SPS.self::SPS.self::SPS."\$table->increments('".$obj->field."') // Ignore size :".$columnlength;
						}
						else {
							$ups[] = self::SPS.self::SPS.self::SPS."\$table->integer('".$obj->field."') // Ignore size : ".$columnlength;
						}
						break;
					case 'varchar' :
						if ( $columnlength == "" ) {
							$ups[] = self::SPS.self::SPS.self::SPS."\$table->string('".$obj->field."')";
						}
						else {
							$ups[] = self::SPS.self::SPS.self::SPS."\$table->string('".$obj->field."', ".$columnlength.")";
						}
						break;
					case 'float' :
						$ups[] = self::SPS.self::SPS.self::SPS."\$table->float('".$obj->field."')";
						break;
					case 'decimal' :
						$ups[] = self::SPS.self::SPS.self::SPS."\$table->decimal('".$obj->field."', ".$columnlength.", ".$columnlength2.")";
						break;
					case 'tinyint' :
						if ( $columnlength == "1" ) {
							$ups[] = self::SPS.self::SPS.self::SPS."\$table->boolean('".$obj->field."')";
						}
						else {
							$ups[] = self::SPS.self::SPS.self::SPS."// unknown combination 'tinyint' and length : ".$columnlength." ".
								$obj->field." type:".$obj->type.
								" collation:".$obj->collation." null:".$obj->null." key:".$obj->key.
								" default:".$obj->default." extra:".$obj->extra.
								" privileges:".$obj->privileges." comment:".$obj->comment;
						}
						break;
					case 'timestamp' :
						$ups[] = self::SPS.self::SPS.self::SPS."\$table->timestamp('".$obj->field."')";
						break;
					case 'datetime' :
						$ups[] = self::SPS.self::SPS.self::SPS."\$table->date('".$obj->field."')";
						break;
					case 'text' :
						$ups[] = self::SPS.self::SPS.self::SPS."\$table->text('".$obj->field."')";
						break;
					case 'blob' :
						$ups[] = self::SPS.self::SPS.self::SPS."\$table->blob('".$obj->field."')";
						break;
					default :
						$ups[] = $this
							->fieldsformat($obj, 'Unknow field type!', self::SPS.self::SPS.self::SPS);
				}

				// Nullable
				if ( $obj->null == "YES" ) {
					$ups[] = self::SPS.self::SPS.self::SPS.self::SPS."->nullable()";
				}

				// Default
				if ( $obj->default != "" ) {
					$ups[] = self::SPS.self::SPS.self::SPS.self::SPS."->default('".$obj->default."')";
				}

				// Unsigned
				if ( $attribute == "unsigned" ) {
					$ups[] = self::SPS.self::SPS.self::SPS.self::SPS."->unsigned()";
				}

				// End of generate for a field
				$ups[] = self::SPS.self::SPS.self::SPS.";";
			}
			// End of generate for a table
			$ups[] = self::SPS.self::SPS."});";
		}

		// Combine all output string arrays
		$code = array( '<?php', '' );
		$code[] = 'class '.Str::classify($filename).'{';
		$code[] = $this->head;
		$code[] = $ups;
		$code[] = $this->body;
		$code[] = $downs;
		$code[] = $this->tail;

		// To be flatten array for implode()
		$flatcode = array( );
		array_walk_recursive($code, function($val) use (&$flatcode) {
				$flatcode[] = $val;
			});

		// To string
		return implode(PHP_EOL, $flatcode);
	}

	/**
	 * フィールドオブジェクトを出力用に整形
	 *
	 * @param stdObj $obj フィールドのオブジェクト
	 * @param string $title タイトル
	 * @param string $prefix プレフィックス、通常はインデント用の空白
	 * @return string
	 */
	private function fieldsformat($obj, $title, $prefix)
	{
		$out = array( );

		$out[] = $prefix."// $title";
		$out[] = $prefix."// name:$obj->field type:$obj->type";
		$out[] = $prefix."// collation:$obj->collation null:$obj->null";
		$out[] = $prefix."// key:$obj->key default:$obj->default";
		$out[] = $prefix."// extra:$obj->extra privileges:$obj->privileges";
		$out[] = $prefix."// comment:$obj->comment";

		return $out;
	}

}