<?php
    if (!defined('_DSROOT_')) { echo __FILE__.'<br />_DSROOT_ undefined'; exit;} // 페이지 직접 접근 불가

    class ClassMSSQL extends ClassUtil
    {
        public $pdo = null;           //
        public $sName = '';         // DB Name
        public $bLog = false;           //
        public $bSql = false;           //

        public $table = array();           //
        public $func = array();           //
        public $proc = array();           //
        public $view = array();           //



        private $sLog;
//        public $info = array();           //


        private $aParam = array();  // xampp 환경에서 proc 가 안되므로 select 처리하기 위한 변수
        private $iParam = 0;        // xampp 환경에서 proc 가 안되므로 select 처리하기 위한 변수
        /***********************************************************************************************************************
        * @desc     생성자
        * @param {object}       DB 객체
        * @return {void}
        ***********************************************************************************************************************/
        function __construct ()
        {
//            $this->bLog = false;
//            $this->sLog = _DSROOT_.'/_log';
//            $this->table = array();
        } // end __construct

        function __destruct ()
        {
        } // end __destruct




        // ########################################################################################################################
        // ###### PUBLIC ##########################################################################################################
        // ########################################################################################################################
        /**************************************************************************/
        /* DB 관련 변수를 생성하는 함수*/
        /**************************************************************************/
        public function getInfo()
        {
            $this->proc['www_sp_system_get_table'               ] = 'dbo.www_sp_system_get_table'               ;
        }




        /**************************************************************************/
        /* 데이터베이스 연결하는 함수*/
        /**************************************************************************/
        public function connectDB($paramInfo_)
        {
            $result = array('result' => false, 'msg' => '', 'key' => '', 'name'=>'', 'sql'=>'', 'data'=>'');
            try {
                if (XAMPP === true) {
                    $dsn = "sqlsrv:Server=".$paramInfo_['host'].", ".$paramInfo_['port'].";Database=".$paramInfo_['db'];
                } else {
                    $dsn = "dblib:host=".$paramInfo_['host'].":".$paramInfo_['port'].";dbname=".$paramInfo_['db'].";";
                }
                $this->pdo = new PDO($dsn, $paramInfo_['user'], $paramInfo_['pwd']);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // 에러 출력
                if (is_object($this->pdo) === false) {
                    $$result['result']['msg'] = 'connection failed (db.conn)';
                    if ($$result['display'] === 'view') {
                        echo $$result['result']['msg'];
                    } else {
                        echo json_encode($$result['result']);
                    }
                    exit;
                }
            } catch (PDOException $e) {
                $$result['result']['msg'] = 'connection failed (db.conn)'.$e->getMessage();
                if ($$result['display'] === 'view') {
                    echo $$result['result']['msg'];
                } else {
                    echo json_encode($$result['result']);
                }
                exit;
            }
            return $this->pdo;
        }

        /**************************************************************************/
        /* 프로시저 함수 */
        /**************************************************************************/
        public function execute($paramSql_)
        {
            $this->coreSetLog($paramSql_);    // 로그 작성
            $stmt = $this->pdo->prepare($paramSql_);
            return $stmt->execute();
        }

        /**************************************************************************/
        /* 질의함수 */
        /**************************************************************************/
        public function selectSql($paramSql_)
        {
            $this->coreSetLog($paramSql_);    // 로그 작성
            $stmt = $this->pdo->prepare($paramSql_);
            $stmt->execute();
            if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
                return $stmt->fetchAll();
            }
            return array();
        }

        /**************************************************************************/
        /* 질의함수 */
        /* 하나의 row 만을 반환함 */
        /**************************************************************************/
        public function selectOne($paramSql_)
        {
            $data = $this->selectSql($paramSql_);
            if (isset($data[0]) === true && is_array($data[0]) === true) {
                return $data[0];
            } else {
                return '';
            }
        }

        //**************************************************************************/
        // 단일필드 검색 시 쿼리의 결과값을 돌려주는 함수
        //**************************************************************************/
        public function selectColumn($paramSql_)
        {
            $data = $this->selectSql($paramSql_);
            if (isset($data[0]) === true) {
                foreach ($data[0] as $key_ => $val_) {
                    return $val_;
                }
            } else {
                return '';
            }
        }




        //**************************************************************************/
        // 멀티쿼리
        //**************************************************************************/
        public function selectMulti($paramSql_)
        {
//            return $this->excuteSql($paramSql_);
//            $aData = Array();
//            $aVal = Array();
//            $this->coreSetLog($paramSql_); // 로그저장
//            $this->pdo->multi_query($paramSql_);
//            $stmt = $this->pdo->store_result();
//            if ($this->pdo->more_results()) {
//                $this->pdo->next_result();
//            }
//            if(is_object($stmt)) {
//                while ($aVal = $stmt->fetch_assoc()) {
//                    $aData[] = $aVal;
//                    $this->pdo->use_result();
//                }
//                $stmt->free_result();
//                $stmt = null;
//            }
//            return $aData;
        }

        /**************************************************************************/
        /* 질의함수 */
        /**************************************************************************/
        public function insertSql($paramSql_, $paramPk_ = true)
        {
            $res = $this->execute($paramSql_);
            if ($res) {
                if ($paramPk_ === true) {
                    return $this->pdo->lastInsertId();
                } else {
                    return $res;
                }
            }
//            return $this->excuteSql($paramSql_);
//            $paramSql_ .= ';';
//            $this->coreSetLog($paramSql_); // 로그저장
//            if ($paramPk_ === true) {
//                $this->pdo->query($paramSql_);
//                return $this->pdo->insert_id;
//            } else {
//
//                return $this->pdo->query($paramSql_);
//            }
        }

        /**************************************************************************/
        /* 질의함수 */
        /**************************************************************************/
        public function updateSql($paramSql_)
        {
            return $this->execute($paramSql_);
//            $paramSql_ .= ';';
//            $this->coreSetLog($paramSql_); // 로그저장
//            return $this->pdo->query($paramSql_);
        }

        /**************************************************************************/
        /* 질의함수 */
        /**************************************************************************/
        public function deleteSql($paramSql_)
        {
            return $this->excuteSql($paramSql_);
//            $paramSql_ .= ';';
//            $this->coreSetLog($paramSql_); // 로그저장
//            return $this->pdo->query($paramSql_);
        }


        // **************************************************************************
        // 자동 인서트 함수 (연관배열의 키값이 테이블의 필드명과 같아야함)
        // **************************************************************************
        public function insertTable($table, $data)
        {
            $fields = $this->get_table_fields($table);
            $data    = $this->array_keys_intersect($data, $fields);
            if (!$table || !is_array($data)) return false;
            $data = $this->replaceHtml($data);
            // $columns = implode(', ',array_keys($data));
            // $values = implode("', '",array_values($data));

//            foreach ($data as $key => $value) {
//                if (in_array($key, $fields) !== true) continue;
//                $columns = implode("\n, ",array_keys($data));
//            }

            $columns = implode("\n, ",array_keys($data));
            $values = implode("'\n, '",array_values($data));

            $sSql = "INSERT INTO ".$table." (\n".$columns."\n) VALUES (\n'".$values."'\n)";
            return $this->execute($sSql);
        }

        // **************************************************************************
        // 자동 업데이트 함수 (연관배열의 키값이 테이블의 필드명과 같아야함)
        // **************************************************************************
        public function updateTable($table, $data, $cond='')
        {
            if(!$table || !is_array($data)) return false;
            $fields = $this->get_table_fields($table);
            $data = $this->replaceHtml($data);

            foreach ($data as $key => $value) {
                if (in_array($key, $fields) !== true) continue;
                $aTmp[] = $key." = '" .$value."'";
            }
            $sSql = "UPDATE ".$table." SET \n".implode("\n, ",$aTmp)." \n".$cond;
            return $this->execute($sSql);
        }


        // **************************************************************************
        // 자동 삭제 함수 (연관배열의 키값이 테이블의 필드명과 같아야함)
        // **************************************************************************
        public function deleteTable($table, $cond='')
        {
            $sSql = 'DELETE FROM '.$table.' '.$cond;
            return $this->execute($sSql);
        }






        /***********************************************************************************************************************
        * @desc     Stored Procedure 결과를 반환
        * @param {string}       프로시저명(db.cfg)  (ex: $sQry = "EXEC ".$this->cDB->T['pr']['mobile_pr_medi']." ?, ?, ?, ?, ?, ?";)
        * @param {array}        프로시저 파라메타 (ex: $bind[] = array($paramPost_['sec_cd'], PDO::PARAM_STR, 5); )
        * @return {array}
        ***********************************************************************************************************************/
        public function excuteSql2 ($paramQry_, $paramParam_ = '')
        {
            if (XAMPP === true) {
                if ($paramParam_ !== '') {
                    foreach ($paramParam_ as $idx_ => $val_) {  // preg_replace_callback 에서 전역변수밖에 인식되지 않으므로
                        $this->aParam[$idx_] = $val_[0];
                    }

                    $paramQry_ = preg_replace_callback("/\?/", function($matches) { return "'".$this->aParam[$this->iParam++]."'"; }, $paramQry_);
                    unset($this->aParam); // 재사용을 위한 초기화
                    $this->iParam = 0;    // 재사용을 위한 초기화


                    $this->coreSetLog($paramQry_);    // 로그 작성

                    return $this->selectSql($paramQry_);
                }
            } else {
                $log = $paramQry_;  // 로그를 위한 작성
                $stmt = $this->pdo->prepare($paramQry_);

                if ($paramParam_ !== '') {
                    foreach ($paramParam_ as $key_ => $val_) {
                        if (isset($val_[2]) === false) {
                            $stmt->bindParam($key_ + 1, $val_[0], $val_[1]);
                        } else {
                            $stmt->bindParam($key_ + 1, $val_[0], $val_[1], $val_[2]);
                        }
                        $log .= "'".$val_[0]."', "; // 로그를 위한 작성
                    }
                }

                $this->coreSetLog($log);    // 로그 작성
                $stmt->execute();
                if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
                    return $stmt->fetchAll();
                }
                return array();
            }
        } // end func

        /***********************************************************************************************************************
        * @desc     Stored Procedure 결과를 반환
        * @param {string}       프로시저명(db.cfg)  (ex: $sQry = "EXEC ".$this->cDB->T['pr']['mobile_pr_medi']." ?, ?, ?, ?, ?, ?";)
        * @param {array}        프로시저 파라메타 (ex: $bind[] = array($paramPost_['sec_cd'], PDO::PARAM_STR); )
        * @return {array}
        ***********************************************************************************************************************/
        public function excuteSql ($paramQry_, $paramParam_ = '')
        {
            $log = preg_replace('/[\?,]/', '', $paramQry_); // 로그를 위한 작성
            $stmt = $this->pdo->prepare($paramQry_);

            if ($paramParam_ !== '') {
                foreach ($paramParam_ as $key_ => $val_) {
                    if (isset($val_[2]) === false || $val_[2] === '') { // 뒤에 문자열 길이가 지정되지 않은 경우
                        $stmt->bindParam($key_ + 1, $val_[0], $val_[1]);
                    } else {
//                        if ($val_[2] !== '') {
//                            $val_[2] = iconv('utf-8', 'euc-kr', $val_[2]);
//                        }
                        $stmt->bindParam($key_ + 1, $val_[0], $val_[1], $val_[2]);
                    }
                    $log .= "'".$val_[0]."', "; // 로그를 위한 작성
                }
            }

            $log = preg_replace('/,[\s]*$/', '', $log); // 로그를 위한 작성(마지막 콤마제거)
            $this->coreSetLog($log);    // 로그 작성

            $stmt->execute();
            if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
//                $stmt->nextRowset();
                return $stmt->fetchAll();
            }
            return array();
        } // end func



//        /***********************************************************************************************************************
//        * @desc     Stored Procedure 결과를 반환
//        * @param {string}       프로시저명(db.cfg)  (ex: $sQry = "EXEC ".$this->cDB->T['pr']['mobile_pr_medi']." ?, ?, ?, ?, ?, ?";)
//        * @param {array}        프로시저 파라메타 (ex: $bind[] = array($paramPost_['sec_cd'], PDO::PARAM_STR, 5); )
//        * @return {array}
//        ***********************************************************************************************************************/
//        public function excuteSql3 ($paramQry_, $paramParam_ = '')
//        {
//            $log = $paramQry_;  // 로그를 위한 작성
//            $output = array();
//            $stmt = $this->pdo->prepare($paramQry_);
//
//            if ($paramParam_ !== '') {
//                foreach ($paramParam_ as $key_ => $val_) {
//                    if ($val_[0] === 'in') {
//                        if (isset($val_[3]) === true && $val_[3] !== '') {
//                            $stmt->bindParam($key_ + 1, $val_[1], $val_[2], $val_[3]);
//                        } else {
//                            $stmt->bindParam($key_ + 1, $val_[1], $val_[2]);
//                        }
//                        $log .= "'".$val_[1]."', "; // 로그를 위한 작성
//                    } else {
//
////                        $virtaul = ${$val_[1]};
//                        $output[$val_[1]] = '';     // 반환받을 변수
//echo('<pre style="color:#f00">');
//echo('<div style="background-color:#000;color:#ff0;">'.__FILE__.'&nbsp;&nbsp;&nbsp;'.__LINE__.'</div>');
//print_r($val_);
//echo('</pre>');
////exit;
//
////                        $stmt->bindParam($key_ + 1, $output[$val_[1]], $val_[2]);
//                        $stmt->bindParam($key_ + 1, $output[$val_[1]], $val_[2], $val_[3]);
////                        $stmt->bindParam($key_ + 1, $output[$val_[1]], $val_[2] | PDO::PARAM_INPUT_OUTPUT, $val_[3]);
//
//                        $log .= "@".$val_[1].", "; // 로그를 위한 작성
//                    }
//                }
//            }
//
//            $this->coreSetLog($log);    // 로그 작성
//            $stmt->execute();
//            if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
//                return $stmt->fetchAll();
//            }
//            return array();
//        } // end func




        /**************************************************************************/
        /* 질의함수 */
        /**************************************************************************/
        public function getPWD($paramPWD_)
        {
            $sSql = "SELECT PWDENCRYPT('".$paramPWD_."') AS pwd";
            $this->coreSetLog($sSql); // 로그저장
            return $this->selectColumn($sSql);
        }




//        /**************************************************************************/
//        /* 테이터베이스에 테이블의 존재여부를 체크하는 함수*/
//        /**************************************************************************/
//        public function table_exist($table)
//        {
//            $bool = false;
//            $tb1_exist = mysql_list_tables($this->database);
//            while($tables = mysql_fetch_array($tb1_exist)){
//                if($tables[0] == $table){
//                    $bool = true;
//                    break;
//                }
//            }
//            return $bool;
//        }


        //**************************************************************************/
        // 테이블의 필드명을 배열로 돌려주는 함수
        //**************************************************************************/
        public function get_table_fields($paramTable_)
        {
            $data = array();
            $result = array();
            $sSql = "EXEC ".$this->proc['www_sp_system_get_table']."'".$paramTable_."'";

            $bind = array();    // 파라메타 설정
            $bind[] = array('www_mem',     PDO::PARAM_STR, 50);
            $data = $this->excuteSql($sSql, $bind);

            foreach ($data as $key_ => $val_) {
                $result[] = $val_['sColunm'];
            }
            return $result;
        }


//        public function getCount($paramSql_)
//        {
//            $data = $this->selectSql($paramSql_);
//            return sizeof($data);
//        }


        /***********************************************************************************************************************
        * @desc     트랜젝션
        * @return {void}
        ***********************************************************************************************************************/
        public function beginTran ()
        {
            $this->pdo->beginTransaction();
        } // end func


        /***********************************************************************************************************************
        * @desc     트랜젝션
        * @return {void}
        ***********************************************************************************************************************/
        public function rollbackTran ()
        {
            $this->pdo->rollBack();
        } // end func


        /***********************************************************************************************************************
        * @desc     트랜젝션
        * @return {void}
        ***********************************************************************************************************************/
        public function commitTran ()
        {
            $this->pdo->commit();
        } // end func



        // ########################################################################################################################
        // ###### PRIVATE #########################################################################################################
        // ########################################################################################################################
        /***********************************************************************************************************************
        * @desc     로그를 작성
        * @param {string}       쿼리문
        * @return {array}
        ***********************************************************************************************************************/
        public function coreSetLog ($paramQry_)
        {
            if ($this->bLog === true) {
//                $fName = (isset($_SESSION['SS_MEM_ID']) === true && $_SESSION['SS_MEM_ID'] !== '' ? $_SESSION['SS_MEM_ID'] : 'noLogin');
//                $logFile = _DSROOT_.'/_log/'.$_SERVER['REMOTE_ADDR'].'_'.$fName.'.sql';
//                $logFile = _DSROOT_.'/data/dev.sql';
                global $logFile;

                $txt = '';
                if (file_exists($logFile) === true) {
                    $fp = fopen($logFile, 'r');
                    while(!feof($fp)) {
                      $txt .= fread($fp, 1024);
                    }
                    fclose($fp);
                }

                $fp = fopen($logFile, 'w');
                fwrite($fp, "-- ==== ".date('Y-m-d H:i:s')." =====================\r\n".$paramQry_."\r\n\r\n\r\n".$txt);
                fclose($fp);
            }
        } // end func


        /***********************************************************************************************************************
        * @desc     INSERT, UPDATE, DELETE 결과를 반환
        * @param {string}       쿼리문
        * @return {array}
        ***********************************************************************************************************************/
        private function coreExecute ($paramSql_)
        {
            $this->coreSetLog($paramSql_);    // 로그 작성
            $stmt = $this->pdo->prepare($paramSql_);
            $stmt->execute();
            if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
                return $stmt->fetchAll();
            }
            return array();

//            return $stmt->execute();
        } // end func










    } // end of class
