<?php
        /*
             * @author: Johanna Herrero Pozuelo
             * Created on: 20/01/2021
             * Aplicacion LogIn-LogOut Tema5
             */

        session_start();//Se recupera la sesion
        if (!isset($_SESSION['User212DWESProyectoTema5'])) {
            header('Location: LogIn.php');
            exit;
        }
        if(isset($_REQUEST['Cancelar'])){ //Si el usuario pulsa cancelar
            header('Location: programa.php');// le manda a la pagina del programa
            exit;
        }
        if(isset($_REQUEST['Cambiar'])){ //Si el usuario pulsa el boton de cancelar
            header('Location: cambiarPassword.php');// le manda a la pagina de cambiar la contraseña
            exit;
        }
        require_once '../core/210322ValidacionFormularios.php';
        require_once '../config/confDBPDO.php';

        if(isset($_REQUEST['Eliminar'])){//Si el usuario pulsa el boton de eleiminar
            try{
                $miDB= new PDO(HOST, USER, PASSWORD);//Creo un objeto PDO
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion

                $usuarioSesion= $_SESSION['User212DWESProyectoTema5'];//Guardo en una variable el usuario de la sesion

                $consulta="DELETE FROM T01_Usuario WHERE T01_CodUsuario='$usuarioSesion'";//Hago la consulta
                $resultadoConsulta=$miDB->prepare($consulta);//Preparo la consulta
                $resultadoConsulta->execute();//Ejecuta la consulta
               
             }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
               $codigoError= $exception->getCode();//Guardo en una variable el codigo del error
               $mensajeError =$excepcion->getMessage();//Guardo en una variable el mensaje de error

               echo "<p style='background-color:red'> Codigo de error: ".$codigoError;
               echo "<p style=background-color:red'> Mensaje de error: ".$mensajeError;
            }finally{
                unset($miDB);//Cierro la conexion
            }
            session_destroy();//Destruyo la sesion
            header('Location: LogIn.php');//Mnado al usuario a la pagina principal
            exit;
        }
         try{
            $miDB= new PDO(HOST, USER, PASSWORD); //Creao un objeto PDO
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion

            $usuarioSesion= $_SESSION['User212DWESProyectoTema5'];//Guardo el usuario de la sesion en una variable

            $consulta="SELECT * FROM T01_Usuario WHERE T01_CodUsuario='$usuarioSesion'";//Hago la consulta
            $resultadoConsulta=$miDB->prepare($consulta);//Preparo la consulta
            $resultadoConsulta->execute();//Ejecuto la consulta

            $oConsulta=$resultadoConsulta->fetchObject();// Si la consulta devuelve algo, lo guardo en un objeto

            $fUsuario=$oConsulta->T01_CodUsuario; //Guardo en una variable el resultado de la fila codigo Usuario
            $fConexiones=$oConsulta->T01_NumConexiones; //Guardo en una variable el resultado de la fila num de Conexiones
            $fDescripcion=$oConsulta->T01_DescUsuario;
            //$fFechaHoraUltimaConexion=$_SESSION['fechaHoraUltimaConexionAnterior'];
            $date=$oConsulta->T01_FechaHoraUltimaConexion;
            $fFechaHoraUltimaConexion = date('d-m-Y H:i:s', $date);
            $fTipoUsuario=$oConsulta->T01_Perfil; //Guardo en una variable el resultado de la fila perfil
            
        }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
            $codigoError = $excepcion->getCode(); //Guardamos en una variable el codigo del error
            $mensajeError = $excepcion->getMessage(); //guardamos en una variable el mensaje del error 

            echo "<p style='background-color:red'> Codigo de error: ".$codigoError;     //Mostramos el  codigo de error
            echo "<p style='background-color: red;'> Mensaje de error:". $mensajeError; //Mostramos el mensaje de error
        }finally{//Para finalizar cerramos la conexion a la base de datos
            unset($miDB);
        }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $entradaOk=true;//Defino la entrada como true

        $aErrores= ['dUsuario'=>null];//inicializo el array de errrores a null
        
        if(isset($_REQUEST['Aceptar'])){ //Si el usuario pulsa el boton de aceptar
            $desUsuario=$_REQUEST['dUsuario']; //guardo en una variable en contenido del formulario
            $aErrores['dUsuario']= validacionFormularios::comprobarAlfaNumerico($_REQUEST['dUsuario'], 255, 4, 1); //Hago la validacion 
            
                foreach($aErrores as $campo =>$error){//Recorro el array de errores buscando si hay
                    if($error !=null){// Si hay algun error 
                        $entradaOk=false; //Ponemos la entrada a false
                        $_REQUEST[$campo]="";//Vacia los campos
                    }
                }
        }else{
            $entradaOk=false;
        }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if($entradaOk){
            try{
                $miDB= new PDO(HOST, USER, PASSWORD); //creo un objeto PDO
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Establezco los atributos
                
                $consulta="UPDATE T01_Usuario SET T01_DescUsuario='$desUsuario' WHERE T01_CodUsuario='$usuarioSesion'";//Consulta
                $resultadoConsulta=$miDB->prepare($consulta);//Preparar la consulta
                $resultadoConsulta->execute();//Ejecutar la consulta
                
                header('Location: programa.php');//una vez hecho el update, manda al usuario al programa
                exit;
            }catch (PDOException $exception){
                $codigoError= $exception->getCode();//Guardo en una variable el codigo del error
                $mensajeError= $exception->getMessage();//Guardo en una variable el mensaje de error
                
                echo "<p style='background-color:red'> Codigo del error: ".$codigoError;//Muestra del codigo de error
                echo "<p style='background-color:red'> Mensaje de error: ".$mensajeError; //Muestra del mensaje de error
            }finally{
                unset($miDB);//fin de la conexion
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
                width: 280px;height: 500px;
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
                background: blueviolet;
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
                width: 150px;height:30px;
            }
            fielset input:nth-of-type(1),input:nth-of-type(3), input:nth-of-type(4), input:nth-of-type(5){
                background: lightgrey;
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
                /*border:1px solid red;*/
                width: 460px;
                margin: 5rem auto;
            }
        </style>
    </head>
    <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div id="cajaTitulo">Editar Perfil</div>
        <fieldset>
            <label for="usuario">Nombre usuario: </label>
            <br>
            <input type="text" id="usuario" name="usuario" value="<?php echo $fUsuario ?>" disabled>
            <br>
            <br>
            <label for="dUsuario">Descripcion usuario: </label>
            <br>
            <input type="text" id="dUsuario" name="dUsuario" value="<?php echo $fDescripcion ?><?php echo(isset($_REQUEST['dUsuario']) ? $_REQUEST['dUsuario'] : null); ?>"><br> <?php echo($aErrores['dUsuario']!=null ? "<span style='color:red'>".$aErrores['dUsuario']."</span>" : null); ?> 
            <br>
            <br>
            <label for="nConexiones">Numero de conexiones:</label>
            <br>
            <input type="text" id="nConexiones" name="nConexiones"  value="<?php echo $fConexiones ?>" disabled>
            <br>
            <br>
            <label for="fechaHoraUltimaConexion">Fecha y hora última conexión: </label>
            <br>
            <input type="text" id="fechaHoraUltimaConexion" name="fechaHoraUltimaConexion"   value="<?php echo $fFechaHoraUltimaConexion ?>" disabled>
            <br>
            <br>
            <label for="tUsuario">Tipo de usuario: </label>
            <br>
            <input type="text" id="tUsuario" name="tUsuario" value="<?php echo $fTipoUsuario ?>" disabled>
            <br>
            <br>
             <label for="contraseña">Contraseña: </label>
            <br>
            <input type="submit" value="Cambiar Contraseña" name="Cambiar"/>
        </fieldset>
        <div class="botones">
            <input type="submit" value="Aceptar Cambios" name="Aceptar"/>
            <input type="submit" value="Cancelar" name="Cancelar"/>
            <input type="submit" value="Eliminar Cuenta" name="Eliminar"/>
        </div>
    </form>
    <footer>2021-22 I.E.S. Los sauces. ©Todos los derechos reservados.<strong> <a href="http://daw212.sauces.local/">Johanna Herrero Pozuelo</a></strong> 
            <a  target="_blank" href="https://github.com/JohannaHPSauces/LoginLogoutTema5/tree/developer"><img src="../../images/git.png" class="git"></a>
    </footer>
</body>
</html>
<?php
       }
?>