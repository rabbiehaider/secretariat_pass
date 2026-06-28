<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<div class="container narrow">
    <div class="visitor-card" id="printArea">
        <div class="pass-topbar">
            <span>Approved Visitor Pass</span>
            <strong><?php echo html_escape($application->visit_date); ?></strong>
        </div>

        <div class="pass-head">
            <div class="pass-brand">
                <img class="gov-emblem" src="<?php echo base_url(); ?>assets/images/Bangladesh_Secretariat.png" alt="Bangladesh Secretariat">
                <div class="pass-brand-copy">
                    <span>Government of the People's Republic of Bangladesh</span>
                    <h1>Bangladesh Secretariat</h1>
                    <p>Visitor Access Card</p>
                </div>
            </div>
            <div class="pass-status">
                <span>Status</span>
                <strong>Approved</strong>
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
                    <span>Visitor Name</span>
                    <h2><?php echo html_escape($application->name); ?></h2>
                    <div class="pass-meta">
                        <span>Pass No</span>
                        <strong><?php echo html_escape($application->pass_no); ?></strong>
                    </div>
                </div>
            </div>

            <div class="qr-panel">
                <div id="qrCode"></div>
            </div>
        </div>

        <dl class="card-grid pass-details">
            <dt>Phone</dt>
            <dd><?php echo html_escape($application->phone); ?></dd>
            <dt>NID or Passport</dt>
            <dd><?php echo html_escape($application->nid); ?></dd>
            <dt>Department</dt>
            <dd><?php echo html_escape($application->department_name); ?></dd>
            <dt>Person to Visit</dt>
            <dd><?php echo html_escape($application->visit_to); ?></dd>
            <dt>Purpose</dt>
            <dd><?php echo html_escape($application->purpose); ?></dd>
        </dl>

        <div class="pass-footer">
            <span>This card is valid only for the approved visit date.</span>
            <strong>Security Verification Required</strong>
        </div>
    </div>
    <button class="btn btn-primary mt-3" onclick="window.print()">Print Card</button>
</div>
<script>
    var qrSize = window.matchMedia('(max-width: 576px)').matches ? 176 : 168;
    new QRCode(document.getElementById('qrCode'), {
        text: '<?php echo site_url('visitor/card/' . $application->qr_token); ?>',
        width: qrSize,
        height: qrSize,
        correctLevel: QRCode.CorrectLevel.L
    });
</script>