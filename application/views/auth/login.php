<div class="auth-wrap">
    <div class="panel">
        <div class="panel-kicker">Restricted Area</div>
        <h1>Admin Login</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo html_escape($error); ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo site_url('auth/login'); ?>">
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="password" required>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Login</button>
        </form>
    </div>
</div>
