<?php 
session_start();
require_once('DBConnection.php');

Class Actions extends DBConnection{
    function __construct(){
        parent::__construct();
    }
    function __destruct(){
        parent::__destruct();
    }
    function login(){
        extract($_POST);
        $sql = "SELECT * FROM user_list where username = '{$username}' and `password` = '".md5($password)."' ";
        @$qry = $this->query($sql)->fetchArray();
        if(!$qry){
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        }else{
            $resp['status'] = "success";
            $resp['msg'] = "Login successfully.";
            foreach($qry as $k => $v){
                if(!is_numeric($k))
                $_SESSION[$k] = $v;
            }
        }
        return json_encode($resp);
    }
    function logout(){
        session_destroy();
        header("location:./");
    }
    function save_user(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
        if(!in_array($k,array('id'))){
            if(!empty($id)){
                if(!empty($data)) $data .= ",";
                $data .= " `{$k}` = '{$v}' ";
                }else{
                    $cols[] = $k;
                    $values[] = "'{$v}'";
                }
            }
        }
        if(empty($id)){
            $cols[] = 'password';
            $values[] = "'".md5($username)."'";
        }
        if(isset($cols) && isset($values)){
            $data = "(".implode(',',$cols).") VALUES (".implode(',',$values).")";
        }
        

       
        @$check= $this->query("SELECT count(user_id) as `count` FROM user_list where `username` = '{$username}' ".($id > 0 ? " and user_id != '{$id}' " : ""))->fetchArray()['count'];
        if(@$check> 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Username already exists.";
        }else{
            if(empty($id)){
                $sql = "INSERT INTO `user_list` {$data}";
            }else{
                $sql = "UPDATE `user_list` set {$data} where user_id = '{$id}'";
            }
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                if(empty($id))
                $resp['msg'] = 'New User successfully saved.';
                else
                $resp['msg'] = 'User Details successfully updated.';
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Saving User Details Failed. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function delete_user(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `user_list` where rowid = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'User successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function update_credentials(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id','old_password')) && !empty($v)){
                if(!empty($data)) $data .= ",";
                if($k == 'password') $v = md5($v);
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if(!empty($password) && md5($old_password) != $_SESSION['password']){
            $resp['status'] = 'failed';
            $resp['msg'] = "Old password is incorrect.";
        }else{
            $sql = "UPDATE `user_list` set {$data} where user_id = '{$_SESSION['user_id']}'";
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.';
                foreach($_POST as $k => $v){
                    if(!in_array($k,array('id','old_password')) && !empty($v)){
                        if(!empty($data)) $data .= ",";
                        if($k == 'password') $v = md5($v);
                        $_SESSION[$k] = $v;
                    }
                }
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function save_petrol_type(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = addslashes(trim($v));
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        if(empty($id)){
            $sql = "INSERT INTO `petrol_type_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `petrol_type_list` set {$data} where petrol_type_id = '{$id}'";
        }
        @$check= $this->query("SELECT COUNT(petrol_type_id) as count from `petrol_type_list` where `name` = '{$name}' ".($id > 0 ? " and petrol_type_id != '{$id}'" : ""))->fetchArray()['count'];
        if(@$check> 0){
            $resp['status'] ='failed';
            $resp['msg'] = 'Petrol Type already exists.';
        }else{
            @$save = $this->query($sql);
            if($save){
                $resp['status']="success";
                if(empty($id))
                    $resp['msg'] = "Petrol Type successfully saved.";
                else
                    $resp['msg'] = "Petrol Type successfully updated.";
            }else{
                $resp['status']="failed";
                if(empty($id))
                    $resp['msg'] = "Saving New Petrol Type Failed.";
                else
                    $resp['msg'] = "Updating Petrol Type Failed.";
                $resp['error']=$this->lastErrorMsg();
            }
        }
        return json_encode($resp);
    }
    function delete_petrol_type(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `petrol_type_list` where petrol_type_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Petrol Type successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_customer(){
        extract($_POST);
        if(empty($id)){
            $prefix = date('Ym');
            $code = sprintf("%'.04d",1);
            while(true){
                $check = $this->query("SELECT count(customer_id) FROM customer_list where customer_code = '".$prefix.'-'.$code."' ")->fetchArray()[0];
                if($check > 0){
                    $code = sprintf("%'.04d",abs($code) + 1);
                }else{
                    break;
                }
            }
            $_POST['customer_code'] = $prefix.'-'.$code;
        }
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = addslashes(trim($v));
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        if(empty($id)){
            $sql = "INSERT INTO `customer_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `customer_list` set {$data} where customer_id = '{$id}'";
        }
        @$save = $this->query($sql);
        if($save){
            $resp['status']="success";
            if(empty($id))
                $resp['msg'] = "Customer successfully saved.";
            else
                $resp['msg'] = "Customer successfully updated.";
        }else{
            $resp['status']="failed";
            if(empty($id))
                $resp['msg'] = "Saving New Customer Failed.";
            else
                $resp['msg'] = "Updating Customer Failed.";
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function delete_customer(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `customer_list` where customer_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Customer successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_transaction(){
        extract($_POST);
        $data = "";
        $prefix = date('Ym');
        $code = sprintf("%'.04d",1);
        $i = 0;
        while(true){
            $i++;
            $chk = $this->query("SELECT count(transaction_id) `count` FROM `transaction_list` where receipt_no = '".$prefix.'-'.$code."' ")->fetchArray()['count'];
            if($chk > 0){
                $code = sprintf("%'.04d",abs($code) + 1);
            }else{
                break;
            }
        }
        $_POST['receipt_no'] = $prefix.'-'.$code;
        $_POST['user_id'] = $_SESSION['user_id'];
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id')) && !is_array($_POST[$k])){
                $v = addslashes(trim($v));
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        if(empty($id)){
            $sql = "INSERT INTO `transaction_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `transaction_list` set {$data} where stock_id = '{$id}'";
        }
        
        @$save = $this->query($sql);
        if($save){
            $resp['status']="success";
            $_SESSION['flashdata']['type']="success";
            if(empty($id))
                $_SESSION['flashdata']['msg'] = "Transaction successfully saved.";
            else
                $_SESSION['flashdata']['msg'] = "Transaction successfully updated.";
            if(empty($id))
            $last_id = $this->query("SELECT last_insert_rowid()")->fetchArray()[0];
            $tid = empty($id) ? $last_id : $id;
            if($type == 2){
                $debt_data = "('{$tid}','{$customer_id}','{$total}')";
                $save_debts = $this->query("INSERT INTO `debt_list` (`transaction_id`,`customer_id`,`amount`) VALUES {$debt_data}");
            }
            $resp['transaction_id'] = $tid;
        }else{
            $resp['status']="failed";
            if(empty($id))
                $resp['msg'] = "Saving New Transaction Failed.";
            else
                $resp['msg'] = "Updating Transaction Failed.";
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function delete_transaction(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `transaction_list` where transaction_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Transaction successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_payment(){
        extract($_POST);
        if(empty($id)){
            $prefix = date('Ym');
            $code = sprintf("%'.04d",1);
            while(true){
                $check = $this->query("SELECT count(payment_id) FROM payment_list where payment_code = '".$prefix.'-'.$code."' ")->fetchArray()[0];
                if($check > 0){
                    $code = sprintf("%'.04d",abs($code) + 1);
                }else{
                    break;
                }
            }
            $_POST['payment_code'] = $prefix.'-'.$code;
        }
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = addslashes(trim($v));
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        if(empty($id)){
            $sql = "INSERT INTO `payment_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `payment_list` set {$data} where payment_id = '{$id}'";
        }
        @$save = $this->query($sql);
        if($save){
            $resp['status']="success";
            if(empty($id))
                $resp['msg'] = "Payment successfully added.";
            else
                $resp['msg'] = "Payment successfully updated.";
        }else{
            $resp['status']="failed";
            if(empty($id))
                $resp['msg'] = "Saving New Payment Failed.";
            else
                $resp['msg'] = "Updating Payment Failed.";
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
}
$a = isset($_GET['a']) ?$_GET['a'] : '';
$action = new Actions();
switch($a){
    case 'login':
        echo $action->login();
    break;
    case 'customer_login':
        echo $action->customer_login();
    break;
    case 'logout':
        echo $action->logout();
    break;
    case 'customer_logout':
        echo $action->customer_logout();
    break;
    case 'save_user':
        echo $action->save_user();
    break;
    case 'delete_user':
        echo $action->delete_user();
    break;
    case 'update_credentials':
        echo $action->update_credentials();
    break;
    case 'save_petrol_type':
        echo $action->save_petrol_type();
    break;
    case 'delete_petrol_type':
        echo $action->delete_petrol_type();
    break;
    case 'save_customer':
        echo $action->save_customer();
    break;
    case 'delete_customer':
        echo $action->delete_customer();
    break;
    case 'save_transaction':
        echo $action->save_transaction();
    break;
    case 'delete_transaction':
        echo $action->delete_transaction();
    break;
    case 'save_payment':
        echo $action->save_payment();
    break;
    default:
    // default action here
    break;
}