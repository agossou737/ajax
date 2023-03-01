<?php
// echo "<pre>";

// print_r($_REQUEST); print_r($_FILES); die();

$action = $_REQUEST["action"];

if (!empty($action)) {

    // var_dump($action);
    // die();
    require_once "includes/Player.php";
    $obj = new Player();
}

if ($action == "adduser" && !empty($_POST["nom"]) && !empty($_POST["email"]) && !empty($_POST["tel"])) {

    $pname = $_POST["nom"];
    $email = $_POST["email"];
    $tel = $_POST["tel"];
    $photo = $_FILES["img"];
    $playerId = (!empty($_POST["userid"])) ? $_POST["userid"] : '';

    //validations
    $imagename = "";

    if (!empty($photo["name"])) {
        $imagename = $obj->uploadPhoto($photo);
        $playerData = [
            "pname" => $pname,
            "pemail" => $email,
            "phone" => $tel,
            "photo" => $imagename
        ];
    } else {
        $playerData = [
            "pname" => $pname,
            "pemail" => $email,
            "phone" => $tel,
            "photo" => $imagename
        ];
    }

    if ($playerId) {
        $obj->update($playerData, $playerId);
    } else {
        $playerId = $obj->add($playerData);
    }


    if (!empty($playerId)) {
        $player = $obj->getRow("id", $playerId);
    }

    echo json_encode([
        "status" => 200,
        "message" => $player
    ]);
    exit();
} else if ($action == "edituser" && !empty($_POST["nom"]) && !empty($_POST["email"]) && !empty($_POST["tel"])) {

    $pname = $_POST["nom"];
    $email = $_POST["email"];
    $tel = $_POST["tel"];
    $photo = $_FILES["img"];
    $playerId = (!empty($_POST["userid"])) ? $_POST["userid"] : '';

    //validations
    $imagename = "";

    if (!empty($photo["name"])) {
        $imagename = $obj->uploadPhoto($photo);
        $playerData = [
            "pname" => $pname,
            "pemail" => $email,
            "phone" => $tel,
            "photo" => $imagename
        ];
    } else {
        $playerData = [
            "pname" => $pname,
            "pemail" => $email,
            "phone" => $tel,
            "photo" => $imagename
        ];
    }

    $obj->update($playerData, $playerId);

    echo json_encode([
        "status" => 200,
        "message" => "Player mis a jour avec success"
    ]);
    exit();
} else if ($action == "getusers") {
    $page = (!empty($_GET["page"])) ? $_GET["page"] : 1;

    $limit = 4;
    $start = ($page - 1) * $limit;

    $players = $obj->getRows($start, $limit);
    if (!empty($players)) {
        $playersList = $players;
    } else {
        $playersList = [];
    }

    $total = $obj->pCount();
    $pcountArr = ["count" => $total, "players" => $playersList];
    echo json_encode($pcountArr);
    exit();
} elseif ($action == "edituser") {
    $playerId = (!empty($_GET["page"])) ? $_GET["page"] : '';

    if (!empty($playerId)) {
        $player = $obj->getRow("id", $playerId);
        echo json_encode($player);
        exit();
    }
} elseif ($action == "delUser") {
    $playerId = (!empty($_GET["page"])) ? $_GET["page"] : '';

    if (!empty($playerId)) {
        $isDeleted = $obj->deleteRow($playerId);

        if ($isDeleted) {
            $message = [
                "supprimer" => 1
            ];
        } else {
            $message = [
                "supprimer" => 0
            ];
        }
        

        echo json_encode($message);
        exit();
    }
} else {
    echo json_encode([
        "status" => 400,
        "message" => "Erreur vous n'avez pas rempli tous les champs"
    ]);
}
