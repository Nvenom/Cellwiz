<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
IF($_REQUEST['Digits'] == 1){
    echo "
	<Response>
	    <Dial timeout='10' record='true' action='https://secure.cellwiz.net/new/frame/controllers/v-controllers/call_tucker.php'>404-236-7370</Dial>
	    <Gather action='https://secure.cellwiz.net/new/frame/controllers/v-controllers/process_gather.php' method='GET' numDigits='1'>
	        <Play loop='0'>https://secure.cellwiz.net/new/frame/controllers/v-controllers/af/hello.wav</Play>
        </Gather>
	</Response>
	";
} ELSE IF($_REQUEST['Digits'] == 2){
    echo "
	<Response>
	    <Dial timeout='10' record='true' action='https://secure.cellwiz.net/new/frame/controllers/v-controllers/call_kennesaw.php'>404-228-7360</Dial>
		<Gather action='https://secure.cellwiz.net/new/frame/controllers/v-controllers/process_gather.php' method='GET' numDigits='1'>
	        <Play loop='0'>https://secure.cellwiz.net/new/frame/controllers/v-controllers/af/hello.wav</Play>
        </Gather>
	</Response>
	";
} ELSE IF($_REQUEST['Digits'] == 3){
    echo "
	<Response>
	    <Dial timeout='10' record='true' action='https://secure.cellwiz.net/new/frame/controllers/v-controllers/call_sandysprings.php'>770-485-6440</Dial>
		<Gather action='https://secure.cellwiz.net/new/frame/controllers/v-controllers/process_gather.php' method='GET' numDigits='1'>
	        <Play loop='0'>https://secure.cellwiz.net/new/frame/controllers/v-controllers/af/hello.wav</Play>
        </Gather>
	</Response>
	";
}
?>