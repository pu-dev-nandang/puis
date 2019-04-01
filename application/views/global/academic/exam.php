<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<style>

    body {
        font-family: "Arial";
    }

    .table_pr {
        border-collapse: collapse;
    }

    table.table_pr, th, td {
        border: 1px solid black;
    }

    table.table_pr th {
        padding: 10px;
    }
    table.table_pr td {
        padding: 15px;
        text-align: center;
    }
    table.table_pr td:last-child {
        text-align: left;
    }
</style>

<table class="table_pr">
    <thead>
    <tr>
        <th style="width: 20px;">No</th>
        <th style="width: 150px;">Code</th>
        <th style="width: 400px;">Description</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td>
            <img src="<?php echo site_url('genrateBarcode/12345678912'); ?>">
        </td>
        <td>
            Food and Beverage Production - Pastry Theory
            <div style="font-size: 12px;margin-top: 5px;">
                Code : ARC12 | Group : ARC12

                <div style="margin-top: 5px;">
                    - Nandang Mulyadi
                    <br/>
                    - Arif Yandi
                </div>
            </div>

        </td>
    </tr>
    </tbody>
</table>


</body>
</html>