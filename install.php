<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js"></script>





<?php
session_start();
require 'shopify.php';
require 'db_config.php';

/* Define your APP`s key and secret */
define('SHOPIFY_API_KEY', '8421ffa0f4a56a9da58bbab28d6bc323');
define('SHOPIFY_SECRET', '209811956d29b6b7fac8add882ad2e4e');

/* Define requested scope (access rights) - checkout https://docs.shopify.com/api/authentication/oauth#scopes   */
define('SHOPIFY_SCOPE', 'read_content,write_content,read_shipping,write_shipping,read_orders,write_orders,write_products,read_products,read_themes,write_themes,read_customers,write_customers,read_fulfillments,write_fulfillments'); //eg: define('SHOPIFY_SCOPE','read_orders,write_orders');

if (isset($_GET['code'])) { // if the code param has been sent to this page... we are in Step 2
    // Step 2: do a form POST to get the access token
    $shopifyClient = new ShopifyClient($_GET['shop'], "", SHOPIFY_API_KEY, SHOPIFY_SECRET);
    session_unset();

    // Now, request the token and store it in your session.
    $_SESSION['token'] = $shopifyClient->getAccessToken($_GET['code']);
    if ($_SESSION['token'] != '')
        $_SESSION['shop'] = $_GET['shop'];
    $shop = isset($_POST['shop']) ? $_POST['shop'] : $_GET['shop'];
    //header("Location: http://".$shop."/admin/app");

    $db_token = $_SESSION['token'];
    $db_shop = $_SESSION['shop'];

    $ins_db = "INSERT INTO test (shop_name,token_name) VALUES ('" . $db_shop . "','" . $db_token . "')";
    $ex = mysqli_query($connection,$ins_db);

    //exit; 

    header("Location: http://" . $shop . "/admin/apps");
    exit;
}
// if they posted the form with the shop name
else if (isset($_POST['shop'])) {

    // Step 1: get the shopname from the user and redirect the user to the
    // shopify authorization page where they can choose to authorize this app
    $shop = isset($_POST['shop']) ? $_POST['shop'] : $_GET['shop'];
    $shopifyClient = new ShopifyClient($shop, "", SHOPIFY_API_KEY, SHOPIFY_SECRET);

    // get the URL to the current page
    $pageURL = 'https';
    // if ($_SERVER["HTTPS"] == "on") {
    //     $pageURL .= "s";
    // }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["SCRIPT_NAME"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"];
    }

    // redirect to authorize url
    header("Location: " . $shopifyClient->getAuthorizeUrl(SHOPIFY_SCOPE, $pageURL));
    exit;
}

// first time to the page, show the form below
?>

<style>
    .panel-default{
        top: 18%;
        position: relative;
        width:50%;
        margin:0 auto;
    }
    .form-horizontal{

        padding: 75px;
    }
    .text-danger{
        font-size: 14px;
        font-weight: 600;        
    }
    .panel-default>.panel-heading{
        font-size: 16px;
        font-weight: 600;
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading">Install MOBILE LAUNCH App</div>
    <div class="panel-body">
        <form action="" method="post" class="form-horizontal" id="install_form">

            <div class="form-group">
                <label for='shop'><strong>The URL of the Shop</strong> 
                </label> 
            </div>
            <div class="form-group">
                <input id="shop"  id="shop" name="shop" size="45" type="text" class="form-control" value="" /> 
                <p class="text-danger">(enter it exactly like this: myshop.myshopify.com)</p> 
            </div>
            <div class="form-group">
                <input name="commit"  type="submit" value="Install" class="btn btn-success"/> 
            </div>
        </form></div>
</div>

<script>
    $(document).ready(function () {
        $('#install_form').bootstrapValidator({
            container: '#messages',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                shop: {
                    validators: {
                        notEmpty: {
                            message: 'The shop URL is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /\.(myshopify.com)$/i,
                            message: 'The shop URL end with this format myshop.myshopify.com'
                        }
                    }
                }
            }
        });
    });
</script>

<!--     <form action="" method="post">
      <label for='shop'><strong>The URL of the Shop</strong> 
        <span class="hint">(enter it exactly like this: myshop.myshopify.com)</span> 
      </label> 
      <p> 
        <input id="shop" name="shop" size="45" type="text" value="" /> 
        <input name="commit" type="submit" value="Install" /> 
      </p> 
    </form> -->
