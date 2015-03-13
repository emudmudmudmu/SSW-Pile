ALTER TABLE `sswd_order`
ADD COLUMN `order_status`  smallint UNSIGNED NOT NULL DEFAULT 1 COMMENT '受注ステータス(1:未出荷, 2:出荷済, 9:キャンセル)' AFTER `bill_no`;

