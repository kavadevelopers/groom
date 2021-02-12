<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function pre_print($array)
{   
    echo count($array);
    echo "<pre>";
    print_r($array);
    exit;
}

function retJson($array){
    header("Content-type: application/json");
    echo json_encode($array);
}

function _vdatetime($datetime)
{
	return date('d-m-Y h:i A',strtotime($datetime));
}

function _sortdate($datetime)
{
    if($datetime!=""){
        return date('Ymd',strtotime($datetime));
    }else{
        return "";
    }
}

function checkSubscriptionExpiration($expireDate)
{
    if($expireDate == NULL){
        return "expired";
    }else{
        $date1 = strtotime($expireDate);
        if($date1 >= strtotime(date('Y-m-d'))){
            return 'active';
        }else{
            return 'expired';
        }
    }
}

function _nowDateTime()
{   
    return date('Y-m-d H:i:s');
}

function getTommorrow()
{
    return date('Y-m-d',strtotime("-1 day",strtotime(date('Y-m-d'))));
}

function vd($date)
{
    return date('d-m-Y',strtotime($date));
}

function vfd($date)
{
    return date('F d, Y',strtotime($date));
}

function dd($date)
{
    return date('Y-m-d',strtotime($date));
}


function dt($time){
    return date('H:i:s',strtotime($time));   
}

function vt($time){
    return date('h:i A',strtotime($time));   
}

function getPretyDateTime($date)
{
    return date('d M Y h:i A',strtotime($date));
}

function subStrr($str, $length = 125, $append = '...') {
    if (strlen($str) > $length) {
        $delim = "~\n~";
        $str = substr($str, 0, strpos(wordwrap($str, $length, $delim), $delim)) . $append;
    } 
    return $str;
}

function getFileExtension($filename){
    return pathinfo($filename, PATHINFO_EXTENSION);
}

function get_setting()
{
	$ci=& get_instance();
    $ci->load->database();
    return $ci->db->get_where('setting',['id' => '1'])->row_array();
}

function get_user(){
	$ci=& get_instance();
    $ci->load->database();
    return $ci->db->get_where('user',['id' => $ci->session->userdata('id')])->row_array();	
}

function get_user_byid($id){
    $ci=& get_instance();
    return $ci->db->get_where('user',['id' => $id])->row_array();  
}

function menu($seg,$array)
{
    $CI =& get_instance();
    $path = $CI->uri->segment($seg);
    foreach($array as $a)
    {
        if($path === $a)
        {
          return array("active","active","pcoded-trigger");
          break;  
        }
    }
}

function sendPush($tokon,$title,$body,$type = '',$dy = ""){
    $url = "https://fcm.googleapis.com/fcm/send";
    $serverKey = get_setting()['fserverkey'];
    if(getDeviceType($tokon) == "ios"){
        if(getCustomerType($tokon) != "customer"){
            $notification = array('title' => $title, 'body' => $body,'sound' => 'sound.wav','badge' => '0');
        }else{
            $notification = array('title' => $title, 'body' => $body,'sound' => 'default','badge' => '0');
        }
        $arrayToSend = array('registration_ids' => $tokon,"priority" => "high","notification" => $notification,'data' => ['title' => $title,'body' => $body,'type' => $type,'dy' => $dy]);
    }else{
        $arrayToSend = array('registration_ids' => $tokon,"priority" => "high",'data' => ['title' => $title,'body' => $body,'type' => $type,'dy' => $dy]);
    }
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    //pre_print($arrayToSend);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0); 
    $result = curl_exec($ch);
    curl_close($ch);
}

function sendEmail($to,$sub,$msg)
{
    $CI =& get_instance();
    $CI->load->library('email');
    $config = array(
        'protocol'      => 'SMTP',
        'smtp_host' => get_setting()['mail_host'],
        'smtp_port' => get_setting()['mail_port'],
        'smtp_user' => get_setting()['mail_username'],
        'smtp_pass' => get_setting()['mail_pass'],
        'mailtype'      => 'html',
        'charset'       => 'utf-8'
    );
    $CI->email->initialize($config);
    $CI->email->set_mailtype("html");
    $CI->email->set_newline("\r\n");
    $CI->email->to($to);
    $CI->email->from(get_setting()['mail_username']);
    $CI->email->subject($sub);
    $CI->email->message($msg);
    if($CI->email->send()){
        //echo "ok";
    }else{
        //echo $CI->email->print_debugger();
    }
}

// Groom

function getCategory($id)
{
    $CI =& get_instance();   
    return $CI->db->get_where('categories',['id' => $id])->row_array();
}

function generateOtp($user,$user_type,$otp_type,$email = false)
{
    $CI =& get_instance();
    $otp = mt_rand(1000, 9999);
    $data = [
        'user'      => $user,
        'otp'       => $otp,
        'usertype'  => $user_type,
        'otptype'   => $otp_type,
        'used'      => 0,
        'cat'       => _nowDateTime()
    ];
    $CI->db->insert('z_otp',$data);
    return $otp;
}

function roundLatLon($lat)
{
    return round($lat,6);
}
?>