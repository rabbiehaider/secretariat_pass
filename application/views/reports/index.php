<div id="reportApp" class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Analytics</span>
            <h1>Reports</h1>
            <p>Date wise application, approval, and entry report.</p>
        </div>
    </div>

    <form class="panel report-filter" @submit.prevent="getReport">
        <label>From <input class="form-control" type="date" v-model="filter.from"></label>
        <label>To <input class="form-control" type="date" v-model="filter.to"></label>
        <button class="btn btn-primary" type="submit">Filter</button>
    </form>

    <div class="stats-grid">
        <div class="stat"><span>Applied</span><strong>{{ summary.applied }}</strong></div>
        <div class="stat"><span>Approved</span><strong>{{ summary.approved }}</strong></div>
        <div class="stat"><span>Rejected</span><strong>{{ summary.rejected }}</strong></div>
        <div class="stat"><span>Used</span><strong>{{ summary.used }}</strong></div>
    </div>

    <div class="panel">
        <h2>Gate Logs</h2>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Pass</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="entry in entries" :key="entry.id">
                        <td>{{ entry.entry_time }}</td>
                        <td>{{ entry.pass_no }}</td>
                        <td>{{ entry.name }}</td>
                        <td>{{ entry.phone }}</td>
                        <td>{{ entry.scan_status }}</td>
                        <td>{{ entry.remarks }}</td>
                    </tr>
                    <tr v-if="entries.length == 0">
                        <td colspan="6" class="text-center text-muted">No logs found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#reportApp',
        data() {
            return {
                filter: {
                    from: '<?php echo date('Y-m-d'); ?>',
                    to: '<?php echo date('Y-m-d'); ?>'
                },
                summary: {
                    applied: 0,
                    approved: 0,
                    rejected: 0,
                    used: 0
                },
                entries: []
            }
        },
        created() {
            this.getReport();
        },
        methods: {
            getReport() {
                let url = '<?php echo site_url('get_report'); ?>?from=' + this.filter.from + '&to=' + this.filter.to;
                axios.get(url).then(res => {
                    let r = res.data;
                    if (r.success) {
                        this.summary = r.summary;
                        this.entries = r.entries;
                    } else {
                        alert(r.message);
                    }
                });
            }
        }
    });
</script>