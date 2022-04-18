<?php
         /*
             * @author: Johanna Herrero Pozuelo
             * Created on: 20/01/2021
             * Aplicacion LogIn-LogOut Tema5
             */
        if(isset($_REQUEST['Cancelar'])){
            header('Location: LogIn.php');
            exit;
        }
        require_once '../core/210322ValidacionFormularios.php';
        require_once '../config/confDBPDO.php';
        
        
        $entradaOk=true;
        $aErrores= ['usuario' =>null,
                    'descripcion' =>null,
                    'password' =>null,
                    'confirmarPassword' =>null ];
        
        if(isset($_REQUEST['Aceptar'])){
            $usuarioIntroducido= $_REQUEST['usuario']; //Guardo el usuario introducido
            $desUsuarioIntroducido= $_REQUEST['descripcion']; //Guardo el la descripcion del usuario introducido
            $passwordIntroducida= $_REQUEST['password']; //Guardo la contraseña introducida
            $confpasswordIntroducida= $_REQUEST['confirmarPassword']; //Guardo la contraseña introducida
            
            $aErrores['usuario']= validacionFormularios::comprobarAlfaNumerico($_REQUEST['usuario'], 12, 4, 1);//Hacemos la validacion del usuario
            $aErrores['descripcion']= validacionFormularios::comprobarAlfaNumerico($_REQUEST['descripcion'], 200, 4, 1);//Hacemos la validacion de la descripcion
            $aErrores['password']= validacionFormularios::validarPassword($_REQUEST['password'], 12, 4, 2, 1); //Hacemos la validacion de la contraseña
            $aErrores['confirmarPassword']= validacionFormularios::validarPassword($_REQUEST['confirmarPassword'], 12, 4, 2, 1); //Hacemos la validacion de la confirmacion de la contraseña
            
            try{
                $miDB= new PDO(HOST, USER, PASSWORD); //Objeto para establecer la conexion
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion
                
                $passwordIntroducidoEncriptado=hash("sha256", $usuarioIntroducido.$passwordIntroducida);
                
                $consulta ="SELECT * from T01_Usuario WHERE T01_CodUsuario='$usuarioIntroducido'"; //consulta
                $resultadoConsulta=$miDB->prepare($consulta); //Preparar la consulta
                $resultadoConsulta->execute();//Ejecutal la consulta
                
                $oUsuario= $resultadoConsulta->fetchObject();//Guardo el resultado de la consulta en un objeto
                
                if($resultadoConsulta->rowCount()>0){ //Si el resultado de la consulta devuelve algo es que existe el usuario
                   $aErrores['usuario']= "Error en el usuario";
                }  
                if($passwordIntroducida!=$confpasswordIntroducida){ //Comprobar que las contraseñas sean iguales
                    $aErrores['confirmarPassword']= "Las contraseñas no coinciden";//Si no coinciden las contraseñas mostramos error
                }
            }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
                $codigoError = $excepcion->getCode(); //Guardamos en una variable el codigo del error
                $mensajeError = $excepcion->getMessage(); //guardamos en una variable el mensaje del error 

                echo "<p style='background-color:red'> Codigo de error: ".$codigoError;     //Mostramos el error
                echo "<p style='background-color: red;'> Mensaje de error:". $mensajeError; //Mostramos el mensaje de error

            }finally{//Para finalizar cerramos la conexion a la base de datos
                     unset($miDB);
            }
                foreach($aErrores as $campo =>$error){//Recorro el array de errores buscando si hay
                    if($error ==null){// Si hay algun error 
                        $entradaOk=false;
                        $_REQUEST['campo']="";//Vacia los campos
                    }
                }
        }else{
            $entradaOk= false; //Si el usuario no le ha dado ha enviar los datos muestra el formulario hasta que ponga algo
        }
        
        if($entradaOk){
            try{
                $miDB= new PDO(HOST, USER, PASSWORD); //establezco la conexion a la base de datos
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion
                
                $consulta="INSERT INTO T01_Usuario (T01_CodUsuario, T01_DescUsuario, T01_Password) VALUES ('$usuarioIntroducido', '$desUsuarioIntroducido', '$passwordIntroducidoEncriptado')";
                $resultadoConsulta=$miDB->prepare($consulta); //preparo la consulta
                $resultadoConsulta->execute();// ejecuto la consulta
                
                $consulta="UPDATE T01_Usuario SET T01_NumConexiones=T01_NumConexiones+1, T01_FechaUltimaConexion= unix_timestamp() WHERE T01_CodUsuario='$usuarioIntroducido'"; //Hago la consulta
                $resultadoConsulta=$miDB->prepare($consulta);//preparar la consulta
                $resultadoConsulta->execute(); //ejecutar la consulta
                    
                $fechaUltimaConexion=$oConsulta->T01_FechaUltimaConexion; //guardo en una variable lo que contiene la columna de fechaUltimaConexion
                $oConsulta= $resultadoConsulta->fetchObject(); //Guardo el resultado de la consulta en un objeto

            }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
                $codigoError = $excepcion->getCode(); //Guardamos en una variable el codigo del error
                $mensajeError = $excepcion->getMessage(); //guardamos en una variable el mensaje del error 

                echo "<p style='background-color:red'> Codigo de error: ".$codigoError;     //Mostramos el error
                echo "<p style='background-color: red;'> Mensaje de error:". $mensajeError; //Mostramos el mensaje de error
            }finally{//Para finalizar cerramos la conexion a la base de datos
                unset($miDB);
            }
                session_start(); //se inicia la sesion
                $_SESSION['User212DWESProyectoTema5']=$usuarioIntroducido; //aqui se guarda el usuario que ha iniciado la sesion
                    if(!is_null($fechaUltimaConexion)){//Si la fecha de la ultima conexion es distinto de null
                        $oFecha =new DateTime();
                        $oFecha->setTimestamp($fechaUltimaConexion);
                        $_SESSION['fechaHoraUltimaConexionAnterior']= $oFecha->format('l jS \of F Y h:i:s A');//aqui se guarda la fecha y la hora de la ultima vez que te conectaste
                    }else{
                       $_SESSION['fechaHoraUltimaConexionAnterior']=null;
                    }
                header('Location: programa.php'); //nos redirige al programa
                exit;
        }else{ 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            body{
                background: black;
            }
            fieldset{
                width: 200px;height: 340px;
                text-align: center;
                color: white;
                font-weight: bold;
                font-size: 20px;
                border: 4px solid orange;
                margin: 3rem auto ;
               /* margin-left: 20rem ;*/
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
            input:nth-of-type(5), input:nth-of-type(6){
                height: 30px; width: 80px;
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
            <fieldset>
                <label for="usuario">Usuario: </label>
                <input type="text" id="usuario" name="usuario" value="<?php echo(isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : null); ?>"> <?php echo($aErrores['usuario']!=null ? "<span style='color:red'>".$aErrores['usuario']."</span>" : null); ?>
                <br>
                <label for="descripcion">Descripcion usuario: </label>
                <input type="text" id="descripcion" name="descripcion" value="<?php echo(isset($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : null); ?>"> <?php echo($aErrores['descripcion']!=null ? "<span style='color:red'>".$aErrores['descripcion']."</span>" : null); ?>
                <br>
                <label for="password">Contraseña: </label>
                <input type="password" id="password" name="password" value="<?php echo(isset($_REQUEST['password']) ? $_REQUEST['password'] : null); ?>"> <?php echo($aErrores['password']!=null ? "<span style='color:red'>".$aErrores['password']."</span>" : null); ?>
                <br>
                <label for="confirmarPassword">Repetir Contraseña: </label>
                <input type="password" id="confirmarPassword" name="confirmarPassword" value="<?php echo(isset($_REQUEST['confirmarPassword']) ? $_REQUEST['confirmarPassword'] : null); ?>"> <?php echo($aErrores['confirmarPassword']!=null ? "<span style='color:red'>".$aErrores['confirmarPassword']."</span>" : null); ?>
                <br>
                <input type="submit" value="Aceptar" name="Aceptar">
                <input type="submit" value="Cancelar" name="Cancelar">
            </fieldset>
        </form>
        <footer>2021-22 I.E.S. Los sauces. ©Todos los derechos reservados. <strong>Johanna Herrero Pozuelo </strong>
            <a  href="https://github.com/JohannaHPSauces/LoginLogoutTema5/tree/developer"><img src="../../proyectoDWES/webroot/images/git.png" class="git"></a>
	</footer>
        
    </body>
</html>
<?php
    }
?>