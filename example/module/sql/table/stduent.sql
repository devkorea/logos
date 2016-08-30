
-- /*******************************************************************************************************
-- * @desc      : 학생 정보
-- * @author    : koreaERP@gmail.com
-- * @data      : 2016-06-04
-- * @modify    :
-- *******************************************************************************************************/
DROP TABLE IF EXISTS  student;
CREATE TABLE student (
      iCode            INT(11) AUTO_INCREMENT           COMMENT 'pk'
    , sName            VARCHAR(030) NOT NULL DEFAULT ''  COMMENT '이름'
    , sBirth          VARCHAR(010) NOT NULL DEFAULT '0000-00-00'  COMMENT '생년월일'
    , sGender          VARCHAR(001) NOT NULL DEFAULT '1'  COMMENT '성별'

    , iCity            INT(11)  NOT NULL DEFAULT 0       COMMENT 'city.iCode'
    , iSalary          INT(11)  NOT NULL DEFAULT 0   COMMENT '급여'
    , PRIMARY KEY   pk_student (iCode)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '
@create : 2016-06-04
@title :  기준정보 - 학생 정보
@desc :
';

INSERT INTO student (sName, sBirth, sGender, iCity, iSalary) VALUES
 ('Ben', '1980-11-23', '1', 1, 50000)
,('Sara', '1970-05-05', '2', 10, 60000)
,('Mark', '1974-09-15', '1', 15, 70000)
,('Pam', '1979-10-27', '2', 25, 80000)
,('Todd', '1983-12-30', '1', 29, 90000)
,('Herry', '1981-01-05', '2', 39, 90000)

INSERT INTO student (sName, sBirth, sGender, iCity, iSalary) VALUES
 ('Peter', '1966-01-01', '1', 1, 150000)

INSERT INTO student (sName, sBirth, sGender, iCity, iSalary) VALUES
 ('Adam', '1970-09-06', '2', 1, 80000) 

INSERT INTO student (sName, sBirth, sGender, iCity, iSalary) VALUES
 ('Eve', '1978-07-07', '2', 5, 120000)  