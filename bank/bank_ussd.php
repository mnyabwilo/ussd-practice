<?php
//capture entire request for debugging
file_put_contents("request.log", file_get_contents('php://input') . "\n", FILE_APPEND);

/**
 * USSD Practice
 * @author    James Ngugi <ngugi823@gmail.com>
 * @link       Strathmore University
 *
 * File contains HTML for simulation in browsers.
 */

// Reads the variables sent via POST from our gateway
//$sessionId = $_POST["sessionId"];
//$serviceCode = $_POST["serviceCode"];
//$phoneNumber = $_POST["phoneNumber"];

$text = @$_POST["text"];

file_put_contents("raw_text.log", $text . "\n", FILE_APPEND);

//explode received text into an array using the asterisk as a delimiter
$parts = explode('*', $text);
$response = '';

file_put_contents("exploded_text.log", $parts[0] . "\n", FILE_APPEND);

switch (@$parts[0]) {
    case '1': //register
        if (empty(@$parts[1])) {
            //is the account number there? 1*ACCOUNTNO
            $response .= "CON Please enter the account number";
        } elseif (empty(@$parts[2])) {
            //is the ID there? 1*ACCOUNTNO*IDNO
            $response .= "CON Please enter your ID number";
        } elseif (empty(@$parts[3])) {
            //is the DOB there? 1*ACCOUNTNO*IDNO*DOB
            $response .= "CON Please enter your date of birth";
        } elseif (empty(@$parts[4])) {
            //is the password there? 1*ACCOUNTNO*IDNO*DOB*PASSWORD
            $response .= "CON Choose a password";
        } elseif (empty(@$parts[5])) {
            //has the password been confirmed 1*ACCOUNTNO*IDNO*DOB*PASSWORD*RETYPEPASS
            $response .= "CON Retype password to confirm";
        } else {
            //@todo, save the user 1*ACCOUNTNO*IDNO*DOB*PASSWORD*RETYPEPASS
            $response .= "END Registration successful. Use your credentials to login.";
        }
        break;
    case '2': //login
        if (empty(@$parts[1])) {
            //is the account number there? 2*ACCOUNTNO
            $response .= "CON Enter the account number";
        } elseif (empty(@$parts[2])) {
            //is the password there? 2*ACCOUNTNO*PASSWORD
            $response .= "CON Enter your password";
        } else {
            //maybe do validation here?
            //lets proceed, lets work with the menu after login
            //2*ACCOUNTNO*PASSWORD*OPTION
            switch (@$parts[3]) {
                case '1': //check balance 2*ACCOUNTNO*PASSWORD*1
                    # @todo find balance from db
                    $response .= "END Your balance is Ksh. 5000";
                    break;
                case '2': //cash deposit 2*ACCOUNTNO*PASSWORD*2
                    if (empty(@$parts[4])) {
                        //is the account number there?  2*ACCOUNTNO*PASSWORD*2*ACCOUNTNO
                        $response .= "CON Enter the account number";
                    } elseif (empty(@$parts[5])) {
                        //is the amount there? 2*ACCOUNTNO*PASSWORD*2*ACCOUNTNO*AMOUNT
                        $response .= "CON Enter amount";
                    } else {
                        //@todo, store deposit amount and increment balance 2*ACCOUNTNO*PASSWORD*3*ACCOUNTNO*AMOUNT
                        $response .= "END Deposit successful. You'll receive an SMS notification. New balance is Ksh. 6000. Thank you for using M-Banking.";
                    }
                    break;
                case '3': //cash withdraw
                    if (empty(@$parts[4])) {
                        //is the account number there?  2*ACCOUNTNO*PASSWORD*3*ACCOUNTNO
                        $response .= "CON Enter the account number";
                    } elseif (empty(@$parts[5])) {
                        //is the amount there? 2*ACCOUNTNO*PASSWORD*3*ACCOUNTNO*AMOUNT
                        $response .= "CON Enter amount";
                    } else {
                        //@todo, withdraw amount and decrement balance 2*ACCOUNTNO*PASSWORD*3*ACCOUNTNO*AMOUNT
                        $response .= "END Withdraw successful. You'll receive an SMS notification. New balance is Ksh. 6000. Thank you for using M-Banking.";
                    }
                    break;
                case '4': //change password
                    if (empty(@$parts[4])) {
                        //is the old password input there? 2*ACCOUNTNO*PASSWORD*4*OLDPASSWORD
                        //@todo, check whether theold password is indeed the old password ;)
                        $response .= "CON Enter the old password number.";
                    } elseif (empty(@$parts[5])) {
                        //is the new password there? 2*ACCOUNTNO*PASSWORD*4*OLDPASSWORD*NEWPASSWORD
                        $response .= "CON Enter the new password";
                    } elseif (empty(@$parts[6])) {
                        //Have they retyped the password 2*ACCOUNTNO*PASSWORD*4*OLDPASSWORD*NEWPASS*RETYPEDPASS
                        $response .= "CON Retype password to confirm";
                    } else {
                        //@todo, change password in DB
                        $response .= "END Your password has been changed. Thank you for using M-Banking.";
                    }
                    break;
                case '5': //mini statement
                    //@todo find last 5 transactions from db and display
                    $response .= "CON Last 5 transactions\n";
                    $response .= "D Ksh.3500 \n";
                    $response .= "W Ksh.1000 \n";
                    $response .= "D Ksh.200 \n";
                    $response .= "D Ksh.300 \n";
                    $response .= "D Ksh.200 \n";
                    $response .= "B Ksh.5300 \n";
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

        break;
    default: //nothing selected,show the main menu
        $response .= "CON Welcome to M-Banking \n";
        $response .= "1. Register \n";
        $response .= "2. Login \n";
        break;
}
// Print the response onto the page so that the gateway can read it
header('Content-type: text/plain');
echo $response;

// DONE!!!
