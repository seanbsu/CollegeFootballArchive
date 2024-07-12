<?php
$pageTitle = "Teams";
include_once('nav.php');
?>

<body>
<div class="body-container">
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li><a id="breadcrumb-team">Teams</a></li>
    </ul>

    <div class="outer-wrapper">
        <div class="inner-wrapper-teams">
            <div class="main-teams">
                <h1>TEAMS</h1>
            </div>
            <div class="searchbar">
                <input type="text" id="team-search" placeholder="Search for a team...">
            </div>
            <table class="teams-table">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                include_once 'teams-functions.php';
                ?>
            </table>
        </div>
    </div>
</div>
</body>

<?php include_once('footer.php'); ?>
