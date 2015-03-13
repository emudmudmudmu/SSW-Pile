ALTER TABLE `sswd_bill` DROP FOREIGN KEY `fk_company_bill`;
ALTER TABLE `sswd_bill` ADD CONSTRAINT `fk_company_bill` FOREIGN KEY (`bill_company`) REFERENCES `sswm_company` (`company_code`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sswd_order` DROP FOREIGN KEY `fk_company_order`;
ALTER TABLE `sswd_order` ADD CONSTRAINT `fk_company_order` FOREIGN KEY (`order_company`) REFERENCES `sswm_company` (`company_code`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sswd_order`
ADD COLUMN `shipping_company`  text NULL COMMENT '�[�����Ж�' AFTER `order_company`,
ADD COLUMN `shipping_person`  text NULL COMMENT '�[����S���Җ�' AFTER `shipping_address`,
ADD COLUMN `email`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '���[���A�h���X' AFTER `shipping_person`,
ADD COLUMN `tel`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '�d�b�ԍ�' AFTER `email`,
ADD COLUMN `fax`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'FAX�ԍ�' AFTER `tel`;

