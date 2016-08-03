<?php
// Be sure to include the file you've just downloaded
require_once 'AfricasTalkingGateway.php';

function db()
{
    $servername = "localhost";
    $username = "makaoyan_dean";
    $password = "deanussd2016.";
    $database = "makaoyan_deanussd";

// Create connection
    $conn = new mysqli($servername, $username, $password, $database);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function registerUser($phoneNumber, $ac_no, $id_no, $dob, $password)
{
    $db = db();
    $password = md5($password);
    //MySqli Insert Query
    $insert_row = $db->query("INSERT INTO customers (phonenumber, account_number, id_number, dob, password, balance) VALUES('$phoneNumber', $ac_no, $id_no, '$dob', '$password', 0)");

    if ($insert_row) {

        //inform the user, inform them by sms
        $message = "Dear customer,Welcome to M-Bank. Your registered account number is {$ac_no}. Dial *384*706# to start transacting. Customer care +254 732061144";

        //send it by sms
        sendSms($phoneNumber, $message);

        return true;
    } else {
        file_put_contents("mysql_error.log", $db->error);
        return false;
    }
}

function loginUser($phoneNumber, $ac_no, $password)
{
    $db = db();
    $password = md5($password);

    $result = $db->query("SELECT * FROM customers WHERE phonenumber='$phoneNumber' AND account_number=$ac_no AND password='$password'");
    if ($result->num_rows > 0) {
        return $result->fetch_object();
    } else {
        return false;
    }
}

function reloadUser($user)
{
    $db = db();
    $password = md5($password);

    $result = $db->query("SELECT * FROM customers WHERE id=$user->id");
    if ($result->num_rows > 0) {
        return $result->fetch_object();
    } else {
        return false;
    }
}

function deposit($ac_no, $amount, $user)
{
    $db = db();
    $password = md5($password);

    if ($db->query("UPDATE customers SET balance=balance+$amount")) {
        //also update the activity
        $insert_row = $db->query("INSERT INTO bank_transactions (userid, phonenumber, type, amount) VALUES($user->id, 'D', $amount)");

        file_put_contents("mysql_error.log", $db->error);

        //inform the user, inform them by sms
        $message = "Dear customer,  Your deposit of Ksh. $amount has been successfully debited to your account. Customer care +254 732061144";

        //send it by sms
        sendSms($user->phonenumber, $message);

        return true;
    } else {
        return false;
    }
}

function withdraw($ac_no, $amount, $user)
{
    $db = db();
    $password = md5($password);
    //check whether the amount is greater than balance
    if ($amount > $user->balance) {
        //inform the user, inform them by sms
        $message = "Dear customer,  You do not have enough balance to withdraw Ksh. {$amount}. Dial *384*706# to start transacting. Customer care +254 732061144";

        //send it by sms
        sendSms($user->phonenumber, $message);

    } else {
        if ($db->query("UPDATE customers SET balance=balance-$amount WHERE id=$user->id")) {

            //also update the activity
            $insert_row = $db->query("INSERT INTO bank_transactions (userid, phonenumber, type, amount) VALUES($user->id, '$user->phonenumber', 'W', $amount)");

            file_put_contents("mysql_error.log", $db->error);

            //inform the user, inform them by sms
            $message = "Dear customer, your withdrawal of Ksh. {$amount} has been processed. Reference ID:" . struuid(false) . ". Details: Strathmore University. Dial *384*706# to start transacting. Customer care +254 732061144";

            //send it by sms
            sendSms($user->phonenumber, $message);

            return true;
        } else {
            return false;
        }
    }

}

function changePassword($user, $newPassword)
{
    $db = db();
    $password = md5($newPassword);

    if ($db->query("UPDATE customers SET password='$password' WHERE id=$user->id")) {
        //lets inform them by sms
        $message = "Dear customer, your M-Bank password has been changed. Customer care +254 732061144";

        //send it by sms
        sendSms($user->phonenumber, $message);

        return true;
    } else {
        return false;
    }

}

function balance($user, $sms = false)
{
    $db = db();
    $password = md5($password);

    $result = $db->query("SELECT balance FROM customers WHERE id=$user->id");
    if ($result->num_rows > 0) {
        $balance = $result->fetch_object()->balance;

        if ($sms === true) {
            $message = "Dear customer, your current balance is Ksh. {$balance}. Thank you for using M-Bank. Customer care +254 732061144";

            //send it by sms
            sendSms($user->phonenumber, $message);
        }

        //then return it.
        return $balance;

    } else {
        return false;
    }
}

function miniStatement($user)
{
    $out = array();
    $db = db();
    $password = md5($password);

    $result = $db->query("SELECT * FROM bank_transactions WHERE userid=$user->id ORDER BY created_at DESC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $out[] = $row;
        }

        $message = "Dear customer, you can also access your financial statement by visiting http://bit.ly/mbank on your internet enabled device. Thank you for using M-Bank. Customer care +254 732061144";

        //send it by sms
        sendSms($user->phonenumber, $message);

        return $out;
    } else {
        return false;
    }
}

function sendSms($recipients, $message)
{

// Specify your login credentials
    $username = "jimgme";
    $apikey = "868c553e52a72eea4d40b9d3d70edaec4196a3def373ca1f4a73f90a1db04c04";

// Create a new instance of our awesome gateway class
    $gateway = new AfricasTalkingGateway($username, $apikey);

// Any gateway error will be captured by our custom Exception class below,
    // so wrap the call in a try-catch block

    try
    {
        // Thats it, hit send and we'll take care of the rest.
        $results = $gateway->sendMessage($recipients, $message);

        foreach ($results as $result) {
            // status is either "Success" or "error message"
            //echo " Number: " . $result->number;
            //echo " Status: " . $result->status;
            //echo " MessageId: " . $result->messageId;
            //echo " Cost: " . $result->cost . "\n";
        }
    } catch (AfricasTalkingGatewayException $e) {
        // "Encountered an error while sending: " . $e->getMessage();
        file_put_contents("at_error_log.log", $e->getMessage());
    }
}

function struuid($entropy)
{
    $s = uniqid("", $entropy);
    $num = hexdec(str_replace(".", "", (string) $s));
    $index = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $base = strlen($index);
    $out = '';
    for ($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
        $a = floor($num / pow($base, $t));
        $out = $out . substr($index, $a, 1);
        $num = $num - ($a * pow($base, $t));
    }
    return $out;
}
