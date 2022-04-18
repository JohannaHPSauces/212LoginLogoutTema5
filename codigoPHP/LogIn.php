<?php
         /*
             * @author: Johanna Herrero Pozuelo
             * Created on: 20/01/2021
             * Aplicacion LogIn-LogOut Tema5
             */
        if(isset($_REQUEST['Registrarse'])){
            header('Location: registro.php');
            exit;
        }
        require_once '../core/210322ValidacionFormularios.php';
        require_once '../config/confDBPDO.php';
        
        $entradaOk= true;
                
        $aErrores = ['usuario' =>null,
                     'password'=> null];
        
        if(isset($_REQUEST['Entrar'])){//Si el usuario ha pulsado el boton de entrar
            $usuarioIntroducido= $_REQUEST['usuario']; //Guardo el usuario introducido
            $passwordIntroducida= $_REQUEST['password']; //Guardo la contraseña introducida
            
            //$aErrores['usuario']= validacionFormularios::comprobarAlfaNumerico($_REQUEST['usuario'], 12, 4, 1);//Hacemos la validacion del usuario
            //$aErrores['password']= validacionFormularios::validarPassword($_REQUEST['password'], 12, 4, 2, 1); //Hacemos la validacion de la contraseña
            
            if(validacionFormularios::comprobarAlfaNumerico($_REQUEST['usuario'], 12, 4, 1)  || validacionFormularios::validarPassword($_REQUEST['password'], 12, 4, 2, 1)){
                $entradaOk= false;
            }else{
                $usuarioIntroducido= $_REQUEST['usuario']; //Guardo el usuario introducido
                $passwordIntroducida= $_REQUEST['password']; //Guardo la contraseña introducida
                
                try{
                    $miDB= new PDO(HOST, USER, PASSWORD); //Objeto para establecer la conexion
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion

                    $consulta ="SELECT T01_FechaUltimaConexion FROM T01_Usuario WHERE T01_CodUsuario='$usuarioIntroducido' AND T01_Password=SHA2('{$_REQUEST['usuario']}{$_REQUEST['password']}', 256)"; //Consulta
                    $resultadoConsulta=$miDB->prepare($consulta); //Preparar la consulta
                    $resultadoConsulta->execute();//Ejecuta la consulta
                    
                    $oConsulta= $resultadoConsulta->fetchObject();//Guardo el resultado de la consulta en un objeto
                    
                }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
                    $codigoError = $excepcion->getCode(); //Guardamos en una variable el codigo del error
                    $mensajeError = $excepcion->getMessage(); //guardamos en una variable el mensaje del error 

                    echo "<p style='background-color:red'> Codigo de error: ".$codigoError;     //Mostramos el error
                    echo "<p style='background-color: red;'> Mensaje de error:". $mensajeError; //Mostramos el mensaje de error

                }finally{//Para finalizar cerramos la conexion a la base de datos
                    unset($miDB);
                }
                
                if(!$oConsulta){
                    $entradaOk= false; //Si el objeto no devuelve ningun resultado entrada false
                }
                
            }
            foreach ($aErrores as $campo => $error){                                //Recorre el array en busca de errores, con que haya uno entra
                    if ($error == null){                
                        $entradaOK = false;                                             //Y nos cambia la variable entrada a false
                        $_REQUEST[$campo]="";                                            //Limpiamos los campos del formulario

                    }
                }
        }else{
            $entradaOk= false;//Si el usuario no le ha dado ha enviar los datos de sesion se sigue mostrando el formulario
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
            if($entradaOk){ //Si el usuarioy la contraseña estan bien, hacemos una consulta para actualizar el numero de conexiones que ha hecho sumandole 1
                try{
                    $miDB= new PDO(HOST, USER, PASSWORD);
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $consulta="UPDATE T01_Usuario SET T01_NumConexiones=T01_NumConexiones+1, T01_FechaUltimaConexion= unix_timestamp() WHERE T01_CodUsuario='$usuarioIntroducido'";
                    $resultadoConsulta=$miDB->prepare($consulta);
                    $resultadoConsulta->execute();
                    
                    $fechaUltimaConexion=$oConsulta->T01_FechaUltimaConexion;
                    $oConsulta= $resultadoConsulta->fetchObject();//Guardo el resultado de la consulta en un objeto

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
                        $_SESSION['fechaHoraUltimaConexionAnterior']= $oFecha->format('Y/m/d H:i:s');//aqui se guarda la fecha y la hora de la ultima vez que te conectaste
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
            div{
                width: 100%;height: 50px;
                background: grey;
                font-size: 40px;
                font-weight: bold;
                color:white;
                text-align: center;
            }
            body{
                background: white;
                /*background-image: url(../images/gato3.jpg);
                background-size: cover;
                background-attachment: fixed;
                background-repeat: no-repeat;*/
            }
            fieldset{
                width: 200px;height: 240px;
                text-align: center;
                color: black;
                padding: 30px;
                font-weight: bold;
                font-size: 20px;
                border: 4px solid black;
                margin: 8rem auto ;
               /* margin-left: 20rem ;*/
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
            input{
                font-size: 15px;
                border-radius:5px;
            }
            input:nth-of-type(3), input:nth-of-type(4){
                width: 47%;height: 17%;
            }
            input:nth-of-type(3):hover, input:nth-of-type(4):hover{
                background: beige;
            }
            span{
                color:red;
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
                color: white;
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
             <div class="atras"><a href="../indexInicio.php"><=</a></div>
            <fieldset>
                <label for="usuario" >Usuario<span> *</span>: </label>
                <input type="text"  id="usuario" name="usuario" value="<?php echo(isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : null); ?>"> <?php echo($aErrores['usuario']!=null ? "<span style='color:red'>".$aErrores['usuario']."</span>" : null); ?>
                <br>
                <br>
                <label for="password">Contraseña<span> * </span>: </label>
                <input type="password" id="password" name="password" value="<?php echo(isset($_REQUEST['password']) ? $_REQUEST['password'] : null); ?>"> <?php echo($aErrores['password']!=null ? "<span style='color:red'>".$aErrores['password']."</span>" : null); ?>
                <br>
                <br><br>
                <input type="submit" value="Entrar" name="Entrar">
                <input type="submit" value="Registrarse" name="Registrarse">
            </fieldset>
        </form>
        <footer>2021-22 I.E.S. Los sauces. ©Todos los derechos reservados. <strong> <a href="http://daw212.sauces.local/">Johanna Herrero Pozuelo</a></strong>
            <a  target="_blank" href="https://github.com/JohannaHPSauces/LoginLogoutTema5/tree/developer"><img src="../../images/git.png" class="git"></a>
	</footer>
    </body>
</html>
<?php
        }
 ?>