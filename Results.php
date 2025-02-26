<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- this is all of the required setups -->
</head>

<body>
    <div class="row">
        <header class="col-lg-12 bg-info">
            <class="col-lg-2">
            <h1 class="col-lg-10 text-center">Results</h1>
        </header>
    </div>
<!--   this is the header information   -->
    <div class="row">
        <nav class="col-lg-2">
            <h2 class="text">Navigation bar</h2>
            <ul class="nav nav-pills nav-stacked">
                <li><a href="index.html">Search</a></li>
                <li><a href="MSearched.php">10 Most Searched</a></li> 
            </ul>
        </nav>
<!--   this is the navigation bar setup   -->
        <?php
        $genre = $_POST["genre"];
        $name = $_POST["name"];
        $rating = $_POST["rating"];
        $year = $_POST["year"];
        ?>
<!-- these are the variables fed in by the search bars -->
        <main class="col-lg-10">
        <?php
        $host = 'localhost';
        $username = "DBlink";
        $password = "P@ss";
        $dbname = "movies";     
        $porta = "3306";
        $charset = 'utf8mb4';
// these are the enviroment variables fot the database connection 
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset;port=$porta";
        try {
            $pdo = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
//         this is the connection to the database being made.
        $command = $pdo->prepare("SELECT * FROM `movie` WHERE `Genre` LIKE ? AND `Rating` LIKE ? AND `Year` LIKE ? AND `Title` LIKE  ?");
        $command -> execute(['%'.$genre.'%','%'.$rating.'%','%'.$year.'%','%'.$name.'%']);
        $results = $command->fetchAll();
//         this is the search being executed using the search variables in a sql command
        if ($command->rowCount()<=0) {
            ?> <H1>Apologies. No Results found</H1><?php
        }
//         this if statement checks if the search returned no resulsts and prints a message if so
        else{
            $command2 = $pdo->prepare("UPDATE `movie` SET `Searched` = `Searched` + 1 WHERE `Genre` LIKE ? AND `Rating` LIKE ? AND `Year` LIKE ? AND `Title` LIKE  ?");
            $command2 -> execute(['%'.$genre.'%','%'.$rating.'%','%'.$year.'%','%'.$name.'%']);
            ?>
<!--     assuming results were found this is where the command to update the "searched" data for all the retrived data is executed     -->
            <table cellspacing="10" cellpadding="10">
            <tr>
            <th>ID</th>
            <th>|Name</th>
            <th>|Studio</th>
            <th>|Status</th>
            <th>|Sound</th>
            <th>|Versions</th>
            <th>|RRP</th>
            <th>|Rating</th>
            <th>|Year</th>
            <th>|Genre</th>
            <th>|Aspect</th>
            </tr>
<!--      this is the setup of the table headings        -->
            <?php foreach ($results as $result) { ?>
                <tr>
                    <td><?php echo $result['ID'] ?></td>
                    <td><?php echo "|" . $result['Title'] ?></td>
                    <td><?php echo "|" . $result['Studio'] ?></td>
                    <td><?php echo "|" . $result['Status'] ?></td>
                    <td><?php echo "|" . $result['Sound'] ?></td>
                    <td><?php echo "|" . $result['Versions'] ?></td>
                    <td><?php echo "|" . $result['RecRetPrice'] ?></td>
                    <td><?php echo "|" . $result['Rating'] ?></td>
                    <td><?php echo "|" . $result['Year'] ?></td>
                    <td><?php echo "|" . $result['Genre'] ?></td>
                    <td><?php echo "|" . $result['Aspect'] ?></td>
                </tr>
<!--     this is where the results are each run over and formated in such a way that they neatly populate the table with the appropriate value         -->
            <?php } 
        }?>
        </main>
    </div>
</body>

</html>