<div id="profileApp" class="container">
    <div class="page-title">
        <div>
            <span class="page-kicker">Visitor Profile</span>
            <h1><?php echo html_escape($visitor->name); ?></h1>
            <p>Your registered visitor information.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="panel profile-panel">
                <dl class="card-grid">
                    <dt>Name</dt><dd><?php echo html_escape($visitor->name); ?></dd>
                    <dt>Email</dt><dd><?php echo html_escape($visitor->email); ?></dd>
                    <dt>Phone</dt><dd><?php echo html_escape($visitor->phone); ?></dd>
                    <dt>NID or Passport</dt><dd><?php echo html_escape($visitor->nid); ?></dd>
                    <dt>Address</dt><dd><?php echo html_escape($visitor->address); ?></dd>
                    <dt>Registered At</dt><dd><?php echo html_escape($visitor->created_at); ?></dd>
                </dl>
                <a class="btn btn-primary mt-3" href="<?php echo site_url('visitor/apply'); ?>">Apply for Visit</a>
                <a class="btn btn-outline-primary mt-3" href="<?php echo site_url('visitor_panel/dashboard'); ?>">Dashboard</a>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="panel text-center">
                <h3>Profile Picture</h3>
                <div class="mb-3">
                    <img :src="photoUrl" alt="Profile Picture" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                
                <button v-if="!showUpdateForm" class="btn btn-sm btn-outline-secondary" @click="showUpdateForm = true">Change Photo</button>
                
                <div v-if="showUpdateForm" class="mt-3 text-left">
                    <div class="btn-group btn-group-toggle d-flex mb-3">
                        <label class="btn btn-sm btn-outline-primary active w-50" @click="photoMode = 'upload'">
                            <input type="radio" name="photoMode" checked> Upload
                        </label>
                        <label class="btn btn-sm btn-outline-primary w-50" @click="startCamera">
                            <input type="radio" name="photoMode"> Camera
                        </label>
                    </div>

                    <div v-show="photoMode === 'upload'" class="mb-3">
                        <input type="file" class="form-control-file" accept="image/*" ref="fileInput" @change="onFileChange">
                    </div>

                    <div v-show="photoMode === 'camera'" class="mb-3">
                        <video ref="video" width="100%" height="auto" autoplay style="border: 1px solid #ccc; border-radius: 4px; background: #000; max-height: 180px;"></video>
                        <button type="button" class="btn btn-secondary btn-sm mt-2 w-100" @click="capturePhoto">Capture Frame</button>
                    </div>

                    <div v-if="photoPreview" class="mb-3 text-center">
                        <img :src="photoPreview" class="img-thumbnail" style="max-height: 120px;">
                    </div>

                    <div class="d-flex">
                        <button class="btn btn-sm btn-success w-50 mr-1" :disabled="submitting" @click="updatePhoto">Save</button>
                        <button class="btn btn-sm btn-light w-50 ml-1" @click="cancelUpdate">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
new Vue({
    el: '#profileApp',
    data() {
        return {
            photoUrl: '<?php echo $visitor->photo ? base_url($visitor->photo) : "data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23ccc\'><path d=\'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4\'/></svg>"; ?>',
            showUpdateForm: false,
            photoMode: 'upload',
            photoPreview: '',
            photoFile: null,
            photoBase64: '',
            stream: null,
            submitting: false
        }
    },
    methods: {
        startCamera() {
            this.photoMode = 'camera';
            this.photoFile = null;
            this.photoPreview = '';
            navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 } })
                .then(stream => {
                    this.stream = stream;
                    this.$refs.video.srcObject = stream;
                })
                .catch(err => {
                    alert("Could not access camera: " + err.message);
                    this.photoMode = 'upload';
                });
        },
        stopCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
        },
        onFileChange(e) {
            this.stopCamera();
            this.photoBase64 = '';
            let file = e.target.files[0];
            if (file) {
                this.photoFile = file;
                let reader = new FileReader();
                reader.onload = (evt) => {
                    this.photoPreview = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        capturePhoto() {
            let video = this.$refs.video;
            let canvas = document.createElement('canvas');
            canvas.width = video.videoWidth || 320;
            canvas.height = video.videoHeight || 240;
            let ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            this.photoBase64 = canvas.toDataURL('image/jpeg');
            this.photoPreview = this.photoBase64;
            this.photoFile = null;
            this.stopCamera();
            this.photoMode = 'upload';
        },
        cancelUpdate() {
            this.stopCamera();
            this.showUpdateForm = false;
            this.photoPreview = '';
            this.photoFile = null;
            this.photoBase64 = '';
        },
        updatePhoto() {
            if (!this.photoPreview) {
                alert('Please select or capture a photo first.');
                return;
            }
            this.submitting = true;
            let fd = new FormData();
            
            let payload = {};
            if (this.photoBase64) {
                payload.photo_base64 = this.photoBase64;
            }
            fd.append('data', JSON.stringify(payload));
            
            if (this.photoFile) {
                fd.append('photo', this.photoFile);
            }

            axios.post('<?php echo site_url('visitor_update_photo'); ?>', fd).then(res => {
                let r = res.data;
                alert(r.message);
                if (r.success) {
                    this.photoUrl = r.photo_url;
                    this.cancelUpdate();
                }
            }).catch(() => {
                alert('Update failed');
            }).then(() => {
                this.submitting = false;
            });
        }
    }
});
</script>

