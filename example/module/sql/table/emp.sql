
-- /*******************************************************************************************************
-- * @desc      : 계정 정보
-- * @author    : koreaERP@gmail.com
-- * @data      : 2016-06-04
-- * @modify    :
-- *******************************************************************************************************/
DROP TABLE IF EXISTS  emp;
CREATE TABLE emp (
      iCode            INT(11) AUTO_INCREMENT           COMMENT 'pk'
    , sName            VARCHAR(030) NOT NULL DEFAULT ''  COMMENT '이름'
    , sBirth          VARCHAR(010) NOT NULL DEFAULT '0000-00-00'  COMMENT '생년월일'
    , sGender          VARCHAR(001) NOT NULL DEFAULT '1'  COMMENT '성별'

    , sCity            VARCHAR(030) NOT NULL DEFAULT ''  COMMENT '도시'
    , iSalary          INT(11)      NOT NULL DEFAULT 0   COMMENT '급여'
    , PRIMARY KEY   pk_emp (iCode)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '
@create : 2016-06-04
@title :  기준정보 - 계정 정보
@desc :
';

INSERT INTO emp (sName, sBirth, sGender, sCity, iSalary) VALUES
 ('Ben', '1980-11-23', '1', 'Seoul', 50000)
,('Sara', '1970-05-05', '2', 'Busan', 60000)
,('Mark', '1974-09-15', '1', 'Jeju', 70000)
,('Pam', '1979-10-27', '2', 'Masan', 80000)
,('Todd', '1983-12-30', '1', 'DaeGu', 90000)

