<?php
wp_head();
$report = admin_money_transfer_logs_report();
?>
<header class="entry-header alignwide">
    <h2 style="text-align: center;">Transfer report</h2>
</header>

<div>
    <table style="text-align: center;">
        <tr>
            <th>2021</th>
            <th>Jan</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>Apr</th>
            <th>May</th>
            <th>Jun</th>
            <th>Jul</th>
            <th>Aug</th>
            <th>Sep</th>
            <th>Oct</th>
            <th>Nov</th>
            <th>Dec</th>
        </tr>
        <?php foreach ($report as $company => $data) { ?>
            <tr>
                <td><?php echo $company;
                    foreach ($data as $month => $d) {?></td>
                <td><?php echo (!empty($d) ? $d : '+0$ / -0$')?></td>
                <?php }?>
            </tr>
        <?php } ?>
    </table>
</div>