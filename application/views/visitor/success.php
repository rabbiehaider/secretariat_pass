<div class="container narrow">
    <div class="panel text-center">
        <div class="success-icon">✓</div>
        <h1>Application Submitted</h1>
        <p>Your application is waiting for admin approval.</p>
        <div class="receipt">
            <strong>Tracking ID:</strong> <?php echo html_escape(visitor_tracking_id($application)); ?><br>
            <strong>Name:</strong> <?php echo html_escape($application->name); ?><br>
            <strong>Phone:</strong> <?php echo html_escape($application->phone); ?><br>
            <strong>Visit Date:</strong> <?php echo html_escape($application->visit_date); ?>
        </div>
        <p class="mt-3 text-muted">Use your Tracking ID and phone number to check approval status.</p>
        <a class="btn btn-outline-primary mt-3" href="<?php echo site_url('/'); ?>">New Application</a>
        <a class="btn btn-primary mt-3" href="<?php echo site_url('visitor/status'); ?>">Check Status</a>
    </div>
</div>
