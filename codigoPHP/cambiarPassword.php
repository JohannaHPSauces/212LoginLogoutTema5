<?php
        /*
             * @author: Johanna Herrero Pozuelo
             * Created on: 21/04/2022
             * Aplicacion LogIn-LogOut Tema5
             */

        session_start();
        if (!isset($_SESSION['User212DWESProyectoTema5'])) {
            header('Location: LogIn.php');
            exit;
        }
        if(isset($_REQUEST['Cancelar'])){
            header('Location: editarPerfil.php');
            exit;
        }
        require_once '../core/210322ValidacionFormularios.php';
        require_once '../config/confDBPDO.php';
        
        $entradaOk= true;
                
        $aErrores = ['viejaPassword' =>null,
                     'nuevaPassword'=> null,
                     'repetirPassword' =>null];
        

        if(isset($_REQUEST['Aceptar'])){
            
            $aErrores['viejaPassword']= validacionFormularios::validarPassword($_REQUEST['viejaPassword'], 8, 4, 2, 1);
            $aErrores['nuevaPassword']= validacionFormularios::validarPassword($_REQUEST['nuevaPassword'], 8, 4, 2, 1);
            $aErrores['repetirPassword']= validacionFormularios::validarPassword($_REQUEST['repetirPassword'], 8, 4, 2, 1);
            
            //Guardo las respuestas del formulario en variables
            $viejaPassword=$_REQUEST['viejaPassword'];
            $nuevaPassword=$_REQUEST['nuevaPassword'];
            $repetirPassword=$_REQUEST['repetirPassword'];
            
            try{
                $miDB= new PDO(HOST, USER, PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion

                $usuarioSesion= $_SESSION['User212DWESProyectoTema5'];//Guardo en una variable el usuario de la sesion

                $consulta="SELECT T01_Password FROM T01_Usuario WHERE T01_CodUsuario='$usuarioSesion'";//Hago la consulta
                $resultadoConsulta=$miDB->prepare($consulta);//Preparo la consulta
                $resultadoConsulta->execute();//Ejecuta la consulta
                
                $oConsulta=$resultadoConsulta->fetchObject();
                
                $passwordUsuario=$oConsulta->T01_Password;
                $passwordEncriptado=hash("sha256", $usuarioSesion.$_REQUEST['viejaPassword']);//Guardo en una variable la contraseña cifrada
                
                //var_dump($passwordUsuario);
                //var_dump($passwordEncriptado);
                
                if($passwordEncriptado!=$passwordUsuario){ //Comprobar que las contraseñas sean iguales
                    $aErrores['viejaPassword']= "Error";//Si no coinciden las contraseñas mostramos error
                }
                if($nuevaPassword!=$repetirPassword){ //Comprobar que las contraseñas sean iguales
                    $aErrores['repetirPassword']= "Las contraseñas no coinciden";//Si no coinciden las contraseñas mostramos error
                }
               
             }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
               $codigoError= $exception->getCode();//Guardo en una variable el codigo del error
               $mensajeError =$excepcion->getMessage();//Guardo en una variable el mensaje de error

               echo "<p style='background-color:red'> Codigo de error: ".$codigoError;//Muestro el codigo del error
               echo "<p style=background-color:red'> Mensaje de error: ".$mensajeError;//Muestro el mensaje de error
            }finally{
                unset($miDB);//Cierro la conexion
            }
             foreach($aErrores as $campo =>$error){//Recorro el array de errores buscando si hay algun error
                    if($error !=null){// Si hay algun error 
                        $entradaOk=false;//la entrada es false
                        $_REQUEST[$campo]="";//Vacia los campos
                    }
                }
        }else{
            $entradaOk=false;
        }
        
        if($entradaOk){
            try{
                $miDB= new PDO(HOST, USER, PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion

                $nuevaPasswordCifrada=hash("sha256", $usuarioSesion.$nuevaPassword);
                $consulta="UPDATE T01_Usuario set T01_Password='$nuevaPasswordCifrada'WHERE T01_CodUsuario='$usuarioSesion'";//Hago la consulta
                $resultadoConsulta=$miDB->prepare($consulta);//Preparo la consulta
                $resultadoConsulta->execute();//Ejecuto la consulta
                
                header('Location: editarPerfil.php');// mando al usuario a editar perfil
                exit;
                
            }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
                $codigoError = $excepcion->getCode(); //Guardamos en una variable el codigo del error
                $mensajeError = $excepcion->getMessage(); //guardamos en una variable el mensaje del error 

                echo "<p style='background-color:red'> Codigo de error: ".$codigoError;     //Mostramos el error
                echo "<p style='background-color: red;'> Mensaje de error:". $mensajeError; //Mostramos el mensaje de error
            }finally{//Para finalizar cerramos la conexion a la base de datos
                unset($miDB);//Cierro la conexion
            }
        }else{
       
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
            fieldset{
                width: 200px;height: 280px;
                text-align: center;
                color: black;
                font-weight: bold;
                font-size: 20px;
                border: 4px solid black;
                margin: 2rem auto ;
               /* margin-left: 20rem ;*/
            }
            .atras{
                width: 6%;height: 50px;
                background: grey;
                text-align: center;
                font-size: 40px;
                font-weight: bold;
            }
            a{
                text-decoration: none;
            }
            body{
                background: white;
                
            }
            input{
                font-size: 15px;
                border-radius:5px;
            }
            .botones input:nth-of-type(2), input:nth-child(1){
                 width: 47%;height: 15%;
                 padding: 5px;
            }
            #usuario{
                font-size: 20px;
            }
            #nConexiones{
                font-size: 20px;
            }
            #fechaHoraUltimaConexion{
                font-size: 16px;
            }
            #tUsuario{
                font-size: 20px;
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
            .botones{
               /* border:1px solid red;*/
                width: 20%;height: 15%;
                margin: 3rem auto;
            }
        </style>
    </head>
    <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div id="cajaTitulo">Cambiar Password</div>
        <fieldset>
            <label for="viejaPassword">Contraseña: </label>
            <br>
            <input type="password" id="viejaPassword" name="viejaPassword"  value="<?php echo(isset($_REQUEST['viejaPassword']) ? $_REQUEST['viejaPassword'] : null); ?>"> <?php echo($aErrores['viejaPassword']!=null ? "<span style='color:red'>".$aErrores['viejaPassword']."</span>" : null); ?> 
            <br>
            <br>
            <label for="nuevaPassword">Nueva Contraseña: </label>
            <br>
            <input type="password" id="nuevaPassword" name="nuevaPassword" value="<?php echo(isset($_REQUEST['nuevaPassword']) ? $_REQUEST['nuevaPassword'] : null); ?>"> <?php echo($aErrores['nuevaPassword']!=null ? "<span style='color:red'>".$aErrores['nuevaPassword']."</span>" : null); ?> 
            <br>
            <br>
            <label for="repetirPassword">Repetir contraseña:</label>
            <br>
            <input type="password" id="repetirPassword" name="repetirPassword"  value="<?php echo(isset($_REQUEST['repetirPassword']) ? $_REQUEST['repetirPassword'] : null); ?>"><br> <?php echo($aErrores['repetirPassword']!=null ? "<span style='color:red'>".$aErrores['repetirPassword']."</span>" : null); ?> 
        </fieldset>
        <div class="botones">
            <input type="submit" value="Aceptar Cambios" name="Aceptar"/>
            <input type="submit" value="Cancelar" name="Cancelar"/>
        </div>
    </form>
    <footer>2021-22 I.E.S. Los sauces. ©Todos los derechos reservados.<strong> <a href="http://daw212.sauces.local/">Johanna Herrero Pozuelo</a></strong> 
            <a  target="_blank" href="https://github.com/JohannaHPSauces/212LoginLogoutTema5"><img src="../../images/git.png" class="git"></a>
    </footer>
</body>
</html>
<?php
       }
?>
        
       
        
       
        