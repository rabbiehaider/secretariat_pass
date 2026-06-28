<div id="visitorApply" class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Visitor Portal</span>
            <h1>New Visit Application</h1>
            <p>Your profile information will be used automatically.</p>
        </div>
        <a class="btn btn-outline-primary" href="<?php echo site_url('visitor_panel/dashboard'); ?>">Back to Dashboard</a>
    </div>

    <form class="panel apply-panel" @submit.prevent="saveApplication">
        <div class="apply-grid">
            <div>
                <div class="profile-strip">
                    <div>
                        <span>Applicant</span>
                        <strong><?php echo html_escape($visitor->name); ?></strong>
                    </div>
                    <div>
                        <span>Phone</span>
                        <strong><?php echo html_escape($visitor->phone); ?></strong>
                    </div>
                    <div>
                        <span>Email</span>
                        <strong><?php echo html_escape($visitor->email); ?></strong>
                    </div>
                </div>

                <div class="form-section-title">Visit Information</div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Person to Visit</label>
                        <input class="form-control" type="text" v-model="application.visit_to" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Department</label>
                        <select class="form-control" v-model="application.department_id" required>
                            <option value="">Select department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo (int) $department->id; ?>"><?php echo html_escape($department->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Visit Date</label>
                        <input class="form-control" type="date" v-model="application.visit_date" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Visit Purpose</label>
                        <input class="form-control" v-model="application.purpose" required>
                    </div>
                </div>
            </div>

            <aside class="photo-upload-panel text-center">
                <div class="form-section-title">Visitor Photo</div>
                <div class="photo-preview mb-2">
                    <img src="<?php echo $visitor->photo ? base_url($visitor->photo) : "data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23ccc\'><path d=\'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4\'/></svg>"; ?>" alt="Registered profile photo" class="img-thumbnail rounded" style="max-height: 180px; width: 180px; object-fit: cover;">
                </div>
                <p class="photo-help text-muted"><small>Your registered profile photo will be printed on the visitor pass.</small></p>
            </aside>
        </div>

        <button class="btn btn-primary apply-submit" :disabled="submitting" type="submit">
            {{ submitting ? 'Submitting...' : 'Submit Application' }}
        </button>
    </form>
</div>

<script>
new Vue({
    el: '#visitorApply',
    data() {
        return {
            submitting: false,
            application: {
                visit_date: '',
                visit_to: '',
                department_id: '',
                purpose: ''
            }
        }
    },
    methods: {
        saveApplication() {
            this.submitting = true;
            let fd = new FormData();
            fd.append('data', JSON.stringify(this.application));

            axios.post('<?php echo site_url('visitor_apply'); ?>', fd).then(res => {
                let r = res.data;
                alert(r.message);
                if (r.success) {
                    window.location = r.redirect;
                }
            }).catch(() => {
                alert('Application submit failed');
            }).then(() => {
                this.submitting = false;
            });
        }
    }
});
</script>
