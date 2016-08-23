
-- /*******************************************************************************************************
-- * @desc      : 과목 정보
-- * @author    : koreaERP@gmail.com
-- * @data      : 2016-06-04
-- * @modify    :
-- *******************************************************************************************************/
DROP TABLE IF EXISTS  lecture;
CREATE TABLE lecture (
      iCode            INT(11) AUTO_INCREMENT           COMMENT 'pk'
    , sName            VARCHAR(030) NOT NULL DEFAULT ''  COMMENT '과목명'

    , PRIMARY KEY   pk_lecture (iCode)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '
@create : 2016-06-04
@title :  기준정보 - 과목 정보
@desc :
';

INSERT INTO lecture (sName) VALUES
 ('Angular')
,('C++')
,('Phython')
,('PowerBuilder')
,('.NET')
,('Delphi')
,('PHP')
,('Go')
,('Java')
,('ASP')
,('Cobol')
