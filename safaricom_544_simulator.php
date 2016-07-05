<?php

/**
 * USSD Practice
 * @author    James Ngugi <ngugi823@gmail.com>
 * @link 	  Strathmore University
 *
 * File contains HTML for simualtion in browsers.
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
			$response = "CON You are about to buy $buying:<br/>";
			$response .= "<a href='?text=1*{$parts[1]}*1'>1</a>. Accept<br/>";
			$response .= "<a href='?text=1*$parts[1]*2'>2</a>. Decline<br/>";
			break;
		}
		break;
	} else {
		//if $parts[1] is empty, they've not selected any option. Show the menu
		$response = "CON Buy 7 Day Bundle:<br/>";
		$response .= "<a href='?text=1*1'>1</a>. 150MB+150sms @Ksh.50<br/>";
		$response .= "<a href='?text=1*2'>2</a>. 60MB+60sms @Ksh.30<br/>";
		$response .= "<a href='?text=1*3'>3</a>. 35MB+35sms @Ksh.20<br/>";
		$response .= "<a href='?text=1*4'>4</a>. 15MB+15sms @Ksh.10<br/>";
		$response .= "<a href='?text=1*5'>5</a>. 7MB+7sms @Ksh.5<br/>";
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
			$response = "CON You are about to buy $buying:<br/>";
			$response .= "<a href='?text=1*{$parts[1]}*1'>1</a>. Accept<br/>";
			$response .= "<a href='?text=1*{$parts[1]}*2'>2</a>. Decline<br/>";
			break;
		}
		break;
	} else {
		$response = "CON Buy 7 Day Bundle:<br/>";
		$response .= "<a href='?text=2*1'>1</a>. 130MB @Sh.100<br/>";
		$response .= "<a href='?text=2*2'>2</a>. 65MB @Sh.50<br/>";
		$response .= "<a href='?text=2*3'>3</a>. 30MB @Sh.30<br/>";
		$response .= "<a href='?text=2*4'>4</a>. 10MB @Sh.10<br/>";
		$response .= "<a href='?text=2*5'>5</a>. 5MB @Sh.5<br/>";
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
			$response = "CON You are about to buy $buying:<br/>";
			$response .= "<a href='?text=1*{$parts[1]}*1'>1</a>. Accept<br/>";
			$response .= "<a href='?text=1*{$parts[1]}*2'>2</a>. Decline<br/>";
			break;
		}
		break;
	} else {
		$response = "CON Buy 30 Day Bundle:<br/>";
		$response .= "<a href='?text=3*1'>1</a>. 12GB @Sh.3000<br/>";
		$response .= "<a href='?text=3*2'>2</a>. 7.5GB @Sh.2000<br/>";
		$response .= "<a href='?text=3*3'>3</a>. 3GB @Sh.1000<br/>";
		$response .= "<a href='?text=3*4'>4</a>. 1GB @Sh.500<br/>";
		$response .= "<a href='?text=3*5'>5</a>. 350MB @Sh.250<br/>";
	}

	break;
case '4': //buy for other number
	//has the phonenumber been set? ask for it! In this case i a hardcoding. No time for real input.
	if (!isset($parts[1])) {

		$response = "END Enter phonenumber:<br/>";
		$response .= "<a href='?text=4*{$parts[1]}'>0728270795</a><br/>";
	} elseif (!isset($parts[2])) {
		//has the amount been set? Again, its hardcoded.
		$response = "END Enter amount between 5MB and 10MB:<br/>";
		$response .= "<a href='?text=4*{$parts[1]}*{$parts[2]}'>6MB</a><br/>";
	} else {
		$response = "END Sorry, you do not have enough bundles to complete this request.<br/>";
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
		$response = "END Visit <a href='safaricom.com/'>Safaricom Website/</a> on your phone to read the FAQ. <br/>";
		break;

	default: //If we are here, no option has been selected. Show the menu.
		$response = "CON Data Manager:<br/>";
		$response .= "<a href='?text=5*1'>1</a>. Accept<br/>";
		$response .= "<a href='?text=5*2'>2</a>. Deactivate<br/>";
		$response .= "<a href='?text=5*3'>3</a>. Check Status<br/>";
		$response .= "<a href='?text=5*4'>4</a>. Activate/DEactivate for other number<br/>";
		$response .= "<a href='?text=5*5'>5</a>. FAQ<br/>";
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
				$response = "CON You are about to buy $buying:<br/>";
				$response .= "<a href='?text=98*8*{$parts[2]}*1'>1</a>. Accept<br/>";
				$response .= "<a href='?text=98*8*{$parts[2]}*2'>2</a>. Decline<br/>";
				break;
			}
			break;
		} else {
			$response = "CON Buy 90 Day Bundle:<br/>";
			$response .= "<a href='?text=98*8*1'>1</a>. 30GB @Sh.9000<br/>";
			$response .= "<a href='?text=98*8*2'>2</a>. 16GB @Sh.6000<br/>";
			$response .= "<a href='?text=98*8*3'>3</a>. 6GB @Sh.3000<br/>";
		}
		break;
	case '9': //facebook
		$response = "END Visit <a href='https://facebook.com/'>facebook.com</a> on your phone to access Facebook. <br/>";
		break;
	case '10': //appstore
		$response = "END Visit <a href='appstore.safaricom.com/'>appstore.safaricom.com/</a> on your phone to download apps <br/>";

		break;
	case '11': //games
		$response = "END Visit <a href='www.safaricom.com/games'>www.safaricom.com/games</a> on your phone to play games <br/>";
		break;
	case '12': //daily nation
		$response = "END Visit <a href='www.nation.co.ke/'>www.nation.co.ke/</a> on your phone to access Daily Nation. <br/>";
		break;
	case '13': //twitter
		$response = "END Visit <a href='https://twitter.com/'>twitter.com</a> on your phone to access Twitter. <br/>";
		break;
	case '14': //Youtube
		$response = "END Visit <a href='https://youtube.com/'>youtube.com</a> on your phone to access Youtube. <br/>";
		break;
	case '15': //my market
		$response = "END  Dial *665# or visit <a href='www.safaricom.com/mymarket'>safaricom.com/mymarket</a> to access MyMarket<br/>";
		break;
	case '16': //whatsapp
		$response = "END Download whatsapp from <a href='https://whatsapp.com/'>whatsapp.com</a> on your phone to chat with family and friends. <br/>";
		break;
	case '17': //google services
		$response = "END Visit <a href='https://apps.google.com/'>apps.google.com</a> on your phone to access Google services. <br/>";
		break;
	case '98':
		//User is on the third screen of the home menu
		///lets find what they have selected so far
		switch (@$parts[2]) {
		case '18': //sambaza internet
			//has the phonenumber been set?
			if (!isset($parts[3])) {

				$response = "END Enter phonenumber to sambaza to to<br/>";
				$response .= "<a href='?text=98*98*18*0728270795'>0728270795</a><br/>";
			} else {
				//we don't have bundles to sambaza so...
				$response = "END Sorry, your data bundle account balance is not enough to complete your sambaza request.<br/>";
			}
			break;
		case '19': //fuata futaa
			//safaricom says:
			$response = "END Sorry this service is no longer available.<br/>";
			break;
		case '20': //browse at 2/-
			switch (@$parts[3]) {
			case '1': //activate
				$response = "END You are now browsing under the 2/- per minute tariff.<br/>";
				break;
			case '2': //deactivate
				$response = "END Your 2/- tariff has been deactivated.<br/>";
				break;

			default: //menu
				$response = "CON Browse @2/-:<br/>";
				$response .= "<a href='?text=98*98*20*1'>1</a>. Activate<br/>";
				$response .= "<a href='?text=98*98*20*2'>2</a>. Deactivate<br/>";
				break;
			}
			break;
		case '21':
			switch (@$parts[3]) {
			case '1': //kulahappy
				$response = "END Visit <a href='https:/kulahappy.com/'>kulahappy.com</a> on your phone to watch KulaHappy clips. <br/>";
				break;
			case '2': //soccer updates
				$response = "END Dial *455# on your phone to receive soccer updates.<br/>";
				break;
			case '3': //twitter
			case '4': //BuniTV
				$response = "END You will receive an SMS notification shortly.<br/>";
				break;

			default: //menu
				$response = "CON Quick links:<br/>";
				$response .= "<a href='?text=98*98*21*1'>1</a>. KulaHappy<br/>";
				$response .= "<a href='?text=98*98*21*2'>2</a>. Soccer Updates<br/>";
				$response .= "<a href='?text=98*98*21*3'>3</a>. Twitter<br/>";
				$response .= "<a href='?text=98*98*21*4>4</a>. BuniTV<br/>";
				break;
			}
			break;
		case '22': //internet settings
			$response = "END You will Internet Settings shortly. Save and install these settings to your phone in order to access the Internet.<br/>";
			break;
		default: //menu
			$response = "CON Quick links:<br/>";
			$response .= "<a href='?text=98*98*18'>18</a>. Sambaza Internet<br/>";
			$response .= "<a href='?text=98*98*19'>19</a>. Fuata Futaa<br/>";
			$response .= "<a href='?text=98*98*20'>20</a>. Browse @2/-<br/>";
			$response .= "<a href='?text=98*98*21'>21</a>. QuickLinks<br/>";
			$response .= "<a href='?text=98*98*22'>22</a>. Internet Settings<br/>";
			$response .= "<a href='?text=98'>0</a>. Back<br/>";
			break;
		}
		break;

	default:
		# They've not selected anything,lets show the second part of the main menu
		$response = "CON Menu<br/>";
		$response .= "<a href='?text=98*8'>8</a>. Daily 90 Day Bundles <br/>";
		$response .= "<a href='?text=98*9'>9</a>. Facebook <br/>";
		$response .= "<a href='?text=98*10'>10</a>. App Store<br/>";
		$response .= "<a href='?text=98*11'>11</a>. Games <br/>";
		$response .= "<a href='?text=98*12'>12</a>. Daily Nation<br/>";
		$response .= "<a href='?text=98*13'>13</a>. Twitter <br/>";
		$response .= "<a href='?text=98*14'>14</a>. YouTube <br/>";
		$response .= "<a href='?text=98*15'>15</a>. MyMarket <br/>";
		$response .= "<a href='?text=98*16'>16</a>. Whatsapp <br/>";
		$response .= "<a href='?text=98*17'>17</a>. Google Services <br/>";
		$response .= "<a href='?text=98*98'>98</a>. MORE<br/>";
		$response .= "<a href='?text='>0</a>. Back<br/>";

		break;
	}
	break;

default:
	// Nothing selected.  Show the first screen of the main menu.
	$response = "CON What would you want to check <br/>";
	$response .= "<a href='?text=1'>1</a>. Daily Bundles <br/>";
	$response .= "<a href='?text=2'>2</a>. Buy 7 Day Bundle <br/>";
	$response .= "<a href='?text=3'>3</a>. Buy 30 Day Bundle <br/>";
	$response .= "<a href='?text=4'>4</a>. Buy for other number <br/>";
	$response .= "<a href='?text=5'>5</a>. My Data Manager <br/>";
	$response .= "<a href='?text=6'>6</a>. Okoa Bundles <br/>";
	$response .= "<a href='?text=7'>7</a>. Check Bundle Balance<br/>";
	$response .= "<a href='?text=98'>98</a>. MORE<br/>";

	break;
}
// Print the response onto the page so that our gateway can read it
//header('Content-type: text/plain');
# Ensure we output as html,otherwise page won't be shown well.
header('Content-type: text/html');
echo $response;

// DONE!!!