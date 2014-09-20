jQuery(document).ready(function($) {

    $('#tilda').tilda(function(command, terminal) {
        if(command == 'help'){
		    terminal.echo(
			'\t'+
			'"/SYSTEM"      =>   Shows System command/Help\n\t'+
			'\t"/SYSTEM.clearcache"    =>   Clears browser Cache\n\t'+
			'\t"/SYSTEM.time"          =>   Shows Current System Time\n\t'+
			'"/ACCOUNT"     =>   Account Commands\n\t'+
			'"/REPORT"      =>   Error Reporting\n\t'+
			'"/DEV"         =>   Developer Commands\n\t'+
			'"CREDITS"      =>   Development Team Information\n\t'
			);
		} else if(command == '/SYSTEM.clearcache'){
		    window.location = "https://secure.cellwiz.net/new/system/index.php?mode=debug"
        } else if(command == '/SYSTEM.time'){
		    terminal.echo('System Time: <?php echo time();?> // <?php echo date("Y-m-d H:i:s"); ?>\nSystem Timezone: <?php echo date_default_timezone_get(); ?>')
        } else {
		    terminal.echo('\tUnkown Command:' + command + ' // Type Help for more Information.');
		}
    });
});