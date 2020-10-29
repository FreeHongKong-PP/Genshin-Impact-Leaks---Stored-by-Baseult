<?php

$lines = file('censored', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES); //Thats the Database - where it gets the accounts from. It checks first if that file exist (i will censor the filepath).

$search = preg_quote($_POST['search'], '~');				//The search by the User for his username
$matches = preg_grep('~\b' . $search . '\b~', $lines);		//It searches in the Database for the searchstring
$myurl = "https://www.dehashed.com/search?query=$search";	//dehashed api for further checking

$count1 = 'count1.txt';							//its a simple txt file with a number (1)
$countdata = file_get_contents ( $count1 );		
$countdata = intval($countdata) + 1;			//everytime someone uses the search button it will increase the number by 1
file_put_contents($count1, $countdata);			//a simple counter on how many users used my website already

echo nl2br("You searched for the Account: $search \n \n");	//output echo

if(!isset($search) || trim($search) == '')					//you cant search for nothing
{
   echo "You did not fill out the required fields.";
}
else{

foreach($matches as $line => $match) {						//checks every single line and prints it if its true
echo nl2br("Line {$line} in my Database: {$match}\n");
	$found = True;
}

if ($found == TRUE)	//if it found the account in my database do this:
{
	$count2 = 'count2.txt';									//if a leaked account has been found it will increase the number in anonther txt file to show how many users have been warned already
	$countdata2 = file_get_contents ( $count2 );
	$countdata2 = intval($countdata2) + 1;
	file_put_contents($count2, $countdata2);
	
	echo nl2br("\n \n Your Account has been Leaked!");
	echo nl2br("\n \n We found something. If the Data above is your Account consider to change your Password as soon as possible at: ");
	echo '<a href="https://account.mihoyo.com/#/account/safetySettings">https://account.mihoyo.com/#/account/safetySettings</a>';
	echo nl2br("\n \n Also you can try to Search with your Username, UID or Email again instead (it is case sensitive). \n For a clear result please use your UID.");

}
else //if it didnt found any account in my database do this:
{
	
if (preg_match("/^\d+$/", $search)) {		//If user only enters numbers then it will do this - Detects if its a UID or not
    echo nl2br("Your UID has not been found in our Database, means your Account has probably not been leaked in any Genshin Account leak and is safe. \n But this doesn't mean that your Username or Email has to be safe. For further checking please visit the Website: ");
	echo '<a href="https://haveibeenpwned.com">https://haveibeenpwned.com</a>';
	echo nl2br(" and ");
	echo '<a href="https://www.dehashed.com">https://www.dehashed.com</a>';
	echo nl2br("\n \n Also you can try to Search with your Username or Email again instead. \n Using your Username or Email might gives you a different result since we are also searching trough haveibeenpwned.com and dehashed.com.");
	
} else {		//If user doesn't only enter numbers it will also search in the dehashed database for the account
    
$html = file_get_contents($myurl);			//it downloads the source content of dehashed
$findme = "Data available but hidden.";		//if in the source it found the string then it means that an account has been found on dehashed
$pos = strpos($html, $findme);		
if ($pos === false) {	//if it doesnt detect an account on dehashed then do this:
	echo nl2br("Your Account has not been found in our Database, means your Account is probably safe. \n For further checking please visit the Website: ");
	echo '<a href="https://haveibeenpwned.com">https://haveibeenpwned.com</a>';
	echo nl2br(" and ");
	echo '<a href="https://www.dehashed.com">https://www.dehashed.com</a>';
	echo nl2br("\n \n Also you can try to Search with your UID, Username or Email again instead. \n For a clear result please use your UID.");
}
else //if it found an account on dehashed then do this
{
	
	$count2 = 'count2.txt';
	$countdata2 = file_get_contents ( $count2 );
	$countdata2 = intval($countdata2) + 1;
	file_put_contents($count2, $countdata2);
	
	echo nl2br("Your Account has been Leaked! \n \n");
	echo nl2br("Your Account has not been found in our Database, But we found your Accountdata at haveibeenpwned and dehashed. \n For further checking please visit the Website: ");
	echo '<a href="https://haveibeenpwned.com">https://haveibeenpwned.com</a>';
	echo nl2br(" and ");
	echo '<a href="https://www.dehashed.com">https://www.dehashed.com</a>';
	echo nl2br("\n \n Also you can try to Search with your Username, UID or Email again instead. \n For a clear result please use your UID.");
	
	
}
}
	

	
}
}

?>