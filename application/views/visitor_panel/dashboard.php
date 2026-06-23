<div id="visitorDashboard" class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Visitor Dashboard</span>
            <h1>Welcome, <?php echo html_escape($visitor->name); ?></h1>
            <p>Manage profile, applications, and approved visitor cards.</p>
        </div>
        <a class="btn btn-primary" href="<?php echo site_url('visitor/apply'); ?>">New Visit Apply</a>
    </div>

    <div class="stats-grid">
        <div class="stat"><span>Total Applied</span><strong>{{ stats.total }}</strong></div>
        <div class="stat"><span>Pending</span><strong>{{ stats.pending }}</strong></div>
        <div class="stat"><span>Approved</span><strong>{{ stats.approved }}</strong></div>
        <div class="stat"><span>Rejected</span><strong>{{ stats.rejected }}</strong></div>
    </div>

    <div class="visitor-dashboard-grid">
        <div class="panel profile-summary">
            <div class="form-section-title">Profile</div>
            <h2><?php echo html_escape($visitor->name); ?></h2>
            <p><?php echo html_escape($visitor->email); ?></p>
            <p><?php echo html_escape($visitor->phone); ?></p>
            <a class="btn btn-outline-primary" href="<?php echo site_url('visitor_panel/profile'); ?>">View Profile</a>
        </div>

        <div class="panel">
            <div class="form-section-title">Apply History</div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Visit Date</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Card</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="application in applications" :key="application.id">
                            <td>{{ application.tracking_id }}</td>
                            <td>{{ application.visit_date }}</td>
                            <td>{{ application.department_name }}</td>
                            <td><span class="badge badge-status" :class="'badge-' + application.status">{{ application.status }}</span></td>
                            <td>
                                <a v-if="application.status == 'approved'" class="btn btn-sm btn-success" :href="application.card_url">View Card</a>
                                <span v-else-if="application.status == 'rejected'">{{ application.rejected_reason }}</span>
                                <span v-else>Waiting</span>
                            </td>
                        </tr>
                        <tr v-if="applications.length == 0">
                            <td colspan="5" class="text-center text-muted">No application submitted yet</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
new Vue({
    el: '#visitorDashboard',
    data() {
        return {
            applications: [],
            stats: {
                total: 0,
                pending: 0,
                approved: 0,
                rejected: 0
            }
        }
    },
    created() {
        this.getApplications();
    },
    methods: {
        getApplications() {
            axios.get('<?php echo site_url('get_visitor_applications'); ?>').then(res => {
                let r = res.data;
                if (r.success) {
                    this.applications = r.applications;
                    this.setStats();
                } else {
                    alert(r.message);
                }
            });
        },
        setStats() {
            this.stats = {
                total: this.applications.length,
                pending: this.applications.filter(a => a.status == 'pending').length,
                approved: this.applications.filter(a => a.status == 'approved').length,
                rejected: this.applications.filter(a => a.status == 'rejected').length
            };
        }
    }
});
</script>
