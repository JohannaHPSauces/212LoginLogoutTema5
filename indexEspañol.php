<?php
         /*
             * @author: Johanna Herrero Pozuelo
             * Created on: 20/04/2022
             * Aplicacion LogIn-LogOut Tema5
             */
        if (isset($_REQUEST['Iniciar'])) {
            header('Location: ../codigoPHP/LogIn.php');
            exit;
        }
        if (!isset($_COOKIE['idioma'])){
            setcookie("idioma", "es", time()+2000002); //Pongo el idioma en español y el tiempo de expiracion en +2000002
            header('Location: indexInicio.php'); 
            exit;
        }
        if (isset($_REQUEST['es'])) {
            header('Location: indexEspañol.php');
            exit;
        }
        
        if(isset($_REQUEST['idiomaBotonSeleccionado'])){
            setcookie("idioma", $_REQUEST['idiomaBotonSeleccionado'], time()+2000002);//Ponemos que el idioma sea el seleccionado en el boton
        }
        
        include_once 'config/confCookie.php';
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            #cajaTitulo{
                width: 100%;height: 50px;
                background: grey;
                font-size: 40px;
                font-weight: bold;
                color:white;
                text-align: center;
            }
            body{
                background: white;
            }
            input{
                width: 200px;height: 60px;
                font-size: 25px;
                text-align: center;
                display: block;
                margin: 1rem auto;
            }
            input:hover{
                background: darkgrey;
            }
            a{
                text-decoration: none;
            }
            p{
                text-align: center;
            }
            footer{
               background: grey;
               border-radius: 5px 5px 5px 5px;
               font-weight: bold;
               position: fixed;
               bottom: -1px;
               width: 100%;
               height: 60px;
               color: black;
               text-align: center;
               padding: 2px;
               vertical-align: middle;
            }
            a img{
                display: flex;
                margin:auto;
                width:35px;
                height:35px;
            }
            strong{
		font-size: 20px;
            }
            strong a{
		color:black;
		text-decoration: none;
            }
            strong a:hover{
		color:blue;
            }
            div:nth-of-type(2){
                background: none;                
                width: 150px;height: 50px;
            }
        </style>
    </head>
    <body>
        <header>
            <!--class="titulo"-->
            <div id="cajaTitulo">Proyecto Login-Logout</div>
            <form class="cajaIdioma">
                <button class="cajaIdioma" type="submit" name="idiomaBotonSeleccionado" value="es" ><img src="images/es.png" alt="cargando.." height="20px"></button>
                <button class="cajaIdioma" type="submit" name="idiomaBotonSeleccionado" value="en" ><img src="images/in.png" alt="cargando.." height="20px"></button>
                <button class="cajaIdioma" type="submit" name="idiomaBotonSeleccionado" value="pt"><img src="images/po.png" alt="cargando.." height="20px"></button>
            </form>
            <br>
            <h1> HAS SELECCIONADO EL IDIOMA ESPAÑOL</h1>
            <input type="submit" value="Iniciar" name="iniciar sesion" class="iniciarsesion"/></input></a>
            <br>
            <a href="../212ProyectoTema5/index.php"><input type="submit" value="Salir" name="Salir" class="iniciarsesion"/></input></a>
        </header>
        <footer>
            2021-22 I.E.S. Los sauces. ©Todos los derechos reservados. <strong> <a href="http://daw212.sauces.local/">Johanna Herrero Pozuelo</a></strong>
            <a target="_blank" href="https://github.com/JohannaHPSauces/LoginLogoutTema5/tree/developer"><img src="../images/git.png" alt="" class="git"></a>
        </footer>
</body>
</html>