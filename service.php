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

    public function get_price()
    {
        $sql = "SELECT MAX(price) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_array($res)) {
            $price['max'] = $line['name'];
        }
        mysqli_free_result($res);

        $sql = "SELECT MIN(price) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_array($res)) {
            $price['min'] = $line['name'];
        }
        mysqli_free_result($res);

        $sql = "SELECT SUM(price) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_array($res)) {
            $price['sum'] = $line['name'];
        }
        mysqli_free_result($res);

        $sql = "SELECT COUNT(id) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_array($res)) {
            $price['count'] = $line['name'];
        }
        mysqli_free_result($res);

        $price['medium'] = ceil($price['sum'] / $price['count']);

        return $price;
    }

    public function get_property_types()
    {
        $sql = "SELECT id,title FROM property_types ";
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_assoc($res)) {
            $all[] = $line;
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_propertys( $where = "", $start = 0)
    {
        $ifwhere = "";

        if(isset($where) && count($where) > 0) {
            foreach ($where as $key => $value) {
                if(!empty($value) && $value!=0) {
                    if($key != "price") {
                        $this->s .= " AND " . $key . " = '" . mysqli_real_escape_string($this->cm, $value) . "' ";
                    }
                    else {
                        $this->s .= " AND " . $key . " < '" . mysqli_real_escape_string($this->cm, $value) . "' ";
                    }
                }
            }
            $ifwhere = trim($this->s, ' AND');
        }

        if(isset($ifwhere) && !empty($ifwhere)) {
            $ifwhere = " WHERE " . $ifwhere;
        }

        $sql = "SELECT * FROM propertys " . $ifwhere . " LIMIT " . $start . "," . $this->onpage;
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_assoc($res)) {
            $all[] = $line;
        }
        mysqli_free_result($res);

        $sql = "SELECT COUNT(id) AS cs FROM propertys " . $ifwhere;
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_assoc($res)) {
            $all['count'] = $line['cs'];
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_property( $id)
    {

        $sql = "SELECT * FROM propertys WHERE id=" . $id;
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_assoc($res)) {
            $all = $line;
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_bedrooms()
    {
        $sql = "SELECT DISTINCT(num_bedrooms) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_array($res)) {
            $all[] = $line['name'];
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_county()
    {
        $sql = "SELECT DISTINCT(county) AS name FROM propertys ";
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_array($res)) {
            $all[] = $line['name'];
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_country($county)
    {
        $sql = "SELECT DISTINCT(country) AS name FROM propertys WHERE county='". mysqli_real_escape_string($this->cm, $county) ."' ";
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_array($res)) {
            $all[] = $line['name'];
        }
        mysqli_free_result($res);

        return $all;
    }

    public function get_town($country)
    {
        $sql = "SELECT DISTINCT(town) AS name FROM propertys WHERE country='". mysqli_real_escape_string($this->cm, $country) ."' ";
        $res = mysqli_query($this->cm, $sql);
        while($line = mysqli_fetch_array($res)) {
            $all[] = $line['name'];
        }
        mysqli_free_result($res);

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

        if(isset($text['data'])) {
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
                      '" . mysqli_real_escape_string($this->cm, $value['uuid']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['property_type_id']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['county']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['country']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['town']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['description']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['address']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['image_full']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['image_thumbnail']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['latitude']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['longitude']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['num_bedrooms']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['num_bathrooms']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['price']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['type']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['created_at']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['updated_at']) . "'); ";

                mysqli_query($this->cm, $sql);

                if (is_array($value['property_type'])) {
                    $sql = "INSERT IGNORE INTO property_types(
                      id,
                      title,
                      description,
                      created_at)
                      
                      VALUES(
                      '" . mysqli_real_escape_string($this->cm, $value['property_type']['id']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['property_type']['title']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['property_type']['description']) . "',
                      '" . mysqli_real_escape_string($this->cm, $value['property_type']['created_at']) . "'); ";


                    mysqli_query($this->cm, $sql);
                } 
            }
        }
    }

}
