<?php

echo ("<style>
    .header {
        position: sticky;
        top: 0;
        padding: 2px 10px;
        background: #555;
        color: white;
    }
</style>");

echo ('<div class="header" id="myHeader">

    <a href="home.php" style="position: absolute; left: 10px; top: 10px;">
        <img src="../files/imgs/homeLogo.png" alt="logout" style="width: 50px; height: 50px;">
    </a>
    <div style="font-size: 2em; text-align: center; width: 100%; padding: 10px 0px">Guess The Game</div>
    <a href="../gestori/gestoreLogout.php" style="position: absolute; right: 10px; top: 10px;">
        <img src="../files/imgs/logoutLogo.png" alt="logout" style="width: 50px; height: 50px;">
    </a>
</div>');