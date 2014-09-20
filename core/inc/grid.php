<?php

/////////////////////////////////
//..........grid.php...........//
//.............................//
//CCNetTech Core Structure v1.0//
//.....Created By CJ Clark.....//
/////////////////////////////////

class GridFeed{

    public function Head($columns){
		echo"
            <table class='GridFeed'>
                <thead>
                    <tr>";
                        foreach($columns as $a => $b){
                            echo"
                                <td>$b</td>
                            ";
                        }
                    echo"</tr>
                </thead>
		";
	}

	public function Body($rows, $columns){
        echo"
            <tbody>";
                foreach($rows as $a){
                    echo "<tr>";
                    foreach($columns as $b => $c){
                        echo "<td>".$a[$b]."</td>";
                    }
                    echo "</tr>";
                }
            echo "</tbody>
        ";
	}

    public function Foot($columns){
        echo"
                <tfoot>
                    <tr>";
                        foreach($columns as $a => $b){
                            echo"
                                <td>$b</td>
                            ";
                        }
                    echo"</tr>
                </tfoot>
            </table>
        ";
    }
}
?>