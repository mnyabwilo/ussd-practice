<?php
require 'functions.php';
/**
 * USSD Practice
 * @author    James Ngugi <ngugi823@gmail.com>
 * @link       Strathmore University
 *
 */

// Reads the variables sent via POST from the AT gateway
$sessionId = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];

$text = $_POST["text"];
//$text = @$_GET["text"];

//explode received text into an array using the asterisk as a delimiter
$parts = explode('*', $text);
$response = '';

switch (@$parts[0]) {
    case '1': //register
        if (empty($parts[1])) {
            //is the account number there? 1*ACCOUNTNO
            $response .= "CON Please enter the account number";
        } elseif (empty($parts[2])) {
            //is the ID there? 1*ACCOUNTNO*IDNO
            $response .= "CON Please enter your ID number";
        } elseif (empty($parts[3])) {
            //is the DOB there? 1*ACCOUNTNO*IDNO*DOB
            $response .= "CON Please enter your date of birth";
        } elseif (empty($parts[4])) {
            //is the password there? 1*ACCOUNTNO*IDNO*DOB*PASSWORD
            $response .= "CON Choose a password";
        } elseif (empty($parts[5])) {
            //has the password been confirmed 1*ACCOUNTNO*IDNO*DOB*PASSWORD*RETYPEPASS
            $response .= "CON Retype password to confirm";
        } else {
            //register the user
            if ($parts[5] == $parts[4]) {
                registerUser($phoneNumber, $parts[1], $parts[2], $parts[3], $parts[4]);
                $response .= "END Registration successful. Use your credentials to login.";
            } else {
                $response .= "END The passwords don't match. Please try again.";
            }

        }
        break;
    case '2': //login
        if (empty($parts[1])) {
            //is the account number there? 2*ACCOUNTNO
            $response .= "CON Enter the account number";
        } elseif (empty($parts[2])) {
            //is the password there? 2*ACCOUNTNO*PASSWORD
            $response .= "CON Enter your password";
        } else {
            //try logging in
            $user = loginUser($phoneNumber, $parts[1], $parts[2]);

            if (is_null($user)) {
                $response .= "END login failed. Check your credentials and try again.";
            } else {
                //save 'globals'
                $account = $parts[1];

                //lets proceed, lets work with the menu a$phoneNumber, fter login
                //2*ACCOUNTNO*PASSWORD*OPTION
                switch (@$parts[3]) {
                    case '1': //check balance 2*ACCOUNTNO*PASSWORD*1
                        $balance = balance($user, true);
                        $response .= "END Your balance is Ksh. {$balance}";
                        break;
                    case '2': //cash deposit 2*ACCOUNTNO*PASSWORD*2
                        if (empty($parts[4])) {
                            //is the account number there?  2*ACCOUNTNO*PASSWORD*2*ACCOUNTNO
                            $response .= "CON Enter the account number";
                        } elseif (empty($parts[5])) {
                            //is the amount there? 2*ACCOUNTNO*PASSWORD*2*ACCOUNTNO*AMOUNT
                            $response .= "CON Enter amount";
                        } else {
                            //2*ACCOUNTNO*PASSWORD*3*ACCOUNTNO*AMOUNT

                            deposit($account, $parts[5], $user);
                            $balance = balance($user);
                            $response .= "END Deposit successful. You'll receive an SMS notification. New balance is Ksh. {$balance}. Thank you for using M-Banking.";
                        }
                        break;
                    case '3': //cash withdraw
                        if (empty($parts[4])) {
                            //is the account number there?  2*ACCOUNTNO*PASSWORD*3*ACCOUNTNO
                            $response .= "CON Enter the account number";
                        } elseif (empty($parts[5])) {
                            //is the amount there? 2*ACCOUNTNO*PASSWORD*3*ACCOUNTNO*AMOUNT
                            $response .= "CON Enter amount";
                        } else {
                            //2*ACCOUNTNO*PASSWORD*3*ACCOUNTNO*AMOUNT
                            withdraw($account, $parts[5], $user);
                            $balance = balance($user);
                            $response .= "END Your cash withdrawal request is being processed. You will receive and SMS notification shortly. Thank you for using M-Banking.";
                        }
                        break;
                    case '4': //change password
                        if (empty($parts[4])) {
                            //is the old password input there? 2*ACCOUNTNO*PASSWORD*4*OLDPASSWORD
                            //@todo, check whether theold password is indeed the old password ;)
                            $response .= "CON Enter the old password number.";
                        } elseif (empty($parts[5])) {
                            //is the new password there? 2*ACCOUNTNO*PASSWORD*4*OLDPASSWORD*NEWPASSWORD
                            $response .= "CON Enter the new password";
                        } elseif (empty($parts[6])) {
                            //Have they retyped the password 2*ACCOUNTNO*PASSWORD*4*OLDPASSWORD*NEWPASS*RETYPEDPASS
                            $response .= "CON Retype password to confirm";
                        } else {
                            if ($parts[5] == $parts[6]) {
                                changePassword($user, $parts[6]);
                                $response .= "END Your password has been changed. Thank you for using M-Banking.";
                            } else {
                                $response .= "END The passwords don't match. Please try again.";
                            }

                        }
                        break;
                    case '5': //mini statement
                        $transactions = miniStatement($user);
                        if (count($transactions) > 0) {
                            $response .= "END Last 5 transactions\n";
                            $i = 0;
                            foreach ($transactions as $key => $value) {
                                $response .= ++$i . ". " . $value['type'] . " Ksh." . $value['amount'] . " \n";
                            }
                        } else {
                            $response .= "END You do not have any transactions yet.";
                        }

                        break;

                    default: //it seems nothing has been selected,show the home screen
                        $response .= "CON Home Menu\n";
                        $response .= "1. Check Balance \n";
                        $response .= "2. Deposit \n";
                        $response .= "3. Withdraw \n";
                        $response .= "4. Change Password \n";
                        $response .= "5. Mini Statement \n";
                        break;
                }
            }
        }

        break;
    default: //nothing selected,show the main menu
        $counter = 1;
        for ($i = 1; $i <= 6; $i++) {
            for ($j = 1; $j <= 10; $j++, $counter++) {
                $response .= $counter . "\t";
            }
            $response .= "\n";
        }
        /*
        $response .= "CON Welcome to M-Banking \n";
        $response .= "1. Register \n";
        $response .= "2. Login \n";
         */
        break;
}
// Print the response onto the page so that the gateway can read it
header('Content-type: text/plain');
echo $response;

// DONE!!!
