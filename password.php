<?php

class RecordExists extends Exception {
    function __construct() {
        echo "Record already exists".PHP_EOL;
    }
}

class SimpleDB {
    private $records = [];

    public function insert($key, $data) {
        if(isset($this->records[$key]) || array_key_exists($key,$this->records)) {
            throw new RecordExists();
        }
        $this->records[$key] = $data;
    }

    public function dump() {
        foreach ($this->records as $key => $value) {
            print "Record id:". $key ." name: ". $value["name"] .PHP_EOL;
        }
    }
}

$db = new SimpleDB();

while($line = fgets(STDIN)){
    $userData = array_map("trim", explode(',', $line));
    try {
        $db->insert($userData[0], ["name" => $userData[1]]);
    } catch(RecordExists $e) {
        // better try another key
    }
}

$db->dump();

?>