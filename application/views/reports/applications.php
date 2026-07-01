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

        .col-lg-8,
        .col-lg-4 {
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

<div id="appReport" class="container">
    <!-- Print Header -->
    <div class="print-only mb-4">
        <div style="display: flex; align-items: center; border-bottom: 3px double #152535; padding-bottom: 15px; margin-bottom: 20px;">
            <img src="<?php echo base_url('assets/images/Bangladesh_Secretariat.png'); ?>" alt="Emblem" style="height: 80px; width: 80px; margin-right: 20px;">
            <div>
                <div style="font-size: 13px; text-transform: uppercase; color: #555; letter-spacing: 0.5px;">Government of the People's Republic of Bangladesh</div>
                <h1 style="font-size: 26px; font-weight: bold; color: #152535; margin: 2px 0;">Bangladesh Secretariat</h1>
                <h3 style="font-size: 18px; color: #1f6f54; margin: 2px 0; font-weight: bold;">Visitor Application Reports</h3>
            </div>
        </div>
    </div>

    <div class="page-title row align-items-center">
        <div class="col-md-8">
            <span class="page-kicker">Analytics</span>
            <h1>Visitor Application Reports</h1>
            <p>Report of visitor applications, visits, and department summaries.</p>
        </div>
        <div class="col-md-4 text-right">
            <button class="btn btn-primary mr-2" onclick="window.print()">Print</button>
            <a class="btn btn-outline-primary" href="<?php echo site_url('report/index'); ?>">Gate Entry Logs</a>
        </div>
    </div>

    <!-- Filters Panel -->
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
            <div class="col-md-4 form-group">
                <label>NID / Passport</label>
                <input class="form-control" placeholder="Search by NID" v-model="filter.nid">
            </div>
            <div class="col-md-4 form-group">
                <label>Department</label>
                <select class="form-control" v-model="filter.department_id">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo $dept->id; ?>"><?php echo html_escape($dept->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>Application Status</label>
                <select class="form-control" v-model="filter.status">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-secondary mr-2" type="button" @click="resetFilters">Reset</button>
            <button class="btn btn-primary" type="submit">Search & Filter</button>
        </div>
    </form>

    <div class="row">
        <!-- Visitor Application Grouped Summary -->
        <div class="col-lg-8 mb-4">
            <div class="panel">
                <h2>Visitor Application Details</h2>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Visitor Info</th>
                                <th>Department</th>
                                <th>Person to Visit</th>
                                <th class="text-center font-weight-bold">Times Applied</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in visitorReport" :key="row.phone + '-' + row.nid + '-' + row.visit_to">
                                <td>
                                    <div><strong>{{ row.name }}</strong></div>
                                    <small class="text-muted">P: {{ row.phone }} | NID: {{ row.nid }}</small>
                                </td>
                                <td>{{ row.department_name || 'N/A' }}</td>
                                <td><strong>{{ row.visit_to }}</strong></td>
                                <td class="text-center font-weight-bold text-primary">
                                    <span class="badge badge-primary badge-pill" style="font-size: 14px; padding: 6px 12px;">{{ row.apply_count }}</span>
                                </td>
                            </tr>
                            <tr v-if="visitorReport.length == 0">
                                <td colspan="4" class="text-center text-muted py-4">No records found matching filters</td>
                            </tr>
                        </tbody>
                        <tfoot v-if="visitorReport.length > 0">
                            <tr style="font-weight:bold;">
                                <td colspan="3" style="text-align:right;">Total Applied</td>
                                <td style="text-align:center;">{{ visitorReport.reduce((prev, curr) => { return prev + parseFloat(curr.apply_count)}, 0) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Department-Wise Summary -->
        <div class="col-lg-4 mb-4">
            <div class="panel">
                <h3>Department Summary</h3>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th class="text-center font-weight-bold">Applications</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in departmentReport" :key="row.id">
                                <td><strong>{{ row.name }}</strong></td>
                                <td class="text-center font-weight-bold text-success" style="font-size: 16px;">
                                    {{ row.total_count || 0 }}
                                </td>
                            </tr>
                            <tr v-if="departmentReport.length == 0">
                                <td colspan="2" class="text-center text-muted py-4">No data available</td>
                            </tr>
                        </tbody>
                    </table>
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
            el: '#appReport',
            data() {
                return {
                    filter: {
                        from: '<?php echo date('Y-m-d'); ?>',
                        to: '<?php echo date('Y-m-d'); ?>',
                        name: '',
                        phone: '',
                        nid: '',
                        department_id: '',
                        status: ''
                    },
                    visitorReport: [],
                    departmentReport: []
                }
            },
            filters: {
                decimal(value) {
                    return value == null || value == '' ? '0.00' : parseFloat(value).toFixed(2);
                }
            },
            created() {
                this.getReport();
            },
            methods: {
                getReport() {
                    let url = '<?php echo site_url('get_application_report'); ?>?from=' + this.filter.from +
                        '&to=' + this.filter.to +
                        '&name=' + encodeURIComponent(this.filter.name) +
                        '&phone=' + encodeURIComponent(this.filter.phone) +
                        '&nid=' + encodeURIComponent(this.filter.nid) +
                        '&department_id=' + this.filter.department_id +
                        '&status=' + this.filter.status;

                    axios.get(url).then(res => {
                        let r = res.data;
                        if (r.success) {
                            this.visitorReport = r.visitor_report;
                            this.departmentReport = r.department_report;
                        } else {
                            alert(r.message);
                        }
                    }).catch(() => {
                        alert('Failed to load application reports');
                    });
                },
                resetFilters() {
                    this.filter.from = '';
                    this.filter.to = '';
                    this.filter.name = '';
                    this.filter.phone = '';
                    this.filter.nid = '';
                    this.filter.department_id = '';
                    this.filter.status = '';
                    this.getReport();
                }
            }
        });
    </script>