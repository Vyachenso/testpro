<!DOCTYPE html>
<html lang="en-EN">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <title>Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="js/custom.js" crossorigin="anonymous"></script>

</head>
<body>
<div class="bg">
    <div class="container">
        <div class="row">
            <form name="form" id="form" method="GET">
                <?php
                if (isset($county)) {
                    print "<h3>county</h3><br><select id='county' name='county' class='form-control form-select form-select-lg mb-3 w-50'>
                    <option value='0'>please choose</option>";
                    foreach ($county as $key => $item) {
                        print "<option value='" . $item . "'>" . $item . "</option>";
                    }
                    print "</select>";
                }
                ?>
                <div class="country"></div>
                <div class="town"></div>

                <?php
                if (isset($bedrooms)) {
                    print "<h3>bedrooms</h3><br>
                    <select id='num_bedrooms' name='num_bedrooms' class='form-control form-select form-select-lg mb-3 w-50'>
                    <option value='0'>please choose</option>";
                    foreach ($bedrooms as $key => $item) {
                        print "<option value='" . $item . "'>" . $item . "</option>";
                    }
                    print "</select>";
                }
                ?>

                <h3>type</h3><br>
                <select id='type' name='type' class='form-control form-select form-select-lg mb-3 w-50'>
                    <option value='0'>please choose</option>
                    <option value='rent'>rent</option>
                    <option value='sale'>rent</option>
                </select>

                <br>
                <h3>price</h3> <h6>min - <?= $price['min'] ?>, max - <?= $price['max'] ?></h6><br>
                <input type="number" name='price' max="<?= $price['max'] ?>" min="<?= $price['min'] ?>" id="price"
                       class='form-control form-control-lg mb-3 w-50'
                       value="<?= $price['medium'] ?>">

                <input type="submit" name="form_submit" class="btn btn-primary">
                <div class="mb-4"></div>
            </form>

            <?php
            if (isset($propertys)) {
                ?>

                <br>
                <!--Listing-grid-view-->
                <section class="listing-page">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-md-push-4">
                                <div class="row">

                                    <?php foreach ($propertys as $key => $value) {
                                        if ($key === "count") continue;
                                        ?>
                                        <div class="col-md-4 grid_listing">
                                            <div class="product-listing-m gray-bg border border-success rounded m-1 p-1">
                                                <div class="product-listing-img">
                                                    <a href="index.php?id=<?= $value['id']; ?>">
                                                        <img src="<?= htmlentities($value['image_thumbnail']); ?>"
                                                             class="img-fluid rounded">
                                                    </a>
                                                    <div class="label_icon">
                                                        <?= htmlentities(mb_substr($value['description'], 0, 100, "utf-8")); ?>
                                                    </div>

                                                </div>
                                                <div class="product-listing-content">
                                                    <h5>
                                                        <a href="index.php?id=<?= $value['id']; ?>">
                                                            <?= htmlentities($value['address']); ?>
                                                        </a>
                                                    </h5>
                                                    <p class="font-weight-bold"><?= $value['price']; ?>$</p>

                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    } ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
                <?
            }
            ?>


            <?php
            if (isset($property)) {
                ?>
                <img src="<?= $property['image_full']; ?>">
                <h1><?= htmlentities($property['county']) . ", "
                    . htmlentities($property['country']) . ", "
                    . htmlentities($property['town']) . ", "
                    . htmlentities($property['address']); ?></h1>
                <h2><?= htmlentities($property['latitude']) . "," . htmlentities($property['longitude']) ?></h2>

                <h3>Bedrooms: <?= htmlentities($property['num_bedrooms']); ?></h3>
                <h3>Bathrooms: <?= htmlentities($property['num_bathrooms']); ?></h3>
                <h3>price: <?= htmlentities($property['price']); ?></h3>
                <h6><?= htmlentities($property['uuid']); ?></h6>
                <p>price: <?= htmlentities($property['description']); ?></p>
                <?
            }
            ?>


        </div>
    </div>
</div>
</body>


</html>