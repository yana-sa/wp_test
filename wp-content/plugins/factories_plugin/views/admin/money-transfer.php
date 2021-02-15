<?php
wp_head();
?>
    <header class="entry-header alignwide">
        <h2 style="text-align: center;">Money transfer logs</h2>
    </header>

    <div class="tablediv">
        <table style="text-align: center;">
            <tr>
                <th>Transferor</th>
                <th>Transferee</th>
                <th>Sum</th>
                <th>Date and time</th>
                <th>Cancel transfer</th>
            </tr>
            <?php foreach ($logs as $log) { ?>
                <tr>
                    <td><?php echo $log['transferor']; ?></td>
                    <td><?php echo $log['transferee']; ?></td>
                    <td><?php echo $log['sum']; ?></td>
                    <td><?php echo $log['date']; ?></td>
                    <td><form method="post">
                        <input type="submit" name="cancel" id="cancel" value="Cancel">
                        <input type="hidden" name="log_id" id="log_id" value="<?php echo $log['id']; ?>"></form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>