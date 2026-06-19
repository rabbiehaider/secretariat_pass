<div class="container">
    <div class="page-title row align-items-center">
        <div class="col">
            <span class="page-kicker">Applications</span>
            <h1><?php echo ucfirst(html_escape($status)); ?> Applications</h1>
        </div>
        <div class="col text-right">
            <a class="btn btn-outline-primary" href="<?php echo site_url('admin/applications/pending'); ?>">Pending</a>
            <a class="btn btn-outline-primary" href="<?php echo site_url('admin/applications/approved'); ?>">Approved</a>
            <a class="btn btn-outline-primary" href="<?php echo site_url('admin/applications/rejected'); ?>">Rejected</a>
        </div>
    </div>
    <div class="panel">
        <?php include APPPATH . 'views/partials/application_table.php'; ?>
    </div>
</div>
