<style>
    @media print {

        nav,
        footer,
        form,
        .btn,
        .page-title,
        .stats-grid,
        .page-kicker {
            display: none !important;
        }

        body {
            background: #fff !important;
            color: #000 !important;
        }

        .container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .panel {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            background: transparent !important;
        }

        .print-only {
            display: block !important;
        }

        .row {
            display: block !important;
        }

        .col-lg-5,
        .col-lg-7 {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            margin-bottom: 30px !important;
            page-break-inside: avoid !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        th,
        td {
            border: 1px solid #ddd !important;
            padding: 6px !important;
        }
    }

    .print-only {
        display: none;
    }
</style>

<div id="reportApp" class="container">
    <!-- Print Header -->
    <div class="print-only mb-4">
        <div style="display: flex; align-items: center; border-bottom: 3px double #152535; padding-bottom: 15px; margin-bottom: 20px;">
            <img src="<?php echo base_url('assets/images/Bangladesh_Secretariat.png'); ?>" alt="Emblem" style="height: 80px; width: 80px; margin-right: 20px;">
            <div>
                <div style="font-size: 13px; text-transform: uppercase; color: #555; letter-spacing: 0.5px;">Government of the People's Republic of Bangladesh</div>
                <h1 style="font-size: 26px; font-weight: bold; color: #152535; margin: 2px 0;">Bangladesh Secretariat</h1>
                <h3 style="font-size: 18px; color: #1f6f54; margin: 2px 0; font-weight: bold;">Gate Pass Entry Logs Report</h3>
            </div>
        </div>
    </div>

    <div class="page-title row align-items-center">
        <div class="col">
            <span class="page-kicker">Analytics</span>
            <h1>Gate Log Reports</h1>
            <p>Date wise application, approval, and entry report.</p>
        </div>
        <div class="col text-right">
            <button class="btn btn-primary" onclick="window.print()">Print</button>
            <a class="btn btn-outline-primary" href="<?php echo site_url('report/applications'); ?>">Application Reports</a>
        </div>
    </div>

    <form class="panel report-filter-form mb-4" @submit.prevent="getReport" style="padding: 20px;">
        <div class="form-row">
            <div class="col-md-3 form-group">
                <label>From Date</label>
                <input class="form-control" type="date" v-model="filter.from">
            </div>
            <div class="col-md-3 form-group">
                <label>To Date</label>
                <input class="form-control" type="date" v-model="filter.to">
            </div>
            <div class="col-md-3 form-group">
                <label>Visitor Name</label>
                <input class="form-control" placeholder="Search by name" v-model="filter.name">
            </div>
            <div class="col-md-3 form-group">
                <label>Visitor Phone</label>
                <input class="form-control" placeholder="Search by phone" v-model="filter.phone">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-3 form-group">
                <label>NID / Passport</label>
                <input class="form-control" placeholder="Search by NID" v-model="filter.nid">
            </div>
            <div class="col-md-3 form-group">
                <label>Pass Number</label>
                <input class="form-control" placeholder="Search by pass no" v-model="filter.pass_no">
            </div>
            <div class="col-md-3 form-group">
                <label>Department</label>
                <select class="form-control" v-model="filter.department_id">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo $dept->id; ?>"><?php echo html_escape($dept->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label>Scan Status</label>
                <select class="form-control" v-model="filter.scan_status">
                    <option value="">All Statuses</option>
                    <option value="valid">Valid Entry</option>
                    <option value="invalid">Invalid Token</option>
                    <option value="expired">Expired Pass</option>
                    <option value="already_used">Already Used Pass</option>
                    <option value="rejected">Rejected Pass</option>
                    <option value="pending">Pending Pass</option>
                </select>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-secondary mr-2" type="button" @click="resetFilters">Reset</button>
            <button class="btn btn-primary" type="submit">Search & Filter</button>
        </div>
    </form>

    <div class="stats-grid mb-4">
        <div class="stat"><span>Applied</span><strong>{{ summary.applied }}</strong></div>
        <div class="stat"><span>Approved</span><strong>{{ summary.approved }}</strong></div>
        <div class="stat"><span>Rejected</span><strong>{{ summary.rejected }}</strong></div>
        <div class="stat"><span>Used</span><strong>{{ summary.used }}</strong></div>
    </div>

    <div class="row">
        <!-- Department-Wise Reports Table -->
        <div class="col-lg-5 mb-4">
            <div class="panel">
                <h2>Department-Wise Summary</h2>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th class="text-center text-warning">Pen</th>
                                <th class="text-center text-success">App</th>
                                <th class="text-center text-danger">Rej</th>
                                <th class="text-center font-weight-bold">Tot</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in deptReport" :key="row.id">
                                <td><small><strong>{{ row.name }}</strong></small></td>
                                <td class="text-center">{{ row.pending_count || 0 }}</td>
                                <td class="text-center">{{ row.approved_count || 0 }}</td>
                                <td class="text-center">{{ row.rejected_count || 0 }}</td>
                                <td class="text-center font-weight-bold">{{ row.total_count || 0 }}</td>
                            </tr>
                            <tr v-if="deptReport.length == 0">
                                <td colspan="5" class="text-center text-muted">No data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Gate Logs Table -->
        <div class="col-lg-7 mb-4">
            <div class="panel">
                <h2>Gate Logs</h2>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Pass</th>
                                <th>Name</th>
                                <th>Dept</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="entry in entries" :key="entry.id">
                                <td><small>{{ entry.entry_time }}</small></td>
                                <td><small><strong>{{ entry.pass_no || 'N/A' }}</strong></small></td>
                                <td>
                                    <div><strong>{{ entry.name || 'N/A' }}</strong></div>
                                    <small class="text-muted">{{ entry.phone || 'N/A' }}</small>
                                </td>
                                <td><small>{{ entry.department_name || 'N/A' }}</small></td>
                                <td>
                                    <span class="badge" :class="logClass(entry.scan_status)">{{ entry.scan_status }}</span>
                                </td>
                                <td><small>{{ entry.remarks }}</small></td>
                            </tr>
                            <tr v-if="entries.length == 0">
                                <td colspan="6" class="text-center text-muted">No logs found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Print Footer -->
    <div class="print-only mt-5" style="border-top: 1px solid #ccc; padding-top: 10px; font-size: 12px; color: #666; display: flex; justify-content: space-between; width: 100%;">
        <span>Bangladesh Secretariat Visitor Management System</span>
        <span>Printed On: <?php echo date('Y-m-d H:i:s'); ?></span>
    </div>
</div>

<script>
    new Vue({
        el: '#reportApp',
        data() {
            return {
                filter: {
                    from: '<?php echo date('Y-m-d'); ?>',
                    to: '<?php echo date('Y-m-d'); ?>',
                    name: '',
                    phone: '',
                    nid: '',
                    pass_no: '',
                    department_id: '',
                    scan_status: ''
                },
                summary: {
                    applied: 0,
                    approved: 0,
                    rejected: 0,
                    used: 0
                },
                entries: [],
                deptReport: []
            }
        },
        created() {
            this.getReport();
        },
        methods: {
            getReport() {
                let url = '<?php echo site_url('get_report'); ?>?from=' + this.filter.from +
                    '&to=' + this.filter.to +
                    '&name=' + encodeURIComponent(this.filter.name) +
                    '&phone=' + encodeURIComponent(this.filter.phone) +
                    '&nid=' + encodeURIComponent(this.filter.nid) +
                    '&pass_no=' + encodeURIComponent(this.filter.pass_no) +
                    '&department_id=' + this.filter.department_id +
                    '&scan_status=' + this.filter.scan_status;
                axios.get(url).then(res => {
                    let r = res.data;
                    if (r.success) {
                        this.summary = r.summary;
                        this.entries = r.entries;
                        this.deptReport = r.department_report;
                    } else {
                        alert(r.message);
                    }
                });
            },
            resetFilters() {
                this.filter.name = '';
                this.filter.phone = '';
                this.filter.nid = '';
                this.filter.pass_no = '';
                this.filter.department_id = '';
                this.filter.scan_status = '';
                this.filter.from = '<?php echo date('Y-m-d'); ?>';
                this.filter.to = '<?php echo date('Y-m-d'); ?>';
                this.getReport();
            },
            logClass(status) {
                switch (status) {
                    case 'valid':
                        return 'badge-success';
                    case 'invalid':
                        return 'badge-danger';
                    case 'expired':
                        return 'badge-warning';
                    case 'already_used':
                        return 'badge-info';
                    default:
                        return 'badge-secondary';
                }
            }
        }
    });
</script>