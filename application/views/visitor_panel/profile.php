<div class="container narrow">
    <div class="page-title">
        <div>
            <span class="page-kicker">Visitor Profile</span>
            <h1><?php echo html_escape($visitor->name); ?></h1>
            <p>Your registered visitor information.</p>
        </div>
    </div>

    <div class="panel profile-panel">
        <dl class="card-grid">
            <dt>Name</dt><dd><?php echo html_escape($visitor->name); ?></dd>
            <dt>Email</dt><dd><?php echo html_escape($visitor->email); ?></dd>
            <dt>Phone</dt><dd><?php echo html_escape($visitor->phone); ?></dd>
            <dt>NID or Passport</dt><dd><?php echo html_escape($visitor->nid); ?></dd>
            <dt>Address</dt><dd><?php echo html_escape($visitor->address); ?></dd>
            <dt>Registered At</dt><dd><?php echo html_escape($visitor->created_at); ?></dd>
        </dl>
        <a class="btn btn-primary mt-3" href="<?php echo site_url('visitor/apply'); ?>">Apply for Visit</a>
        <a class="btn btn-outline-primary mt-3" href="<?php echo site_url('visitor_panel/dashboard'); ?>">Dashboard</a>
    </div>
</div>

