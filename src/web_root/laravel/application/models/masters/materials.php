<?php

namespace Masters;
use Laravel\Cache;
use Laravel\Database as DB;
use Laravel\Log;

class Materials {
	
	/**
	 * 材種を全て取得します。
	 *
	 * @return array sswm_materialのデータ一覧
	 */
	public static function getAll() {
		return DB::table('m_material')->order_by('material_code', 'asc')
			->get();
	}
}
