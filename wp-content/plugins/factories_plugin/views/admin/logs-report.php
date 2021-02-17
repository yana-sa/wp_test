<?php
wp_head();
$report = admin_money_transfer_logs_report();
?>
<header class="entry-header alignwide">
    <h2 style="text-align: center;">Transfer report</h2>
</header>

<div class="tablediv">
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
            <?php foreach($report as $data) { ?>
            <tr>
                <td><?php echo $data['company'];
                    for($i=1; $i<=12; $i++) {?></td>
                <td ><?php
                    $profit = isset($data['profit']) ? $data['profit'] : '0';
                    $loss = isset($data['loss']) ? $data['loss'] : '0';
                    if ($data['month'] == $i) {
                        echo '+' . $profit . '$ / -' . $loss . '$';
                        } else {
                        echo '+0$ / -0$';
                    }
                } ?></td>
            </tr>
        <?php }?>
    </table>
</div>