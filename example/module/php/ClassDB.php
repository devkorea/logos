<?php
if (!defined('_DSROOT_')) { echo __FILE__.'<br />_DSROOT_ undefined'; exit;} // 페이지 직접 접근 불가

//include_once _KOROOT_.'/lib/php/ClassUtil.php';
class ClassMYSQL extends ClassUtil
{
    public $pdo = null;           //
    public $sName = '';           // DB Name
    public $bLog = false;           //
    public $bSql = false;           //

    public $table = array();           //
    public $func = array();           //
    public $proc = array();           //
    public $view = array();           //

    /***********************************************************************************************************************
     * @desc     생성자
     * @param {object}       DB 객체
     * @return {void}
     ***********************************************************************************************************************/
    function __construct ()
    {
//        $this->cfg = $paramCFG_;
    } // end __construct

    function __destruct ()
    {
    } // end __destruct


    // ########################################################################################################################
    // ###### PUBLIC ##########################################################################################################
    // ########################################################################################################################
    /**************************************************************************/
    /* 데이터베이스 연결하는 함수*/
    /**************************************************************************/
    public function connectDB($paramInfo_) {
        $result = array('result' => false, 'msg' => '', 'key' => '', 'name'=>'', 'sql'=>'', 'data'=>'');
        try {
            switch ($$paramInfo_['type']) {
                case 'mysql':
                    $this->pdo = new PDO('mysql:host='.$paramInfo_['host'].':'.$paramInfo_['port'].';dbname='.$paramInfo_['db'], $paramInfo_['user'], $paramInfo_['pwd'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    break;
                case 'mssql':
                    if (XAMPP === true) {
                        $dsn = "sqlsrv:Server=".$paramInfo_['host'].", ".$paramInfo_['port'].";Database=".$paramInfo_['db'];
                    } else {
                        $dsn = "dblib:host=".$paramInfo_['host'].":".$paramInfo_['port'].";dbname=".$paramInfo_['db'].";";
                    }
                    break;
            }

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


    public function selectSql($paramQry_) {
//        $this->coreLogWrite($paramQry_, '');

        $stmt = $this->pdo->prepare($paramQry_);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    /***********************************************************************************************************************
     * @desc     Stored Procedure 결과를 반환
     * @param {string}       프로시저명(db.cfg)  (ex: $sQry = "EXEC ".$this->cDB->T['pr']['mobile_pr_medi']." ?, ?, ?, ?, ?, ?";)
     * @param {array}        프로시저 파라메타 (ex: $bind[] = [$paramPost_['sec_cd'], PDO::PARAM_STR, 5)];
     * @return {array}
     ***********************************************************************************************************************/
    public function excuteSql($paramQry_, $paramBind_) {
        $stmt = $this->pdo->prepare($paramQry_);
        if ($paramBind_ !== '') {
            foreach ($paramBind_ as $key_ => $val_) {
                if (isset($val_[2]) === false || $val_[2] === '') {
                    $stmt->bindParam($key_ + 1, $val_[0], $val_[1]);
                } else {
                    $stmt->bindParam($key_ + 1, $val_[0], $val_[1], $val_[2]);
                }
            }
        }

        $this->coreLogWrite($paramQry_, $paramBind_);    // 로그 작성
        $stmt->execute();
        if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
            return $stmt->fetchAll();
        }
        return [];
    }



    // ########################################################################################################################
    // ###### PRIVATE #########################################################################################################
    // ########################################################################################################################
    /***********************************************************************************************************************
     * @desc     로그를 작성
     * @param {string}       쿼리문
     * @return {array}
     ***********************************************************************************************************************/
    public function coreLogWrite ($paramQry_, $paramBind_)
    {
        if ($this->bLog === true) {
            if (is_array($paramBind_) === true) {
                $paramQry_ = preg_replace("/[\?,\(\)]+/", '', $paramQry_);
                $paramQry_ = preg_replace("/[\s]+$/", '', $paramQry_);
                $len = sizeof($paramBind_);
                $paramQry_ .= '(';
                foreach ($paramBind_ as $idx_ => $val_) {
                    if ($val_[1] === 1) {
                        $paramQry_ .= $val_[0];
                    } else {
                        $paramQry_ .= "'".$val_[0]."'";
                    }
                    if ($len > $idx_ + 1)
                        $paramQry_ .= ', ';
                }
                $paramQry_ .= ');';
            }

            $txt = '';
            if (file_exists($this->cfg['log']) === true) {
                $fp = fopen($this->cfg['log'], 'r');
                while(!feof($fp)) {
                    $txt .= fread($fp, 1024);
                }
                fclose($fp);
            }

            $fp = fopen($this->cfg['log'], 'w');
            fwrite($fp, "-- ==== ".date('Y-m-d H:i:s')." =====================\n".$paramQry_."\n\n\n".$txt);
            fclose($fp);
        }
    } // end func



} // end of class
