<?php
session_start();
if($_SERVER['REQUEST_METHOD']=="POST"){
    include("conexion.php");
    $errores= array();
    //print_r($_POST);
    
    $correo=(isset($_POST['email']))?htmlspecialchars($_POST['email']):null;
    $password=(isset($_POST['password']))?$_POST['password']:null;

    if(empty($correo)){
        $errores['correo']="El coreo es obligatorio";
        
    }elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
        $errores['correo']="Formato de correo no valido";
    }
    if(empty($password)){
        $errores['password']="La contraseña es obligatoria";
   }

   if(empty($errores)){

    try{

        $pdo=new PDO('mysql:host='.$direccionservidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // para que el pdo maneje los errores de manera automatica


        $sql="SELECT * FROM usuarios WHERE email=:email";
        $sentencia= $pdo->prepare($sql);
        $sentencia->execute(['email'=>$correo]);

        $usuarios= $sentencia->fetchAll(PDO::FETCH_ASSOC);
        //print_r($usuarios);

        $login=false;
       
        foreach($usuarios as $user){
            
            if (password_verify($password, $user["contraseña"])) {
                $_SESSION['usuario_id']=$user['id'];
                $_SESSION['usuario_nombre']=$user['nombre']." ".$user['apellidos'];
                $_SESSION['usuario_categoria']=$user['categoria']."/".$user['subcategoria'];
                
                $login=true;
            }


        }
        
        if($login){
            echo "Existe el usuario en la DB";
            header("Location:index.php");
        }else{
            echo "No existe el usuario en la DB";
        }

    }catch(PDOException $e){

    }

   }else{

    foreach($errores as $error){

        echo "<br/>".$error."<br/>";
    }
    echo "<br><a href='login.html'>Regresar al login</a><br>";

   }






}


?>