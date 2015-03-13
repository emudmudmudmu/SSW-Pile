CREATE TABLE `sswd_news` (
`news_id`  int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'お知らせID' ,
`news_date`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '日付' ,
`news_title`  text NOT NULL COMMENT 'タイトル' ,
`news_content`  text NULL COMMENT '本文' ,
PRIMARY KEY (`news_id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8
COMMENT='協会からのお知らせ'
;

