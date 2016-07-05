<?php

/**
 * USSD Practice
 * @author    James Ngugi <ngugi823@gmail.com>
 * @link 	  Strathmore University
 *
 * No HTML,plain text for interface with gateway.
 */

// Reads the variables sent via POST from our gateway
//$sessionId = $_POST["sessionId"];
//$serviceCode = $_POST["serviceCode"];
//$phoneNumber = $_POST["phoneNumber"];

$text = @$_GET["text"];

//explode received text into an array using the asterisk as a delimiter
$parts = explode('*', $text);

switch (@$parts[0]) {
case '1': //daily bundle
	if (!empty(@$parts[1])) {
		if (@$parts[1] == '1') {
//1*1
			$buying = "150MB+150sms @Ksh.50";
		} elseif (@$parts[1] == '2') {
//1*2
			$buying = "60MB+60sms @Ksh.30";
		} elseif (@$parts[1] == '3') {
//1*3
			$buying = "35MB+35sms @Ksh.20";
		} elseif (@$parts[1] == '4') {
//1*4
			$buying = "15MB+15sms @Ksh.10";
		} elseif (@$parts[1] == '5') {
//1*5
			$buying = "7MB+7sms @Ksh.5";
		}
		//check whether they have accepted/declined purchase,otherwise ask for confirmation.
		switch (@$parts[2]) {
		case '1': //accepted //e.g.1*1*1
			$response = "END Your request for the $buying is being processed.You will receive an SMS confirmation shortly.";
			break;
		case '2': //declined //e.g.1*1*1
			$response = "END Thank you for staying with Safaricom the better option.";
			break;

		default: //confirmation menu   NB:$parts[1] contains the selected bundle.
			$response = "CON You are about to buy $buying: \n";
			$response .= "1 . Accept \n";
			$response .= "2 . Decline \n";
			break;
		}
		break;
	} else {
		//if $parts[1] is empty, they've not selected any option. Show the menu
		$response = "CON Buy 7 Day Bundle: \n";
		$response .= "1 . 150MB+150sms @Ksh.50 \n";
		$response .= "2 . 60MB+60sms @Ksh.30 \n";
		$response .= "3 . 35MB+35sms @Ksh.20 \n";
		$response .= "4 . 15MB+15sms @Ksh.10 \n";
		$response .= "5 . 7MB+7sms @Ksh.5 \n";
	}
	break;
case '2': //7 day bundle
	if (!empty(@$parts[1])) {
		if (@$parts[1] == '1') {
			$buying = "130MB @Sh.100";
		} elseif (@$parts[1] == '2') {
			$buying = "65MB @Sh.50";
		} elseif (@$parts[1] == '3') {
			$buying = "30MB @Sh.30";
		} elseif (@$parts[1] == '4') {
			$buying = "10MB @Sh.10";
		} elseif (@$parts[1] == '5') {
			$buying = "5MB @Sh.5";
		}
		switch (@$parts[2]) {
		case '1': //accept
			$response = "END Your request for the $buying is being processed.You will receive an SMS confirmation shortly.";
			break;
		case '2': //decline
			$response = "END Thank you for staying with Safaricom the better option.";
			break;

		default: //confirmation menu
			$response = "CON You are about to buy $buying: \n";
			$response .= "1 . Accept \n";
			$response .= "2 . Decline \n";
			break;
		}
		break;
	} else {
		$response = "CON Buy 7 Day Bundle: \n";
		$response .= "1 . 130MB @Sh.100 \n";
		$response .= "2 . 65MB @Sh.50 \n";
		$response .= "3 . 30MB @Sh.30 \n";
		$response .= "4 . 10MB @Sh.10 \n";
		$response .= "5 . 5MB @Sh.5 \n";
	}
	break;
case '3': //30 day bundles
	if (!empty(@$parts[1])) {
		if (@$parts[1] == '1') {
			$buying = "12GB @Sh.3000";
		} elseif (@$parts[1] == '2') {
			$buying = "7.5GB @Sh.2000";
		} elseif (@$parts[1] == '3') {
			$buying = "3GB @Sh.1000";
		} elseif (@$parts[1] == '4') {
			$buying = "1GB @Sh.500";
		} elseif (@$parts[1] == '5') {
			$buying = "350MB @Sh.250";
		}
		switch (@$parts[2]) {
		case '1': //accept
			$response = "END Your request for the $buying is being processed.You will receive an SMS confirmation shortly.";
			break;
		case '2': //decline
			$response = "END Thank you for staying with Safaricom the better option.";
			break;

		default: //confirmation menu
			$response = "CON You are about to buy $buying: \n";
			$response .= "1 . Accept \n";
			$response .= "2 . Decline \n";
			break;
		}
		break;
	} else {
		$response = "CON Buy 30 Day Bundle: \n";
		$response .= "1 . 12GB @Sh.3000 \n";
		$response .= "2 . 7.5GB @Sh.2000 \n";
		$response .= "3 . 3GB @Sh.1000 \n";
		$response .= "4 . 1GB @Sh.500 \n";
		$response .= "5 . 350MB @Sh.250 \n";
	}

	break;
case '4': //buy for other number
	//has the phonenumber been set? ask for it! In this case i a hardcoding. No time for real input.
	if (!isset($parts[1])) {

		$response = "END Enter phonenumber: \n";
		$response .= "0728270795  \n";
	} elseif (!isset($parts[2])) {
		//has the amount been set? Again, its hardcoded.
		$response = "END Enter amount between 5MB and 10MB: \n";
		//@todo modify this accordingly. Dont hardcode
		$response .= "6MB  \n";
	} else {
		$response = "END Sorry, you do not have enough bundles to complete this request. \n";
	}
	break;
case '5': //my data manager
	switch (@$parts[1]) {
	case '1': //activate
		$response = "END Your data manager has been activated. Safaricom.";
		break;
	case '2': //deactivate
		$response = "END Your data manager has been deactivated. Safaricom.";
		break;
	case '3': //check status
		$response = "END You will receive an SMS notification shortly. Safaricom.";
		break;
	case '4': //activate for other number
		$response = "END Sorry, this service is no longer available. Safaricom.";
		break;
	case '5': //FAQ
		$response = "END Visit <a href='safaricom.com/'>Safaricom Website/  on your phone to read the FAQ.  \n";
		break;

	default: //If we are here, no option has been selected. Show the menu.
		$response = "CON Data Manager: \n";
		$response .= "1 . Accept \n";
		$response .= "2 . Deactivate \n";
		$response .= "3 . Check Status \n";
		$response .= "4 . Activate/DEactivate for other number \n";
		$response .= "5 . FAQ \n";
		break;
	}
	break;
case '6': //okoa bundles. No real okoa going on.
	$response = "END Request not successful. Your line must be in use for 6 months or more, have repaid previous Okoa debt and used more than 5/= within the past 1 week.";
	break;
case '7': //check balance
	$response = "END You will receive your data bundle balance shortly.";
	break;
case '98':
	//User is now on screen two of the homepage.
	///lets find what they have selected so far
	switch (@$parts[1]) {

	case '8': //buy 90 day bundle
		if (!empty(@$parts[2])) {
			if (@$parts[2] == '1') {
				$buying = "30GB @Sh.9000";
			} elseif (@$parts[2] == '2') {
				$buying = "16GB @Sh.6000";
			} elseif (@$parts[2] == '3') {
				$buying = "6GB @Sh.3000";
			}

			switch (@$parts[3]) {
			case '1': //accept
				$response = "END Your request for the $buying is being processed.You will receive an SMS confirmation shortly.";
				break;
			case '2': //decline
				$response = "END Thank you for staying with Safaricom the better option.";
				break;

			default: //confirmation menu
				$response = "CON You are about to buy $buying: \n";
				$response .= "1 . Accept \n";
				$response .= "2 . Decline \n";
				break;
			}
			break;
		} else {
			$response = "CON Buy 90 Day Bundle: \n";
			$response .= "1 . 30GB @Sh.9000 \n";
			$response .= "2 . 16GB @Sh.6000 \n";
			$response .= "3 . 6GB @Sh.3000 \n";
		}
		break;
	case '9': //facebook
		$response = "END Visit facebook.com  on your phone to access Facebook.  \n";
		break;
	case '10': //appstore
		$response = "END Visit appstore.safaricom.com  on your phone to download apps  \n";

		break;
	case '11': //games
		$response = "END Visit www.safaricom.com/games  on your phone to play games  \n";
		break;
	case '12': //daily nation
		$response = "END Visit www.nation.co.ke  on your phone to access Daily Nation.  \n";
		break;
	case '13': //twitter
		$response = "END Visit twitter.com  on your phone to access Twitter.  \n";
		break;
	case '14': //Youtube
		$response = "END Visit youtube.com  on your phone to access Youtube.  \n";
		break;
	case '15': //my market
		$response = "END  Dial *665# or visit safaricom.com/mymarket  to access MyMarket \n";
		break;
	case '16': //whatsapp
		$response = "END Download whatsapp from whatsapp.com  on your phone to chat with family and friends.  \n";
		break;
	case '17': //google services
		$response = "END Visit apps.google.com  on your phone to access Google services.  \n";
		break;
	case '98':
		//User is on the third screen of the home menu
		///lets find what they have selected so far
		switch (@$parts[2]) {
		case '18': //sambaza internet
			//has the phonenumber been set?
			if (!isset($parts[3])) {

				$response = "END Enter phonenumber to sambaza to to \n";
				//@todo Modify this accordingly. Stop hardcoding
				$response .= "0728270795  \n";
			} else {
				//we don't have bundles to sambaza so...
				$response = "END Sorry, your data bundle account balance is not enough to complete your sambaza request. \n";
			}
			break;
		case '19': //fuata futaa
			//safaricom says:
			$response = "END Sorry this service is no longer available. \n";
			break;
		case '20': //browse at 2/-
			switch (@$parts[3]) {
			case '1': //activate
				$response = "END You are now browsing under the 2/- per minute tariff. \n";
				break;
			case '2': //deactivate
				$response = "END Your 2/- tariff has been deactivated. \n";
				break;

			default: //menu
				$response = "CON Browse @2/-: \n";
				$response .= "1 . Activate \n";
				$response .= "2 . Deactivate \n";
				break;
			}
			break;
		case '21':
			switch (@$parts[3]) {
			case '1': //kulahappy
				$response = "END Visit kulahappy.com  on your phone to watch KulaHappy clips.  \n";
				break;
			case '2': //soccer updates
				$response = "END Dial *455# on your phone to receive soccer updates. \n";
				break;
			case '3': //twitter
			case '4': //BuniTV
				$response = "END You will receive an SMS notification shortly. \n";
				break;

			default: //menu
				$response = "CON Quick links: \n";
				$response .= "1 . KulaHappy \n";
				$response .= "2 . Soccer Updates \n";
				$response .= "3 . Twitter \n";
				$response .= "4 . BuniTV \n";
				break;
			}
			break;
		case '22': //internet settings
			$response = "END You will Internet Settings shortly. Save and install these settings to your phone in order to access the Internet. \n";
			break;
		default: //menu
			$response = "CON Quick links: \n";
			$response .= "18 . Sambaza Internet \n";
			$response .= "19 . Fuata Futaa \n";
			$response .= "20 . Browse @2/- \n";
			$response .= "21 . QuickLinks \n";
			$response .= "22 . Internet Settings \n";
			$response .= "0 . Back \n";
			break;
		}
		break;

	default:
		# They've not selected anything,lets show the second part of the main menu
		$response = "CON Menu \n";
		$response .= "8 . Daily 90 Day Bundles  \n";
		$response .= "9 . Facebook  \n";
		$response .= "10 . App Store \n";
		$response .= "11 . Games  \n";
		$response .= "12 . Daily Nation \n";
		$response .= "13 . Twitter  \n";
		$response .= "14 . YouTube  \n";
		$response .= "15 . MyMarket  \n";
		$response .= "16 . Whatsapp  \n";
		$response .= "17 . Google Services  \n";
		$response .= "98 . MORE \n";
		$response .= "0 . Back \n";

		break;
	}
	break;

default:
	// Nothing selected.  Show the first screen of the main menu.
	$response = "CON What would you want to check  \n";
	$response .= "1 . Daily Bundles  \n";
	$response .= "2 . Buy 7 Day Bundle  \n";
	$response .= "3 . Buy 30 Day Bundle  \n";
	$response .= "4 . Buy for other number  \n";
	$response .= "5 . My Data Manager  \n";
	$response .= "6 . Okoa Bundles  \n";
	$response .= "7 . Check Bundle Balance \n";
	$response .= "98 . MORE \n";

	break;
}
// Print the response onto the page so that our gateway can read it
header('Content-type: text/plain');
//header('Content-type: text/html');
echo $response;

// DONE!!!