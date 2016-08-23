
-- /*******************************************************************************************************
-- * @desc      : 국가 정보
-- * @author    : koreaERP@gmail.com
-- * @data      : 2016-06-04
-- * @modify    :
-- *******************************************************************************************************/
DROP TABLE IF EXISTS  nation;
CREATE TABLE nation (
      iCode            INT(11) AUTO_INCREMENT           COMMENT 'pk'
    , sName            VARCHAR(030) NOT NULL DEFAULT ''  COMMENT '이름'
    , PRIMARY KEY   pk_nation (iCode)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '
@create : 2016-06-04
@title :  기준정보 - 국가 정보
@desc :
';

INSERT INTO nation (sName) VALUES
 ('한국')
,('中國')
,('America')


