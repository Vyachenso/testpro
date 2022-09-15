<?php

class service
{
    private $cm, $s;
    private $onpage = 90;

    public function __construct($host, $user_db, $pass, $db)
    {
        $this->cm = mysqli_connect($host, $user_db, $pass);
        mysqli_select_db($this->cm, $db);
    }

    public function __destruct()
    {
        mysqli_close($this->cm);
    }

    public function query($sql)
    {
        mysqli_query($this->cm, $sql);
        return "ok";
    }

    public function get_price($where)
    {
        $price = array();
        $sql = "SELECT MAX(price) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while ($line = mysqli_fetch_array($res)) {
            $price['max'] = $line['name'];
        }
        mysqli_free_result($res);

        $sql = "SELECT MIN(price) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while ($line = mysqli_fetch_array($res)) {
            $price['min'] = $line['name'];
        }
        mysqli_free_result($res);

        $sql = "SELECT SUM(price) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while ($line = mysqli_fetch_array($res)) {
            $price['sum'] = $line['name'];
        }
        mysqli_free_result($res);

        $sql = "SELECT COUNT(id) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while ($line = mysqli_fetch_array($res)) {
            $price['count'] = $line['name'];
        }
        mysqli_free_result($res);

        $price['medium'] = ceil($price['sum'] / $price['count']);

        return $price;
    }

    public function get_property_types($where)
    {
        $all = array();
        $sql = "SELECT id,title FROM property_types ";
        $res = mysqli_query($this->cm, $sql);
        while ($line = mysqli_fetch_assoc($res)) {
            $all[] = $line;
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_propertys($data)
    {
        $all = array();
        $start = 0;
        if (isset($data['page']) && is_numeric($data['page'])) {
            $start = ceil($this->onpage * $data['page']);
        }

        $ifwhere = "";

        if (isset($data) && count($data) > 0) {
            foreach ($data as $key => $value) {
                if (!empty($value) && $value != 0 && $key !== "page") {
                    if ($key !== "price_min" && $key !== "price_max") {
                        $this->s .= " AND " . $key . " = '" . mysqli_real_escape_string($this->cm, $value) . "' ";
                    } else {
                        if ($key === "price_min") {
                            $this->s .= " AND price >= '" . mysqli_real_escape_string($this->cm, $value) . "' ";
                        }
                        if ($key === "price_max") {
                            $this->s .= " AND price <= '" . mysqli_real_escape_string($this->cm, $value) . "' ";
                        }
                    }
                }
            }
            $ifwhere = $this->s;
        }

        if (isset($ifwhere) && !empty($ifwhere)) {
            $ifwhere = trim($this->s, ' AND');
            $ifwhere = " WHERE " . $ifwhere;
        }

        $sql = "SELECT * FROM propertys " . $ifwhere . " LIMIT " . $start . "," . $this->onpage;
        $res = mysqli_query($this->cm, $sql);
        while ($line = mysqli_fetch_assoc($res)) {
            $all[] = $line;
        }
        mysqli_free_result($res);

        $sql = "SELECT COUNT(id) AS cs FROM propertys " . $ifwhere;
        $res = mysqli_query($this->cm, $sql);
        while ($line = mysqli_fetch_assoc($res)) {
            $all['count'] = $line['cs'];
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_property($id)
    {
        $all = array();
        $sql = "SELECT * FROM propertys WHERE id=" . $id;
        $res = mysqli_query($this->cm, $sql);
        while ($line = mysqli_fetch_assoc($res)) {
            $all = $line;
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_bedrooms()
    {
        $all = array();
        $sql = "SELECT DISTINCT(num_bedrooms) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while ($line = mysqli_fetch_array($res)) {
            $all[] = $line['name'];
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_county($where)
    {
        $all = array();
        $sql = "SELECT DISTINCT(county) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while ($line = mysqli_fetch_array($res)) {
            $all[] = $line['name'];
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_country($data)
    {
        $all = array();
        if (isset($data['county'])) {
            $county = $data['county'];
            $sql = "SELECT DISTINCT(country) AS name FROM propertys WHERE county='" . mysqli_real_escape_string($this->cm, $county) . "' ";
            $res = mysqli_query($this->cm, $sql);
            while ($line = mysqli_fetch_array($res)) {
                $all[] = $line['name'];
            }
            mysqli_free_result($res);
        }

        return $all;
    }

    public function get_town($data)
    {
        $all = array();
        if (isset($data['country'])) {
            $country = $data['country'];
            $sql = "SELECT DISTINCT(town) AS name FROM propertys WHERE country='" . mysqli_real_escape_string($this->cm, $country) . "' ";
            $res = mysqli_query($this->cm, $sql);
            while ($line = mysqli_fetch_array($res)) {
                $all[] = $line['name'];
            }
            mysqli_free_result($res);
        }
        return $all;
    }

    public function get_api($page = "")
    {
        if (empty($page)) {
            $apiUrl = 'https://trial.craig.mtcserver15.com/api/properties?';
            $api_key = "2S7rhsaq9X1cnfkMCPHX64YsWYyfe1he";

            $query = http_build_query(array('api_key' => $api_key));
            $curl = curl_init($apiUrl . $query);
        } else {
            $curl = curl_init($page);
        }

        // additional options
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => true));
        $answer = curl_exec($curl);
        curl_close($curl);

        $text = json_decode($answer, true);

        if (isset($text['data'])) {
            $this->save_property($text['data']);
        }

        if (isset($text['next_page_url'])) {
            print $text['next_page_url'] . "\r\n";
            $this->get_api($text['next_page_url']);
        }

        return "ok";
    }


    public function save_property($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $sql = "INSERT IGNORE INTO propertys(
                      uuid,
                      property_type_id,
                      county,
                      country,
                      town,
                      description,
                      address,
                      image_full,
                      image_thumbnail,
                      latitude,
                      longitude,
                      num_bedrooms,
                      num_bathrooms,
                      price,
                      type,
                      created_at,
                      updated_at
                      ) 
                      VALUES(
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['uuid'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['property_type_id'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['county'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['country'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['town'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['description'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['address'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['image_full'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['image_thumbnail'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['latitude'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['longitude'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['num_bedrooms'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['num_bathrooms'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['price'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['type'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['created_at'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['updated_at'])) . "'); ";

                mysqli_query($this->cm, $sql);

                if (is_array($value['property_type'])) {
                    $sql = "INSERT IGNORE INTO property_types(
                      id,
                      title,
                      description,
                      created_at)
                      
                      VALUES(
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['property_type']['id'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['property_type']['title'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['property_type']['description'])) . "',
                      '" . mysqli_real_escape_string($this->cm, htmlentities($value['property_type']['created_at'])) . "'); ";


                    mysqli_query($this->cm, $sql);
                }
            }
        }
    }

}
