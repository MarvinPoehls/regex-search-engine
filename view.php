<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Regex Search Engine</title>
    <style>
        .engine-dropdown {
            border-radius: 10px 0 0 10px;
            border-right: 0;
        }
        .search-button {
            border-radius: 0 10px 10px 0;
        }

        .searchbar {
            border-radius: 0;
        }

        .suggestions {
            border: solid 1px #dee2e6;
            border-radius: 0.75rem;
        }

        .underline {
            text-decoration: underline;
        }

        .headline:hover {
            background-color: lightgrey;
        }

        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
    <body class="bg-light">
        <div class="row">
            <div class="col"></div>
            <div class="col-6 px-5">
                <div class="row">
                    <div class="col"></div>
                    <div class="col-auto mt-5">
                        <h1 class="fw-bolder">Regex Search-Engine</h1>
                    </div>
                    <div class="col"></div>
                </div>
                <form action="index.php" method="get">
                    <div class="row mt-5">
                        <div class="col-auto p-0">
                            <select class="form-control engine-dropdown" name="engine" title="Search Engine">
                                <option value="www.google.com" <?= $engine == 'www.google.com' ? 'selected' : '' ?>>Google</option>
                                <option value="www.bing.com" <?= $engine == 'www.bing.com' ? 'selected' : '' ?>>Bing</option>
                            </select>
                        </div>
                        <div class="col p-0">
                            <input class="form-control searchbar" type="search" name="query" title="searchbar" placeholder="Websuche" value="<?= !empty($query) ? $query : '' ?>">
                        </div>
                        <div class="col-auto p-0">
                            <button type="submit" class="btn btn-primary search-button"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                    <div class="suggestions mt-3 shadow overflow-hidden">
                        <?php if (!empty($matches[1])) { ?>
                            <?php for ($i = 0; $i < $SUGGESTION_LIMIT; $i++) { ?>
                                <?php if (isset($matches[1][$i]) && isset($matches[2][$i])) { ?>
                                    <a href="<?= urldecode($matches[1][$i]) ?>">
                                        <div class="py-2 px-3 headline">
                                            <div class="row">
                                                <div class="col-auto d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-search"></i>
                                                </div>
                                                <div class="col">
                                                    <p class="m-0 underline"><?= $config['utf8_encode'] ? utf8_encode($matches[2][$i]) : $matches[2][$i] ?></p>
                                                    <p class="text-secondary"><?= $config['utf8_encode'] ? utf8_encode($matches[3][$i]) : $matches[3][$i] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        <?php } else {?>
                            <p class="text-secondary m-0 py-2 px-3">Es wurden keine Ergebnisse für deine Suche gefunden.</p>
                        <?php } ?>
                    </div>
                </form>
            </div>
            <div class="col"></div>
        </div>
    </body>
</html>