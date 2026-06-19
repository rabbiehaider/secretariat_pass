<div id="visitorApply" class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Public Portal</span>
            <h1>Visitor Application</h1>
            <p>Apply for Bangladesh Secretariat entry approval.</p>
        </div>
    </div>

    <form class="panel apply-panel" method="post" enctype="multipart/form-data" action="<?php echo site_url('visitor/submit'); ?>" @submit="validateSubmit">
        <?php if ($this->session->flashdata('apply_error')): ?>
            <div class="alert alert-danger"><?php echo html_escape($this->session->flashdata('apply_error')); ?></div>
        <?php endif; ?>
        <div class="apply-grid">
            <div>
                <div class="form-section-title">Visitor Information</div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Full Name</label>
                        <input class="form-control" type="text" name="name" v-model="form.name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Phone</label>
                        <input class="form-control" type="text" name="phone" v-model.trim="form.phone" inputmode="numeric" maxlength="11" pattern="^01[13-9][0-9]{8}$" required>
                        <small v-if="phoneError" class="form-text text-danger">{{ phoneError }}</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>NID or Passport</label>
                        <input class="form-control" type="number" name="nid" v-model="form.nid" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Visit Date</label>
                        <input class="form-control" type="date" name="visit_date" v-model="form.visit_date" min="<?=  date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="form-section-title">Visit Information</div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Person to Visit</label>
                        <input class="form-control" type="text" name="visit_to" v-model="form.visit_to" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Department</label>
                        <select class="form-control" name="department_id" v-model="form.department_id" required>
                            <option value="">Select department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo (int) $department->id; ?>"><?php echo html_escape($department->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Visit Purpose</label>
                    <input class="form-control" name="purpose" v-model="form.purpose" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control" name="address" rows="3" v-model="form.address" required></textarea>
                </div>
            </div>

            <aside class="photo-upload-panel">
                <div class="form-section-title">Visitor Photo</div>
                <div class="photo-preview">
                    <img v-if="photoPreview" :src="photoPreview" alt="Selected visitor photo">
                    <div v-else class="photo-preview-empty">Photo Preview</div>
                </div>
                <label class="photo-picker">
                    <input type="file" name="photo" accept="image/jpeg,image/png" @change="previewPhoto">
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
        data: {
            submitting: false,
            form: {
                name: '',
                phone: '',
                nid: '',
                visit_date: '',
                visit_to: '',
                department_id: '',
                purpose: '',
                address: ''
            },
            photoPreview: '',
            photoName: '',
            phoneError: ''
        },
        methods: {
            validateSubmit: function(event) {
                var regexMobile = /^01[13-9][\d]{8}$/;

                if (!regexMobile.test(this.form.phone)) {
                    event.preventDefault();
                    this.phoneError = 'Please enter a valid Bangladeshi mobile number.';
                    return;
                }

                this.phoneError = '';
                this.submitting = true;
            },
            previewPhoto: function(event) {
                var file = event.target.files && event.target.files[0];
                this.photoPreview = '';
                this.photoName = '';

                if (!file) {
                    return;
                }

                this.photoName = file.name;
                this.photoPreview = URL.createObjectURL(file);
            }
        },
        watch: {
            'form.phone': function(value) {
                var regexMobile = /^01[13-9][\d]{8}$/;
                this.phoneError = value && !regexMobile.test(value) ? 'Please enter a valid Bangladeshi mobile number.' : '';
            }
        }
    });
</script>
