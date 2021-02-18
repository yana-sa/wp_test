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
            <?php foreach($report as $r) { ?>
            <tr>
                <td><?php echo $r['company'];
                    for($i=1; $i<=12; $i++) {?></td>
                <td ><?php
                    foreach ($r['data'] as $data) {
                        $profit = isset($data['profit']) ? $data['profit'] : '0';
                        $loss = isset($data['loss']) ? $data['loss'] : '0';

                        if ($data['month'] == $i) {
                            $profit = isset($data['profit']) ? $data['profit'] : '0';
                            $loss = isset($data['loss']) ? $data['loss'] : '0';
                            echo '+' . $profit . '$ / -' . $loss . '$';
                        }
                    }
                    } ?></td>
            </tr>
        <?php }?>
    </table>
</div>