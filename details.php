<!DOCTYPE html>
<html>
    <head>
    <style>
            table {
                margin: 0 auto;
                font-size: large;
                border: 1px solid black;
            }

            h1 {
                text-align: center;
                color: #006600;
                font-size: xx-large;
                font-family: 'Gill Sans', 'Gill Sans MT', 
                    ' Calibri', 'Trebuchet MS', 'sans-serif';
            }

            td {
                background-color: #E4F5D4;
                border: 1px solid black;
            }

            th,
            td {
                font-weight: bold;
                border: 1px solid black;
                padding: 10px;
                text-align: center;
            }
            td {
                font-weight: lighter;
            }
        </style>
    </head>
    </html>


<?php
include "conn.php";
if(isset($_GET['bnum'])){
    $bnum=$_GET['bnum'];
    $sql="SELECT * from qr WHERE Batch_Number='$bnum'";
    $result = mysqli_query($con, $sql);
    $query = array();
                while($query[] = mysqli_fetch_assoc($result));
                array_pop($query);
                echo '<table border="1">';
                echo '<tr>';
                foreach($query[0] as $key => $value) {
                    echo '<td>';
                    echo $key;
                    echo '</td>';
                }
                echo '</tr>';
                foreach($query as $row) {
                    echo '<tr>';
                    foreach($row as $column) {
                        echo '<td>';
                        echo $column;
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
                
}

?>