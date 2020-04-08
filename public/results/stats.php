<?php
ini_set('display_errors', 1);
error_reporting(1);
require '../../config/settings.php';
require '../../src/id_obfuscation.php';

session_start();

if ($_GET['op'] === 'logout') {
    $_SESSION['logged'] = false;
    // redirect?
    exit;
}

if ($_GET['op'] === 'login' && $_POST['password'] === STATS_PASSWORD) {
    $_SESSION['logged'] = true;
}


if ($_SESSION['logged']) {
    require '../../src/database.php';

    // fetch a single test
    if ($_GET['op'] === 'id' && !empty($_GET['id'])) {
        $id = $_GET['id'];
        if (OBFUSCATE_IDS) {
            $id = deobfuscateId($id);
        }
        $result = $db->prepare('SELECT id,timestamp,ip,ispinfo,ua,lang,dl,ul,ping,jitter,log,extra from speedtest_users where id=?');
        $result->execute(array($id));
    } else {
        $result = $db->query('SELECT id,timestamp,ip,ispinfo,ua,lang,dl,ul,ping,jitter,log,extra from speedtest_users order by timestamp desc limit 100');
    }
}

//error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0, s-maxage=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>LibreSpeed - Stats</title>
    <style type="text/css">
        html, body {
            margin: 0;
            padding: 0;
            border: none;
            width: 100%;
            min-height: 100%;
        }

        html {
            background-color: hsl(198, 72%, 35%);
            font-family: "Segoe UI", "Roboto", sans-serif;
        }

        body {
            background-color: #FFFFFF;
            box-sizing: border-box;
            width: 100%;
            max-width: 70em;
            margin: 4em auto;
            box-shadow: 0 1em 6em #00000080;
            padding: 1em 1em 4em 1em;
            border-radius: 0.4em;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 300;
            margin-bottom: 0.1em;
        }

        h1 {
            text-align: center;
        }

        table {
            margin: 2em 0;
            width: 100%;
        }

        table, tr, th, td {
            border: 1px solid #AAAAAA;
        }

        th {
            width: 6em;
        }

        td {
            word-break: break-all;
        }
    </style>
</head>
<body>
<h1>LibreSpeed - Stats</h1>
<?php if (STATS_PASSWORD === 'PASSWORD'): ?>
    Please set STATS_PASSWORD in config/settings.php to enable access.
<?php endif; ?>

<?php if ($_SESSION['logged']): ?>
    <form action="stats.php" method="GET"><input type="hidden" name="op" value="logout"/><input type="submit"
                                                                                                value="Logout"/>
    </form>
    <form action="stats.php" method="GET">
        <h3>Search test results</h3>
        <input type="hidden" name="op" value="id"/>
        <input type="text" name="id" id="id" placeholder="Test ID" value=""/>
        <input type="submit" value="Find"/>
        <input type="submit" onclick="document.getElementById('id').value=''" value="Show last 100 tests"/>
    </form>
    <?php

    while ($row = $result->fetch()): ?>
        <table>
            <tr>
                <th>Test ID</th>
                <td><?= htmlspecialchars((OBFUSCATE_IDS ? (obfuscateId($row['id']) . ' (deobfuscated: ' . $row['id'] . ')') : $row['id']), ENT_HTML5, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>Date and time</th>
                <td><?= htmlspecialchars($row['timestamp'], ENT_HTML5, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>IP and ISP Info</th>
                <td><?= $ip ?><br/><?= htmlspecialchars($row['ispinfo'], ENT_HTML5, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>User agent and locale</th>
                <td><?= $ua ?><br/><?= htmlspecialchars($row['lang'], ENT_HTML5, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>Download speed</th>
                <td><?= htmlspecialchars($row['dl'], ENT_HTML5, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>Upload speed</th>
                <td><?= htmlspecialchars($row['ul'], ENT_HTML5, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>Ping</th>
                <td><?= htmlspecialchars($row['ping'], ENT_HTML5, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>Jitter</th>
                <td><?= htmlspecialchars($row['jitter'], ENT_HTML5, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>Log</th>
                <td><?= htmlspecialchars($row['log'], ENT_HTML5, 'UTF-8') ?></td>
            </tr>
            <tr>
                <th>Extra info</th>
                <td><?= htmlspecialchars($row['extra'], ENT_HTML5, 'UTF-8') ?></td>
            </tr>
        </table>
    <?php
    endwhile;
endif;
?>


<?php if (!$_SESSION['logged'] && STATS_PASSWORD !== 'PASSWORD'): ?>
    <form action="stats.php?op=login" method="POST">
        <h3>Login</h3>
        <input type="password" name="password" placeholder="Password" value=""/>
        <input type="submit" value="Login"/>
    </form>
<?php endif ?>
</body>
</html>
