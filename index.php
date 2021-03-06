<?php
include('Poll.php');

$poll = new Poll();
$pollData = $poll->getPoll();
if (isset($_POST['vote'])) {
    $pollVoteData = array(
        'pollid' => $_POST['pollid'],
        'pollOptions' => $_POST['options']
    );
    $isVoted = $poll->updateVote($pollVoteData);
    if ($isVoted) {
        setcookie($_POST['pollid'], 1, time() + 1 * 30);
        $voteMessage = 'Your have voted successfully.';
    } else {
        $voteMessage = 'Your had already voted.';
    }
}

?>

<?php
require "header.php";
?>

<head>
    <link rel="stylesheet" href="css/main-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="emojilib/css/emoji.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Jost&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/0639910d01.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.plot.ly/plotly-2.11.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
</head>

<body>
    <div id="body">
        <div id="stocks">
            <div class="stockDiv">
                <a href="./stock.php?company=GOOGL" class="link" target="_blank">
                    <p>GOOGL - Google|USD</p>
                    <div class="currency">
                        <h3 class="cena">---</h3>
                    </div>
                    <div class="dcchange">
                    </div>
                </a>
            </div>
            <div class="stockDiv">
                <a href="./stock.php?company=NFLX" class="link" target="_blank">
                    <p>NFLX - Netflix|USD</p>
                    <div class="currency">
                        <h3 class="cena">---</h3>
                    </div>
                    <div class="dcchange">
                    </div>
                </a>
            </div>
            <div class="stockDiv">
                <a href="./stock.php?company=AMZN" class="link" target="_blank">
                    <p>AMZN - Amazon|USD</p>
                    <div class="currency">
                        <h3 class="cena">---</h3>
                    </div>
                    <div class="dcchange">
                    </div>
                </a>
            </div>
            <div class="stockDiv">
                <a href="./stock.php?company=FB" class="link" target="_blank">
                    <p>FB - Meta|USD</p>
                    <div class="currency">
                        <h3 class="cena">---</h3>
                    </div>
                    <div class="dcchange">
                    </div>
                </a>
            </div>
            <div class="stockDiv">
                <a href="./stock.php?company=AAPL" class="link" target="_blank">
                    <p>AAPL - Apple|USD</p>
                    <div class="currency">
                        <h3 class="cena">---</h3>
                    </div>
                    <div class="dcchange">
                    </div>
                </a>
            </div>
            <div class="stockDiv">
                <a href="./stock.php?company=TSLA" class="link" target="_blank">
                    <p>TSLA - Tesla|USD</p>
                    <div class="currency">
                        <h3 class="cena"></i>---</h3>
                    </div>
                    <div class="dcchange">
                    </div>
                </a>
            </div>

        </div>

        <div class="ankety">
            <div class="contain">

                <div class="poll-container">
                    <?php echo !empty($voteMessage) ? '<div class="alert alert-danger"><strong>Warning!</strong> ' . $voteMessage . '</div>' : ''; ?>
                    <form action="" method="post" name="pollFrm">
                        <?php
                        foreach ($pollData as $poll) {

                            echo "<h3>" . $poll['question'] . "</h3>";
                            $pollOptions = explode("||||", $poll['options']);
                            echo "<ul>";
                            for ($i = 0; $i < count($pollOptions); $i++) {
                                echo '<li><input type="radio" name="options" value="' . $i . '" > ' . $pollOptions[$i] . '</li>';
                            }
                            echo "</ul>";
                            echo '<input type="hidden" name="pollid" value="' . $poll['pollid'] . '">';
                            echo '<br><input type="submit" name="vote" class="btn btn-primary" value="Vote">';
                            echo '<a href="results.php"> View Results</a>';
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>


        <div id="content">
            <?php

            if (isset($_SESSION['userId'])) {

                echo '<div id="pridaniPrispevkuTlacitko">
            <h3 id="pridejsvujnapad">What is on your mind?</h3><a href="addpost.php"><button class="btn btn-success" id="tlacitkopridani">Add Post</button></a>
            </div>';
            } else {
                echo '<div id="pridaniPrispevku">
            <h3 id="info-warning">You must first log in or <a href="signup.php">register</a> to add a post.</h3>
            </div>
            ';
            }

            include "./php/models/API.php";
            ?>

        </div>

        <div id="posts">
            <?php
            $mysqli = new mysqli('localhost', 'root', '', 'ezstonks');
            $sql = "SELECT idPosts, idUsers, uidUsers, topic, postText, date FROM posty ORDER BY date DESC;";
            if ($stmt = $mysqli->prepare($sql)) {
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($id, $user, $username, $topic, $text, $date);
                        while ($row = $stmt->fetch()) {
                            echo '<div class="prispevek">';
                            echo '<div class="content">';
                            if (isset($_SESSION['userId'])) {
                                if ($user == $_SESSION['userId']) {
                                    echo '
                        <div id="formystlacitkama">
                        <form method="post" action="changepost.php" id="formsbuttonama1">
                        <button type="submit" name="zmenitPost" value=' . $id . ' class="btn btn-warning btn-sm" id="meniciTlacitko">Change</button>
                        </form>
                        
                        <form method="post" id="formsbuttonama2">
                        <button type="submit" name="odstranitPost" value=' . $id . ' class="btn btn-danger btn-sm" id="odstranovaciTlacitko">Delete Post</button>
                        </form>
                        </div>  
                        ';
                                }
                            }
                            echo '<h3 class="nadpisPrispevku">' . $topic . '</h3>
                        <p class="textuzivatele">' . $text . '</p>          
                        <h3 class="datum">Added on: ' . $date . '</h3>
                        <h3 class="autor">Author: ' . $username . '</h3>
                        </div>
                        </div>';
                        }
                    }
                }
            } else {
                echo $mysqli->error;
            }

            if (isset($_POST['odstranitPost'])) {
                $sql = "DELETE FROM posty WHERE idPosts =" . $_POST['odstranitPost'] . " ";
                $res = $mysqli->query($sql);
                $page = $_SERVER['PHP_SELF'];
                echo '<meta http-equiv="Refresh" content="0;' . $page . '">';
            }
            ?>
        </div>
    </div>


    <script src="js/API.js"></script>
</body>

<?php
require "footer.php";
?>