<?php
    if (!defined('_DSROOT_')) { echo __FILE__.'<br />_DSROOT_ undefined'; exit;} // 페이지 직접 접근 불가

    /**
     * Created by PhpStorm.
     * User: USER
     * Date: 2016-08-18
     * Time: 오후 2:12
     */
    class ClassFactory
    {
        public $db = [];
        public $pdo = [];
        public $result = array('result' => false, 'msg' => '', 'key' => '', 'name'=>'', 'sql'=>'', 'data'=>'');
        /***********************************************************************************************************************
         * @desc     생성자
         * @param {object}       DB 객체
         * @return {void}
         ***********************************************************************************************************************/
        function __construct ()
        {
        } // end

        function __destruct ()
        {
        } // end


        function setDB ($info_)
        {
            $this->db[$info_['db']] = $info_;
    //        echo '<pre>';
    //        print_r($this->db);
    //        echo '</pre>';
        } // end



        function connectDB ($db_)
        {
            if (isset($this->db[$db_]) === true) {
                $db = null;
                switch ($this->db[$db_]['type']) {
                    case 'mysql':
                        $db = new ClassMYSQL();
                        break;
                    case 'mssql':
                        $db = new ClassMSSQL();
                        break;
                }
                $db->connectDB($this->db[$db_]);
                $this->pdo[$db_] = &$db;
            }
//            echo '<pre>';
//            print_r($this->pdo[$db_]);
//            echo '</pre>';
            return $this->pdo[$db_];
        } // end




        function clearResult ()
        {
            $this->result = array('result' => false, 'msg' => '', 'key' => '', 'name'=>'', 'sql'=>'', 'data'=>'');
        } // end


    }