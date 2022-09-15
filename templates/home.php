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
                        print "<option "; if(isset($data['county']) && $data['county'] === $item) print "selected"; print " value='" . $item . "'>" . $item . "</option>";
                    }
                    print "</select>";
                }
                ?>
                <div class="country">
                    <?php
                    if (isset($county)) {
                        print "<h3>country</h3><br>
                    <select id='country' name='country' class='form-control form-select form-select-lg mb-3 w-50'>
                    <option value='0'>please choose</option>";
                        foreach ($country as $key => $item) {
                            print "<option "; if(isset($data['country']) && $data['country'] === $item) print "selected"; print " value='" . $item . "'>" . $item . "</option>";
                        }
                        print "</select>";
                    }
                    ?>
                </div>
                <div class="town">
                    <?php
                    if (isset($country)) {
                        print "<h3>town</h3><br>
                    <select id='town' name='town' class='form-control form-select form-select-lg mb-3 w-50'>
                    <option value='0'>please choose</option>";
                        foreach ($town as $key => $item) {
                            print "<option "; if(isset($data['town']) && $data['town'] === $item) print "selected"; print " value='" . $item . "'>" . $item . "</option>";
                        }
                        print "</select>";
                    }
                    ?>
                </div>

                <?php
                if (isset($bedrooms)) {
                    print "<h3>bedrooms</h3><br>
                    <select id='num_bedrooms' name='num_bedrooms' class='form-control form-select form-select-lg mb-3 w-50'>
                    <option value='0'>please choose</option>";
                    foreach ($bedrooms as $key => $item) {
                        print "<option "; if(isset($data['num_bedrooms']) && $data['num_bedrooms'] === $item) print "selected"; print " value='" . $item . "'>" . $item . "</option>";
                    }
                    print "</select>";
                }
                ?>

                <h3>type</h3><br>
                <select id='type' name='type' class='form-control form-select form-select-lg mb-3 w-50'>
                    <option value='0'>please choose</option>
                    <option <? if(isset($data['type']) && $data['type'] === 'rent') print " selected "; ?> value='rent'>rent</option>
                    <option <? if(isset($data['type']) && $data['type'] === 'sale') print " selected "; ?> value='sale'>sale</option>
                </select>

                <?php
                if (isset($data['price_min']) && !empty($data['price_min'])) {
                    $price['min'] = $data['price_min'];
                }
                if (isset($data['price_max']) && !empty($data['price_min'])) {
                    $price['max'] = $data['price_max'];
                }
                ?>
                <br>
                <h3>price min</h3> <br>
                <input type="number" name='price_min' value="<?= $price['min'] ?>" id="price" class='form-control form-control-lg mb-3 w-25'>

                <h3>price max<h3> <br>
                <input type="number" name='price_max' value="<?= $price['max'] ?>" id="price" class='form-control form-control-lg mb-3 w-25'>

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
                                                        <img src="<?= $value['image_thumbnail']; ?>"
                                                             class="img-fluid rounded">
                                                    </a>
                                                    <div class="label_icon">
                                                        <?= mb_substr($value['description'], 0, 100, "utf-8"); ?>
                                                    </div>

                                                </div>
                                                <div class="product-listing-content">
                                                    <h5>
                                                        <a href="index.php?id=<?= $value['id']; ?>">
                                                            <?= $value['address']; ?>
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
                echo $paginator;
            }
            ?>


            <?php
            if (isset($property)) {
                ?>
                <img src="<?= $property['image_full']; ?>">
                <h1><?= $property['county'] . ", "
                    . $property['country'] . ", "
                    . $property['town'] . ", "
                    . $property['address']; ?></h1>
                <h2><?= $property['latitude'] . "," . $property['longitude']; ?></h2>

                <h3>Bedrooms: <?= $property['num_bedrooms']; ?></h3>
                <h3>Bathrooms: <?= $property['num_bathrooms']; ?></h3>
                <h3>price: <?= $property['price']; ?></h3>
                <h6><?= $property['uuid']; ?></h6>
                <p> <?= $property['description']; ?></p>
                <?
            }
            ?>


        </div>
    </div>
</div>
</body>


</html>