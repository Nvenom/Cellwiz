<?php
FUNCTION CURL($URL, $DS=ARRAY(), $MET="GET") {
    $DS = json_encode($DS);
	
    $CH = CURL_INIT();
    CURL_SETOPT($CH, CURLOPT_URL,$URL);
    CURL_SETOPT($CH, CURLOPT_CUSTOMREQUEST, $MET); 
	IF(!EMPTY($DS)){
        CURL_SETOPT($CH, CURLOPT_POSTFIELDS, $DS);
	}

    CURL_SETOPT($CH, CURLOPT_RETURNTRANSFER,1); 
    CURL_SETOPT($CH, CURLOPT_CONNECTTIMEOUT,1);
    CURL_SETOPT($CH, CURLOPT_USERPWD, "Cellwiz:cpr12345");
    CURL_SETOPT($CH, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);   
    $CON = CURL_EXEC($CH);
    CURL_CLOSE($CH);
    RETURN $CON;
}
echo '<pre>';
print_r(json_decode(CURL('https://api.github.com/repos/Nvenom/cellwiz/issues',ARRAY("bio" => "This is my bio" )),true));
?>