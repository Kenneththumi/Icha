<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');
//session_destroy();
echo '<link href="'.SITE_PATH.'modules/shoppingcart/css/shopping-cart-styles.css" rel="stylesheet" type="text/css" />';

$this->loadscripts('jquery.sticky','yes');
$this->wrapscript("$(document).ready(function() {
    
    function processCart(pid,action)
    {
        $.ajax({
            url: '".SITE_PATH."modules/shoppingcart/cartprocessing.php',
            type: 'POST',
            data: { id: pid, type: 'publication', task: action},
            dataType: 'html',
            beforeSend: function(){
                $('.sidecart').html('<p><img src=\"".DEFAULT_TEMPLATE_PATH."/images/greenloader.gif\" ></p>');
            },
            success: function(data,textStatus){
                $('.sidecart').html('<p>'+data+'</p>');
            }
        });
    }

    $('.cartholder').hcSticky({
        noContainer: true
    });
    
    $('.addtocart').click(function(){
        var pid = $(this).attr('id');
        processCart(pid,'add');
    });
    
    $('a.removeitem').click(function(){
        var pid = $(this).attr('id');
        processCart(pid,'remove');
    });
    
    $('a.removeitem').qtip({ // Grab some elements to apply the tooltip to
                                                content: {
                                                    text: 'Click to remove this publication from the cart'
                                                },
                                                position: {
                                                    my: 'bottom left',
                                                    at: 'bottom right',
                                                    target: 'mouse'
                                                },
                                                style: {
                                                            classes: 'qtip-light qtip-shadow'
                                                        }
                                            })
});");


echo '<div class="cartholder">';
echo '<ul class="tabs">
	<li><a href="#">
            <img src="'.SITE_PATH.'modules/shoppingcart/images/shopping_cart.png"> <h1>Shopping Cart</h1>
        </a></li>
    </ul>';

echo '<div class="sidecart">';

include ABSOLUTE_PATH.'modules/shoppingcart/showcartdetails.php';

echo '</div>';
echo '</div>';
?>
