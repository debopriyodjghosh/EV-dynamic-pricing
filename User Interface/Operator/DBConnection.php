<?php
if(!is_dir(__DIR__.'./db'))
    mkdir(__DIR__.'./db');
if(!defined('db_file')) define('db_file',__DIR__.'./db/psms_db.db');
if(!defined('tZone')) define('tZone',"Asia/Manila");
if(!defined('dZone')) define('dZone',ini_get('date.timezone'));
function my_udf_md5($string) {
    return md5($string);
}

Class DBConnection extends SQLite3{
    protected $db;
    function __construct(){
        $this->open(db_file);
        $this->createFunction('md5', 'my_udf_md5');
        $this->exec("PRAGMA foreign_keys = ON;");

        $this->exec("CREATE TABLE IF NOT EXISTS `user_list` (
            `user_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `type` INTEGER NOT NULL Default 1,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 

        //User Comment
        // Type = [ 1 = Administrator, 2 = Cashier]
        // Status = [ 1 = Active, 2 = Inactive]

        $this->exec("CREATE TABLE IF NOT EXISTS `petrol_type_list` (
            `petrol_type_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `name` TEXT NOT NULL,
            `price` REAL NOT NULL,
            `status` INTEGER NOT NULL DEFAULT 1
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `customer_list` (
            `customer_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `customer_code` TEXT NOT NULL,
            `fullname` TEXT NOT NULL,
            `contact` TEXT NOT NULL,
            `email` TEXT NOT NULL,
            `address` TEXT NOT NULL,
            `status` INTEGER NOT NULL DEFAULT 1
        )");

        $this->exec("CREATE TABLE IF NOT EXISTS `transaction_list` (
            `transaction_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `customer_id` INTEGER NOT NULL,
            `receipt_no` TEXT NOT NULL,
            `petrol_type_id` TEXT NOT NULL,
            `price` REAL NOT NULL,
            `liter` REAL NOT NULL,
            `amount` REAL NOT NULL,
            `discount` REAL NOT NULL,
            `total` REAL NOT NULL DEFAULT 0,
            `tendered_amount` REAL NOT NULL DEFAULT 0,
            `change` REAL NOT NULL DEFAULT 0,
            `type` INTEGER NOT NULL DEFAULT 1,
            `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `user_id` INTEGER NOT NULL DEFAULT 1,
            FOREIGN KEY(`user_id`) REFERENCES `user_list`(`user_id`) ON DELETE SET NULL,
            FOREIGN KEY(`petrol_type_id`) REFERENCES `petrol_type_list`(`petrol_type_id`) ON DELETE SET NULL,
            FOREIGN KEY(`customer_id`) REFERENCES `customer_list`(`customer_id`) ON DELETE SET NULL
        )");

        $this->exec("CREATE TABLE IF NOT EXISTS `debt_list` (
            `debt_id` Integer NOT NULL PRIMARY KEY AUTOINCREMENT,
            `transaction_id` Integer NOT NULL,
            `customer_id` Integer NOT NULL,
            `amount` REAL NOT NULL DEFAULT 0,
            `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`transaction_id`) REFERENCES `transaction_list`(`transaction_id`) ON DELETE CASCADE,
            FOREIGN KEY(`customer_id`) REFERENCES `customer_list`(`customer_id`) ON DELETE CASCADE
        )");

        $this->exec("CREATE TABLE IF NOT EXISTS `payment_list` (
            `payment_id` Integer NOT NULL PRIMARY KEY AUTOINCREMENT,
            `payment_code` TEXT NOT NULL,
            `customer_id` Integer NOT NULL,
            `amount` REAL NOT NULL DEFAULT 0,
            `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`customer_id`) REFERENCES `customer_list`(`customer_id`) ON DELETE CASCADE
        )");

        
        // $this->exec("CREATE TRIGGER IF NOT EXISTS updatedTime_prod AFTER UPDATE on `product_list`
        // BEGIN
        //     UPDATE `product_list` SET date_updated = CURRENT_TIMESTAMP where product_id = product_id;
        // END
        // ");

        $this->exec("INSERT or IGNORE INTO `user_list` VALUES (1,'Administrator','admin',md5('admin123'),1,1, CURRENT_TIMESTAMP)");

    }
    function __destruct(){
         $this->close();
    }
}

$conn = new DBConnection();

// $conn = new mysqli("localhost","root","", "test");

// // Check connection
// if ($conn -> connect_errno) {
//   echo "Failed to connect to MySQL: " . $conn  -> connect_error;
//   exit();
// }


// // $dbcon=mysqli_connect("localhost","root","");

// // mysqli_select_db($dbcon,"urbanclap");

?>