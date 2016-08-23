<?php
if (!defined('_DSROOT_')) { echo __FILE__.'<br />_DSROOT_ undefined'; exit;} // 페이지 직접 접근 불가


/************************************************************************************************************************
@desc   :
@create :
@auth   :
@modify :
@modify :
@modify :
 ***********************************************************************************************************************/

//    namespace _lib\php;
class ClassUtil
{

    /***********************************************************************************************************************
     * @desc     생성자
     * @param {array}        DB 접속정보
     * @param {array}        DB object 정보
     * @param {object}       ECHO 객체
     * @return {void}
     ***********************************************************************************************************************/
    function __construct ()
    {
    } // end __construct

    function __destruct ()
    {
    } // end __destruct

    // ########################################################################################################################
    // ###### PUBLIC ##########################################################################################################
    // ########################################################################################################################
    /***********************************************************************************************************************
     * @desc     POST가 아니면 이동
     * @param    (string}        이동할 URL
     * @return   {boolean}
     ***********************************************************************************************************************/
    public function checkPost ($paramMsg_)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(array('result' => false, 'msg' => $paramMsg_, 'key' => '', 'name'=>'', 'data'=>''));
            exit;
        }
        return true;
    }

    /***********************************************************************************************************************
     * @desc     문자열의 값을 포멧에 맞게 반환
     * @param    (bool}          구분자를 넣을지 삭제할지 여부
     * @param    (string}        타입(bizNo, date)
     * @param    (string}        치환할 값
     * @return   {string}        치환결과
     ***********************************************************************************************************************/
    public function getFormat ($bDel_, $paramType_, $paramData_)
    {
        $paramData_ = trim($paramData_);
        if ($paramData_ === '') return;
        switch ($paramType_) {  // 일단 무조건 분리자는 지움
            case 'bizNo':
            case 'date':
                $paramData_ = preg_replace('/[\-\.\:\,]*/', '', $paramData_);
                break;
        }

        if ($bDel_ === true)
            return $paramData_;

        switch ($paramType_) {
            case 'bizNo':
                $paramData_ = substr($paramData_, 0, 3).'-'.substr($paramData_, 3, 2).'-'.substr($paramData_, 5, 10);
                break;
            case 'date':
                $paramData_ = substr($paramData_, 0, 4).'-'.substr($paramData_, 4, 2).'-'.substr($paramData_, 5, 2);
                break;
        }
        return $paramData_;
    }

    /***********************************************************************************************************************
     * @desc     UTF-8 문자열 자르기
     * @param    (string}        대상문자열
     * @param    (int}           반환받을 문자수
     * @param    (string}        끝에 붙여줄 문자열
     * @return   {string}
     ***********************************************************************************************************************/
    public function cutString ($paramString_, $paramCut_, $paramTail_='…')
    {
        $aData_ = preg_split("//u", $paramString_, -1, PREG_SPLIT_NO_EMPTY);
        $len_ = sizeof($aData_);

        if ($len_ >= $paramCut_) {
            $cutStr_ = array_slice($aData_, 0, $paramCut_);
            $paramString_ = join("", $cutStr_);

            return $paramString_.($len_ > $paramCut_ ? $paramTail_ : '');
        } else {
            $paramString_ = join("", $aData_);
            return $paramString_;
        }
    }

//function cut_str($str, $len, $suffix="…")
//{
//    $arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
//    $str_len = count($arr_str);
//
//    if ($str_len >= $len) {
//        $slice_str = array_slice($arr_str, 0, $len);
//        $str = join("", $slice_str);
//
//        return $str . ($str_len > $len ? $suffix : '');
//    } else {
//        $str = join("", $arr_str);
//        return $str;
//    }
//}


    /***********************************************************************************************************************
     * @desc     필요한 변수를 제외하고 링크로 연결하여 반환 ($_GET 으로 링크처리할 때 사용)
     * @param {array}        제외할 값 (색인배열)
     * @param {array}        폼값
     * @return {string}
     ***********************************************************************************************************************/
    public function getURL ($paramRemove_, &$paramForm_, $paramWD_ = false)
    {
        if ($paramWD_ == false) {
            $url = _DSROOT_.'/dsitec.html?';
        } else {
//                $url = _DSROOT_.'/wd.html?w=1&amp;';
            $url = _DSROOT_.'/wd.html?';
        }

//            $check = true;
//            $bAdd = false;
//            foreach ($paramForm_ as $key1_ => $val_) {
//                $val_ = trim($val_);
//                if ($val_ === '') continue;
//                $check = true;
//                foreach ($paramRemove_ as $idx_ => $key2_) {
//                    if ($key1_ === $key2_) {
//                        $check = false;
//                        break;
//                    }
//                }
//
//                if ($check === true) {
//                    $bAdd = true;
//                    $url .= $key1_.'='.$val_.'&';
//                }
//            }
////            if ($bAdd === true) return substr($url, 0, -1);
////            else return $url;
//            return $url;


//echo('<pre style="color:#f00">');
//echo('<div style="background-color:#000;color:#ff0;">'.__FILE__.'&nbsp;&nbsp;&nbsp;'.__LINE__.'</div>');
//print_r($url);
//echo('</pre>');
    }







    /***********************************************************************************************************************
     * @desc     기본값을 반환함
     * @param {string}       변수명
     * @return {mixed}
     ***********************************************************************************************************************/
    public function getVar ($paramKey_, $paramVal_)
    {
        if (isset($_GET[$paramKey_]) === true && $_GET[$paramKey_] !== '') {
            return trim($_GET[$paramKey_]);
        } else {
            if (isset($_POST[$paramKey_]) === true && $_POST[$paramKey_] !== '') {
                return trim($_POST[$paramKey_]);
            } else {
                return trim($paramVal_);
            }
        }
    }


    /***********************************************************************************************************************
     * @desc     변수를 확인함
     * @param {array}       변수
     * @return {array}
     ***********************************************************************************************************************/
    public function  chkVar (&$paramPost_, $paramCfg_)
    {
        $param_ = array('result' => false, 'msg' => '', 'key' => '', 'name'=>'', 'data'=>'');
        foreach ($paramCfg_ as $key_ => $arr_) {
            $param_['name'] = $arr_['name'];
            if (isset($arr_['trim']) === true && $arr_['trim'] === true) {
                $paramPost_[$key_] = trim($paramPost_[$key_]);
            }


            if (isset($arr_['required']) === true && $arr_['required'] === true) {
                if ($paramPost_[$key_] === '') {
                    $param_['key'] = $key_;
                    $param_['msg'] = '필수값입니다.';
                    return $param_;
                }
            }

            if (isset($arr_['comma']) === true) {
                if ($arr_['comma'] === false)
                    $paramPost_[$key_] = $this->parseComma($paramPost_[$key_], true);
                else
                    $paramPost_[$key_] = $this->parseComma($paramPost_[$key_], false);
            }



//echo('<pre style="color:#f00">');
//echo('<div style="background-color:#000;color:#ff0;">'.__FILE__.'&nbsp;&nbsp;&nbsp;'.__LINE__.'</div>');
//print_r($param_['name']);
//echo('</pre>');
//

//모든 공백 체크 정규식
//var regExp = /\s/g;
//
//숫자만 체크 정규식
//var regExp = /^[0-9]+$/;
//
//이메일 체크 정규식
//var regExp = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
//
//
//핸드폰번호 정규식
//var regExp = /^\d{3}-\d{3,4}-\d{4}$/;
//
//
//일반 전화번호 정규식
//var regExp = /^\d{2,3}-\d{3,4}-\d{4}$/;
//
//
//아이디나 비밀번호 정규식
//var regExp = /^[a-z0-9_]{4,20}$/;
//
//휴대폰번호 체크 정규식
//var regExp = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/;




            foreach ($arr_ as $ques_ => $val_) {
                switch ($ques_) {
                    case 'check':
                        switch ($val_) {
                            case 'id':
                                if (!preg_match('/^[a-zA-Z0-9]{'.$arr_['min'].','.$arr_['max'].'}+$/', $paramPost_[$key_])) {
                                    $param_['key'] = $key_;
                                    $param_['msg'] = 'ID 형식에 맞지 않습니다.';
                                    return $param_;
                                }
                                break;
                            case 'int':

                                break;
                            case 'float':

                                break;
                            case 'string':

                                break;
                        }

                        break;
                    case 'min':
                        if (strlen($paramPost_[$key_]) < $val_) {
                            $param_['key'] = $key_;
                            $param_['msg'] = '문자의 길이가 '.$val_.'글자 보다 길게 입력하세요.';
                            return $param_;
                        }
                        break;
                    case 'max':
                        if (strlen($paramPost_[$key_]) > $val_) {
                            $param_['key'] = $key_;
                            $param_['msg'] = '문자의 길이가 '.$val_.'글자 보다 적게 입력하세요.';
                            return $param_;
                        }
                        break;
                    case 'minval':
                        if ($paramPost_[$key_] < $val_) {
                            $param_['key'] = $key_;
                            $param_['msg'] = $val_.' 보다 큰 값을 입력하세요.';
                            return $param_;
                        }
                        break;
                    case 'maxval':
                        if ($paramPost_[$key_] > $val_) {
                            $param_['key'] = $key_;
                            $param_['msg'] = $val_.' 보다 작은 값을 입력하세요.';
                            return $param_;
                        }
                        break;
                }




            }
        }
        $param_['name'] = '';
        $param_['key'] = '';
        $param_['result'] = true;
        return $param_;
    }



    /***********************************************************************************************************************
     * @desc     전화번호를 분리하거나 합쳐줌
     * @param {string}       전화번호
     * @param {string}       전화번호
     * @param {string}       전화번호
     * @param {bool}         제거여부
     * @return {string}
     ***********************************************************************************************************************/
    public function parseTel ($paramTel1_, $paramTel2_, $paramTel3_, $paramRemove_ = false)
    {
        if ($paramRemove_ === false) {
            if ($paramTel1_ !== '' && $paramTel2_ !== '' && $paramTel3_ !== '') {
                return $paramTel1_.'-'.$paramTel2_.'-'.$paramTel3_;
            } else {
                return '';
            }
        } else {

        }
    }





    /***********************************************************************************************************************
     * @desc     콤마를 넣거나 지움
     * @param {mixed}        값
     * @param {bool}         제거여부
     * @return {mixed}
     ***********************************************************************************************************************/
    public function parseComma ($paramNum_, $paramRemove_ = false)
    {
        if ($paramNum_ === '' || $paramNum_ === 0 || $paramNum_ === '0' || $paramNum_ === 0.0 || $paramNum_ === '0.0') {
            return $paramNum_;
        }
        if ($paramRemove_ === false) {
            return number_format($paramNum_);
        } else {
            return preg_replace('/[^0-9\.]/m', '',$paramNum_);
        }
    }




    /***********************************************************************************************************************
     * @desc     문자열을 잘라서 반환
     * @param {int}          잘라줄 문자열 수
     * @param {string}       대상 문자열
     * @return {string}
     ***********************************************************************************************************************/
//        public function cutString ($paramCut_, $paramData_)
//        {
//            if (strlen($paramData_) > $paramCut_) {
//                $count = 0;
//                for ($i = 0; $i<$paramCut_; $i++) {
//                    $cut = ord(substr($paramData_, $i, 1));
//                    if ($cut > 127) {
//                        $count++;
//                    }
//                }
//                $mod = $count % 2;
//                if ($mod === 0) {
//                    $paramData_ = mb_substr($paramData_, 0, $paramCut_, 'utf-8');
//                    $paramData_ = $paramData_.'.';
//                } else {
//                    $paramCut_ = $paramCut_ + 1;
//                    $paramData_ = mb_substr($paramData_, 0, $paramCut_, 'utf-8');
//                    $paramData_ = $paramData_.'.';
//                }
//            }
//            return $paramData_;
//        }




    public function array_keys_intersect(&$post, &$columns_) {
        if(!is_array($columns_)) return false;
        $aTmp = Array();
        foreach ($columns_ as $idx_ => $val_) {
            if(isset($post[$val_])) $aTmp[$val_] = $post[$val_];
        }
        return $aTmp;
    }
















    /***********************************************************************************************************************
     * @desc     특수문자를 제거하거나 HTML Entity 로 치환함
     * @param {bool}         제거할 지 여부
     * @param {string}       값
     * @return {int}
     * $paramData_ = preg_replace ('/[#\&\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i', '', $paramData_);
     ***********************************************************************************************************************/
    public function replaceInjection ($bRemove_, &$paramKey_, &$paramData_)
    {
        if ($bRemove_ === true) {
            foreach ($paramKey_ as $idx_ => $col_) {
                if (isset($paramData_[$col_]) === true && $paramData_[$col_] !== '') {
                    $paramData_[$col_] = preg_replace ('/["]/m', '', $paramData_[$col_]);
                }
            }
        } else {
            foreach ($paramKey_ as $idx_ => $col_) {
                if (isset($paramData_[$col_]) === true && $paramData_[$col_] !== '') {
                    $paramData_[$col_] = preg_replace ('/["]/m', '', $paramData_[$col_]);
                }
            }
        }
        return $paramData_;
    }


    /***********************************************************************************************************************
     * @desc     특수문자를 제거하거나 HTML Entity 로 치환함
     * @param {mixed}        치환대상(string, array)
     * @param {bool}         HTML 에서 Entity로 변환할 지 여부
     * @return {int}
     * $paramData_ = preg_replace ('/[#\&\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i', '', $paramData_);
     ***********************************************************************************************************************/
    public function replaceHtml ($paramData_, $bChange_ = true)
    {
        if ($bChange_ === true) {
            switch ($this->getArrayType($paramData_)) {
                case -1:    // string
                    $paramData_ = htmlspecialchars($paramData_, ENT_QUOTES);
                    break;
                default :  // array
                    foreach ($paramData_ as $key_ => $data_) {
                        if (is_array($data_) === false) {
                            $paramData_[$key_] = htmlspecialchars($data_, ENT_QUOTES);
                        } else {
                            foreach ($data_ as $key2_ => $data2_) {
                                if (is_array($data2_) === false) {
                                    $paramData_[$key_][$key2_] = htmlspecialchars($data2_, ENT_QUOTES);
                                } else {
                                    $paramData_[$key_][$key2_] = $data2_;
                                }
                            }
                        }
                    }
                    break;
            }
        } else {

            switch ($this->getArrayType($paramData_)) {
                case -1:    // string
                    $paramData_ = preg_replace('/["]/m', '', $paramData_);
                    break;
                default :  // array
                    foreach ($paramData_ as $key_ => $data_) {
                        if (is_array($data_) === false) {
                            $paramData_[$key_] = preg_replace('/["]/m', '', $data_);
                        } else {
                            foreach ($data_ as $key2_ => $data2_) {
                                $paramData_[$key_][$key2_] = preg_replace('/["]/m', '', $data2_);
                            }
                        }
                    }
                    break;
            }

        }
        return $paramData_;
    }

    /***********************************************************************************************************************
     * @desc     에디터에서 저장한 태그를 원래의 태그로 봔환
     * @param {mixed}        치환대상(string, array)
     * @param {bool}         HTML 에서 Entity로 변환할 지 여부
     * @return {int}
     * $paramData_ = preg_replace ('/[#\&\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i', '', $paramData_);
     ***********************************************************************************************************************/
    public function replaceEditor ($paramHtml_, $bChange_ = true)
    {
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans = array_flip($trans);
        return strtr($paramHtml_, $trans);
    }

    /***********************************************************************************************************************
     * @desc     referer 를 반환
     * @return {string}
     ***********************************************************************************************************************/
    public function getReferer ()
    {
        if (isset($_SERVER['HTTP_REFERER']) === true && $_SERVER['HTTP_REFERER'] !== '') {
            if (stristr($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF'])) {
                return _DSROOT_.'/';
            } else {
                return $_SERVER['HTTP_REFERER'];
            }
        }

//            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//                return $_SERVER['REQUEST_URI'];
//            } else {
//                return $_SERVER['REQUEST_URI'].getenv('QUERY_STRING');
//            }
    }


    /***********************************************************************************************************************
     * @desc     | 로 존재하는 이미지 파일명을 분리해서 이미지 태그를 반환
     * @param {string}       원본 경로(root 에서 부터의 상대경로)
     * @param {string}       | 로 만들어진 이미지 파일명
     * @param {int}          이미지 넓이
     * @param {int}          이미지 높이
     * @return {string}
     ***********************************************************************************************************************/
    public function parseImg ($paramDir_, $paramImg_, $paramW_, $paramH_, $paramAlt_ = '')
    {
        $paramImg_ = trim($paramImg_);
        if ($paramImg_ === '') return '';
        $tmp = explode('|', $paramImg_);
        if (isset($tmp[1]) === false || $tmp[1] === '') return '';

        return '<img src="'._DSROOT_.'/data/'.$paramDir_.'/'.$tmp[1].'" style="width:'.$paramW_.'px;height:'.$paramH_.'px" alt="'.$paramAlt_.'" />';
    }


    /***********************************************************************************************************************
     * @desc     thumbnail 이미지를 생성하고 파일명을 반환
     * @param {string}       원본 경로(root 에서 부터의 상대경로)
     * @param {string}       생성 경로(root 에서 부터의 상대경로)
     * @param {string}       이미지명
     * @param {int}          이미지 넓이
     * @param {int}          이미지 높이
     * @return {string}
     ***********************************************************************************************************************/
////        public function getThumbnail ($paramOld_, $paramNew_, $paramName_, $paramW_, $paramH_)
    public function getThumbnail (&$paramData_, $paramW_, $paramH_)
    {
        $info    = getimagesize(_DSROOT_.$paramData_['sDir'].$paramData_['sServer']);
        echo('<pre style="color:#f00">');
        echo('<div style="background-color:#000;color:#ff0;">'.__FILE__.'&nbsp;&nbsp;&nbsp;'.__LINE__.'</div>');
//print_r($paramData_['sDir'].$paramData_['sServer']);
        print_r($info);
        echo('</pre>');
//exit;








        $img = array('w'=>0, 'h'=>0, 'type'=>'');
        $img['w'] = $info[0];
        $img['h'] = $info[1];
        if ($info[2] == 1) {
            $img['type'] = 'ImageGIF';
            $img['from'] = 'ImageCreateFromGIF';
        }
        if ($info[2] == 2) {
            $img['type'] = 'ImageJPEG';
            $img['from'] = 'ImageCreateFromJPEG';
        }
        if ($info[2] == 3) {
            $img['type'] = 'ImagePNG';
            $img['from'] = 'ImageCreateFromPNG';
        }


        if ($img['w'] > $img['h']) {
            $img['nw'] = $paramW_;
            $img['nh'] = intval($img['h'] * $img['nw'] / $img['w']);
        } else {
            $img['nh'] = $paramH_;
            $img['nw'] = intval($img['w'] * $img['nh'] / $img['h']);
        }

        // 가로 비율을 구한다
        $tmp   = $paramH_ / $img['h'];
        $width = intval($img['w'] * $tmp);

        // 세로 비율을 구한다
        $tmp    = $paramW_ / $img['w'];
        $height = intval($img['h'] * $tmp);


        if ($img['w'] <= $paramW_ && $img['h'] <= $paramH_) { // 원본이 썸네일보다 작을 때 (가로 세로 작다)
            $img['nw']  = $img['w'];
            $img['nh'] = $img['h'];
        } else if ($img['w'] > $img['h']) { // 가로가 크다
            if ($img['w'] < $paramW_) { // 원본 가로가 썸네일보다 작다면
                $paramW_ = $img['w'];
            }

            if ($img['h'] < $paramH_) { // 원본 세로가 썸네일보다 작다면
                $paramH_ = $img['h'];
            }

            if ($width < $paramW_) { // 가로비율이 썸네일보다 작다면
                $img['nw'] = $paramW_;
            } else {
                $img['nw'] = $width;
            }

            if ($height < $paramH_) { // 세로비율이 썸네일보다 작다면
                $img['nh'] = $paramH_;
            } else {
                $img['nh'] = $height;
            }
        } else if ($img['w'] < $img['h']) { // 세로가 크다

            if ($img['w'] < $paramW_) { // 원본 가로가 썸네일보다 작다면
                $paramW_ = $img['w'];
            }

            if ($img['h'] < $paramH_) { // 원본 세로가 썸네일보다 작다면
                $paramH_ = $img['h'];
            }

            if ($width < $paramW_) { // 가로비율이 썸네일보다 작다면
                $img['nw'] = $paramW_;
            } else {
                $img['nw'] = $width;
            }

            if ($height < $paramH_) { // 세로비율이 썸네일보다 작다면
                $img['nh'] = $paramH_;
            } else {
                $img['nh'] = $height;
            }
        } else { // 가로세로 같을 때 비율처리
            if ($width > $paramW_) { // 가로 비율이 썸네일을 초과
                $img['nw'] = $paramW_;
            } else {
                $img['nw'] = $width;
            }

            if ($height > $paramH_) { // 세로 비율이 썸네일을 초과
                $img['nh'] = $paramH_;
            } else {
                $img['nh'] = $height;
            }
        }

        if ($img['type']) {
            $old_image  = $img['from']($paramOld_.'/'.$paramName_);
            $new_image  = imagecreatetruecolor($paramW_, $paramH_);

            imagesavealpha($new_image, true);
            $trans_colour = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($new_image, 0, 0, $trans_colour);

            imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $img['nw'], $img['nh'], $img['w'], $img['h']);
            $img['type']($new_image, $paramNew_.'/'.$paramName_);

            echo('<pre style="color:#00f">');
            echo('<div style="background-color:#000;color:#ff0;">'.__FILE__.'&nbsp;&nbsp;&nbsp;'.__LINE__.'</div>');
            print_r('이미지생성됨 '.$paramNew_.'/'.$paramName_);
            echo('</pre>');

            return $paramName_;
        }
        return '';
    }

    /***********************************************************************************************************************
     * @desc     | 로 존재하는 이미지 파일명을 분리해서 이미지 태그를 반환
     * @param {mixed}        색인배열, 연관배열, string
     * @return {int}         string=-1, 색인=0, 연관=1
     ***********************************************************************************************************************/
    public function getArrayType ($paramArr_)
    {
        if (is_array($paramArr_) === false) return -1;

        if ($paramArr_ === array_values($paramArr_)) {
            return 0;
        } else {
            return 1;
        }
    }



    /***********************************************************************************************************************
     * @desc     AES-256과 HMAC 을 사용한 암호화 문자열을 반환
     * @param {string}       키 문자열
     * @param {string}       암호화 할 문자열
     * @return {string}
     ***********************************************************************************************************************/
    public function getEncrypt($paramKey_, $paramStr_)
    {
        // 보안을 최대화하기 위해 비밀번호를 해싱한다.
        $paramKey_ = hash('sha256', $paramKey_, true);

        // 용량 절감과 보안 향상을 위해 평문을 압축한다.
        $paramStr_ = gzcompress($paramStr_);

        // 초기화 벡터를 생성한다.
        $iv_source = defined('MCRYPT_DEV_URANDOM') ? MCRYPT_DEV_URANDOM : MCRYPT_RAND;
        $iv = mcrypt_create_iv(32, $iv_source);

        // 암호화한다.
        $paramStr_ = mcrypt_encrypt('rijndael-256', $paramKey_, $paramStr_, 'cbc', $iv);

        // 위변조 방지를 위한 HMAC 코드를 생성한다. (encrypt-then-MAC)
        $hmac = hash_hmac('sha256', $paramStr_, $paramKey_, true);

        // 암호문, 초기화 벡터, HMAC 코드를 합하여 반환한다.
//            return base64_encode($paramStr_ . $iv . $hmac);
        return rawurlencode(base64_encode($paramStr_ . $iv . $hmac));
    }
    // 위의 함수로 암호화한 문자열을 복호화한다.
    // 복호화 과정에서 오류가 발생하거나 위변조가 의심되는 경우 false를 반환한다.

    /***********************************************************************************************************************
     * @desc     AES-256과 HMAC 을 사용한 복호화 문자열을 반환
     * @param {string}       키 문자열
     * @param {string}       복호화 할 암호 문자열
     * @return {string}
     ***********************************************************************************************************************/
    public function getDecrypt($paramKey_, $paramStr_)
    {
        // 초기화 벡터와 HMAC 코드를 암호문에서 분리하고 각각의 길이를 체크한다.
        $paramStr_ = @base64_decode(rawurldecode($paramStr_), true);
        if ($paramStr_ === false) return false;
        $len = strlen($paramStr_);
        if ($len < 64) return false;
        $iv = substr($paramStr_, $len - 64, 32);
        $hmac = substr($paramStr_, $len - 32, 32);
        $paramStr_ = substr($paramStr_, 0, $len - 64);

        // 암호화 함수와 같이 비밀번호를 해싱한다.
        $paramKey_ = hash('sha256', $paramKey_, true);

        // HMAC 코드를 사용하여 위변조 여부를 체크한다.
        $hmac_check = hash_hmac('sha256', $paramStr_, $paramKey_, true);
        if ($hmac !== $hmac_check) return false;

        // 복호화한다.
        $paramStr_ = @mcrypt_decrypt('rijndael-256', $paramKey_, $paramStr_, 'cbc', $iv);
        if ($paramStr_ === false) return false;

        // 압축을 해제하여 평문을 얻는다.
        $paramStr_ = @gzuncompress($paramStr_);
        if ($paramStr_ === false) return false;

        // 이상이 없는 경우 평문을 반환한다.
        return $paramStr_;
    }

    /***********************************************************************************************************************
     * @desc     데이터 encode, decode
     * @param {string}       변환 결과 언어셋(euc-kr, utf-8)
     * @param {array}        변환할 컬럼
     * @param {array }       변환할 데이터 (1차 연관배열)
     * @return {array}
     ***********************************************************************************************************************/
    public function setEncode ($paramCharset, &$paramKey_, &$paramData_)
    {
        if (is_array($paramKey_) !== true || is_array($paramData_) !== true) {
            return $paramData_;
        }

        if (XAMPP === false) {      // DB 에서 가져온 데이터를 전환
            $charset1 = 'CP949';
            $charset2 = 'UTF-8//IGNORE';
//                $charset2 = 'UTF-8//TRANSLIT';
//                $charset2 = 'UTF-8';
            if (strtolower($paramCharset) === 'euc-kr') {   // 웹에서 DB 에 질의, 저장할때
                $charset1 = 'UTF-8'; // IGNORE TRANSLIT
                //                $charset1 = 'UTF-8//IGNORE'; // IGNORE TRANSLIT
//                    $charset2 = 'EUC-KR//IGNORE';
                $charset2 = 'CP949//IGNORE';
//                    $charset2 = 'CP949';
//                    $charset2 = 'EUC-KR';
            }

            foreach ($paramKey_ as $idx_ => $col_) {
                if (isset($paramData_[$col_]) === true && $paramData_[$col_] !== '') {
                    if (is_array($paramData_[$col_]) === false) {
                        $paramData_[$col_] = trim($paramData_[$col_]);
                        if ($paramData_[$col_] !== '') {
//                                $paramData_[$col_] = htmlspecialchars($paramData_[$col_]);
                            $paramData_[$col_] = iconv($charset1, $charset2, $paramData_[$col_]);
                        }
                    }
                }
            }
        }
        return $paramData_;
    } // end insert


    /***********************************************************************************************************************
     * @desc     날짜 구분자를 모두 제거하거나 구분자를 - 로 넣어줌
     * @param {bool}         제거할 지 여부
     * @param {string}       날짜
     * @return {string}
     ***********************************************************************************************************************/
    public function replaceDate ($bRemove_, $paramData_)
    {
        if (!$paramData_ !== '') {
            if ($bRemove_ === true) {
                return preg_replace('/[\-\.,\/]/', '\\1', $paramData_);
            } else {
                return preg_replace('/(\d{4})(\d{2})(\d{2})/', "\\1-\\2-\\3", $paramData_);
            }
        }
        return $paramData_;
    } // end replaceDate

    // ########################################################################################################################
    // ###### PRIVATE ##########################################################################################################
    // ########################################################################################################################



}