<div id="visitorRegister" class="container narrow">
    <div class="page-title">
        <div>
            <span class="page-kicker">Visitor Account</span>
            <h1>Create Visitor Account</h1>
            <p>Register once, then apply and track every visit from your dashboard.</p>
        </div>
    </div>

    <form class="panel" @submit.prevent="registerVisitor">
        <div class="form-section-title">Personal Information</div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Full Name</label>
                <input class="form-control" v-model="visitor.name" required>
            </div>
            <div class="form-group col-md-6">
                <label>NID or Passport</label>
                <input class="form-control" v-model="visitor.nid" required>
            </div>
        </div>

        <div class="form-section-title">Login Information</div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Email</label>
                <input class="form-control" type="email" v-model="visitor.email" required>
            </div>
            <div class="form-group col-md-6">
                <label>Phone</label>
                <input class="form-control" v-model="visitor.phone" inputmode="numeric" maxlength="11" pattern="^01[13-9][0-9]{8}$" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Password</label>
                <input class="form-control" type="password" v-model="visitor.password" minlength="6" required>
            </div>
            <div class="form-group col-md-6">
                <label>Confirm Password</label>
                <input class="form-control" type="password" v-model="visitor.confirm_password" minlength="6" required>
            </div>
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea class="form-control" rows="3" v-model="visitor.address" required></textarea>
        </div>

        <!-- Photo Upload / Capture Section -->
        <div class="form-section-title">Profile Picture</div>
        <div class="form-row align-items-center mb-3">
            <div class="col-md-6 form-group">
                <label style="display: none !important;">Choose Upload Method</label>
                <div style="display: none !important;" class=" btn-group btn-group-toggle d-flex mb-3">
                    <label class="btn btn-outline-primary active w-50" @click="photoMode = 'upload'">
                        <input type="radio" name="photoMode" checked> File Upload
                    </label>
                    <label class="btn btn-outline-primary w-50" @click="startCamera">
                        <input type="radio" name="photoMode"> Capture Webcam
                    </label>
                </div>

                <div v-show="photoMode === 'upload'">
                    <label>Upload Image (JPG/PNG, max 2MB)</label>
                    <input type="file" class="form-control-file" accept="image/*" ref="fileInput" @change="onFileChange">
                </div>

                <div v-show="photoMode === 'camera'">
                    <video ref="video" width="100%" height="auto" autoplay style="border: 1px solid #ccc; border-radius: 4px; background: #000; max-height: 240px;"></video>
                    <button type="button" class="btn btn-secondary btn-sm mt-2 w-100" @click="capturePhoto">Capture Frame</button>
                </div>
            </div>

            <div class="col-md-6 text-center">
                <div v-if="photoPreview" class="mt-3">
                    <p class="mb-1 text-muted">Preview</p>
                    <img :src="photoPreview" alt="Profile Preview" class="img-thumbnail" style="max-height: 180px;">
                    <button type="button" class="btn btn-danger btn-sm d-block mx-auto mt-2" @click="clearPhoto">Clear Photo</button>
                </div>
                <div v-else class="text-muted p-4 border rounded" style="border-style: dashed !important; border-width: 2px;">
                    No Profile Picture Selected
                </div>
            </div>
        </div>

        <button class="btn btn-primary" :disabled="submitting" type="submit">
            {{ submitting ? 'Creating Account...' : 'Create Account' }}
        </button>
        <a class="btn btn-outline-primary ml-2" href="<?php echo site_url('visitor_auth/login'); ?>">Already Registered</a>
    </form>
</div>

<script>
    new Vue({
        el: '#visitorRegister',
        data() {
            return {
                submitting: false,
                photoMode: 'upload',
                photoPreview: '',
                photoFile: null,
                photoBase64: '',
                stream: null,
                visitor: {
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    confirm_password: '',
                    nid: '',
                    address: ''
                }
            }
        },
        methods: {
            startCamera() {
                this.photoMode = 'camera';
                this.photoFile = null;
                navigator.mediaDevices.getUserMedia({
                    video: {
                        width: 640,
                        height: 480
                    }
                }).then(stream => {
                    this.stream = stream;
                    this.$refs.video.srcObject = stream;
                }).catch(err => {
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
                canvas.width = video.videoWidth || 640;
                canvas.height = video.videoHeight || 480;
                let ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                this.photoBase64 = canvas.toDataURL('image/jpeg');
                this.photoPreview = this.photoBase64;
                this.photoFile = null;
                this.stopCamera();
                this.photoMode = 'upload';
            },
            clearPhoto() {
                this.stopCamera();
                this.photoPreview = '';
                this.photoFile = null;
                this.photoBase64 = '';
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            },
            registerVisitor() {
                if (!this.photoPreview) {
                    alert('Please upload or capture a profile picture.');
                    return;
                }
                this.submitting = true;
                let fd = new FormData();

                let payload = {
                    ...this.visitor
                };
                if (this.photoBase64) {
                    payload.photo_base64 = this.photoBase64;
                }

                fd.append('data', JSON.stringify(payload));
                if (this.photoFile) {
                    fd.append('photo', this.photoFile);
                }

                axios.post('<?php echo site_url('visitor_register'); ?>', fd).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        window.location = r.redirect;
                    }
                }).catch(() => {
                    alert('Registration failed');
                }).then(() => {
                    this.submitting = false;
                });
            }
        }
    });
</script>