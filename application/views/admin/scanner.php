<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<div id="adminScannerApp" class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Visitor Verification</span>
            <h1>QR Code Scanner</h1>
            <p>Scan visitor's QR code pass to load complete database details.</p>
        </div>
    </div>

    <div class="row">
        <!-- Scanner Panel -->
        <div class="col-md-5 mb-4">
            <div class="panel">
                <h3>QR Reader</h3>
                <div id="adminReader" style="width: 100%; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; background: #fafafa;"></div>
                <div class="manual-box mt-3">
                    <div class="input-group">
                        <input class="form-control" v-model="token" placeholder="Enter QR token manually">
                        <div class="input-group-append">
                            <button class="btn btn-primary" :disabled="busy" @click="fetchDetails(token)">Load</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Panel -->
        <div class="col-md-7 mb-4">
            <div class="panel">
                <h3>Visitor & Pass Complete Details</h3>
                <div v-if="busy" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Fetching visitor details...</p>
                </div>
                <div v-else-if="visitor.id">
                    <div class="row align-items-center mb-4 border-bottom pb-3">
                        <div class="col-sm-8">
                            <h2 class="mb-1 text-primary">{{ visitor.name }}</h2>
                            <p class="mb-0 text-muted">Tracking ID: {{ visitor.tracking_id }}</p>
                        </div>
                        <div class="col-sm-4 text-center">
                            <img :src="visitor.photo_url || defaultPhoto" alt="Visitor Photo" class="img-thumbnail rounded" style="max-height: 120px; object-fit: cover;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small mb-0">Phone Number</label>
                            <div><strong>{{ visitor.phone }}</strong></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small mb-0">NID / Passport</label>
                            <div><strong>{{ visitor.nid }}</strong></div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label class="text-muted small mb-0">Address</label>
                            <div>{{ visitor.address }}</div>
                        </div>
                    </div>

                    <h4 class="mt-3 border-bottom pb-2">Visit Information</h4>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small mb-0">Department</label>
                            <div><strong>{{ visitor.department_name }}</strong></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small mb-0">Visit To (Officer)</label>
                            <div><strong>{{ visitor.visit_to }}</strong></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small mb-0">Scheduled Date</label>
                            <div><strong>{{ visitor.visit_date }}</strong></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small mb-0">Application Status</label>
                            <div>
                                <span class="badge badge-status" :class="'badge-' + visitor.status">{{ visitor.status }}</span>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label class="text-muted small mb-0">Purpose</label>
                            <div>{{ visitor.purpose }}</div>
                        </div>
                        <div v-if="visitor.status === 'rejected'" class="col-sm-12 mb-3 text-danger">
                            <label class="text-muted small mb-0">Cancellation Note</label>
                            <div class="p-2 bg-light border rounded"><strong>{{ visitor.rejected_reason }}</strong></div>
                        </div>
                    </div>

                    <h4 class="mt-3 border-bottom pb-2">Gate Scan Logs (History)</h4>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Scan Time</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="log in logs" :key="log.id">
                                    <td><small>{{ log.entry_time }}</small></td>
                                    <td>
                                        <span class="badge" :class="logClass(log.scan_status)">{{ log.scan_status }}</span>
                                    </td>
                                    <td><small>{{ log.remarks }}</small></td>
                                </tr>
                                <tr v-if="logs.length === 0">
                                    <td colspan="3" class="text-center text-muted py-2"><small>No entry scans logged yet</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-else class="text-center text-muted py-5">
                    <p class="mb-0">Scan a visitor pass QR Code or type the token manually to view the full details.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
new Vue({
    el: '#adminScannerApp',
    data() {
        return {
            token: '',
            busy: false,
            visitor: {},
            logs: [],
            defaultPhoto: "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ccc'><path d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4'/></svg>"
        }
    },
    mounted() {
        var vm = this;
        var scanner = new Html5QrcodeScanner('adminReader', {
            fps: 10,
            qrbox: 240
        });
        scanner.render(function(decodedText) {
            vm.token = decodedText;
            vm.fetchDetails(decodedText);
        });
    },
    methods: {
        fetchDetails(token) {
            if (!token || this.busy) return;
            this.busy = true;
            this.visitor = {};
            this.logs = [];
            
            axios.get('<?php echo site_url('admin/scanner_details'); ?>?token=' + encodeURIComponent(token))
                .then(res => {
                    let r = res.data;
                    if (r.success) {
                        this.visitor = r.application;
                        this.logs = r.gate_logs;
                    } else {
                        alert(r.message);
                    }
                })
                .catch(() => {
                    alert('Failed to load visitor details from server.');
                })
                .finally(() => {
                    this.busy = false;
                });
        },
        logClass(status) {
            switch(status) {
                case 'valid': return 'badge-success';
                case 'invalid': return 'badge-danger';
                case 'expired': return 'badge-warning';
                case 'already_used': return 'badge-info';
                default: return 'badge-secondary';
            }
        }
    }
});
</script>
