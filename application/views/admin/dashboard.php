<div id="adminDashboard" class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Control Room</span>
            <h1>Admin Dashboard</h1>
            <p>Visitor approval and entry overview.</p>
        </div>
    </div>

    <div class="stats-grid">
        <a class="stat" href="<?php echo site_url('report/index'); ?>"><span>Today Applied</span><strong>{{ stats.today_apply }}</strong></a>
        <a class="stat" href="<?php echo site_url('admin/applications/pending'); ?>"><span>Pending</span><strong>{{ stats.pending }}</strong></a>
        <a class="stat" href="<?php echo site_url('admin/applications/approved'); ?>"><span>Approved</span><strong>{{ stats.approved }}</strong></a>
        <a class="stat" href="<?php echo site_url('report/index'); ?>"><span>Used Today</span><strong>{{ stats.used_today }}</strong></a>
    </div>

    <div class="actions-bar">
        <a class="btn btn-primary" href="<?php echo site_url('admin/applications/pending'); ?>">Pending Applications</a>
        <a class="btn btn-outline-primary" href="<?php echo site_url('report/index'); ?>">Reports</a>
        <a class="btn btn-outline-dark" href="<?php echo site_url('gate/scanner'); ?>">Gate Scanner</a>
    </div>

    <div class="panel">
        <h2>Recent Applications</h2>
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
                    <tr v-for="row in recent" :key="row.id">
                        <td>{{ row.tracking_id }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.phone }}</td>
                        <td>{{ row.visit_date }}</td>
                        <td>{{ row.department_name }}</td>
                        <td><span class="badge badge-status" :class="'badge-' + row.status">{{ row.status }}</span></td>
                        <td>
                            <a v-if="row.status == 'approved'" class="btn btn-sm btn-outline-primary" :href="row.card_url">Card</a>
                            <span v-else>{{ row.status }}</span>
                        </td>
                    </tr>
                    <tr v-if="recent.length == 0">
                        <td colspan="7" class="text-center text-muted">No records found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
new Vue({
    el: '#adminDashboard',
    data() {
        return {
            stats: {
                today_apply: 0,
                pending: 0,
                approved: 0,
                used_today: 0
            },
            recent: []
        }
    },
    created() {
        this.getDashboard();
    },
    methods: {
        getDashboard() {
            axios.get('<?php echo site_url('get_admin_dashboard'); ?>').then(res => {
                let r = res.data;
                if (r.success) {
                    this.stats = r.stats;
                    this.recent = r.recent;
                } else {
                    alert(r.message);
                }
            });
        }
    }
});
</script>

