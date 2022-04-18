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
                background: blueviolet;
                font-size: 40px;
                font-weight: bold;
                color:white;
                text-align: center;
            }
            body{
                background: rgb(215, 185, 250);
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
               background: blueviolet;
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
        </style>
    </head>
    <body>
        <header>
            <!--class="titulo"-->
            <div id="cajaTitulo">Proyecto Login-Logout</div>
            <br>
            <a href="codigoPHP/LogIn.php"><input type="submit" value="Iniciar sesión" name="iniciar sesion" class="iniciarsesion"/></input></a>
            <br>
            <a href="../212DWESProyectoTema5/index.php"><input type="submit" value="Salir" name="Salir" class="iniciarsesion"/></input></a>
        </header>
        <footer>
            2021-22 I.E.S. Los sauces. ©Todos los derechos reservados. <strong> <a href="http://daw212.sauces.local/">Johanna Herrero Pozuelo</a></strong>
            <a target="_blank" href="https://github.com/JohannaHPSauces/LoginLogoutTema5/tree/developer"><img src="../images/git.png" alt="" class="git"></a>
        </footer>
</body>
</html>