<style>
	#categoryImage {
		height: 100%;
	}
</style>
<div id="categories">
	<div class="row" style="margin: 0;">
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">Category Entry Form</legend>
			<div class="control-group">
				<div class="col-xs-12 col-md-6 col-md-offset-1">
					<form @submit.prevent="saveCategory">
						<div class="form-group clearfix">
							<label class="control-label col-xs-4 col-md-4"> Name:</label>
							<div class="col-xs-8 col-md-8">
								<input type="text" class="form-control" v-model="category.Category_Name" required>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-xs-4 col-md-4"> Image:</label>
							<div class="col-xs-8 col-md-8">
								<input type="file" class="form-control" @change="previewImage"
									style="padding: 3px;height: 30px;">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-xs-4 col-md-4"> Icon:</label>
							<div class="col-xs-8 col-md-8">
								<input type="file" class="form-control" @change="previewIcon"
									style="padding: 3px;height: 30px;">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-xs-4 col-md-4"> Slug:</label>
							<div class="col-xs-8 col-md-8">
								<input type="text" class="form-control" v-model="category.route" required>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Is Home:</label>
							<div class="col-md-1">
								<input type="checkbox" v-model="category.is_home">
							</div>
							<div class="col-md-6 text-right">
								<input type="button" class="btnReset" value="Reset" @click="resetForm">
								<input type="submit" class="btnSave" value="Save">
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-2 text-center;">
					<div class="form-group clearfix" style="display: flex;align-items:center;flex-direction:column;">
						<div style="width: 100px;height:100px;border: 1px solid #ccc;overflow:hidden;">
							<img id="categoryImage" v-if="imageUrl == '' || imageUrl == null"
								src="/uploads/no_image.jpg">
							<img id="categoryImage" v-if="imageUrl != '' && imageUrl != null" v-bind:src="imageUrl">
						</div>
					</div>
				</div>
				<div class="col-md-2 text-center;">
					<div class="form-group clearfix" style="display: flex;align-items:center;flex-direction:column;">
						<div style="width: 100px;height:100px;border: 1px solid #ccc;overflow:hidden;">
							<img id="categoryImage" v-if="iconUrl == '' || iconUrl == null" src="/uploads/no_image.jpg">
							<img id="categoryImage" v-if="iconUrl != '' && iconUrl != null" v-bind:src="iconUrl">
						</div>
					</div>
				</div>
			</div>
		</fieldset>
	</div>

	<div class="row">
		<div class="col-sm-12 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div>
		<div class="col-md-12">
			<div class="table-responsive">
				<datatable :columns="columns" :data="categories" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.sl }}</td>
							<td>{{ row.Category_Name }}</td>
							<td><span v-if="row.is_home == 'true'">Displayed</span></td>
							<td>{{ row.route }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<i class="btnEdit fa fa-pencil" @click="editCategory(row)"></i>
									<i class="btnDelete fa fa-trash" @click="deleteCategory(row.Category_SlNo)"></i>
								<?php } ?>
							</td>
						</tr>
					</template>
				</datatable>
				<datatable-pager v-model="page" type="abbreviated" :per-page="per_page"
					style="margin-bottom: 50px;"></datatable-pager>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#categories',
		data() {
			return {
				category: {
					Category_SlNo: 0,
					Category_Name: '',
					is_home: false,
					route: '',
				},
				categories: [],
				imageUrl: '',
				selectedFile: null,
				iconUrl: '',
				selectedIcon: null,

				columns: [{
						label: 'Sl',
						field: 'sl',
						align: 'center'
					},
					{
						label: 'Category Name',
						field: 'Category_Name',
						align: 'center'
					},
					{
						label: 'Is Home',
						field: 'is_home',
						align: 'center',
						filterable: false
					},
					{
						label: 'Slug',
						field: 'route',
						align: 'center',
						filterable: false
					},
					{
						label: 'Action',
						align: 'center',
						filterable: false
					}
				],
				page: 1,
				per_page: 100,
				filter: ''
			}
		},
		created() {
			this.getCategories();
		},
		methods: {
			getCategories() {
				axios.get('/get_categories').then(res => {
					this.categories = res.data.map((item, index) => {
						item.sl = index + 1;
						return item;
					});
				})
			},
			saveCategory() {
				if (this.category.Category_Name == '') {
					Swal.fire({
						icon: "error",
						text: "Category name is empty!",
					});
					return;
				}
				if (this.category.route == '') {
					Swal.fire({
						icon: "error",
						text: "Slug is empty!",
					});
					return;
				}
				let url = '/add_category';
				if (this.category.Category_SlNo != 0) {
					url = '/update_category';
				}

				let fd = new FormData();
				fd.append('image', this.selectedFile);
				fd.append('icon', this.selectedIcon);
				fd.append('data', JSON.stringify(this.category));

				axios.post(url, fd, {
					onUploadProgress: upe => {
						let progress = Math.round(upe.loaded / upe.total * 100);
					}
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.status) {
						this.resetForm();
						this.getCategories();
					}
				})
			},
			editCategory(category) {
				let keys = Object.keys(this.category);
				keys.forEach(key => {
					this.category[key] = category[key];
				})

				this.category.is_home = category.is_home == 'true' ? true : false;

				if (category.Category_Image == null || category.Category_Image == '') {
					this.imageUrl = null;
				} else {
					this.imageUrl = category.Category_Image;
				}

				if (category.Category_Icon == null || category.Category_Icon == '') {
					this.iconUrl = null;
				} else {
					this.iconUrl = category.Category_Icon;
				}
			},
			deleteCategory(categoryId) {
				if (confirm('Are you sure?')) {
					axios.post('/delete_category', {
						categoryId: categoryId
					}).then(res => {
						let r = res.data;
						alert(r.message);
						if (r.status) {
							this.getCategories();
						}
					})
				}
			},
			resetForm() {
				this.category = {
					Category_SlNo: 0,
					Category_Name: '',
					is_home: false,
					route: ''
				}
				this.imageUrl = '';
				this.selectedFile = null;
				this.iconUrl = '';
				this.selectedIcon = null;
			},
			previewImage(event) {
				if (event.target.files.length > 0) {
					this.selectedFile = event.target.files[0];
					this.imageUrl = URL.createObjectURL(this.selectedFile);
				} else {
					this.selectedFile = null;
					this.imageUrl = null;
				}

				// const WIDTH = 150;
				// const HEIGHT = 150;
				// if (event.target.files[0]) {
				// 	let reader = new FileReader();
				// 	reader.readAsDataURL(event.target.files[0]);
				// 	reader.onload = (ev) => {
				// 		let img = new Image();
				// 		img.src = ev.target.result;
				// 		img.onload = async e => {
				// 			let canvas = document.createElement('canvas');
				// 			canvas.width = WIDTH;
				// 			canvas.height = HEIGHT;
				// 			const context = canvas.getContext("2d");
				// 			context.drawImage(img, 0, 0, canvas.width, canvas.height);
				// 			let new_img_url = context.canvas.toDataURL(event.target.files[0].type);
				// 			this.imageUrl = new_img_url;
				// 			const resizedImage = await new Promise(rs => canvas.toBlob(rs, 'image/jpeg/svg', 1))
				// 			this.selectedFile = new File([resizedImage], event.target.files[0].name, {
				// 				type: resizedImage.type
				// 			});
				// 		}
				// 	}
				// } else {
				// 	event.target.value = '';
				// }
			},
			previewIcon(event) {
				if (event.target.files.length > 0) {
					this.selectedIcon = event.target.files[0];
					this.iconUrl = URL.createObjectURL(this.selectedIcon);
				} else {
					this.selectedIcon = null;
					this.iconUrl = null;
				}

				// const WIDTH = 150;
				// const HEIGHT = 150;
				// if (event.target.files[0]) {
				// 	let reader = new FileReader();
				// 	reader.readAsDataURL(event.target.files[0]);
				// 	reader.onload = (ev) => {
				// 		let img = new Image();
				// 		img.src = ev.target.result;
				// 		img.onload = async e => {
				// 			let canvas = document.createElement('canvas');
				// 			canvas.width = WIDTH;
				// 			canvas.height = HEIGHT;
				// 			const context = canvas.getContext("2d");
				// 			context.drawImage(img, 0, 0, canvas.width, canvas.height);
				// 			let new_icon_url = context.canvas.toDataURL(event.target.files[0].type);
				// 			this.iconUrl = new_icon_url;
				// 			const resizedImage = await new Promise(rs => canvas.toBlob(rs, 'image/jpeg/svg', 1))
				// 			this.selectedIcon = new File([resizedImage], event.target.files[0].name, {
				// 				type: resizedImage.type
				// 			});
				// 		}
				// 	}
				// } else {
				// 	event.target.value = '';
				// }
			},
		}
	})
</script>