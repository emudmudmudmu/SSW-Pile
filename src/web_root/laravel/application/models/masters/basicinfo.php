<?php
namespace Masters;

use Laravel\Database as DB;

class BasicInfo {

	
	private static function _get($key) {
		// TODO キャッシュ化したい。
		return DB::table('m_basicinfo')
			->where_basicinfo_id($key)
			->only('info_value');
	}
	
	
	/**
	 * パーツ出荷場所会社を取得します。
	 */
	public static function getShippingCompany() {
		$company_code = self::_get(BI_SHIPPING_COMPANY);
		return Companies::get($company_code);
	}
	
	
	/**
	 * パーツ出荷場所会社名を取得します。
	 */
	public static function getShippingCompanyName() {
		$company = self::getShippingCompany();
		return $company->company_name;
	}
	
	
	/**
	 * パーツ出荷場所会社メールアドレスを取得します。
	 */
	public static function getShippingCompanyEmail() {
		$company = self::getShippingCompany();
		return $company->email;
	}
	
	
	/**
	 * 名称を取得します。
	 */
	public static function getAssociationName() {
		return self::_get(BI_ASSOCIATION_NAME);
	}
	
	
	/**
	 * 郵便番号を取得します。
	 */
	public static function getZip() {
		$zip1 = pad3(self::_get(BI_ZIP1));
		$zip2 = pad4(self::_get(BI_ZIP2));
		return "〒{$zip1}-{$zip2}";
	}
	
	
	/**
	 * 住所を取得します。
	 */
	public static function getAddress() {
		return self::_get(BI_ADDRESS);
	}
	
	
	/**
	 * 電話番号を取得します。
	 */
	public static function getTel() {
		return self::_get(BI_TEL);
	}
	
	
	/**
	 * FAX番号を取得します。
	 */
	public static function getFax() {
		return self::_get(BI_FAX);
	}
	
	
	/**
	 * 担当を取得します。
	 */
	public static function getPerson() {
		return self::_get(BI_PERSON);
	}
	
	
	/**
	 * 銀行名を取得します。
	 */
	public static function getBank() {
		return self::_get(BI_BANK_NAME);
	}
	
	
	/**
	 * 支店名を取得します。
	 */
	public static function getBankBranch() {
		return self::_get(BI_BANK_BRANCH_NAME);
	}
	
	
	/**
	 * 預金種別を取得します。
	 */
	public static function getAccountType() {
		return self::_get(BI_BANK_AC_TYPE);
	}
	
	
	/**
	 * 口座番号を取得します。
	 */
	public static function getAccountNumber() {
		return self::_get(BI_BANK_AC_NUMBER);
	}
	
	
	/**
	 * 名義人を取得します。
	 */
	public static function getAccountName() {
		return self::_get(BI_BANK_AC_HOLDER);
	}
	
	
}
