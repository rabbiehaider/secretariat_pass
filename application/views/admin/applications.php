<div id="adminApplications" class="container">
    <div class="page-title row align-items-center">
        <div class="col">
            <span class="page-kicker">Applications</span>
            <h1>{{ pageTitle }}</h1>
        </div>
        <div class="col text-right">
            <a class="btn btn-outline-primary" href="<?php echo site_url('admin/applications/all'); ?>">All</a>
            <a class="btn btn-outline-primary" href="<?php echo site_url('admin/applications/pending'); ?>">Pending</a>
            <a class="btn btn-outline-primary" href="<?php echo site_url('admin/applications/approved'); ?>">Approved</a>
            <a class="btn btn-outline-primary" href="<?php echo site_url('admin/applications/rejected'); ?>">Rejected</a>
        </div>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tracking</th>
                        <th>Visitor</th>
                        <th>Phone</th>
                        <th>Visit</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in applications" :key="row.id">
                        <td>{{ row.tracking_id }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.phone }}</td>
                        <td>{{ row.visit_date }}</td>
                        <td>{{ row.department_name }}</td>
                        <td><span class="badge badge-status" :class="'badge-' + row.status">{{ row.status }}</span></td>
                        <td>
                            <template v-if="row.status == 'pending'">
                                <button class="btn btn-sm btn-success" @click="approveApplication(row)">Approve</button>
                                <button class="btn btn-sm btn-danger" @click="rejectApplication(row)">Reject</button>
                            </template>
                            <a v-else-if="row.status == 'approved'" class="btn btn-sm btn-outline-primary" :href="row.card_url">Card</a>
                            <span v-else>{{ row.rejected_reason }}</span>
                        </td>
                    </tr>
                    <tr v-if="applications.length == 0">
                        <td colspan="7" class="text-center text-muted">No records found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#adminApplications',
        data() {
            return {
                status: '<?php echo html_escape($status); ?>',
                applications: []
            }
        },
        computed: {
            pageTitle() {
                return this.status.charAt(0).toUpperCase() + this.status.slice(1) + ' Applications';
            }
        },
        created() {
            this.getApplications();
        },
        methods: {
            getApplications() {
                axios.get('<?php echo site_url('get_applications'); ?>?status=' + this.status).then(res => {
                    let r = res.data;
                    if (r.success) {
                        this.applications = r.applications;
                    } else {
                        alert(r.message);
                    }
                });
            },
            approveApplication(application) {
                if (!confirm('Approve this application?')) {
                    return;
                }

                let fd = new FormData();
                fd.append('data', JSON.stringify({
                    id: application.id
                }));

                axios.post('<?php echo site_url('approve_application'); ?>', fd).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getApplications();
                    }
                });
            },
            rejectApplication(application) {
                let reason = '';
                while (true) {
                    reason = prompt('Please enter a mandatory Cancel Note (reason for rejection):');
                    if (reason === null) {
                        return;
                    }
                    reason = reason.trim();
                    if (reason !== '') {
                        break;
                    }
                    alert('Cancel Note is mandatory. You cannot reject an application without a reason.');
                }

                let fd = new FormData();
                fd.append('data', JSON.stringify({
                    id: application.id,
                    reason: reason
                }));

                axios.post('<?php echo site_url('reject_application'); ?>', fd).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getApplications();
                    }
                });
            }
        }
    });
</script>