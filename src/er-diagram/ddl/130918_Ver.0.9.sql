SET SESSION FOREIGN_KEY_CHECKS=0;


/* Create Tables */

CREATE TABLE sswd_bill
(
	bill_no int unsigned NOT NULL AUTO_INCREMENT COMMENT '請求書番号',
	bill_nen int unsigned NOT NULL COMMENT '請求年',
	bill_tuki int unsigned NOT NULL COMMENT '請求月',
	bill_company int(3) unsigned COMMENT '請求先',
	total bigint COMMENT '請求金額',
	tax bigint COMMENT '消費税額',
	rate int(3) unsigned COMMENT '税率',
	bill_date date COMMENT '請求書発行日',
	payment_date date COMMENT '入金確認',
	reissue_date date COMMENT '再発行日',
	PRIMARY KEY (bill_no)
) ENGINE = InnoDB COMMENT = '請求書' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswd_bill_meisai
(
	bill_meisai_no int unsigned NOT NULL AUTO_INCREMENT COMMENT '明細番号',
	bill_no int unsigned NOT NULL COMMENT '請求書番号',
	PRIMARY KEY (bill_meisai_no)
) ENGINE = InnoDB COMMENT = '請求書明細' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswd_construction
(
	construction_no varchar(20) NOT NULL COMMENT '認定番号',
	construction_eda int unsigned DEFAULT 0 NOT NULL COMMENT '枝番',
	construction_company int(3) unsigned NOT NULL COMMENT '施工会社',
	nendo int(2) unsigned NOT NULL COMMENT '識別年度',
	company_seq int unsigned COMMENT '会社通番',
	architect_company int(3) unsigned COMMENT '設計会社',
	architect int unsigned COMMENT '設計担当者',
	engineer int unsigned COMMENT '施工管理技術者',
	order_company int(3) unsigned COMMENT '発注元',
	construction_name text COMMENT '工事名称',
	construction_address text COMMENT '工事場所',
	construction_start_date date COMMENT '着工日',
	complete_date date COMMENT '完工日',
	report_date date COMMENT '報告書承認日',
	amount int unsigned COMMENT '打設本数',
	material_code int unsigned COMMENT '材種コード',
	sybt smallint unsigned COMMENT '種別',
	sybt2 smallint unsigned COMMENT '種別2',
	kouzou smallint unsigned COMMENT '構造',
	yoto text COMMENT '用途',
	kiso smallint unsigned COMMENT '基礎形式',
	floor int COMMENT '階数',
	height numeric(5,2) COMMENT '高さ',
	nokidake numeric(5,2) COMMENT '軒高',
	totalarea numeric(6,2) COMMENT '延べ面積',
	depth numeric(5,2) COMMENT '最大施工深さ',
	status smallint unsigned COMMENT 'ステータス',
	create_date timestamp DEFAULT now() NOT NULL COMMENT '登録日時',
	update_date timestamp COMMENT '更新日時',
	delete_date timestamp COMMENT '削除日時',
	del_flg tinyint(1) unsigned DEFAULT 0 NOT NULL COMMENT '削除フラグ',
	PRIMARY KEY (construction_no, construction_eda)
) ENGINE = InnoDB COMMENT = '物件' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswd_order
(
	order_no int unsigned NOT NULL AUTO_INCREMENT COMMENT '注文番号',
	order_date timestamp DEFAULT now() NOT NULL COMMENT '注文日時',
	order_company int(3) unsigned COMMENT '施工会社（発注元）',
	shipping_name text COMMENT '納入先名',
	shipping_address text COMMENT '納入先住所',
	arrival_date date COMMENT '納入希望日',
	subtotal bigint COMMENT 'パーツ代金',
	rate int(3) unsigned COMMENT '税率',
	shipping_fee bigint COMMENT '運賃',
	shipping_agent text COMMENT '運送会社',
	shipping_date date COMMENT '出荷日',
	delivery_date date COMMENT '到着予定日',
	agent_inqno text COMMENT '問い合わせNo.',
	agent_tel text COMMENT '運送会社問い合わせ先',
	bill_no int unsigned COMMENT '請求書番号',
	update_date timestamp COMMENT '更新日時',
	cancel tinyint(1) unsigned DEFAULT 0 COMMENT 'キャンセル',
	cancel_date timestamp COMMENT 'キャンセル日時',
	PRIMARY KEY (order_no)
) ENGINE = InnoDB COMMENT = 'パーツ受注' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswd_order_meisai
(
	order_meisai_no int unsigned NOT NULL AUTO_INCREMENT COMMENT '明細番号',
	order_no int unsigned NOT NULL COMMENT '注文番号',
	item_size varchar(10) COMMENT '先端翼径',
	item_type smallint unsigned COMMENT '仕様',
	item_price bigint DEFAULT 0 COMMENT '発注単価',
	item_sprice bigint DEFAULT 0 COMMENT '販売単価',
	quantity int DEFAULT 0 COMMENT '数量',
	del_flg tinyint(1) unsigned COMMENT '削除フラグ',
	delete_date timestamp COMMENT '削除日時',
	PRIMARY KEY (order_meisai_no)
) ENGINE = InnoDB COMMENT = 'パーツ受注明細' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_architect
(
	no int unsigned NOT NULL AUTO_INCREMENT COMMENT 'No.',
	name text NOT NULL COMMENT '氏名',
	company_code int(3) unsigned DEFAULT 0 NOT NULL COMMENT '会社コード',
	certificated date NOT NULL COMMENT '認定日',
	create_date timestamp DEFAULT now() NOT NULL COMMENT '登録日時',
	update_date timestamp COMMENT '更新日時',
	delete_date timestamp COMMENT '削除日時',
	del_flg tinyint(1) unsigned DEFAULT 0 NOT NULL COMMENT '削除フラグ',
	PRIMARY KEY (no)
) ENGINE = InnoDB COMMENT = '設計担当者' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_area
(
	company_code int(3) unsigned NOT NULL COMMENT '会社コード',
	pref_code int(2) unsigned NOT NULL COMMENT '都道府県コード'
) ENGINE = InnoDB COMMENT = '施工エリア' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_basicinfo
(
	basicinfo_id int unsigned NOT NULL COMMENT '基本情報ID',
	info_name text NOT NULL COMMENT '項目名',
	info_value text NOT NULL COMMENT '項目値',
	create_date timestamp DEFAULT now() NOT NULL COMMENT '登録日時',
	update_date timestamp COMMENT '更新日時',
	delete_date timestamp COMMENT '削除日時',
	del_flg tinyint(1) unsigned DEFAULT 0 NOT NULL COMMENT '削除フラグ',
	PRIMARY KEY (basicinfo_id)
) ENGINE = InnoDB COMMENT = '基本情報' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_company
(
	company_code int(3) unsigned DEFAULT 0 NOT NULL COMMENT '会社コード',
	company_type smallint unsigned NOT NULL COMMENT '会社区分',
	company_name text NOT NULL COMMENT '会社名',
	ceo text COMMENT '代表者',
	zip1 varchar(3) COMMENT '郵便番号1',
	zip2 varchar(4) COMMENT '郵便番号2',
	address text COMMENT '住所',
	tel varchar(20) NOT NULL COMMENT '電話番号',
	fax varchar(20) COMMENT 'FAX番号',
	tanto text COMMENT '連絡担当者',
	email text COMMENT 'メールアドレス',
	join_date date DEFAULT NULL COMMENT '加入日',
	create_date timestamp DEFAULT now() NOT NULL COMMENT '登録日時',
	update_date timestamp COMMENT '更新日時',
	delete_date timestamp COMMENT '削除日時',
	del_flg tinyint(1) unsigned DEFAULT 0 NOT NULL COMMENT '削除フラグ',
	PRIMARY KEY (company_code)
) ENGINE = InnoDB COMMENT = '会社' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_ctax
(
	start_date datetime NOT NULL COMMENT '適用開始日',
	end_date datetime DEFAULT '9999-12-31 23:59:59' NOT NULL COMMENT '適用終了日',
	rate int(3) unsigned NOT NULL COMMENT '税率',
	PRIMARY KEY (start_date)
) ENGINE = InnoDB COMMENT = '消費税' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_engineer
(
	no int unsigned NOT NULL AUTO_INCREMENT COMMENT 'No.',
	name text NOT NULL COMMENT '氏名',
	company_code int(3) unsigned DEFAULT 0 NOT NULL COMMENT '会社コード',
	certificated date NOT NULL COMMENT '認定日',
	create_date timestamp DEFAULT now() NOT NULL COMMENT '登録日時',
	update_date timestamp COMMENT '更新日時',
	delete_date timestamp COMMENT '削除日時',
	del_flg tinyint(1) unsigned DEFAULT 0 NOT NULL COMMENT '削除フラグ',
	PRIMARY KEY (no)
) ENGINE = InnoDB COMMENT = '施工管理技術者' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_item
(
	item_size varchar(10) NOT NULL COMMENT '先端翼径',
	start_date datetime NOT NULL COMMENT '適用開始日',
	end_date datetime COMMENT '適用終了日',
	item_price1 bigint NOT NULL COMMENT '発注単価（理事）',
	item_price2 bigint NOT NULL COMMENT '発注単価（一般）',
	item_sprice1 bigint NOT NULL COMMENT '販売単価（理事）',
	item_sprice2 bigint NOT NULL COMMENT '販売単価（一般）',
	create_date timestamp DEFAULT now() NOT NULL COMMENT '登録日時',
	update_date timestamp COMMENT '更新日時',
	delete_date timestamp COMMENT '削除日時',
	del_flg tinyint(1) unsigned DEFAULT 0 NOT NULL COMMENT '削除フラグ',
	PRIMARY KEY (item_size, start_date)
) ENGINE = InnoDB COMMENT = 'パーツ単価' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_licensefees
(
	start_date datetime NOT NULL COMMENT '適用開始日',
	end_date datetime COMMENT '適用終了日',
	licensefees_price1 bigint NOT NULL COMMENT '単価（10本まで）',
	licensefees_price2 bigint NOT NULL COMMENT '単価（50本まで）',
	licensefees_price3 bigint NOT NULL COMMENT '単価（51本以上）',
	create_date timestamp DEFAULT now() NOT NULL COMMENT '登録日時',
	update_date timestamp COMMENT '更新日時',
	delete_date timestamp COMMENT '削除日時',
	del_flg tinyint(1) unsigned DEFAULT 0 NOT NULL COMMENT '削除フラグ',
	PRIMARY KEY (start_date)
) ENGINE = InnoDB COMMENT = '工法使用料単価' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_material
(
	material_code int unsigned NOT NULL AUTO_INCREMENT COMMENT '材種コード',
	material_name text NOT NULL COMMENT '材種名',
	PRIMARY KEY (material_code)
) ENGINE = InnoDB COMMENT = '材種' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_pref
(
	pref_code int(2) unsigned NOT NULL COMMENT '都道府県コード',
	pref_name varchar(4) NOT NULL COMMENT '都道府県名',
	PRIMARY KEY (pref_code)
) ENGINE = InnoDB COMMENT = '都道府県' DEFAULT CHARACTER SET utf8;


CREATE TABLE sswm_user
(
	user_id int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ユーザーID',
	login_id text NOT NULL COMMENT 'ログインID',
	company_code int(3) unsigned NOT NULL COMMENT '会社コード',
	auth_type smallint NOT NULL COMMENT '権限',
	passwd text NOT NULL COMMENT 'パスワード',
	passwd_changed_flg tinyint(1) unsigned DEFAULT 0 NOT NULL COMMENT 'パスワード変更フラグ',
	last_login_date timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL COMMENT '最終ログイン日時',
	number_of_trials int unsigned DEFAULT 0 NOT NULL COMMENT 'ログイン試行回数',
	unlock_date timestamp COMMENT 'アカウントロック自動解除日時',
	last_trial_date timestamp COMMENT '最終ログイン試行日時',
	create_date timestamp DEFAULT now() NOT NULL COMMENT '登録日時',
	update_date timestamp COMMENT '更新日時',
	delete_date timestamp COMMENT '削除日時',
	del_flg tinyint(1) unsigned DEFAULT 0 NOT NULL COMMENT '削除フラグ',
	PRIMARY KEY (user_id)
) ENGINE = InnoDB COMMENT = 'ログインユーザー' DEFAULT CHARACTER SET utf8;



/* Create Foreign Keys */

ALTER TABLE sswd_bill_meisai
	ADD CONSTRAINT fk_bill_meisai FOREIGN KEY (bill_no)
	REFERENCES sswd_bill (bill_no)
	ON UPDATE CASCADE
	ON DELETE CASCADE
;


ALTER TABLE sswd_order_meisai
	ADD FOREIGN KEY (order_no)
	REFERENCES sswd_order (order_no)
	ON UPDATE CASCADE
	ON DELETE CASCADE
;


ALTER TABLE sswd_construction
	ADD CONSTRAINT fk_architect_construction FOREIGN KEY (architect)
	REFERENCES sswm_architect (no)
	ON UPDATE CASCADE
	ON DELETE SET NULL
;


ALTER TABLE sswd_bill
	ADD CONSTRAINT fk_company_bill FOREIGN KEY (bill_company)
	REFERENCES sswm_company (company_code)
	ON UPDATE CASCADE
	ON DELETE SET NULL
;


ALTER TABLE sswd_construction
	ADD CONSTRAINT fk_company_construction_c FOREIGN KEY (construction_company)
	REFERENCES sswm_company (company_code)
	ON UPDATE CASCADE
	ON DELETE RESTRICT
;


ALTER TABLE sswd_construction
	ADD CONSTRAINT fk_company_construction_a FOREIGN KEY (architect_company)
	REFERENCES sswm_company (company_code)
	ON UPDATE CASCADE
	ON DELETE RESTRICT
;


ALTER TABLE sswd_construction
	ADD CONSTRAINT fk_company_construction_o FOREIGN KEY (order_company)
	REFERENCES sswm_company (company_code)
	ON UPDATE CASCADE
	ON DELETE RESTRICT
;


ALTER TABLE sswd_order
	ADD CONSTRAINT fk_company_order FOREIGN KEY (order_company)
	REFERENCES sswm_company (company_code)
	ON UPDATE CASCADE
	ON DELETE SET NULL
;


ALTER TABLE sswm_architect
	ADD CONSTRAINT fk_company_architect FOREIGN KEY (company_code)
	REFERENCES sswm_company (company_code)
	ON UPDATE CASCADE
	ON DELETE RESTRICT
;


ALTER TABLE sswm_area
	ADD CONSTRAINT fk_company_area FOREIGN KEY (company_code)
	REFERENCES sswm_company (company_code)
	ON UPDATE CASCADE
	ON DELETE CASCADE
;


ALTER TABLE sswm_engineer
	ADD CONSTRAINT fk_company_engineer FOREIGN KEY (company_code)
	REFERENCES sswm_company (company_code)
	ON UPDATE CASCADE
	ON DELETE RESTRICT
;


ALTER TABLE sswm_user
	ADD CONSTRAINT fk_company_user FOREIGN KEY (company_code)
	REFERENCES sswm_company (company_code)
	ON UPDATE CASCADE
	ON DELETE CASCADE
;


ALTER TABLE sswd_construction
	ADD CONSTRAINT fk_engineer_construction FOREIGN KEY (engineer)
	REFERENCES sswm_engineer (no)
	ON UPDATE CASCADE
	ON DELETE SET NULL
;


ALTER TABLE sswd_construction
	ADD CONSTRAINT fk_material_construction FOREIGN KEY (material_code)
	REFERENCES sswm_material (material_code)
	ON UPDATE CASCADE
	ON DELETE SET NULL
;


ALTER TABLE sswm_area
	ADD CONSTRAINT fk_pref_area FOREIGN KEY (pref_code)
	REFERENCES sswm_pref (pref_code)
	ON UPDATE CASCADE
	ON DELETE CASCADE
;



/* Create Indexes */

CREATE INDEX idx_bill_ym USING BTREE ON sswd_bill (bill_nen ASC, bill_tuki ASC);
CREATE INDEX idx_bill_company ON sswd_bill (bill_company ASC);
CREATE INDEX idx_construction_company USING BTREE ON sswd_construction (construction_company ASC);
CREATE INDEX idx_construction_nendo USING BTREE ON sswd_construction (nendo ASC);
CREATE INDEX idx_construction_seq USING BTREE ON sswd_construction (company_seq ASC);
CREATE INDEX idx_construction_start_date USING BTREE ON sswd_construction (construction_start_date ASC);
CREATE INDEX idx_complete_date USING BTREE ON sswd_construction (complete_date ASC);
CREATE INDEX idx_order_date USING BTREE ON sswd_order (order_date ASC);
CREATE INDEX idx_order_construction_company USING BTREE ON sswd_order (order_company ASC);
CREATE INDEX idx_meisai_order_no ON sswd_order_meisai (order_no ASC);
CREATE INDEX idx_company_code_in_user USING BTREE ON sswm_user (company_code ASC);



