<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            
            #cajaTitulo{
                width: 100%;height: 50px;
                background: blueviolet;
                font-size: 40px;
                font-weight: bold;
                color:white;
                text-align: center;
            }
            .atras{
                width: 6%;height: 50px;
                background: blueviolet;
                text-align: center;
                font-size: 40px;
                font-weight: bold;
            }
            body{
                background:black;
                text-align: center;
                
            }
            h3{
                color:darkslateblue;
                font-size: 25px;
            }
            table{
                border: solid 2px black;
            }
            th{
                background: orange;
                color:white;
            }
            td{
                border: solid 1px black;
                background: bisque;
            }
            a{
                text-decoration: none;
            }
       </style>
    </head>
    <body>
        
        <div id="cajaTitulo">Ventana de detalle</div>
        <div class="atras"><a href="programa.php"><=</a></div>

<?php
         /*
             * @author: Johanna Herrero Pozuelo
             * Created on: 20/01/2021
             * Aplicacion LogIn-LogOut Tema5
             */
        session_start();
        if (!isset($_SESSION['User212DWESProyectoTema5'])) {
            header('Location: LogIn.php');
        }
        
        echo "<h3>Variable SESSION</h3><br>";
            echo "<table>";
            echo"<tr>";
            echo"<th>Clave</th>";
            echo "<th>Valor</th>";
            echo "</tr>";
            foreach ($_SESSION as $clave => $valor) {
                echo '<tr>';
                echo "<td>$clave</td>";
                echo "<td>$valor</td>";
                echo '</tr>';
            }
            echo '</table>';
    /////////////////////////////////////////////////////////////
        echo "<h3>Variable COOKIE</h3><br>";
            echo "<table>";
            echo"<tr>";
            echo"<th>Clave</th>";
            echo "<th>Valor</th>";
            echo "</tr>";
            foreach ($_COOKIE as $clave => $valor) {
                echo '<tr>';
                echo "<td>$clave</td>";
                echo "<td>$valor</td>";
                echo '</tr>';
            }
            echo '</table>';
 ////////////////////////////////////////////////////////    
        echo "<h3>Variable SERVER</h3><br>";
            echo "<table>";
            echo"<tr>";
            echo"<th>Clave</th>";
            echo "<th>Valor</th>";
            echo "</tr>";
            foreach ($_SERVER as $clave => $valor) {
                echo '<tr>';
                echo "<td>$clave</td>";
                echo "<td>$valor</td>";
                echo '</tr>';
            }
            echo '</table>';    
///////////////////////////////////////////////////////////       
        echo "<h3>Variable REQUEST</h3><br>";
            echo "<table>";
            echo"<tr>";
            echo"<th>Clave</th>";
            echo "<th>Valor</th>";
            echo "</tr>";
            foreach ($_REQUEST as $clave => $valor) {
                echo '<tr>';
                echo "<td>$clave</td>";
                echo "<td>$valor</td>";
                echo '</tr>';
            }
            echo '</table>';    
//////////////////////////////////////////////////////////       
            
         echo "<h3>Variable FILES</h3><br>";
            echo "<table>";
            echo"<tr>";
            echo"<th>Clave</th>";
            echo "<th>Valor</th>";
            echo "</tr>";
            foreach ($_FILES as $clave => $valor) {
                echo '<tr>';
                echo "<td>$clave</td>";
                echo "<td>$valor</td>";
                echo '</tr>';
            }
            echo '</table>';    
///////////////////////////////////////////////////////////
           echo "<h3>Variable ENV</h3><br>";
            echo "<table>";
            echo"<tr>";
            echo"<th>Clave</th>";
            echo "<th>Valor</th>";
            echo "</tr>";
            foreach ($_ENV as $clave => $valor) {
                echo '<tr>';
                echo "<td>$clave</td>";
                echo "<td>$valor</td>";
                echo '</tr>';
            }
            echo '</table>';
            
?>

    </body>
</html>