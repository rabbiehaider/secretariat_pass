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

	#subCategoryForm label {
		font-size: 13px;
	}

	#subCategoryForm select {
		border-radius: 3px;
	}

	#subCategoryForm .add-button {
		padding: 2.5px;
		width: 100%;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
		cursor: pointer;
		border-radius: 3px;
	}

	#subCategoryForm .add-button:hover {
		background-color: #41add6;
		color: white;
	}
</style>
<div id="subCategoryForm">
	<div class="row" style="margin: 0;">
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">Sub-Category Entry Form</legend>
			<div class="control-group">
				<div class="col-xs-12 col-md-6 col-md-offset-3">
					<form @submit.prevent="saveSubCategory">
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Category: <small style="color:red">*</small></label>
							<div class="col-md-7" style="display: flex;align-items:center;margin-bottom:5px;">
								<div style="width: 86%;">
									<v-select v-bind:options="categories" style="margin:0;" v-model="selectedCategory"
										label="Category_Name" placeholder="Select Category"></v-select>
								</div>
								<div style="width:13%;margin-left:2px;">
									<a href="<?= base_url('category') ?>" class="add-button" target="_blank" title="Add New Category"><i class="fa fa-plus" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-xs-4 col-md-4"> Sub-Category Name:</label>
							<div class="col-xs-7 col-md-7">
								<input type="text" class="form-control" v-model="sub_category.SubCategory_Name" required>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-xs-4 col-md-4"> Slug:</label>
							<div class="col-xs-7 col-md-7">
								<input type="text" class="form-control" v-model="sub_category.route" required>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-md-5"></label>
							<div class="col-md-6 text-right">
								<input type="button" class="btnReset" value="Reset" @click="resetForm">
								<input type="submit" class="btnSave" value="Save">
							</div>
						</div>
					</form>
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
				<datatable :columns="columns" :data="sub_categories" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.sl }}</td>
							<td>{{ row.SubCategory_Name }}</td>
							<td>{{ row.Category_Name }}</td>
							<td>{{ row.route }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<i class="btnEdit fa fa-pencil" @click="editSubCategory(row)"></i>
									<i class="btnDelete fa fa-trash" @click="deleteSubCategory(row.SubCategory_SlNo)"></i>
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
		el: '#subCategoryForm',
		data() {
			return {
				sub_category: {
					SubCategory_SlNo: 0,
					Category_SlNo: 0,
					SubCategory_Name: '',
					route: ''
				},
				sub_categories: [],

				categories: [],
				selectedCategory: null,

				columns: [{
						label: 'Sl',
						field: 'sl',
						align: 'center'
					},
					{
						label: 'Sub-Category Name',
						field: 'SubCategory_Name',
						align: 'center'
					},
					{
						label: 'Category Name',
						field: 'Category_Name',
						align: 'center'
					},
					{
						label: 'Slug',
						field: 'route',
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
				filter: ''
			}
		},
		created() {
			this.getCategories();
			this.getSubCategories();
		},
		methods: {
			getCategories() {
				axios.get('/get_categories').then(res => {
					this.categories = res.data;
				})
			},
			getSubCategories() {
				axios.get('/get_sub_categories').then(res => {
					this.sub_categories = res.data.map((item, index) => {
						item.sl = index + 1;
						return item;
					});
				})
			},
			saveSubCategory() {
				if (this.selectedCategory == null || this.selectedCategory.Category_SlNo == undefined) {
					Swal.fire({
						icon: "error",
						text: "Please select category",
					});
					return;
				}

				if (this.sub_category.SubCategory_Name == '') {
					Swal.fire({
						icon: "error",
						text: "Sub-Category name is empty!",
					});
					return;
				}

				if (this.sub_category.route == '') {
					Swal.fire({
						icon: "error",
						text: "Slug is empty!",
					});
					return;
				}

				this.sub_category.Category_SlNo = this.selectedCategory.Category_SlNo;

				let url = '/add_sub_category';
				if (this.sub_category.SubCategory_SlNo != 0) {
					url = '/update_sub_category';
				}

				axios.post(url, this.sub_category).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.status) {
						this.resetForm();
						this.getSubCategories();
					}
				})
			},
			editSubCategory(sub_category) {
				let keys = Object.keys(this.sub_category);
				keys.forEach(key => {
					this.sub_category[key] = sub_category[key];
				})
				this.selectedCategory = {
					Category_SlNo: sub_category.Category_SlNo,
					Category_Name: sub_category.Category_Name
				}
			},
			deleteSubCategory(subCategoryId) {
				if (confirm('Are you sure?')) {
					axios.post('/delete_sub_category', {
						subCategoryId: subCategoryId
					}).then(res => {
						let r = res.data;
						alert(r.message);
						if (r.status) {
							this.getSubCategories();
						}
					})
				}
			},
			resetForm() {
				this.sub_category = {
					SubCategory_SlNo: 0,
					Category_SlNo: 0,
					SubCategory_Name: '',
					route: ''
				}
				// this.selectedCategory = null;
			}
		}
	})
</script>