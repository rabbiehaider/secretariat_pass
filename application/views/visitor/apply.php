<div id="visitorApply" class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Public Portal</span>
            <h1>Visitor Application</h1>
            <p>Apply for Bangladesh Secretariat entry approval.</p>
        </div>
    </div>

    <form class="panel" method="post" enctype="multipart/form-data" action="<?php echo site_url('visitor/submit'); ?>" @submit="submitting = true">
        <div class="form-section-title">Visitor Information</div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Full Name</label>
                <input class="form-control" name="name" v-model="form.name" required>
            </div>
            <div class="form-group col-md-6">
                <label>Phone</label>
                <input class="form-control" name="phone" v-model="form.phone" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>NID or Passport</label>
                <input class="form-control" name="nid" v-model="form.nid" required>
            </div>
            <div class="form-group col-md-6">
                <label>Visit Date</label>
                <input class="form-control" type="date" name="visit_date" v-model="form.visit_date" v-bind:min="" required>
            </div>
        </div>
        <div class="form-section-title">Visit Information</div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Person to Visit</label>
                <input class="form-control" name="visit_to" v-model="form.visit_to" required>
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
        <div class="form-group">
            <label>Photo</label>
            <input class="form-control-file" type="file" name="photo" accept="image/jpeg,image/png">
        </div>
        <button class="btn btn-primary" :disabled="submitting" type="submit">
            <!-- {{ submitting ? 'Submitting...' : 'Submit Application' }} -->
              Submit Application
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
            retr:
        }
    });
</script>