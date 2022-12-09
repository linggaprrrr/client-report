<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Receipt Smart Wholesale</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    table.desc {
      border: 1px solid; font-size: 9px;
    }

    table.desc th {
      border: 1px solid; font-size: 9px;
    }

    table.desc td {
      border: 1px solid; font-size: 9px;
    }

    table.desc {
      width: 100%;
      border-collapse: collapse;
    }

    .text-center {
      text-align: center;
    }

    table.data {
      width: 100%;
    }
    table.data td {
      font-size: 9px;
      text-align: left;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h2></h2>
    <table class="data"> 
      <tr>
        <td colspan="5" style="background-color: #0000FF; color: #0000FF">&nbsp</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td colspan="2"><h1>RECEIPT</h1></td>
        <td colspan="3" rowspan="4" style="align-items: right; text-align:right"><img src="<?= $img ?>" style="width: 150px"></td>
      </tr>
      <tr>
        <td colspan="3" style="font-size: 11px;">Smart Wholesale</td>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td colspan="3" style="font-size: 11px;">464 NE 219th Ave</td>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td colspan="3" style="font-size: 11px;">Gresham, OR 97030</td>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td colspan="2" style="background-color: #cccccc;">BILL TO</td>     
        <td></td>  
        <td colspan="2" style="background-color: #cccccc;">PURCHASE DETAIL</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">Client Name</td>
        <td style="vertical-align: top;"><?= $manifestDesc[0]->fullname ?></td>
        <td></td>
        <td style="vertical-align: top;"># Of Units</td>
        <td style="text-align: right; vertical-align:top"><?= $totalUnit ?></td> 
      </tr>
      <tr>
        <td style="vertical-align: top;">Company</td>
        <td style="vertical-align: top;"><?= $manifestDesc[0]->company ?></td>
        <td></td>
        <td style="vertical-align: top;">Total Original Retail</td>
        <td style="text-align: right; vertical-align:top">$<?= number_format($totalRetail, 2) ?></td>        
      </tr>
      <tr>
        <td style="vertical-align: top;">Purchase Date</td>
        <td style="vertical-align: top;"><?= date('m/d/y', strtotime($manifestDesc[0]->date))?></td>
        <td></td>
        <td style="vertical-align: top;">Total Client Cost</td>
        <td style="text-align: right">$<?= number_format($totalClientCost, 2) ?></td>
        
      </tr>
      <tr>
        <td style="vertical-align: top;">Link</td>
        <td style="color: #0000FF; vertical-align:top; font-size: 8px"><?= (empty($link[0])) ? '-' : '<u>'. $link[0]->link. '</u>' ?></td>
        <td></td>
        <td>Avg. Unit Client Cost</td>
        <td style="text-align: right; vertical-align:top">$<?= number_format($avgUnitClientCost, 2) ?></td>
        
      </tr>
      <tr>
        <td style="vertical-align: top;">Purchase Amount</td>
        <td style="vertical-align:top">$<?= number_format($manifestDesc[0]->cost, 2) ?></td>
        <td></td>
        <td style="vertical-align: top;">Avg. Unit Retail</td>
        <td style="text-align: right; vertical-align:top">$<?= number_format($avgUnitRetail, 2) ?></td>        
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="vertical-align: top;">Condition</td>
        <td style="text-align: right; vertical-align:top">New</td>        
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="vertical-align: top;">Cost Left</td>
        <td style="text-align: right; background-color:aqua; vertical-align:top">$<?= number_format($totalCostLeft, 2) ?></td>        
      </tr>
    </table>
    <br>
    <table class="desc"> 
      <thead>
        <tr style="background-color: #0000FF;">
          <th><span style="color: white">UPC/SKU</span></th>
          <th><span style="color: white">ITEM DESCRIPTION</span></th>
          <th><span style="color: white">ORIGINAL QTY</span></th>
          <th><span style="color: white">RETAIL VALUE</span></th>
          <th><span style="color: white">TOTAL ORIGINAL RETAIL</span></th>
          <th><span style="color: white">TOTAL CLIENT COST</span></th>
          <th><span style="color: white">VENDOR NAME</span></th>
        </tr>
      </thead>
      <tbody>
          <?php foreach($manifestData as $row) : ?>
          <tr>
            <td class="text-center"><?= $row->sku ?></td>
            <td><?= $row->item_description ?></td>
            <td class="text-center"><?= $row->qty ?></td>
            <td class="text-center">$<?= number_format($row->retail_value, 2) ?></td>
            <td class="text-center">$<?= number_format($row->original_value, 2) ?></td>
            <td class="text-center">$<?= number_format($row->cost, 2) ?></td>
            <td class="text-center"><?= $row->vendor ?></td>
          </tr>
          <?php endforeach ?>
          <tr style="background-color: blue; color: white">
            <td class="text-center" style="border-color: blue;">-----</td>
            <td class="text-center" style="border-color: blue;">- End of the list -</td>
            <td class="text-center" style="border-color: blue;">-----</td>
            <td class="text-center" style="border-color: blue;">-----</td>
            <td class="text-center" style="border-color: blue;">-----</td>
            <td class="text-center" style="border-color: blue;">-----</td>
            <td class="text-center" style="border-color: blue;">-----</td>
          </tr>
          <tr style="background-color: blue; color: white; ">           
            <td style="border-color: blue;"></td> 
            <td class="text-center" style="border-color: blue;"># Of Units</td>
            <td class="text-center" colspan="" style="border-color: blue;"><?= $totalUnit ?></td>        
            <td class="text-center" colspan="4" style="border-color: blue;"></td>        
          </tr>
          <tr style="background-color: blue; color: white; ">           
            <td style="border-color: blue;"></td> 
            <td class="text-center" style="border-color: blue;">Total Original Retail</td>
            <td class="text-center" style="border-color: blue;">$<?= number_format($totalRetail, 2)?></td>        
            <td class="text-center" colspan="4" style="border-color: blue;"></td>        
          </tr>
          <tr style="background-color: blue; color: white; ">           
            <td style="border-color: blue;"></td> 
            <td class="text-center" style="border-color: blue;">Total Client Cost</td>
            <td class="text-center" style="border-color: blue;">$<?= number_format($totalClientCost, 2) ?></td>
            <td class="text-center" colspan="4" style="border-color: blue;"></td>        
          </tr>
          <tr style="background-color: blue; color: white; ">           
            <td style="border-color: blue;"></td> 
            <td class="text-center" style="border-color: blue;">Avg. Unit Client Cost</td>
            <td class="text-center" style="border-color: blue;">$<?= number_format($avgUnitClientCost, 2) ?></td>
            <td class="text-center" colspan="4" style="border-color: blue;"></td>        
          </tr>
          <tr style="background-color: blue; color: white; ">           
            <td style="border-color: blue;"></td> 
            <td class="text-center" style="border-color: blue;">Avg. Unit Retail</td>
            <td class="text-center" style="border-color: blue;">$<?= number_format($avgUnitRetail, 2) ?></td>
            <td class="text-center" colspan="4" style="border-color: blue;"></td>        
          </tr>
      </tbody>
    </table>
  </div>
</body>
</html>