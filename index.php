<!DOCTYPE html>
<?php

function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

if (!isset($_COOKIE['myList'])) {
    // first startup items and cookie
    $items = array(
        array(
            "name" => "matas",
            "age" => "23",
            "occupation" => "Programeris"
        )
    );

    setcookie("myList", json_encode($items), time() + 86400, "/");
} else {
    // if startup already happened, get list into variable
    $items = json_decode($_COOKIE['myList'], true);
}

//filter
if (!isset($_COOKIE['ageLimit'])){
    $ageLimit = 30;
    setcookie('ageLimit',$ageLimit,time()+86400,'/');
} else if(isset($_POST['limitButton'])){
    $ageLimit = $_POST['limit'];

    setcookie('ageLimit',$ageLimit,time()+86400,'/');
        
     header("Location: index.php");
} else {
    $ageLimit = $_COOKIE['ageLimit'];
}
?>
<?php
    //inserting what you wrote
    if (isset($_POST["insert"])) {

        $name = $_POST["name"];
        $age = $_POST["age"];
        $occupation = $_POST["occupation"];

        $items[] = array(
            "name" => $name = $_POST["name"],
            "age" => $age = $_POST["age"],
            "occupation" => $occupation = $_POST["occupation"]
        );

        setcookie('myList', json_encode($items), time() + 86400, "/");

        //reload page
        header("Location: index.php");
    }
    //inserting 10 random ones
    if (isset($_POST["insert10"])) {
        for ($i = 0; $i < 10; $i++) {
            
            $randomString = '';

            for ($b = 0; $b < 10; $b++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }
            $items[] = array(

                "name" => $randomString,
                "age" => rand(30,100),
                "occupation" => $randomString
            );
            
        }
        setcookie('myList', json_encode($items), time() + 86400, "/");
        header("Location: index.php");
    }
    //deleting button
    for ($i = 0; $i < count($items); $i++) {
        if (isset($_POST['remove' . $i])) {
            // console_log("istrinti mygtukas");
            unset($items[$i]);
            $items = array_values($items);
            setcookie('myList', json_encode($items), time() + (86400), "/");
            // console_log('this' . $i);

            header("Location: index.php");
        }
    }
    ?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uzduotis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            width: 95%;
            margin: auto;
        }
    </style>

</head>

<body>


    <!-- table printing -->
    <div class="div1 mb-3">
        <form method="POST" action="index.php">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vardas</th>
                        <th scope="col">Amzius</th>
                        <th scope="col">Profesija</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // rodo tiek kiek sako filtras
                    for ($i = 0; $i < count($items); $i++) {
                        if ($items[$i]["age"] < $ageLimit) {
                            continue;
                        }
                        echo "<tr>";
                        echo "<th scope='row'>" . $i . "</th>";
                        echo "<td>" . $items[$i]["name"] . "</td>";
                        echo "<td>" . $items[$i]["age"] . "</td>";
                        echo "<td>" . $items[$i]["occupation"] . "</td>";
                        echo "<td><button class='btn btn-danger' type='submit' name='remove$i'>Ištrinti</button>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>

    <hr />
    

    <!-- irasymo sekcija -->
    <div class="form col-lg-4">
        <form method="POST" action="index.php">
            <div class="mb-3">
                <label class="form-label">Vardas</label>
                <input class="form-control" name="name" />
            </div>
            <div class="mb-3">
                <label class="form-label">Amžius</label>
                <input type="number" class="form-control" name="age" />
            </div>
            <div class="mb-3">
                <label class="form-label">Profesija</label>
                <input class="form-control" name="occupation" />
            </div>
            <button class="btn btn-primary" type="submit" name="insert">Įrašyti</button>
            <button class="btn btn-primary" type="submit" name="insert10">Įrašyti 10 random</button>
        </form>
    </div>

    <hr />


    <!-- filtravimo sekcija -->
    <div class="form col-lg-4">
        <form method="POST" action="index.php">
            <div class="mb-3">
                <label class="form-label">Rodyti vyresnius nei:</label>
                <input class="form-control" type="number" value="<?php echo $ageLimit ?>" name="limit" />
            </div>
            <button class="btn btn-primary" type="submit" name="limitButton">Filtruoti</button>
        </form>
    </div>

    
</body>

</html>