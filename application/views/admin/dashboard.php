<div class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Control Room</span>
            <h1>Admin Dashboard</h1>
            <p>Visitor approval and entry overview.</p>
        </div>
    </div>
    <div class="stats-grid">
        <a class="stat" href="<?php echo site_url('report/index'); ?>"><span>Today Applied</span><strong><?php echo (int) $stats['today_apply']; ?></strong></a>
        <a class="stat" href="<?php echo site_url('admin/applications/pending'); ?>"><span>Pending</span><strong><?php echo (int) $stats['pending']; ?></strong></a>
        <a class="stat" href="<?php echo site_url('admin/applications/approved'); ?>"><span>Approved</span><strong><?php echo (int) $stats['approved']; ?></strong></a>
        <a class="stat" href="<?php echo site_url('report/index'); ?>"><span>Used Today</span><strong><?php echo (int) $stats['used_today']; ?></strong></a>
    </div>
    <div class="actions-bar">
        <a class="btn btn-primary" href="<?php echo site_url('admin/applications/pending'); ?>">Pending Applications</a>
        <a class="btn btn-outline-primary" href="<?php echo site_url('report/index'); ?>">Reports</a>
        <a class="btn btn-outline-dark" href="<?php echo site_url('gate/scanner'); ?>">Gate Scanner</a>
    </div>
    <div class="panel">
        <h2>Recent Applications</h2>
        <?php $applications = $recent;
        include APPPATH . 'views/partials/application_table.php'; ?>
    </div>
</div>