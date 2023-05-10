<div class = "container-fluid">  
    <div class = "text-center">  
    <img src =  
    <?php echo "https://chart.googleapis.com/chart?cht=qr&chl=" .
    $sale['client_name'].' '.
   $sale['Ref'].' '.
   $detail['name'].' '.
   $detail['code'].' '.
   $detail['price'].' '.
   $detail['quantity'].' '.
   $detail['unitSale'].' '.
   $detail['DiscountNet'].' '.
   $detail['taxe'].' '.
   $detail['total'].' ' ?>
   @if($detail['is_imei'] && $detail['imei_number'] !==null)
   <p>IMEI/SN : {{$detail['imei_number']}}</p>
   @endif ' '
   <?php echo $sale['TaxNet'].' '.
   $sale['discount'].' '.
   $sale['shipping'].' '.
   $symbol . $sale['GrandTotal'].' '.
   $symbol . $sale['paid_amount'].' '.
   $symbol .$sale['due'].' '.
   $setting['CompanyName'].' '.
   $sale['date'].
    "&chs=160x160&chld=L|0" ?>
				 ;
        class = "qr-code img-thumbnail img-responsive" />   
    </div>