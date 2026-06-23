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

            <aside class="photo-upload-panel">
                <div class="form-section-title">Mandatory Visitor Photo</div>
                <div class="photo-preview">
                    <img v-if="photoPreview" :src="photoPreview" alt="Selected visitor photo">
                    <div v-else class="photo-preview-empty">Photo Required</div>
                </div>
                <label class="photo-picker">
                    <input type="file" accept="image/jpeg,image/png" @change="previewPhoto" required>
                    <span>{{ photoName || 'Choose Image' }}</span>
                </label>
                <p class="photo-help">JPG or PNG image, maximum 2MB.</p>
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
            },
            selectedFile: null,
            photoPreview: '',
            photoName: ''
        }
    },
    methods: {
        saveApplication() {
            if (this.selectedFile == null) {
                alert('Visitor photo is required.');
                return;
            }

            this.submitting = true;
            let fd = new FormData();
            fd.append('photo', this.selectedFile);
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
        },
        previewPhoto(event) {
            let file = event.target.files && event.target.files[0];
            this.photoPreview = '';
            this.photoName = '';
            this.selectedFile = null;

            if (!file) {
                return;
            }

            this.photoName = file.name;
            this.photoPreview = URL.createObjectURL(file);
            this.selectedFile = file;
        }
    }
});
</script>
