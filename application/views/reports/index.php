<div class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Analytics</span>
            <h1>Reports</h1>
            <p>Date wise application, approval, and entry report.</p>
        </div>
    </div>
    <form class="panel report-filter" method="get">
        <label>From <input class="form-control" type="date" name="from" value="<?php echo html_escape($from); ?>"></label>
        <label>To <input class="form-control" type="date" name="to" value="<?php echo html_escape($to); ?>"></label>
        <button class="btn btn-primary" type="submit">Filter</button>
    </form>
    <div class="stats-grid">
        <div class="stat"><span>Applied</span><strong><?php echo (int) $summary['applied']; ?></strong></div>
        <div class="stat"><span>Approved</span><strong><?php echo (int) $summary['approved']; ?></strong></div>
        <div class="stat"><span>Rejected</span><strong><?php echo (int) $summary['rejected']; ?></strong></div>
        <div class="stat"><span>Used</span><strong><?php echo (int) $summary['used']; ?></strong></div>
    </div>
    <div class="panel">
        <h2>Gate Logs</h2>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Pass</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td><?php echo html_escape($entry->entry_time); ?></td>
                            <td><?php echo html_escape($entry->pass_no); ?></td>
                            <td><?php echo html_escape($entry->name); ?></td>
                            <td><?php echo html_escape($entry->phone); ?></td>
                            <td><?php echo html_escape($entry->scan_status); ?></td>
                            <td><?php echo html_escape($entry->remarks); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($entries)): ?>
                        <tr><td colspan="6" class="text-center text-muted">No logs found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
