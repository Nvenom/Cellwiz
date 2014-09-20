<?php
CLASS GANTT{
    PUBLIC STATIC FUNCTION RENDER($STH, $ENH, $DATA=ARRAY()){
	    $HOURS = ARRAY(
		    '12AM','1AM','2AM','3AM','4AM','5AM','6AM','7AM','8AM','9AM','10AM','11AM',
			'12PM','1PM','2PM','3PM','4PM','5PM','6PM','7PM','8PM','9PM','10PM','11PM'
		);
		$H=$STH;
		$M=$STH;
		$DIF = $ENH - $STH;
		ECHO '<tr>';
		$TY = 0;
		WHILE($H <= $ENH){
		    IF($TY == 0){$SPAN = 61;}ELSE{$SPAN = 60;}
		    ECHO '<td colspan="'.$SPAN.'" class="gantt-hour"><b>'.$HOURS[$H].'</b></td>';
			$H++;
			$TY++;
		}
		ECHO '</tr><tr>
		    <td class="lcolumn"><b>Tickets</b></td>
			<td class="lcolumn2"><b>Status</b></td>
			<td class="lcolumn3"><b>Technician</b></td>
		';
		$TIME = DATE("H:i:s");
		$TIME = EXPLODE(":",$TIME);
		WHILE($M <= $ENH){
		    $I = 0;
			WHILE($I <= 59){
			    IF($I==0 && $M==$STH){$BD = 'style="border-right:0px none;"';} ELSE {$BD = '';}
		        IF($M==$TIME[0] && $I==$TIME[1]){
					ECHO '<td class="gantt-now" '.$BD.'>'.$I.'</td>';
				} ELSE {
					ECHO '<td '.$BD.'>'.$I.'</td>';
				}
				$I++;
			}
			$M++;
		}
		ECHO '</tr>';
		
		FOREACH($DATA AS $ROW){
		    $A=$STH;
			$STIME = EXPLODE(":",$ROW['STIME']);
			$ETIME = EXPLODE(":",$ROW['ETIME']);
			$MODEL = $ROW['MODEL'];
			SWITCH($ROW['DAMAGE']){
			    CASE 1: 
				    $CLASS = 'gantt-red';
				    $SPAN  = '46';
					$TITLE = $MODEL;
				    BREAK;
				CASE 2:
				    $CLASS = 'gantt-blue';
					$SPAN  = '241';
					$TITLE = $MODEL;
				    BREAK;
				CASE 3:
				    $CLASS = 'gantt-green';
					$SPAN  = '61';
					$TITLE = $MODEL;
					BREAK;
				CASE 100:
				    $CLASS = 'gantt-yellow';
					$SPAN  = '11';
					$TITLE = $MODEL;
				    BREAK;
				CASE 101:
				    $CLASS = 'gantt-orange';
					$SPAN  = '11';
					$TITLE = $MODEL;
					$ROW['STATUS'] = 'A/W';
					BREAK;
				CASE 102:
				    $CLASS = 'gantt-purple';
					$SPAN  = '11';
					$TITLE = $MODEL;
					$ROW['STATUS'] = 'P/O';
					BREAK;
			}
			IF($ROW['ETIME'] <= DATE("H:i:s")){$PULSE = 'pulse';} ELSE {$PULSE = '';}
		    ECHO '<tr>
			    <td class="lcolumn"><b>'.$ROW['TICKET'].'</b></td>
				<td class="lcolumn2 '.$CLASS.'" onClick="LoadTicket('."'".$ROW['TICKET']."'".')">'.$ROW['STATUS'].'</td>
				<td class="lcolumn3 '.$PULSE.'"><b>'.$ROW['TECH'].'</b></td>
			';
			$SP = 0;
		    WHILE($A <= $ENH){
		        $I = 0;
			    WHILE($I <= 59){
				    IF($STIME[0] == $ETIME[0]){
					    IF(($A==$STIME[0] || $A==$ETIME[0]) && ($I>=$STIME[1] && $I<=$ETIME[1])){
						    IF($I==$ETIME[1] && $A==$ETIME[0]){ECHO '<td colspan="'.$SP.'">&nbsp;</td><td class="'.$CLASS.'" colspan="'.$SPAN.'" onClick="LoadTicket('."'".$ROW['TICKET']."'".')">'.$TITLE.'</td>';}
						} ELSE {
						    $SP++;
						}
					} ELSE {
					    IF(($A==$STIME[0] && $I>=$STIME[1]) || ($A==$ETIME[0] && $I<=$ETIME[1]) || ($A>$STIME[0] && $A<$ETIME[0])){
							IF($I==$ETIME[1] && $A==$ETIME[0]){ECHO '<td colspan="'.$SP.'">&nbsp;</td><td class="'.$CLASS.'" colspan="'.$SPAN.'" onClick="LoadTicket('."'".$ROW['TICKET']."'".')">'.$TITLE.'</td>';}
						} ELSE {
						    $SP++;
						}
					}
				    $I++;
			    }
			    $A++;
		    }
		    ECHO '</tr>';
		}
	}
}
?>