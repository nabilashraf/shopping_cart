<?php
// 'product image' object
class ProductImage{
 
    // database connection and table name
    private $conn;
    private $table_name = "product_images";
 
    // object properties
    public $id;
    public $product_id;
    public $name;
    public $timestamp;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
}

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$product = new Product($db);
$product_image = new ProductImage($db);

// to prevent undefined index notice
$action = isset($_GET['action']) ? $_GET['action'] : "";
 
// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 6; // set records or rows of data per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query LIMIT clause

// read the first product image related to a product
function readFirst(){
 
    // select query
    $query = "SELECT id, product_id, name
            FROM " . $this->table_name . "
            WHERE product_id = ?
            ORDER BY name DESC
            LIMIT 0, 1";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind prodcut id variable
    $stmt->bindParam(1, $this->product_id);
 
    // execute query
    $stmt->execute();
 
    // return values
    return $stmt;
}

// read all product image related to a product
function readByProductId(){
 
    // select query
    $query = "SELECT id, product_id, name
            FROM " . $this->table_name . "
            WHERE product_id = ?
            ORDER BY name ASC";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->product_id=htmlspecialchars(strip_tags($this->product_id));
 
    // bind prodcut id variable
    $stmt->bindParam(1, $this->product_id);
 
    // execute query
    $stmt->execute();
 
    // return values
    return $stmt;
}

echo "<div class='col-md-4' id='product-img'>";
 
    // read all related product image
    $stmt_product_image = $product_image->readByProductId();
    $num_product_image = $stmt_product_image->rowCount();
 
    // if count is more than zero
    if($num_product_image>0){
        // loop through all product images
        $x=0;
        while ($row = $stmt_product_image->fetch(PDO::FETCH_ASSOC)){
            // image name and source url
            $product_image_name = $row['name'];
            $source="uploads/images/{$product_image_name}";
            $show_product_img=$x==0 ? "display-block" : "display-none";
            echo "<a href='{$source}' target='_blank' id='product-img-{$row['id']}' class='product-img {$show_product_img}'>";
                echo "<img src='{$source}' style='width:100%;' />";
            echo "</a>";
            $x++;
        }
    }else{ echo "No images."; }
echo "</div>";

echo "<div class='col-md-5'>";
 
    echo "<div class='product-detail'>Price:</div>";
    echo "<h4 class='m-b-10px price-description'>&#36;" . number_format($product->price, 2, '.', ',') . "</h4>";
 
    echo "<div class='product-detail'>Product description:</div>";
    echo "<div class='m-b-10px'>";
        // make html
        $page_description = htmlspecialchars_decode(htmlspecialchars_decode($product->description));
 
        // show to user
        echo $page_description;
    echo "</div>";
 
    echo "<div class='product-detail'>Product category:</div>";
    echo "<div class='m-b-10px'>{$product->category_name}</div>";
 
echo "</div>";

echo "<div class='col-md-2'>";
 
    // if product was already added in the cart
    if(array_key_exists($id, $_SESSION['cart'])){
        echo "<div class='m-b-10px'>This product is already in your cart.</div>";
        echo "<a href='cart.php' class='btn btn-success w-100-pct'>";
            echo "Update Cart";
        echo "</a>";
 
    }
 
    // if product was not added to the cart yet
    else{
 
        echo "<form class='add-to-cart-form'>";
            // product id
            echo "<div class='product-id display-none'>{$id}</div>";
 
            echo "<div class='m-b-10px f-w-b'>Quantity:</div>";
            echo "<input type='number' value='1' class='form-control m-b-10px cart-quantity' min='1' />";
 
            // enable add to cart button
            echo "<button style='width:100%;' type='submit' class='btn btn-primary add-to-cart m-b-10px'>";
                echo "<span class='glyphicon glyphicon-shopping-cart'></span> Add to cart";
            echo "</button>";
 
        echo "</form>";
    }
echo "</div>";