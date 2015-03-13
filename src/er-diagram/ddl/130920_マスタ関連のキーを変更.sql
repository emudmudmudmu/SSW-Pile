ALTER TABLE `sswm_licensefees`
ADD COLUMN `licensefee_id`  int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '工法使用料単価ID' FIRST ,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`licensefee_id`);

ALTER TABLE `sswm_item`
ADD COLUMN `item_id`  int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'パーツ単価ID' FIRST ,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`item_id`);

ALTER TABLE `sswm_ctax`
ADD COLUMN `ctax_id`  int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '消費税ID' FIRST ,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`ctax_id`);


ALTER TABLE `sswd_construction` DROP FOREIGN KEY `fk_material_construction`;

ALTER TABLE `sswm_material`
MODIFY COLUMN `material_code`  int(10) UNSIGNED NOT NULL COMMENT '材種コード' ,
ADD COLUMN `material_id`  int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '材種ID' FIRST ,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`material_id`);


ALTER TABLE `sswd_construction`
CHANGE COLUMN `material_code` `material_id`  int(10) UNSIGNED NULL DEFAULT NULL COMMENT '材種ID' AFTER `amount`;

ALTER TABLE `sswd_construction` ADD CONSTRAINT `fk_material_construction` FOREIGN KEY (`material_id`) REFERENCES `sswm_material` (`material_id`) ON DELETE SET NULL ON UPDATE CASCADE;

