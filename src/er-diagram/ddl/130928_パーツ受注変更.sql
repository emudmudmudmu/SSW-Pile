ALTER TABLE `sswd_order`
MODIFY COLUMN `cancel_date`  timestamp NULL DEFAULT NULL COMMENT 'キャンセル日時' AFTER `cancel`;

ALTER TABLE `sswd_order`
DROP COLUMN `cancel`;

