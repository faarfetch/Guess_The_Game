<?php

echo ("<style>
    .header {
        position: sticky;
        top: 0;
        padding: 2px 10px;
        background: #4682b4;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header div {
        flex-grow: 1;
        text-align: center;
    }
    .header a {
        display: flex;
        align-items: center;
    }
    .header img {
        width: 50px;
        height: 50px;
    }
</style>");

echo ('<div class="header" id="myHeader">
    <a href="home.php">
        <img src="../files/imgs/homeLogo.png" alt="home">
    </a>
    <div style="font-size: 2em; text-align: center; width: 100%; padding: 10px 0px">Guess The Game</div>
    <a href="../gestori/gestoreLogout.php">
        <img src="../files/imgs/logoutLogo.png" alt="logout">
    </a>
</div>');