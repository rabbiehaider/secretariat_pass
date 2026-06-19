<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<div class="container narrow">
    <div class="visitor-card" id="printArea">
        <div class="pass-ribbon">
            <span>Approved Entry Pass</span>
            <strong><?php echo html_escape($application->visit_date); ?></strong>
        </div>
        <div class="card-head pass-head">
            <div class="pass-brand">
                <div class="gov-mark"><img src="<?php echo base_url(); ?>assets/images/Bangladesh_Secretariat.png" style="height: 80px;" alt="Bangladesh Secretariat"></div>
                <div>
                    <span>Government of the People's Republic of Bangladesh</span>
                    <h1>Bangladesh Secretariat</h1>
                    <p>Visitor Access Card</p>
                </div>
            </div>
        </div>

        <div class="pass-body">
            <div class="identity-block">
                <?php if ($application->photo): ?>
                    <img class="visitor-photo" src="<?php echo base_url($application->photo); ?>" alt="Visitor photo">
                <?php else: ?>
                    <div class="visitor-photo photo-empty">Photo</div>
                <?php endif; ?>
                <div class="identity-copy">
                    <span>Visitor</span>
                    <h2><?php echo html_escape($application->name); ?></h2>
                    <div class="pass-no"><?php echo html_escape($application->pass_no); ?></div>
                </div>
            </div>

            <div class="qr-panel">
                <div id="qrCode"></div>
                <span>Scan at gate</span>
            </div>
        </div>

        <dl class="card-grid pass-details">
            <dt>Phone</dt><dd><?php echo html_escape($application->phone); ?></dd>
            <dt>NID or Passport</dt><dd><?php echo html_escape($application->nid); ?></dd>
            <dt>Department</dt><dd><?php echo html_escape($application->department_name); ?></dd>
            <dt>Person to Visit</dt><dd><?php echo html_escape($application->visit_to); ?></dd>
            <dt>Purpose</dt><dd><?php echo html_escape($application->purpose); ?></dd>
            <dt>Status</dt><dd><span class="verified-chip">Approved</span></dd>
        </dl>

        <div class="pass-footer">
            <span>This card is valid only for the approved visit date.</span>
            <strong>Security Verification Required</strong>
        </div>
    </div>
    <button class="btn btn-primary mt-3" onclick="window.print()">Print Card</button>
</div>
<script>
new QRCode(document.getElementById('qrCode'), {
    text: '<?php echo html_escape($application->qr_token); ?>',
    width: 112,
    height: 112
});
</script>
