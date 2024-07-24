<?php
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include("conexion.php");
    $errores= array();
    $success= false;

    $nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : null;
    $apellidos = (isset($_POST['apellidos'])) ? $_POST['apellidos'] : null;
    $edad = (isset($_POST['edad'])) ? $_POST['edad'] : null;
    $categoria = (isset($_POST['categoria'])) ? $_POST['categoria'] : null;
    $subcategoria = (isset($_POST['subcategoria'])) ? $_POST['subcategoria'] : null;
    $genero = (isset($_POST['genero'])) ? $_POST['genero'] : null;
    $correo = (isset($_POST['correo'])) ? $_POST['correo'] : null;
    $password=(isset($_POST['password']))?($_POST['password']):null;
    $confirmarpassword=(isset($_POST['confirmarpassword']))?($_POST['confirmarpassword']):null;



    if(empty($nombre)){
        $errores['nombre']="Debe ingresar su nombre";
    }
    if(empty($apellidos)){
        $errores['apellidos']="Debe ingresar sus apellidos";
    }
    if(empty($edad)){
        $errores['edad']="Debe ingresar su edad";
    }
    if(empty($categoria)){
        $errores['categoria']="Debe seleccionar su Departamento";
    }
    if(empty($subcategoria)){
        $errores['subcategoria']="Debe seleccionar su Area";
    }
    if(empty($genero)){
        $errores['genero']="Debe seleccionar él genero";
    }

    if(empty($correo)){
        $errores['correo']="El coreo es obligatorio";
        
    }elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
        $errores['correo']="Formato de correo no valido";
    }

    if(empty($password)){
        $errores['password']="La contraseña es obligatoria";
        
    }
    if(empty($confirmarpassword)){
        $errores['confirmarpassword']="Confrima la contraseña";
    }elseif($password!= $confirmarpassword){
        $errores['confirmarpassword']="Las contraseñas no coinciden";

    }

    foreach($errores as $error){

        echo "<br/>".$error."<br/>";
    }

    if(empty($errores)){
        
        try{
            $pdo=new PDO('mysql:host='.$direccionservidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // para que el pdo maneje los errores de manera automatica
            
            $nuevoPassword=password_hash($password, PASSWORD_DEFAULT);

            $sql="INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `edad`, `genero`, `categoria`, `subcategoria`, `email`, `contraseña`) 
            VALUES (NULL, :nombre, :apellidos, :edad, :genero, :categoria, :subcategoria, :email, :password);";
            $resultado=$pdo->prepare($sql);
            $resultado->execute(array(
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':edad' => $edad,
                ':genero' => $genero,
                ':categoria' => $categoria,
                ':subcategoria' => $subcategoria,
                ':email' => $correo,
                ':password' => $nuevoPassword
            ));

            //header("Location:login.html");
            $success=true;


        }catch(PDOException $e){

            echo "Hubo un error de conexión" .$e-getMessage();

        }

    }else{
        echo "No se registraron los datos, póngase en contacto con el administrador";
    }
}



?>

<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <div class="container mt-5">
            <div class="row justify-content-center ">
                <div class="col-md-7 col-lg-7">
<?php
                    if(isset($success)){

?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        <strong>¡Regstro Exitoso!</strong> Ya puedes iniciar sesión. <a href="login.html"
                            class="btn btn-success">Login</a>
                    </div>
                <?php
                }
                ?>
                    <div class="card">
                        <div class="card-header" style="font-weight: bold;">Formulario de Registro</div>
                        <div class="card-body">

                            <form action="registro.php" id="formularioderegistro" method="post">

                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Nombre: </label>
                                            <input type="text" class="form-control" name="nombre" id="nombre"
                                                aria-describedby="helpId" placeholder="" required />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Apellidos:
                                            </label>
                                            <input type="text" class="form-control" name="apellidos" id="apellidos"
                                                aria-describedby="helpId" placeholder="" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Edad: </label>
                                            <input type="number" class="form-control" name="edad" id="edad"
                                                aria-describedby="helpId" placeholder="" required />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="genero" class="form-label">Género:</label>
                                            <select class="form-select" name="genero" id="genero" required>
                                                <option value>Seleccione su género</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="categoria" class="form-label">Departamento: </label>
                                            <select class="form-select" name="categoria" id="categoria"
                                                onchange="mostrarSubcategorias()" required>
                                                <option value="">Seleccione su departamento</option>
                                                <option value="Administrativo">Administrativo</option>
                                                <option value="Operativo">Operativo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="subcategoria" class="form-label">Área: </label>
                                            <select class="form-select" name="subcategoria" id="subcategoria" required>
                                                <option value="">Seleccione su área</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="correo" class="form-label">Correo: </label>
                                    <input type="email" class="form-control" name="correo" id="correo"
                                        aria-describedby="emailHelpId" placeholder="correo@ejemplo.com" required />
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Contraseña:
                                            </label>
                                            <input type="password" class="form-control" name="password" id="password"
                                                placeholder="" required />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">

                                            <label for="" class="form-label">Repetir
                                                contraseña: </label>
                                            <input type="password" class="form-control" name="confirmarpassword"
                                                id="confirmarpassword" placeholder="" required />
                                            <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success">Registrarse</button>
                                <a href="login.html" class="btn btn-secondary">Login</a>

                            </form>
                        </div>
                        <div class="card-footer text-muted"></div>
                    </div>
                </div>
            </div>
        </div>


    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
    <script>
        // JavaScript para manejar el cambio en las categorías
        function mostrarSubcategorias() {
            const categoria = document.getElementById("categoria").value;
            const subcategoriasAdmin = ["Compras", "Almacen", "Recursos Humanos", "Control"];
            const subcategoriasOperativo = ["Super intendente", "Topografia", "Maquinaria", "Seguridad", "Salud"];
            const subcategoriaSelect = document.getElementById("subcategoria");

            // Limpiar las subcategorías actuales
            subcategoriaSelect.innerHTML = "";

            let subcategorias = [];
            if (categoria === "Administrativo") {
                subcategorias = subcategoriasAdmin;
            } else if (categoria === "Operativo") {
                subcategorias = subcategoriasOperativo;
            }

            // Añadir las nuevas opciones de subcategorías
            subcategorias.forEach(subcat => {
                const option = document.createElement("option");
                option.value = subcat;
                option.text = subcat;
                subcategoriaSelect.appendChild(option);
            });
        }
    </script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            document.getElementById("formularioderegistro").addEventListener('submit', function (event) {


                var password = document.getElementById("password").value;
                var confirmarpassword = document.getElementById("confirmarpassword").value;

                if (password !== confirmarpassword) {
                    document.getElementById('confirmarpassword').classList.add('is-invalid');
                    event.preventDefault();
                } else {
                    document.getElementById('confirmarpassword').classList.remove('is-invalid');
                }
            });
        });
    </script>
</body>

</html>