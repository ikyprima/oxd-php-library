<?php
    require_once './utils.php';
    require_once './oxdlibrary/Uma_rp_get_rpt.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
    try{
        if($oxdRpConfig->conn_type == "local"){
    //	    This is for OXD Socket
            $uma_rp_get_rpt = new Uma_rp_get_rpt();
        }
        else if($oxdRpConfig->conn_type == "web"){
    //	    This is for OXD-TO-HTTP
            $uma_rp_get_rpt = new Uma_rp_get_rpt($config);
        }
        $uma_rp_get_rpt->setRequest_oxd_id($oxdObject->oxd_id);
        $uma_rp_get_rpt->setRequest_ticket("8d736e57-e407-4ec2-ab8b-92df70d90edb");
        
        if($oxdRpConfig->conn_type == "local"){
            $uma_rp_get_rpt->setRequest_protection_access_token(getClientProtectionAccessToken());
        }else if($oxdRpConfig->conn_type == "web"){
            $uma_rp_get_rpt->setRequest_protection_access_token(getClientProtectionAccessToken($config));
        }
        $uma_rp_get_rpt->request();
        echo $uma_rp_get_rpt->getResponseJSON();
    }
    catch(Exception $e){
        echo "{\"error\":\"".$e->getMessage()."\"}";
    }
?>
