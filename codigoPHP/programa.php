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
        
        if(isset($_REQUEST['Cerrar'])){ //Si el usuario da al boton de cerrar te lleva al logIn
            session_destroy();//Cuando le damos a cerrar sesion, destruye la sesion
            header('Location: LogIn.php');
            exit;
        }
        if(isset($_REQUEST['Detalle'])){//Si el usuario da al boton de detalle te lleva al detalle
            header('Location: detalle.php');
            exit;
        }
        if(isset($_REQUEST['Editar'])){//Si el usuario da al boton de detalle te lleva al detalle
            header('Location: editarPerfil.php');
            exit;
        }
        require_once '../core/210322ValidacionFormularios.php';
        require_once '../config/confDBPDO.php';
        
        try{
            
            $miDB= new PDO(HOST, USER, PASSWORD);
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion
            
            $usuarioSesion=$_SESSION['User212DWESProyectoTema5']; //guardo el usuario de la sesion para hacer el select
            
            $consulta="SELECT T01_NumConexiones,T01_FechaHoraUltimaConexion, T01_DescUsuario, T01_ImagenUsuario FROM T01_Usuario WHERE T01_CodUsuario='$usuarioSesion'";
            $resultadoConsulta=$miDB->prepare($consulta);
            $resultadoConsulta->execute();
            
            $oConsulta= $resultadoConsulta->fetchObject();//Guardo el resultado de la consulta en un objeto
            
            //guardo los datos obtenidos en variables para que me sea mas facil luego mostrarlos
            $nConexiones=$oConsulta->T01_NumConexiones;
            $desUsuario=$oConsulta->T01_DescUsuario;
            $imagenUsuario=$oConsulta->T01_ImagenUsuario;
            $fechaUltimaConexion= $_SESSION['fechaHoraUltimaConexionAnterior'];
            
                    
        }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
            $codigoError = $excepcion->getCode(); //Guardamos en una variable el codigo del error
            $mensajeError = $excepcion->getMessage(); //guardamos en una variable el mensaje del error 

            echo "<p style='background-color:red'> Codigo de error: ".$codigoError;     //Mostramos el error
            echo "<p style='background-color: red;'> Mensaje de error:". $mensajeError; //Mostramos el mensaje de error
            }finally{//Para finalizar cerramos la conexion a la base de datos
                unset($miDB);
            }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            div:nth-of-type(1){
                width: 100%;height: 50px;
                background: grey;
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
            a{
                text-decoration: none;
            }
            div:nth-of-type(2){
                width: 100%;height: 50px;
                margin: 2rem auto;
                font-size: 25px;
                font-weight: bold;
                color:black;
            }
            body{
                background: white;
                
            }
            fieldset{
                text-align: center;
                width: 450px;height: 20px;
                color: black;
                font-weight: bold;
                border:none;
                border-radius: 10%;
                margin: 3rem auto;
            }
            input{
                width: 100px;height:40px;
            }
            input:nth-of-type(4){
                width:135px; height: 40px;
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
            
         </style>
    </head>
    <body>
        <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div id="cajaTitulo">Proyecto Login-Logout</div>
            <div id="infoUsuario">Bienvenid@ <?php echo $desUsuario ?> es la <?php echo $nConexiones ?>ª vez que te conectas <?php 
                if(!is_null($fechaUltimaConexion)){?>
                y la ultima conexion fue <?php echo $fechaUltimaConexion; } ?></div>
            <fieldset>
                <input type="submit" value="Detalle" name="Detalle">
                <input type="submit" value="Editar perfil" name="Editar">
                <input type="submit" value="Cerrar Sesión" name="Cerrar">
                <!--<input type="submit" value="Mto Departamentos" name="Mantenimiento">-->
            </fieldset>
        </form>
        <footer>2021-22 I.E.S. Los sauces. ©Todos los derechos reservados.<strong> <a href="http://daw212.sauces.local/">Johanna Herrero Pozuelo</a></strong> 
            <a  target="_blank" href="https://github.com/JohannaHPSauces/LoginLogoutTema5/tree/developer"><img src="../../images/git.png" class="git"></a>
	</footer>
    </body>
</html>
