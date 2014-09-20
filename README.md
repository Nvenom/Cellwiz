/*=================<img src="https://secure.cellwiz.net/new/frame/skins/default/images/login-header.png" align="center" alt="Cellwiz"/>============================*\
<br/>


Cellwiz P.O.S. System designed around inventory Management.

This is open source, i dont watch this repository as its an old project of mine. Config files are missing to make this work. Feel free to use it for whatever you want.

Cellwiz Engine is the framework being developed to host this system. It uses PDO to handle all MySQL connections and queries.
```txt
File:     frame/engine.php
Version:  0.1.6
Classes:  barcode,format,grid,hash,mysql,test,tracking,users

This revision is stable and dosent require any major changes.
```

Engine Functions
```php
ENGINE::START();       //Includes Default files and function for the framework.
ENGINE::START('HASH'); //Includes Default files and functions + hash class for passwords.
	
ENGINE::TICKETINFO(/* Ticket ID */); //Grabs all that tickets infomartion and stores it in a massive tree array.
ENGINE::VOIP(/* User Array */);      //Initializes Twilio with the user defined.
ENGINE::ITEM(/* Item ID */);         //Generates an items information from inventory and sends a message to manager when out of stock.
ENGINE::SERVICE(/* Service ID */);   //Generates a services information.
```

User Functions
```php
/* These functions require ENGINE::START() to be initialized before any of them are called. */
$USER = USER::INFO(/* User ID */);  //Stores all that users info in the Variable $USER.

$USER = USER::VERIFY(/* $Level, $InAjax */);
$USER = USER::VERIFY(1,TRUE);
//It Grabs the users id from a cookie than checks to see if their level is >= $level. 
//$inAjax is passed as either True or False and determines failure fallback.

USER::LOG(/* Message, User ID */);
USER::LOG("Test Log Message");
//Sends a message to the users log if no user ID is supplied it will fallback to a cookie.

USER::NOTE(/* Ticket ID, Note, Type of Note, User ID */);
USER::NOTE(0000000001, "Test Note", 1);
//Sends a note to the Ticket Id Supplied.
```

*Test Functions*
```php
/* This function requires ENGINE::START() to be initialized before it is called. */
TEST::FUNCTION(/* Function, Parameters */);
TEST::FUNCTION('ENGINE::START',ARRAY('HASH'));
//This function logs the time it takes for a function to complete in the console of your browser.
```
