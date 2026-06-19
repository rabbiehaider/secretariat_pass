<div id="statusApp" class="container narrow">
    <div class="page-title">
        <div>
            <span class="page-kicker">Visitor Panel</span>
            <h1>Check Application Status</h1>
            <p>Use your tracking ID and phone number from the application receipt.</p>
        </div>
    </div>

    <form class="panel" method="post" action="<?php echo site_url('visitor/status'); ?>" @submit="checking = true">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo html_escape($error); ?></div>
        <?php endif; ?>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Tracking ID</label>
                <input class="form-control" type="number" name="tracking_id" required>
            </div>
            <div class="form-group col-md-6">
                <label>Phone</label>
                <input class="form-control" name="phone" required>
            </div>
        </div>
        <button class="btn btn-primary" :disabled="checking" type="submit">
            {{ checking ? 'Checking...' : 'Check Status' }}
        </button>
    </form>

    <?php if ($application): ?>
        <div class="panel status-card mt-3">
            <div class="status-top">
                <div>
                    <span>Tracking ID #<?php echo (int) $application->id; ?></span>
                    <h2><?php echo html_escape($application->name); ?></h2>
                </div>
                <span class="badge badge-status badge-<?php echo html_escape($application->status); ?>">
                    <?php echo html_escape($application->status); ?>
                </span>
            </div>

            <dl class="card-grid">
                <dt>Phone</dt><dd><?php echo html_escape($application->phone); ?></dd>
                <dt>Visit Date</dt><dd><?php echo html_escape($application->visit_date); ?></dd>
                <dt>Department</dt><dd><?php echo html_escape($application->department_name); ?></dd>
                <dt>Person to Visit</dt><dd><?php echo html_escape($application->visit_to); ?></dd>
                <dt>Purpose</dt><dd><?php echo html_escape($application->purpose); ?></dd>
                <?php if ($application->status === 'rejected'): ?>
                    <dt>Reject Reason</dt><dd><?php echo html_escape($application->rejected_reason); ?></dd>
                <?php endif; ?>
            </dl>

            <?php if ($application->status === 'approved'): ?>
                <div class="alert alert-success mt-3">Your pass has been approved. Show the QR card at the gate.</div>
                <a class="btn btn-success" href="<?php echo site_url('visitor/my_card/' . $application->id . '?phone=' . rawurlencode($application->phone)); ?>">View QR Card</a>
            <?php elseif ($application->status === 'pending'): ?>
                <div class="alert alert-warning mt-3">Your application is waiting for admin approval.</div>
            <?php else: ?>
                <div class="alert alert-danger mt-3">Your application was rejected.</div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
new Vue({
    el: '#statusApp',
    data: {
        checking: false
    }
});
</script>
