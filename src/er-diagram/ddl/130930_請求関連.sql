ALTER TABLE `sswd_construction`
ADD COLUMN `bill_no`  int UNSIGNED NULL COMMENT '請求書番号' AFTER `depth`;

ALTER TABLE `sswd_bill_meisai`
ADD COLUMN `meisai_date`  date NULL DEFAULT NULL COMMENT '日付, 注文日or完工日' AFTER `bill_no`,
ADD COLUMN `bill_type`  smallint(1) UNSIGNED NULL DEFAULT NULL COMMENT '品目(1:工法使用料, 2:パーツ代金, 3:運賃)' AFTER `meisai_date`,
ADD COLUMN `bill_name`  text NULL COMMENT '品名' AFTER `bill_type`,
ADD COLUMN `price`  bigint NULL DEFAULT NULL COMMENT '単価' AFTER `bill_name`,
ADD COLUMN `quantity`  int NULL AFTER `price`,
ADD COLUMN `amount`  bigint NULL COMMENT '金額' AFTER `quantity`,
ADD COLUMN `order_id`  int NULL DEFAULT NULL COMMENT '注文番号' AFTER `amount`,
ADD COLUMN `comment`  text NULL COMMENT '摘要' AFTER `order_id`;

-- 打設本数(amount)と名称がかぶるので変更
ALTER TABLE `sswd_bill_meisai`
CHANGE COLUMN `amount` `sub_total`  bigint(20) NULL DEFAULT NULL COMMENT '金額' AFTER `quantity`;

-- 設計書の単純な誤り
ALTER TABLE `sswd_bill_meisai`
CHANGE COLUMN `order_id` `order_no`  int(11) NULL DEFAULT NULL COMMENT '注文番号' AFTER `sub_total`;

-- 再発行日をtimestampに
ALTER TABLE `sswd_bill`
MODIFY COLUMN `reissue_date`  timestamp NULL DEFAULT NULL COMMENT '再発行日' AFTER `payment_date`;

