
-- /*******************************************************************************************************
-- * @desc      : 도시 정보
-- * @author    : koreaERP@gmail.com
-- * @data      : 2016-06-04
-- * @modify    :
-- *******************************************************************************************************/
DROP TABLE IF EXISTS  city;
CREATE TABLE city (
      iCode            INT(11) AUTO_INCREMENT           COMMENT 'pk'
    , iNation     INT(11) NOT NULL DEFAULT 0      COMMENT 'Nation.iCode'
    , sName            VARCHAR(030) NOT NULL DEFAULT ''  COMMENT '이름'
    , PRIMARY KEY   pk_city (iCode)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '
@create : 2016-06-04
@title :  기준정보 - 계정 정보
@desc :
';

INSERT INTO city (iNation, sName) VALUES
 (1, '서울')
,(1, '인천')
,(1, '천안')
,(1, '대전')
,(1, '대구')
,(1, '부산')
,(1, '제주')
,(1, '익산')
,(1, '전주')
,(1, '광주')
,(1, '여수')
,(1, '목포')
,(2, '嘉黎')
,(2, '訶陵')
,(2, '甘肅')
,(2, '甘州')
,(2, '江西')
,(2, '江蘇')
,(2, '江孜')
,(2, '江河')
,(2, '開魯')
,(2, '開封')
,(2, '開遠')
,(2, '居延')
,(2, '建州')
,(2, '建平')
,(2, '瓊海')
,(3, 'Guam')
,(3, 'Nevada')
,(3, 'Nebraska')
,(3, 'New York')
,(3, 'New Hampshire')
,(3, 'Massachusetts')
,(3, 'Michigan')
,(3, 'Minnesota')
,(3, 'Alaska')
,(3, 'Wisconsin')
,(3, 'California')
,(3, 'Kentucky')
,(3, 'Texas')
,(3, 'Hawaii')