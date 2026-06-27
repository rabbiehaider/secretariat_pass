<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<div id="gateApp" class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Security Gate</span>
            <h1>Gate Scanner</h1>
            <p>Scan QR code or enter token manually.</p>
        </div>
    </div>
    <div class="scanner-layout">
        <div class="panel">
            <div id="reader"></div>
            <div class="manual-box">
                <input class="form-control" v-model="token" placeholder="Paste QR token">
                <button class="btn btn-primary" @click="verify(token)">Verify</button>
            </div>
        </div>
        <div class="panel result-panel" :class="resultClass">
            <h2>{{ result.message || 'Waiting for scan' }}</h2>
            <div v-if="result.visitor">
                <p><strong>Name:</strong> {{ result.visitor.name }}</p>
                <p><strong>Pass:</strong> {{ result.visitor.pass_no }}</p>
                <p><strong>Visit To:</strong> {{ result.visitor.visit_to }}</p>
                <p><strong>Purpose:</strong> {{ result.visitor.purpose }}</p>
            </div>
        </div>
    </div>
</div>
<script>
    new Vue({
        el: '#gateApp',
        data: {
            token: '',
            busy: false,
            result: {}
        },
        computed: {
            resultClass: function() {
                if (!this.result.status) return '';
                return this.result.success ? 'result-ok' : 'result-bad';
            }
        },
        mounted: function() {
            var vm = this;
            var scanner = new Html5QrcodeScanner('reader', {
                fps: 10,
                qrbox: 240
            });
            scanner.render(function(decodedText) {
                vm.token = decodedText;
                vm.verify(decodedText);
            });
        },
        methods: {
            verify: function(token) {
                if (!token || this.busy) return;
                this.busy = true;
                var form = new FormData();
                form.append('data', JSON.stringify({ token: token }));
                axios.post('<?php echo site_url('verify_gate_pass'); ?>', form)
                    .then(function(response) {
                        this.result = response.data;
                    }.bind(this))
                    .catch(function() {
                        this.result = {
                            success: false,
                            status: 'invalid',
                            message: 'Verification failed'
                        };
                    }.bind(this))
                    .then(function() {
                        this.busy = false;
                    }.bind(this));
            }
        }
    });
</script>
