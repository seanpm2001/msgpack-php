--TEST--
broken random data test
--SKIPIF--
<?php
if(!extension_loaded('msgpack')) {
    die("skip");
}
if (version_compare(PHP_VERSION, "7.1", ">=")) {
    die("skip PHP >= 7.1");
}
?>
--FILE--
<?php

error_reporting(E_ERROR | E_PARSE);

function test() {
    $serialized = msgpack_serialize(null);
    $serialized = substr($serialized, 0, -1);

    $length = mt_rand(1, 255);
    for ($i = 0; $i < $length; ++$i) {
        $serialized .= chr(mt_rand(0, 255));
    }

    // if returned null everything is OK
    if (($unserialized = msgpack_unserialize($serialized)) === null) {
        return true;
    }

    // whole data is read?
    if ($serialized !== msgpack_serialize($unserialized)) {
        return true;
    }

    echo bin2hex($serialized), "\n";
    var_dump($unserialized);

    return false;
}

mt_srand(0x4c05b583);
for ($i = 0; $i < 100; ++$i) {
    if (!test()) break;
}

?>
--EXPECT--
