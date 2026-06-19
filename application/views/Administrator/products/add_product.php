<style>
	.v-select {
		margin-bottom: 5px;
		background: #fff;
		border-radius: 3px;
	}

	.v-select.open .dropdown-toggle {
		border-bottom: 1px solid #ccc;
	}

	.v-select .dropdown-toggle {
		padding: 0px;
		height: 25px;
		border: none;
	}

	.v-select input[type=search],
	.v-select input[type=search]:focus {
		margin: 0px;
	}

	.v-select .vs__selected-options {
		overflow: hidden;
		flex-wrap: nowrap;
	}

	.v-select .selected-tag {
		margin: 2px 0px;
		white-space: nowrap;
		position: absolute;
		left: 0px;
	}

	.v-select .vs__actions {
		margin-top: -5px;
	}

	.v-select .dropdown-menu {
		width: auto;
		overflow-y: auto;
	}

	#products label {
		font-size: 13px;
	}

	#products select {
		border-radius: 3px;
	}

	#products .add-button {
		padding: 2.5px;
		width: 100%;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
		cursor: pointer;
		border-radius: 3px;
	}

	#products .add-button:hover {
		background-color: #41add6;
		color: white;
	}

	/* image upload csss */
	.image-container {
		display: flex;
		flex-wrap: wrap;
		margin-top: 5px;
	}

	.image-item {
		position: relative;
		margin-right: 10px;
		margin-bottom: 10px;
	}

	.profile-pic {
		width: 85px;
		height: 80px;
		object-fit: cover;
		cursor: pointer;
		border: 1px solid #ccc;
	}

	.remove-button {
		position: absolute;
		top: 5px;
		right: 5px;
		padding: 3px 8px;
		background-color: #f44336;
		color: white;
		border: none;
		border-radius: 50%;
		cursor: pointer;
		font-weight: bold;
	}

	.remove-button:hover {
		background-color: #d32f2f;
	}

	.ck-editor__editable {
		min-height: 120px !important;
	}
</style>
<div id="products">
	<form @submit.prevent="saveProduct">
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">Product Entry Form</legend>
			<div class="control-group">
				<div class="row" style="margin: 0;">
					<div class="col-md-6">
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Product Id:</label>
							<div class="col-md-8">
								<input type="text" class="form-control" v-model="product.Product_Code">
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Category:</label>
							<div class="col-md-8" style="display: flex;align-items:center;margin-bottom:5px;">
								<div style="width: 88%;">
									<v-select v-bind:options="categories" style="margin:0;" v-model="selectedCategory" label="Category_Name"></v-select>
								</div>
								<div style="width:11%;margin-left:2px;">
									<a href="<?= base_url('category') ?>" class="add-button" target="_blank" title="Add New Category"><i class="fa fa-plus" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Sub-Category:</label>
							<div class="col-md-8" style="display: flex;align-items:center;margin-bottom:5px;">
								<div style="width: 88%;">
									<v-select v-bind:options="filter_subcategories" style="margin:0;" v-model="selectedSubCategory"
										label="SubCategory_Name"></v-select>
								</div>
								<div style="width:11%;margin-left:2px;">
									<a href="<?= base_url('sub_category') ?>" class="add-button" target="_blank" title="Add New Sub-Category"><i class="fa fa-plus" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Brand:</label>
							<div class="col-md-8" style="display: flex;align-items:center;margin-bottom:5px;">
								<div style="width: 88%;">
									<v-select v-bind:options="brands" style="margin:0;" v-model="selectedBrand"
										label="brand_name"></v-select>
								</div>
								<div style="width:11%;margin-left:2px;">
									<span class="add-button"
										@click.prevent="modalOpen('/add_brand', 'Add Brand', 'brand_name')"><i class="fa fa-plus"></i></span>
								</div>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Color:</label>
							<div class="col-md-8" style="display: flex;align-items:center;margin-bottom:5px;">
								<div style="width: 88%;">
									<v-select v-bind:options="colors" style="margin:0;" v-model="selectedColor"
										label="color_name"></v-select>
								</div>
								<div style="width:11%;margin-left:2px;">
									<span class="add-button"
										@click.prevent="modalOpen('/add_color', 'Add Color', 'color_name')"><i class="fa fa-plus"></i></span>
								</div>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Product Name:</label>
							<div class="col-md-8">
								<input type="text" class="form-control" v-model="product.Product_Name" required>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Unit:</label>
							<div class="col-md-8" style="display: flex;align-items:center;margin-bottom:5px;">
								<div style="width: 88%;">
									<v-select v-bind:options="units" style="margin:0;" v-model="selectedUnit"
										label="Unit_Name"></v-select>
								</div>
								<div style="width:11%;margin-left:2px;">
									<span class="add-button"
										@click.prevent="modalOpen('/add_unit', 'Add Unit', 'Unit_Name')"><i class="fa fa-plus"></i></span>
								</div>
							</div>
						</div>
						<!-- <div class="form-group clearfix">
							<label class="control-label col-md-4">VAT:</label>
							<div class="col-md-8">
								<input type="number" min="0" step="any" class="form-control" v-model="product.vat">
							</div>
						</div> -->

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Video Link:</label>
							<div class="col-md-8">
								<input type="url" class="form-control" v-model="product.Video_Url" required>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Product Description:</label>
							<div class="col-md-8">
								<textarea name="editor" id="editor" cols="30" rows="3" v-model="product.short_description"></textarea>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Re-order level:</label>
							<div class="col-md-7">
								<input type="number" min="0" step="any" class="form-control" v-model="product.Product_ReOrederLevel" required>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Purchase Rate:</label>
							<div class="col-md-7">
								<input type="number" min="0" step="any" id="purchase_rate" class="form-control" v-model="product.Product_Purchase_Rate" required v-bind:disabled="product.is_service ? true : false">
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Sales Rate:</label>
							<div class="col-md-7">
								<input type="number" min="0" step="any" class="form-control" v-model="product.Product_SellingPrice" required>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Wholesale Rate:</label>
							<div class="col-md-7">
								<input type="number" min="0" step="any" class="form-control" v-model="product.Product_WholesaleRate" required>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Previous Rate:</label>
							<div class="col-md-7">
								<input type="number" min="0" step="any" class="form-control" v-model="product.Product_PreviousPrice" required>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4">Product Image</label>
							<div class="col-md-7">
								<div class="row">
									<div class="col-md-6">
										<input type="file" style="padding: 0px 5px; border-radius: 3px;" id="catImg" class="form-control" ref="image" @change="previewImage" />
										<div v-if="imageUrl != ''" style="display: none;" :style="{ display: imageUrl != '' ? '' : 'none' }">
											<img :src="imageUrl" style="width:100px; height:80px; margin-bottom: 5px;">
										</div>
									</div>
									<div class="col-md-6">
										<input type="file" style="padding: 0px 5px; border-radius: 3px;" id="catImg" class="form-control" ref="image" @change="previewSizeImage" />
										<div v-if="sizeUrl != ''" style="display: none;" :style="{ display: sizeUrl != '' ? '' : 'none' }">
											<img :src="sizeUrl" style="width:100px; height:80px; margin-bottom: 5px;">
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4" for="productImage">Other Images</label>
							<div class="col-md-7">
								<input type="file" style="padding: 0px 5px; border-radius: 3px;" ref="productImage" id="multi_image" class="form-control" multiple @change="previewGallery" />

								<div class="image-container" style="display: none;" :style="{display: images.length > 0 ? '' : 'none'}">
									<div v-for="(image, index) in images" :key="index" class="image-item">
										<img class="profile-pic" :src="image.gallery_image" />
										<button type="button" class="remove-button" @click="removeFromGallery(index)"><i class="fa fa-times"></i></button>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4"></label>
							<div class="col-md-4">
								<label for="is_offer">
									<input type="checkbox" id="is_offer" v-model="product.is_offer"> Is Offer
								</label>
							</div>
							<div class="col-md-4">
								<label for="is_popular">
									<input type="checkbox" id="is_popular" v-model="product.is_popular"> Is Popular
								</label>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4"></label>
							<div class="col-md-4">
								<label for="is_arrival">
									<input type="checkbox" id="is_arrival" v-model="product.is_arrival"> New Arrival
								</label>
							</div>
							<div class="col-md-4">
								<label for="is_website">
									<input type="checkbox" id="is_website" v-model="product.is_website"> Is Show Website
								</label>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-md-4"></label>
							<div class="col-md-11 text-right">
								<input type="button" @click="clearForm" class="btnReset" value="Reset">
								<input type="submit" class="btnSave" value="Save">
							</div>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
	</form>

	<div class="row">
		<div class="col-md-12 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div>
		<div class="col-md-12">
			<div class="table-responsive">
				<datatable :columns="columns" :data="products" :filter-by="filter">
					<template scope="{ row }">
						<tr>
							<td>{{ row.Product_Code }}</td>
							<td style="text-align: left;padding-left:3px;">{{ row.Product_Name }}</td>
							<td>{{ row.Category_Name }}</td>
							<td>{{ row.SubCategory_Name }}</td>
							<td>{{ row.brand_name }}</td>
							<td>{{ row.color_name }}</td>
							<td>{{ row.Product_Purchase_Rate }}</td>
							<td>{{ row.Product_SellingPrice }}</td>
							<!-- <td>{{ row.Product_WholesaleRate }}</td> -->
							<td>{{ row.Product_PreviousPrice }}</td>
							<!-- <td>{{ row.vat }}</td> -->
							<td>
								<span v-if="row.is_website == 'false'" class="badge badge-success">Soft</span>
								<span v-else class="badge badge-warning">Web</span>
							</td>
							<!-- <td>
								<span v-if="row.is_service == 'false'" class="badge badge-success">Product</span>
								<span v-else class="badge badge-warning">Service</span>
							</td> -->
							<td>{{ row.Unit_Name }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<i class="btnEdit fa fa-pencil" @click="editProduct(row)"></i>
									<i class="btnDelete fa fa-trash" @click="deleteProduct(row.Product_SlNo)"></i>
								<?php } ?>
								<i @click="window.open(`/barcode/${row.Product_SlNo}`, '_blank')" class="btnBarcode fa fa-barcode"></i>
							</td>
						</tr>
					</template>
				</datatable>
				<datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
			</div>
		</div>
	</div>

	<!-- modal form -->
	<div class="modal formModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-sm" role="document">
			<form @submit.prevent="saveModalData($event)">
				<div class="modal-content">
					<div class="modal-header" style="display: flex;align-items: center;justify-content: space-between;">
						<h5 class="modal-title" v-html="modalTitle"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="padding-top: 0;">
						<div class="form-group">
							<label for="">Name</label>
							<input type="text" :name="formInput" v-model="fieldValue" class="form-control"
								autocomplete="off" />
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btnReset" data-dismiss="modal">Close</button>
						<button type="submit" class="btnSave">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#products',
		data() {
			return {
				product: {
					Product_SlNo: '',
					Product_Code: "<?php echo $productCode; ?>",
					Product_Name: '',
					ProductCategory_ID: '',
					ProductSubCategory_ID: '',
					Brand_ID: '',
					Color_ID: '',
					Product_ReOrederLevel: 0,
					Product_Purchase_Rate: 0,
					Product_SellingPrice: 0,
					Product_WholesaleRate: 0,
					Product_PreviousPrice: 0,
					Unit_ID: '',
					vat: 0,
					Video_Url: '',
					Product_Description: '',
					is_website: true,
					is_service: false,
					is_offer: false,
					is_popular: false,
					is_arrival: false
				},
				products: [],
				categories: [],
				selectedCategory: null,
				filter_subcategories: [],
				sub_categories: [],
				selectedSubCategory: null,
				brands: [],
				selectedBrand: null,
				colors: [],
				selectedColor: null,
				units: [],
				selectedUnit: null,


				columns: [{
						label: 'Product Id',
						field: 'Product_Code',
						align: 'center',
						filterable: false
					},
					{
						label: 'Product Name',
						field: 'Product_Name',
						align: 'center'
					},
					{
						label: 'Category',
						field: 'Category_Name',
						align: 'center'
					},
					{
						label: 'Sub-Category',
						field: 'SubCategory_Name',
						align: 'center'
					},
					{
						label: 'Brand',
						field: 'brand_name',
						align: 'center'
					},
					{
						label: 'Color',
						field: 'color_name',
						align: 'center'
					},
					{
						label: 'Purchase Price',
						field: 'Product_Purchase_Rate',
						align: 'center'
					},
					{
						label: 'Sales Price',
						field: 'Product_SellingPrice',
						align: 'center'
					},
					// {
					// 	label: 'Wholesale Price',
					// 	field: 'Product_WholesaleRate',
					// 	align: 'center'
					// },
					{
						label: 'Discount Price',
						field: 'Product_PreviousPrice',
						align: 'center'
					},
					// {
					// 	label: 'VAT',
					// 	field: 'vat',
					// 	align: 'center'
					// },
					{
						label: 'Type',
						field: 'is_website',
						align: 'center'
					},
					// {
					// 	label: 'Type',
					// 	field: 'is_service',
					// 	align: 'center'
					// },
					{
						label: 'Unit',
						field: 'Unit_Name',
						align: 'center'
					},
					{
						label: 'Action',
						align: 'center',
						filterable: false
					}
				],
				page: 1,
				per_page: 100,
				filter: '',

				formInput: '',
				url: '',
				modalTitle: '',
				fieldValue: '',
				editor: null,

				imageUrl: '',
				selectedFile: null,

				sizeUrl: '',
				selectedSFile: null,
				images: [],
			}
		},
		watch: {
			selectedCategory(category) {
				this.filter_subcategories = this.sub_categories.filter(c => c.Category_SlNo == category.Category_SlNo);
			}
		},
		created() {
			this.imageUrl = "uploads/no_image.jpg";
			this.sizeUrl = "uploads/no_image.jpg";
			this.getCategories();
			this.getSubCategories();
			this.getBrands();
			this.getColors();
			this.getUnits();
			this.getProducts();
		},
		methods: {
			initializeEditors() {
				ClassicEditor.create(document.querySelector('#editor')).then(newEditor => {
					this.editor = newEditor;
				}).catch(error => {
					console.error(error);
				});
			},
			changeIsService() {
				if (this.product.is_service) {
					this.product.Product_Purchase_Rate = 0;
				}
			},
			getCategories() {
				axios.get('/get_categories').then(res => {
					this.categories = res.data;
				})
			},
			getSubCategories() {
				axios.get('/get_sub_categories').then(res => {
					this.sub_categories = res.data;
				})
			},
			getBrands() {
				axios.get('/get_brands').then(res => {
					this.brands = res.data;
				})
			},
			getColors() {
				axios.get('/get_colors').then(res => {
					this.colors = res.data;
				})
			},
			getUnits() {
				axios.get('/get_units').then(res => {
					this.units = res.data;
				})
			},
			getProducts() {
				axios.get('/get_products').then(res => {
					this.products = res.data;
				})
			},
			previewImage() {
				if (event.target.files.length > 0) {
					this.selectedFile = event.target.files[0];
					this.imageUrl = URL.createObjectURL(this.selectedFile);
				} else {
					this.selectedFile = null;
					this.imageUrl = '';
				}
			},
			previewSizeImage() {
				if (event.target.files.length > 0) {
					this.selectedSFile = event.target.files[0];
					this.sizeUrl = URL.createObjectURL(this.selectedSFile);
				} else {
					this.selectedSFile = null;
					this.sizeUrl = '';
				}
			},
			previewGallery() {
				if (event.target.files == undefined || event.target.files.length < 1) {
					this.images = [];
					return;
				}

				Array.from(event.target.files).forEach(file => {
					this.images.push({
						gallery_id: null,
						gallery_image: URL.createObjectURL(file)
					});
				})
			},
			removeFromGallery(ind) {
				if (this.product.Product_SlNo != "") {
					axios.post('/check_gallery_image', {
						productId: this.product.Product_SlNo,
						productImage: this.images[ind].gallery_file
					}).then(res => {
						if (res.data.found) {
							axios.post('/delete_pgallery_image', {
								productId: this.product.Product_SlNo,
								productImage: this.images[ind].gallery_file
							}).then(res => {
								let r = res.data;
								if (r.success) {
									this.images.splice(ind, 1);
								}
							})
						} else {
							this.images.splice(ind, 1);
						}
					})

				} else {
					this.images.splice(ind, 1);
				}
			},
			saveProduct() {
				if (this.selectedCategory == null) {
					Swal.fire({
						icon: "error",
						text: "Select category",
					});
					return;
				}
				if (this.selectedSubCategory == null) {
					Swal.fire({
						icon: "error",
						text: "Select Sub-Category",
					});
					return;
				}
				if (this.selectedUnit == null) {
					Swal.fire({
						icon: "error",
						text: "Select unit",
					});
					return;
				}
				if (this.selectedBrand != null) {
					this.product.Brand_ID = this.selectedBrand.brand_SiNo;
				}
				if (this.selectedColor != null) {
					this.product.Color_ID = this.selectedColor.color_SiNo;
				}

				this.product.ProductCategory_ID = this.selectedCategory.Category_SlNo;
				this.product.ProductSubCategory_ID = this.selectedSubCategory.SubCategory_SlNo;
				this.product.Unit_ID = this.selectedUnit.Unit_SlNo;
				this.product.Product_Description = this.editor.getData();

				let fd = new FormData();

				let files = $('#multi_image')[0].files;
				for (let i = 0; i < files.length; i++) {
					let image_name = files[i].name;
					let image_ext = image_name.substring(image_name.lastIndexOf('.') + 1);

					if (!["png", "jpeg", "jpg", "webp", "gif"].includes(image_ext)) {
						alert('Image format ' + image_ext + ' not support!');
						return;
					}
					fd.append("images[]", files[i]);
				}

				fd.append('data', JSON.stringify(this.product));

				let url = '/add_product';
				if (this.product.Product_SlNo != 0) {
					url = '/update_product';
				}

				fd.append('image', this.selectedFile);
				fd.append('sizeImage', this.selectedSFile);

				axios.post(url, fd, {
					onUploadProgress: upe => {
						let progress = Math.round(upe.loaded / upe.total * 100);
						console.log(progress);
					}
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.clearForm();
						this.product.Product_Code = r.productId;
						this.getProducts();
					}
				})
			},
			editProduct(product) {
				let keys = Object.keys(this.product);
				keys.forEach(key => {
					this.product[key] = product[key];
				})

				this.product.Video_Url = product.Video_Url;
				this.product.Product_Description = this.editor.setData(product.Product_Description);

				this.product.is_website = product.is_website == 'true' ? true : false;
				this.product.is_service = product.is_service == 'true' ? true : false;
				this.product.is_arrival = product.is_arrival == 'true' ? true : false;
				this.product.is_offer = product.is_offer == 'true' ? true : false;
				this.product.is_popular = product.is_popular == 'true' ? true : false;

				this.selectedCategory = {
					Category_SlNo: product.ProductCategory_ID,
					Category_Name: product.Category_Name
				}

				this.selectedSubCategory = {
					SubCategory_SlNo: product.ProductSubCategory_ID,
					SubCategory_Name: product.SubCategory_Name
				}

				this.selectedBrand = {
					brand_SiNo: product.Brand_ID,
					brand_name: product.brand_name
				}

				this.selectedColor = {
					color_SiNo: product.Color_ID,
					color_name: product.color_name
				}

				this.selectedUnit = {
					Unit_SlNo: product.Unit_ID,
					Unit_Name: product.Unit_Name
				}

				if (product.Product_Image == null || product.Product_Image == '') {
					this.imageUrl = "uploads/no_image.jpg";
				} else {
					this.imageUrl = product.Product_Image;
				}

				if (product.Product_SizeImage == null || product.Product_SizeImage == '') {
					this.sizeUrl = "uploads/no_image.jpg";
				} else {
					this.sizeUrl = product.Product_SizeImage;
				}

				if (this.product.Product_SlNo != null) {
					this.images = [];
					axios.post('/get_product_images', {
						productId: product.Product_SlNo
					}).then(res => {
						this.images = res.data;
						this.images.map(i => {
							i.gallery_file = i.Gallery_Image;
							i.gallery_image = `/uploads/product_gallery/${i.Gallery_Image}`;
							return i;
						})
					})
				}
			},
			deleteProduct(productId) {
				let deleteConfirm = confirm('Are you sure?');
				if (deleteConfirm == false) {
					return;
				}
				axios.post('/delete_product', {
					productId: productId
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.getProducts();
					}
				})
			},
			clearForm() {
				this.product = {
					Product_SlNo: '',
					Product_Code: "",
					Product_Name: '',
					ProductCategory_ID: '',
					ProductSubCategory_ID: '',
					Brand_ID: '',
					Color_ID: '',
					Product_ReOrederLevel: 0,
					Product_Purchase_Rate: 0,
					Product_SellingPrice: 0,
					Product_WholesaleRate: 0,
					Product_PreviousPrice: 0,
					Unit_ID: '',
					vat: 0,
					Product_Description: '',
					is_website: true,
					is_service: false
				}
				this.product.Product_Code = "<?php echo $this->mt->generateProductCode(); ?>";

				this.editor.setData("");
				this.selectedCategory = null;
				this.selectedSubCategory = null;
				this.selectedBrand = null;
				this.selectedColor = null;
				this.selectedUnit = null;
				this.imageUrl = '';
				this.sizeUrl = '';
				this.selectedFile = null;
				this.selectedSFile = null;
				this.images = [];
			},
			// modal data store
			modalOpen(url, title, txt) {
				$(".formModal").modal("show");
				this.formInput = txt;
				this.url = url;
				this.modalTitle = title;
			},
			saveModalData(event) {
				let filter = {}
				if (this.formInput == "brand_name") {
					filter.brand_name = this.fieldValue;
				}
				if (this.formInput == "color_name") {
					filter.color_name = this.fieldValue;
				}
				if (this.formInput == "Unit_Name") {
					filter.Unit_Name = this.fieldValue;
				}

				axios.post(this.url, filter).then(res => {
					if (this.formInput == "brand_name") {
						this.getBrands();
					}
					if (this.formInput == "color_name") {
						this.getColors();
					}
					if (this.formInput == "Unit_Name") {
						this.getUnits();
					}

					$(".formModal").modal('hide');
					this.formInput = '';
					this.url = "";
					this.modalTitle = '';
					this.fieldValue = '';
				})
			}
		},
		mounted() {
			this.initializeEditors();
		},
	})
</script>