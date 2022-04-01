<?php

    require "ui-components.php";
    require "database/config.php";
    require "utils.php";

    $clients = get_clients($pdo);

    require "parts/head.html";
?>

<main role="main">
    <div class="container">
        <?php print_header();?>
        <div class="row">
            <div class="col-md-8">
                <form action="index.php" method="get">
                    <input type="submit" name="showAll" value = "Show all clients" />
                    <input type="submit" name="filter" value = "filter clients" />
                </form>
                <?php  print_clients_list($clients);?>
            </div>
        </div>
        <?php print_footer();?>
    </div>
</main>

<?php require "parts/tail.html";?>