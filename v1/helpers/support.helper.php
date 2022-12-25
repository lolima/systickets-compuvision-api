<?php

class FunctionsHelper
{


    public static function addDate($date, $day) //add days
    {
        $sum = strtotime(date("Y-m-d", strtotime("$date")) . " +$day days");
        $dateTo = date('Y-m-d', $sum);
        return $dateTo;
    }

    public static function prepareQuery($sql)
    {
        $sql = str_replace(" Invalid date", "null", $sql);
        $sql = str_replace("Invalid date", "null", $sql);
        $sql = str_replace("'null'", "null", $sql);
        $sql = str_replace("''", "null", $sql);
        $sql = str_replace("' '", "null", $sql);
        return $sql;
    }


    public static function sort_by($subfield, &$array, $type)
    {
        $sortarray = array();
        foreach ($array as $key => $row) {
            $sortarray[$key] = $row[$subfield];
        }

        if ($type == 'ASC') {
            array_multisort($sortarray, SORT_ASC, $array);
        }
        if ($type == 'DESC') {
            array_multisort($sortarray, SORT_DESC, $array);
        }
    }

    public static function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }

    public static function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = [];

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                array_push($temp_array, $val);
            }
            $i++;
        }
        return $temp_array;
    }

    public static function getYearsDifference($then)
    {
        $then = date('Ymd', strtotime($then));
        $diff = date('Ymd') - $then;
        return substr($diff, 0, -4);
    }

    public static function generateRandomId($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyz')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    public static function encryptNewPassword($password)
    {

        $alg = 6; // sha-512 encryption algorithm
        $cost = 160000; // computational cost (default = 50000)
        $salt = self::generateRandomId(16); // random string (A-Z|a-z|0-9)
        $hash = crypt($password, "\$" . $alg . "\$rounds=" . $cost . "\$" . $salt);

        return $hash;
    }

    public static function GUIDv4($trim = true)
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true) {
                return trim(com_create_guid(), '{}');
            } else {
                return com_create_guid();
            }
        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        // Fallback (PHP 4.2+)
        mt_srand((float) microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $lbrace = $trim ? "" : chr(123); // "{"
        $rbrace = $trim ? "" : chr(125); // "}"
        $guidv4 = $lbrace .
            substr($charid, 0, 8) . $hyphen .
            substr($charid, 8, 4) . $hyphen .
            substr($charid, 12, 4) . $hyphen .
            substr($charid, 16, 4) . $hyphen .
            substr($charid, 20, 12) .
            $rbrace;
        return $guidv4;
    }

    public static function checkGUIDv4($value)
    {
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        // preg_match($UUIDv4, $value) or die('UUID inválido');
        preg_match($UUIDv4, $value, $result);

        return $result;
    }

    public static function checkFields($payload, $required)
    {
        $error = false;

        foreach ($required as $field) {
            if (empty($payload[$field])) {
                $error = true;
            }
        }

        if ($error) {
            return false;
        } else {
            return true;
        }
    }

    function moveUploadedFile($directory, Slim\Http\UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    public static function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }
}

class Correios
{
    /**
     * Recupera um endereço através de seu CEP
     *
     * @param $cep
     *
     * @return array|bool
     */
    public static function getEnderecoOld($cep)
    {
        if (!$cep) {
            return array(
                "status" => false,
                "message" => 'CEP inválido.'
            );
        }

        //Consulta
        $action = "http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaCepEndereco.cfm";
        $ch = curl_init($action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "relaxation=" . $cep);

        $response = curl_exec($ch);
        curl_close($ch);

        //Tratamento
        $doc = new \DOMDocument();
        $doc->loadHTML($response);
        $xpath = new \DOMXPath($doc);

        $emptyCEP = $xpath->query('//p[contains(.,"DADOS NAO ENCONTRADOS")]')->length === 1;

        if ($emptyCEP) {
            return array(
                "status" => false,
                "message" => 'CEP não encontrado.'
            );
        }

        $columns = $xpath->query('//table[@class="tmptabela"]/tr/td/text()');

        $cidadeUf = explode('/', $columns[2]->nodeValue);

        return array(
            "status" => true,
            "logradouro" => $columns[0]->nodeValue,
            "bairro" => $columns[1]->nodeValue,
            "cidade" => $cidadeUf[0],
            "uf" => $cidadeUf[1],
            "cep" => $columns[3]->nodeValue
        );
    }

    public function getEndereco($cep)
    {
        if (!$cep) {
            return array(
                "status" => false,
                "message" => 'CEP inválido.'
            );
        }

        $cep = str_replace("-", "", $cep);
        $cep = str_replace(".", "", $cep);
        $action = 'https://viacep.com.br/ws/' . $cep . '/json';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $action,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => null,
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        return array(
            "status" => true,
            "logradouro" => $response->logradouro,
            "bairro" => $response->bairro,
            "cidade" => $response->localidade,
            "uf" => $response->uf,
            "cep" => $response->logradouro
        );
    }
}

class MailHelper
{

    public static function sendTicketEmail($client_name)
    {
        $ch = curl_init();

        $content = '{
            "Messages": [
                {
                    "From": {
                        "Email": "no-reply@compuvision.com",
                        "Name": "compuvision"
                    },
                    "To": [
                        {
                            "Email": "contato@compuvision.com",
                            "Name": "Administrativo - compuvision"
                        }
                    ],
                    "TemplateID": 4126528,
                    "TemplateLanguage": true,
                    "Subject": "compuvision :: Novo chamado",
                    "Variables": {
                        "client_name": "' . $client_name . '"
                    }
                }
            ]
        }';
        curl_setopt($ch, CURLOPT_URL, 'https://api.mailjet.com/v3.1/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_USERPWD,  '95755fa196823333848aaa88e7b50576:0560f3180e568ebb4069c90691b8b496');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        $obj['result'] = json_decode($result);

        if (curl_errno($ch)) {
            curl_close($ch);
            $obj['status'] = false;
        } else {
            curl_close($ch);
            $obj['status'] = true;
        }

        return $obj;


        //print_r($result);
    }
}

abstract class BasicEnum
{
    // Static Variables
    private static $constCacheArray = NULL;

    /**
     * Checks if name exists in enum
     * @param: (string)
     * @param: (bool)
     * @return: (bool)
     */
    public static function isValidName($name, $strict = false)
    {
        $constants = self::getConstants();
        if ($strict)
            return array_key_exists($name, $constants);
        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    /**
     * Checks if value exists in enum
     * @param: (mixed)
     * @return: (bool)
     */
    public static function isValidValue($value)
    {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict = true);
    }

    /**
     * Returns value with a given name
     * @param: (string)
     * @return: (mixed)
     */
    public static function getValueByName($name)
    {
        $constants = self::getConstants();
        if (array_key_exists($name, $constants))
            return $constants[$name];
        return false;
    }

    /**
     * Returns the name of the constant with a given value
     * If Enum has non-unqiue values then the first instance is returned
     * Should have it return array of constants with given value
     * @param: (string)
     * @return: (mixed)
     */
    public static function getNameByValue($value)
    {
        $constants = self::getConstants();
        $flip = array_flip($constants);
        if (array_key_exists($value, $flip))
            return $flip[$value];
        return false;
    }


    /**
     * Gets constant from defined enum
     * @return: (mixed)
     */
    public static function getConstants()
    {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }
}
