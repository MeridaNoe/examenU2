<?php
header('Content-Type: application/json');
$metodo = $_SERVER['REQUEST_METHOD'];
switch ($metodo) {
    case 'GET':
        //Consulta
        if ($_GET['accion'] == 'personaje') {
            try {
                $conexion = new PDO("mysql:host=localhost;dbname=kof;charset=utf8", "root", "");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            if (isset($_GET['id'])) { 
                $pstm = $conexion->prepare('SELECT * from personaje WHERE = id =:n');
                $pstm->bindParam(':n', $_GET['id']);
                $pstm->execute();
                $rs = $pstm->fetchAll(PDO::FETCH_ASSOC);
                if ($rs != null) {
                    echo json_encode($rs[0], JSON_PRETTY_PRINT);
                } else {
                    echo "No se encontraron coincidencias";
                }
            } else {
                $pstm = $conexion->prepare('SELECT p.*,ma.magia,tL.tipo_Lucha 
                FROM personaje p INNER JOIN magia ma INNER JOIN tipo_lucha tL ON p.magia_id = ma.id, and p.tipo_lucha_id = tL.id;');
                $pstm->execute();
                $rs = $pstm->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($rs, JSON_PRETTY_PRINT);
            }
        }
        if ($_GET["accion"] == "magia") {
            try {
                $conexion = new PDO("mysql:host=localhost;dbname=kof;charset=utf8", "root", "");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            $pstm = $conexion->prepare('SELECT * FROM magia;');
            $pstm->execute();
            $rs = $pstm->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rs, JSON_PRETTY_PRINT);
        }
        exit();
        if ($_GET["accion"] == "tipo_lucha") {
            try {
                $conexion = new PDO("mysql:host=localhost;dbname=kof;charset=utf8", "root", "");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            $pstm = $conexion->prepare('SELECT * FROM tipo_lucha;');
            $pstm->execute();
            $rs = $pstm->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rs, JSON_PRETTY_PRINT);
        }
        exit();
        break;
    case 'POST':
        if ($_GET['accion'] == 'personaje') {
            $jsonData = json_decode(file_get_contents("php://input"));
            try {
                $conn = new PDO("mysql:host=localhost;dbname=kof;charset=utf8", "root", "");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            $query = $conn->prepare("INSERT INTO personaje (name, lastname, birthday, utiliza_magia, estatura,peso,equipo,magia_id,tipo_lucha_id) VALUES (:name,:lastname,:birthday,:utiliza_magia,:estatura,:peso,:equipo,:magia_id,:tipo_lucha_id)");
            $query->bindParam(":name", $jsonData->name);
            $query->bindParam(":lastname", $jsonData->lastname);
            $query->bindParam(":birthday", $jsonData->birthday);
            $query->bindParam(":utiliza_magia", $jsonData->utiliza_magia);
            $query->bindParam(":estatura", $jsonData->estatura);
            $query->bindParam(":peso", $jsonData->peso);
            $query->bindParam(":equipo", $jsonData->equipo);
            $query->bindParam(":magia_id", $jsonData->magia_id);
            $query->bindParam(":tipo_lucha_id", $jsonData->tipo_lucha_id);
            $result = $query->execute();
            if ($result) {
                $_POST["error"] = false;
                $_POST["message"] = "Registrado correctamente";
                $_POST["status"] = 200;
            } else {
                $_POST["error"] = true;
                $_POST["message"] = "Error al registrar";
                $_POST["status"] = 400;
            }
            echo json_encode(($_POST));
        }
        break;
    case 'PUT':
        if ($_GET['accion'] == 'personaje') {
            $jsonData = json_decode(file_get_contents("php://input"));
            try {
                $conn = new PDO("mysql:host=localhost;dbname=kof;charset=utf8", "root", "");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            $query = $conn->prepare("UPDATE `personaje` SET `name` = :name, `lastname` = :lastname, 
            `birthday` = :birthday, `utiliza_magia` = :utiliza_magia, `estatura` = :estatura, `peso` = :peso,`equipo` = :equipo, `magia_id` = :magia_id, `tipo_lucha_id` =:tipo_lucha_id WHERE `id` = :id;");
            $query->bindParam(":name", $jsonData->name);
            $query->bindParam(":lastname", $jsonData->lastname);
            $query->bindParam(":birthday", $jsonData->birthday);
            $query->bindParam(":utiliza_magia", $jsonData->utiliza_magia);
            $query->bindParam(":estatura", $jsonData->estatura);
            $query->bindParam(":peso", $jsonData->peso);
            $query->bindParam(":equipo", $jsonData->equipo);
            $query->bindParam(":magia_id", $jsonData->magia_id);
            $query->bindParam(":tipo_lucha_id", $jsonData->tipo_lucha_id);
            $query->bindParam(":id", $jsonData->id);
            $result = $query->execute();
            if ($result) {
                $_POST["error"] = false;
                $_POST["message"] = "actualizado correctamente";
                $_POST["status"] = 200;
            } else {
                $_POST["error"] = true;
                $_POST["message"] = "Error al acttualizar";
                $_POST["status"] = 400;
            }
            echo json_encode(($_POST));
        }
        break;
    default:
        echo "Método no soportado.";
        break;
}